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
 * Folder synchronizing service
 *
 * @package TYPO3
 * @subpackage tx_newsdirsync
 */
class Tx_Newsdirsync_Synchronize_Folder {

	const HASH_ALGORITHM = 'md5';

	/** @var string */
	protected $directory = '';
	/** @var int */
	protected $newsId = 0;
	/** @var  t3lib_DB */
	protected $db;
	/** @var int */
	protected $insertedImages = 0;

	public function __construct($directory, $newsId) {
		$this->directory = PATH_site . trim($directory, '/') . '/';
		$this->newsId = (int)$newsId;
		$this->db = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Synchronize the folders
	 *
	 * @return void
	 */
	public function synchronizeDirectoryWithArticle() {
		$filesInDirectory = $this->getFilesFromDirectory();

		$filesInDirectory = $this->removeDuplicatesInSynchronizedDirectory($filesInDirectory);
		$filesInDirectory = $this->removeDuplicatesFromGivenNewsElements($filesInDirectory);

		if (!empty($filesInDirectory)) {
			$this->addNewMediaElements($filesInDirectory);
		}
	}

	/**
	 * Get all images from the directory
	 *
	 * @return array
	 */
	protected function getFilesFromDirectory() {
		$allowedExtensions = $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'];
		$filesInDirectory = t3lib_div::getFilesInDir($this->directory, $allowedExtensions, TRUE, 1);

		return $filesInDirectory;
	}

	/**
	 * Persist the media files
	 *
	 * @param array $files
	 */
	protected function addNewMediaElements(array $files) {
		/** @var t3lib_basicFileFunctions $fileFunc */
		$fileFunc = t3lib_div::makeInstance('t3lib_basicFileFunctions');

		$newsRecord = $this->db->exec_SELECTgetSingleRow('*', 'tx_news_domain_model_news', 'uid=' . $this->newsId);
		$lastMediaRecord = $this->db->exec_SELECTgetSingleRow('uid,sorting', 'tx_news_domain_model_media', 'deleted=0 AND type=0 AND parent=' . $this->newsId, '', 'sorting DESC');

		$sorting = $lastMediaRecord['sorting'];
		foreach ($files as $uniqueKey => $filePath) {
			$sorting = $sorting + 100;

			$fileInfo = pathinfo($filePath);

			$cleanedFileName = $fileFunc->cleanFileName($fileInfo['basename']);
			$fixedPath = $fileInfo['dirname'] . '/' . $cleanedFileName;
			$newFileName = $fileFunc->getUniqueName($fixedPath, PATH_site . 'uploads/tx_news');

			$newFileInfo = pathinfo($newFileName);
			t3lib_div::upload_copy_move($filePath, $newFileName);

			$update = array(
				'type' => 0,
				'pid' => $newsRecord['pid'],
				'sys_language_uid' => $newsRecord['sys_language_uid'],
				'parent' => $newsRecord['uid'],
				'image' => $newFileInfo['basename'],
				'tstamp' => $GLOBALS['EXEC_TIME'],
				'crdate' => $GLOBALS['EXEC_TIME'],
				'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
				'sorting' => $sorting
			);
			$this->db->exec_INSERTquery('tx_news_domain_model_media', $update);
			$this->insertedImages++;
		}
	}

	/**
	 * Remove duplicates found in the existing media elements from the new files
	 *
	 * @param array $files
	 * @return array files
	 */
	protected function removeDuplicatesFromGivenNewsElements(array $files) {
		$mediaElements = $this->db->exec_SELECTgetRows(
			'image',
			'tx_news_domain_model_media',
			'deleted=0 AND type=0 AND parent=' . $this->newsId
		);

		$hashesOfExistingFiles = array();
		foreach ($mediaElements as $element) {
			$hash = hash_file(self::HASH_ALGORITHM, PATH_site . 'uploads/tx_news/' . $element['image']);
			$hashesOfExistingFiles[$hash] = 1;
		}

		foreach ($files as $key => $file) {
			$hash = hash_file(self::HASH_ALGORITHM, $file);
			if (isset($hashesOfExistingFiles[$hash])) {
				unset($files[$key]);
			}
		}

		return $files;
	}


	/**
	 * Remove duplicates based on the file's hash
	 *
	 * @param array $files array of files
	 * @return array modified files
	 */
	protected function removeDuplicatesInSynchronizedDirectory(array $files) {
		$hashes = array();
		foreach ($files as $key => $file) {
			$hash = hash_file(self::HASH_ALGORITHM, $file);
			if (!isset($hashes[$hash])) {
				$hashes[$hash] = $file;
			} else {
				unset($files[$key]);
			}
		}

		return $files;
	}


	/**
	 * Get the news id
	 *
	 * @return int
	 */
	public function getNewsId() {
		return $this->newsId;
	}

	/**
	 * Get the directory
	 *
	 * @return string
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 *
	 * Get the inserted images
	 *
	 * @return int
	 */
	public function getInsertedImages() {
		return $this->insertedImages;
	}

}