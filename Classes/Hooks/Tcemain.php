<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <georg.ringer@cyberhouse.at>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Hook for tcemain
 *
 * @package TYPO3
 * @subpackage tx_newsdirsync
 */
class Tx_Newsdirsync_Hooks_Tcemain {

	/**
	 * This method is called by a hook in the TYPO3 Core Engine (TCEmain) when a record is saved.
	 *
	 * @param    array $fieldArray : The field names and their values to be processed (passed by reference)
	 * @param    string $table : The table TCEmain is currently processing
	 * @param    string $id : The records id (if any)
	 * @param    object $pObj : Reference to the parent object (TCEmain)
	 * @return    void
	 */
	public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, &$pObj) {
		if (!self::isValidForProcessing($table, $id)) {
			return;
		}

		$directory = $fieldArray['directory_sync'];
		unset($fieldArray['directory_sync']);

		if (empty($directory)) {
			return;
		}
		if (!self::isValidDirectory($directory)) {
			return;
		}

		/** @var Tx_Newsdirsync_Synchronize_Folder $synchronizeService */
		$synchronizeService = t3lib_div::makeInstance('Tx_Newsdirsync_Synchronize_Folder', $directory, $id);
		$synchronizeService->synchronizeDirectoryWithArticle();

		$countOfInsertedImages = $synchronizeService->getInsertedImages();
		self::notifyUser($countOfInsertedImages, $directory);
	}


	/**
	 * @param string $tableName
	 * @param integer $id
	 * @return boolean
	 */
	protected static function isValidForProcessing($tableName, $id) {
		return $tableName === 'tx_news_domain_model_news' && (int)$id > 0;
	}


	/**
	 * Check if given string is a valid path to a directory
	 *
	 * @param string $directory
	 * @return boolean
	 */
	protected static function isValidDirectory($directory) {
		$directory = trim($directory, '/') . '/';

		/** @var t3lib_basicFileFunctions $fileFunc */
		$fileFunc = t3lib_div::makeInstance('t3lib_basicFileFunctions');
		$fileFunc->init($GLOBALS['FILEMOUNTS'], $$GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);
		$directoryIsInFileMount = $fileFunc->checkPathAgainstMounts(PATH_site . $directory);

		if (is_dir(PATH_site . $directory) && t3lib_div::validPathStr($directory) && !empty($directoryIsInFileMount)) {
			return TRUE;
		} else {
			if (!$directoryIsInFileMount) {
				$message = sprintf($GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:error.no_valid_dir'), $directory);
			} else {
				$message = sprintf($GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:error.dir_not_allowed'), $directory);
			}

			/** @var t3lib_FlashMessage $flashMessage */
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
				htmlspecialchars($message),
				$GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:synchronized.header', TRUE),
				t3lib_FlashMessage::ERROR,
				TRUE
			);
			t3lib_FlashMessageQueue::addMessage($flashMessage);
		}

		return FALSE;
	}

	/**
	 * Notify the user about the added images
	 *
	 * @param integer $count count of inserted images
	 * @param string $directory synchronized directory
	 */
	protected static function notifyUser($count, $directory) {
		$flashMessage = NULL;
		if ($count === 0) {
			$message = sprintf($GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:synchronized.no_images_synchronized'), $directory);
			/** @var t3lib_FlashMessage $flashMessage */
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
				htmlspecialchars($message),
				$GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:synchronized.header', TRUE),
				t3lib_FlashMessage::NOTICE,
				TRUE
			);
		} else {
			$message = sprintf($GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:synchronized.images_synchronized'), $directory, $count);
			/** @var t3lib_FlashMessage $flashMessage */
			$flashMessage = t3lib_div::makeInstance('t3lib_FlashMessage',
				htmlspecialchars($message),
				$GLOBALS['LANG']->sL('LLL:EXT:newsdirsync/Resources/Private/Language/locallang_be.xml:synchronized.header', TRUE),
				t3lib_FlashMessage::OK,
				TRUE
			);
		}

		t3lib_FlashMessageQueue::addMessage($flashMessage);
	}
}
