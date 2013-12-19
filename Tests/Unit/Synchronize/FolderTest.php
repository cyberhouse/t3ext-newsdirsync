<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Georg Ringer <georg.ringer@cyberhouse.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests for Tx_Newsdirsync_Synchronize_Folder
 *
 * @package TYPO3
 * @subpackage tx_newsdirsync
 */
class Tx_Newsdirsync_Tests_Unit_Synchronize_Folder extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @test
	 * @return void
	 */
	public function constructorSetsCorrectProperties() {
		$synchronizeService = new Tx_Newsdirsync_Synchronize_Folder('/fo/', '123x');

		$this->assertEquals(PATH_site . 'fo/', $synchronizeService->getDirectory());
		$this->assertEquals(123, $synchronizeService->getNewsId());
	}

	/**
	 * @test
	 * @return void
	 */
	public function removeDuplicatesInSynchronizedDirectoryRemovesDuplicates() {
		/** @var Tx_Newsdirsync_Synchronize_Folder $mockedFolder */
		$mockedFolder = $this->getAccessibleMock('Tx_Newsdirsync_Synchronize_Folder', array('dummy'));
		$mockedFolder->_set('directory', 'typo3conf/ext/newsdirsync/Tests/Unit/Resources');

		$files = $mockedFolder->_call('getFilesFromDirectory');
		$this->assertEquals(3, count($files));

		$files = $mockedFolder->_call('removeDuplicatesInSynchronizedDirectory', $files);
		$this->assertEquals(2, count($files));
	}

	/**
	 * @test
	 * @return void
	 */
	public function getInsertedImagesWorks() {
		/** @var Tx_Newsdirsync_Synchronize_Folder $mockedFolder */
		$mockedFolder = $this->getAccessibleMock('Tx_Newsdirsync_Synchronize_Folder', array('dummy'));
		$mockedFolder->_set('insertedImages', 3);

		$count = $mockedFolder->getInsertedImages();
		$this->assertEquals(3, $count);
	}

}