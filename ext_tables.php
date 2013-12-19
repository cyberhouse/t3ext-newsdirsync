<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

/***************
 * Add fields to news item
 */
$tempColumns = array(
	'directory_sync' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:tx_news_domain_model_news.directory_sync',
		'config' => array(
			'type' => 'input',
			'eval' => 'trim',
		)
	),
);

t3lib_extMgm::addTCAcolumns('tx_news_domain_model_news', $tempColumns, TRUE);
t3lib_extMgm::addToAllTCAtypes('tx_news_domain_model_news', 'directory_sync', '', 'after:media');