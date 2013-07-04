<?php

/**
 * @category   MagentoPycho
 * @package    MagentoPycho_Lightboxes
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagentoPycho_Lightboxes_Block_Adminhtml_Config_Fancybox_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    protected $_template = 'lightboxes/config/fancybox/info.phtml';

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element) {
        return $this->toHtml();
    }
}
