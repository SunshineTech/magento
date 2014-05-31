<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('themeswitcher_theme')}
  ADD `stores` varchar(255) NOT NULL default '0';
  
    ");

$installer->endSetup(); 