<?php

class Magestore_Themeswitcher_Model_Source_Skin extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_isFullLabel = false;

    /**
     * Setter
     * Add package name to label
     *
     * @param boolean $isFullLabel
     * @return Magestore_Themeswitcher_Model_Source_Skin
     */
    public function setIsFulllabel($isFullLabel)
    {
        $this->_isFullLabel = $isFullLabel;
        return $this;
    }

    /**
     * Getter
     *
     * @return boolean
     */
    public function getIsFullLabel()
    {
        return $this->_isFullLabel;
    }

    /**
     * Retrieve All Design Theme Options
     *
     * @param bool $withEmpty add empty (please select) values to result
     * @return array
     */
    public function getAllOptions($withEmpty = true)
    {
        if (is_null($this->_options)) {
            $skin = Mage::getModel('themeswitcher/package')->getSkinList();
            $options = array();
            foreach ($skin as $package => $themes){
                $packageOption = array('label' => $package);
                $themeOptions = array();
                foreach ($themes as $theme) {
                    $themeOptions[] = array(
                        'label' => ($this->getIsFullLabel() ? $package . ' / ' : '') . $theme,
                        'value' => $package . '/' . $theme
                    );
                }
                $packageOption['value'] = $themeOptions;
                $options[] = $packageOption;
            }
            $this->_options = $options;
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array(
                'value'=>'',
                'label'=>Mage::helper('core')->__('-- Please Select --'))
            );
        }
        return $options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);

        return $value;
    }
}