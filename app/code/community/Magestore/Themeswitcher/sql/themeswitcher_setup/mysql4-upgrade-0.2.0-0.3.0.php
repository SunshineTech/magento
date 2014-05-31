<?php

$installer = $this;
$installer->startSetup();
$installer->run("
				ALTER TABLE {$this->getTable('themeswitcher_theme')} ADD cmshomepage varchar(255)  NOT NULL default '';
				");
$installer->endSetup();
?>