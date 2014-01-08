<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newsdirsync".
 *
 * Auto generated 08-01-2014 12:19
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News directory sync',
	'description' => 'Sync media files from a given directory',
	'category' => 'be',
	'author' => 'Georg Ringer',
	'author_email' => 'georg.ringer@cyberhouse.at',
	'author_company' => '',
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
	'version' => '1.0.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.5-6.1.99',
			'news' => '2.2.1-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:16:{s:16:"ext_autoload.php";s:4:"45c6";s:12:"ext_icon.gif";s:4:"777b";s:17:"ext_localconf.php";s:4:"50ee";s:14:"ext_tables.php";s:4:"00e8";s:10:"README.rst";s:4:"653e";s:25:"Classes/Hooks/Tcemain.php";s:4:"f240";s:30:"Classes/Synchronice/Folder.php";s:4:"a06d";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"fb18";s:40:"Resources/Public/Images/screenshot-1.png";s:4:"58f8";s:40:"Resources/Public/Images/screenshot-2.png";s:4:"3706";s:32:"Tests/Unit/Hooks/TcemainTest.php";s:4:"ea22";s:31:"Tests/Unit/Resources/icon_1.gif";s:4:"777b";s:35:"Tests/Unit/Resources/other icon.gif";s:4:"e922";s:39:"Tests/Unit/Resources/some duplicate.gif";s:4:"777b";s:34:"Tests/Unit/Resources/Some_text.txt";s:4:"80a7";s:37:"Tests/Unit/Synchronize/FolderTest.php";s:4:"d782";}',
	'suggests' => array(
	),
);

?>