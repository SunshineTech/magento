<?php

class Magestore_Themeswitcher_Adminhtml_ThemeswitcherController extends Mage_Adminhtml_Controller_action {

    public function indexAction() {
        Mage::log('indexAction');
        $this->loadLayout()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('themeswitcher/theme')->load($id);

        $model->loadBrowser();
        $model->loadPlatform();
        //$model->loadCmshomepage();

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('themeswitcher_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('themeswitcher/theme');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Theme Manager'), Mage::helper('adminhtml')->__('Theme Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Theme News'), Mage::helper('adminhtml')->__('Theme News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('themeswitcher/adminhtml_themeswitcher_edit'));
            /* if ($model->getId()) {
              $this->_addLeft($this->getLayout()->createBlock('adminhtml/store_switcher'));
              } */
            $this->_addLeft($this->getLayout()->createBlock('themeswitcher/adminhtml_themeswitcher_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('themeswitcher')->__('Theme does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        
        $stores_post = $this->getRequest()->getPost('stores');
        $stores = '';
        foreach ($stores_post as $store) {
            $stores.=$store . ',';
        }
        if ($data = $this->getRequest()->getPost()) {
            $data['skin'] = $data['template'];
            $data['layout'] = $data['template'];
            $data['browser'] = array('all');

            //zend_debug::dump($data);die();
            //die($data['cmshomepage']);
            $model = Mage::getModel('themeswitcher/theme');
            $model->setData($data)
                    ->setStores($stores)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                $model->assignBrowser();
                $model->assignPlatform();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('themeswitcher')->__('Theme was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), 'store' => $stores));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store' => $stores));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('themeswitcher')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('themeswitcher/theme');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $themeswitcherIds = $this->getRequest()->getParam('themeswitcher');
        if (!is_array($themeswitcherIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($themeswitcherIds as $themeswitcherId) {
                    $themeswitcher = Mage::getModel('themeswitcher/theme')->load($themeswitcherId);
                    $themeswitcher->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($themeswitcherIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $themeswitcherIds = $this->getRequest()->getParam('themeswitcher');
        if (!is_array($themeswitcherIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($themeswitcherIds as $themeswitcherId) {
                    $themeswitcher = Mage::getSingleton('themeswitcher/theme')
                            ->load($themeswitcherId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($themeswitcherIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'themeswitcher.csv';
        $content = $this->getLayout()->createBlock('themeswitcher/adminhtml_themeswitcher_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'themeswitcher.xml';
        $content = $this->getLayout()->createBlock('themeswitcher/adminhtml_themeswitcher_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}
