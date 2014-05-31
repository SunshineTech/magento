<?php

class Magestore_Themeswitcher_Model_Package extends Mage_Core_Model_Design_Package
{
	public function getSkinList($package = null)
	{
        $result = array();

        if (is_null($package)){
            foreach ($this->getPackageList() as $package){
                $result[$package] = $this->getSkinList($package);
            }
        } else {
            $directory = Mage::getBaseDir('skin') . DS . 'frontend' . DS . $package;
            $result = $this->_listDirectories($directory);
        }
		
        return $result;		
	}
	
    private function _listDirectories($path, $fullPath = false)
    {
        $result = array();
        $dir = opendir($path);
        if ($dir) {
            while ($entry = readdir($dir)) {
                if (substr($entry, 0, 1) == '.' || !is_dir($path . DS . $entry)){
                    continue;
                }
                if ($fullPath) {
                    $entry = $path . DS . $entry;
                }
                $result[] = $entry;
            }
            unset($entry);
            closedir($dir);
        }

        return $result;
    }	
}