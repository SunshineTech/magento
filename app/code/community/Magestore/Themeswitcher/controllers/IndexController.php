<?php
class Magestore_Themeswitcher_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/themeswitcher?id=15 
    	 *  or
    	 * http://site.com/themeswitcher/id/15 	
    	 */
    	/* 
		$themeswitcher_id = $this->getRequest()->getParam('id');

  		if($themeswitcher_id != null && $themeswitcher_id != '')	{
			$themeswitcher = Mage::getModel('themeswitcher/themeswitcher')->load($themeswitcher_id)->getData();
		} else {
			$themeswitcher = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($themeswitcher == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$themeswitcherTable = $resource->getTableName('themeswitcher');
			
			$select = $read->select()
			   ->from($themeswitcherTable,array('themeswitcher_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$themeswitcher = $read->fetchRow($select);
		}
		Mage::register('themeswitcher', $themeswitcher);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
	public function fullVersionSceenAction(){
            Mage::getSingleton('core/session')->setfullScreen('fulling'); 
             $this->_redirect(null);
    }
    public function mobileVersionSceenAction(){
            //Mage::getSingleton('core/session')->setfullScreen('fulling'); 
            Mage::getSingleton('core/session')->unsfullScreen();
             $this->_redirect(null);
    }
}