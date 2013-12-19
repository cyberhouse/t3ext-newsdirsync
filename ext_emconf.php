<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News directory sync',
	'description' => 'Sync media files from directories',
	'category' => 'fe',
	'author' => 'Georg Ringer',
	'author_email' => 'georg.ringer@cyberhouse.at',
	'author_company' => 'Cyberhouse GmbH',
	'shy' => '',
	'dependencies' => 'news',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.5-6.1.99',
			'news' => '2.2.1-0.0.0',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(),
);

?>