<?php

/**
 * SunshineBiz_Location Region controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Adminhtml_RegionController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('system')
                ->_addBreadcrumb($this->__('System'), $this->__('System'))
                ->_addBreadcrumb($this->__('Location Management'), $this->__('Location Management'))
                ->_addBreadcrumb($this->__('Region Management'), $this->__('Region Management'));

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('System'))
                ->_title($this->__('Location Management'))
                ->_title($this->__('Region Management'));

        $this->_initAction()->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {

        $this->_title($this->__('System'))
                ->_title($this->__('Location Management'))
                ->_title($this->__('Region Management'));

        $regionId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('location/region');
        if ($regionId) {
            $model->setLoadAll(true)->load($regionId);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This region no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->addLocaleNames($this->getRequest()->getParam('locale'));
        } else {
            $model->setCountryId(Mage::helper('core')->getDefaultCountry());
        }

        $this->_title($model->getId() ? $model->getDefaultName() : $this->__('New Region'));
        $data = Mage::getSingleton('adminhtml/session')->getRegionData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('locations_region', $model);
        if ($regionId) {
            $breadcrumb = $this->__('Edit Region');
        } else {
            $breadcrumb = $this->__('New Region');
        }

        $this->_initAction()->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }

    public function saveAction() {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            
            $regionId = $this->getRequest()->getParam('region_id');
            /** @var $model SunshineBiz_Location_Model_Region */
            $model = Mage::getModel('location/region')->setLoadAll(true)->load($regionId);
            if ($regionId && $model->isObjectNew()) {
                $this->_getSession()->addError($this->__('This region no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
            
            $model->setData($data);
            try {
                $model->save();
                $this->_getSession()->addSuccess($this->__('The region has been saved.'));
                $this->_getSession()->setRegionData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setRegionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('region_id')));
                return;
            }
        } 
        $this->_redirect('*/*/');
    }

    public function deleteAction() {

        if($regionId = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('location/region')->setId($regionId)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The region has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $regionId));
                return;
            }
        }
        
        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find a region to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {

        $regionIds = $this->getRequest()->getParam('region');
        if (!is_array($regionIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select at least one item to delete.'));
        } else {
            try {
                foreach ($regionIds as $regionId) {
                    Mage::getModel('location/region')->setId($regionId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        $this->__('Total of %d region(s) were successfully deleted.', count($regionIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/location/region');
    }
}