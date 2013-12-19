<?php

if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY]
	= 'EXT:' . $_EXTKEY . '/Classes/Hooks/Tcemain.php:Tx_Newsdirsync_Hooks_Tcemain';
