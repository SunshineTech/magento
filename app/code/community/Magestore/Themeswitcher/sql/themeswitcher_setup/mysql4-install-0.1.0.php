<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('themeswitcher_theme_browser')};
DROP TABLE IF EXISTS {$this->getTable('themeswitcher_theme_platform')};
DROP TABLE IF EXISTS {$this->getTable('themeswitcher_theme')};

CREATE TABLE {$this->getTable('themeswitcher_theme')} (
  `theme_id` int(11) unsigned NOT NULL auto_increment,
  `stores` varchar(255) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `layout` varchar(255) NOT NULL default '',
  `template` varchar(255) NOT NULL default '',
  `skin` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '2',
  `description` text NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('themeswitcher_theme_browser')} (
  `theme_browser_id` int(11) unsigned NOT NULL auto_increment,
  `theme_id` int(11) unsigned NOT NULL,
  `browser` varchar(255) NOT NULL default '',
  INDEX (`theme_id`),
  FOREIGN KEY (`theme_id`) REFERENCES {$this->getTable('themeswitcher_theme')} (`theme_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  PRIMARY KEY (`theme_browser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('themeswitcher_theme_platform')} (
  `theme_platform_id` int(11) unsigned NOT NULL auto_increment,
  `theme_id` int(11) unsigned NOT NULL,
  `platform` varchar(255) NOT NULL default '',
  INDEX (`theme_id`),
  FOREIGN KEY (`theme_id`) REFERENCES {$this->getTable('themeswitcher_theme')} (`theme_id`) ON UPDATE CASCADE ON DELETE CASCADE,  
  PRIMARY KEY (`theme_platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->endSetup(); 