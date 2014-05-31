<?php

/**
 * SunshineBiz_Cms media controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Adminhtml_MediaController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
                ->_setActiveMenu('cms/media')
                ->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('cms')->__('CMS'))
                ->_addBreadcrumb(Mage::helper('cms')->__('Medias'), Mage::helper('cms')->__('Medias'))
        ;
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('CMS'))->_title($this->__('Medias'));

        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $this->_title($this->__('CMS'))->_title($this->__('Medias'));

        $id = $this->getRequest()->getParam('media_id');
        $model = Mage::getModel('sunshinebiz_cms/media');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This media no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Media'));
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            if (is_array($data['src']) && !empty($data['src']['delete'])) {
                $data['src'] = $data['src']['value'];
            }
            $model->setData($data);
        }

        Mage::register('cms_media', $model);

        $this->_initAction()
                ->_addBreadcrumb($id ? Mage::helper('cms')->__('Edit Media') : Mage::helper('cms')->__('New Media'), $id ? Mage::helper('cms')->__('Edit Media') : Mage::helper('cms')->__('New Media'))
                ->renderLayout();
    }

    protected function _delArrayElement($key, $destArray) {
        $returnArray = array();
        foreach ($destArray as $element) {
            if (strpos($element, $key) === 0) {
                continue;
            }

            $returnArray[] = $element;
        }

        return $returnArray;
    }

    protected function _preProcess($data) {
        /**
          if (in_array(0, $data['stores'])) {
          $data['stores'] = 0;
          } else {
          foreach (Mage::getSingleton('adminhtml/system_store')->getWebsiteCollection() as $website) {
          if (in_array($website->getId(), $data['stores'])) {
          $data['stores'] = $this->_delArrayElement($website->getId() . '-', $data['stores']);
          continue;
          }

          foreach ($website->getGroupCollection() as $group) {
          if (in_array($website->getId() . '-' . $group->getId(), $data['stores'])) {
          $data['stores'] = $this->_delArrayElement($website->getId() . '-' . $group->getId() . '-', $data['stores']);
          continue;
          }
          }
          }

          $data['stores'] = implode(',', $data['stores']);
          }
         * */
        if (in_array('All', $data['skins'])) {
            $data['skins'] = 'All';
        } else {
            $design = Mage::getModel('core/design_package')->getThemeList();
            foreach ($design as $package => $themes) {
                if (in_array($package, $data['skins'])) {
                    $data['skins'] = $this->_delArrayElement($package . '/', $data['skins']);
                    continue;
                }

                foreach ($themes as $theme) {
                    if (in_array($package . '/' . $theme, $data['skins'])) {
                        $data['skins'] = $this->_delArrayElement($package . '/' . $theme . '/', $data['skins']);
                        continue;
                    }
                }
            }

            $data['skins'] = implode(',', $data['skins']);
        }

        return $data;
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('media_id');
            $model = Mage::getModel('sunshinebiz_cms/media')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This media no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            $model->setData($this->_preProcess($data));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('The media has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('media_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('block_id' => $this->getRequest()->getParam('block_id')));
                return;
            }
        }

        $this->_redirect('*/*/');
    }

    public function deleteAction() {

        if (($mediaId = $this->getRequest()->getParam('media_id'))) {
            try {
                Mage::getModel('sunshinebiz_cms/media')->setId($mediaId)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The media has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('media_id' => $mediaId));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find a media to delete.'));
        $this->_redirect('*/*/');
    }

    public function massChangeStatusAction() {

        $mediaIds = $this->getRequest()->getParam('media');
        if (!is_array($mediaIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select at least one item to change status.'));
        } else {
            try {
                foreach ($mediaIds as $mediaId) {

                    $model = Mage::getModel('sunshinebiz_cms/media')->load($mediaId);
                    if ($model->getId()) {
                        $model->setIsActive(!$model->getIsActive())->save();
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        $this->__('Total of %d media(s) were successfully changed status.', count($mediaIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $mediaIds = $this->getRequest()->getParam('media');
        if (!is_array($mediaIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($mediaIds as $mediaId) {
                    Mage::getModel('sunshinebiz_cms/media')->setId($mediaId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        $this->__('Total of %d media(s) were successfully deleted.', count($mediaIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('cms/media');
    }
}
