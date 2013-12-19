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
 * Tests for Tx_Newsdirsync_Hooks_Tcemain
 *
 * @package TYPO3
 * @subpackage tx_newsdirsync
 */
class Tx_Newsdirsync_Tests_Unit_Hooks_Tcemain extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var array
	 */
	private $backupGlobalVariables;

	/**
	 * Set up global variables
	 *
	 * @return void
	 */
	public function setUp() {
		$this->backupGlobalVariables = array(
			'FILEMOUNTS' => $GLOBALS['FILEMOUNTS'],
		);
	}

	/**
	 * Tear down global variables
	 *
	 * @return void
	 */
	public function tearDown() {
		foreach ($this->backupGlobalVariables as $key => $data) {
			$GLOBALS[$key] = $data;
		}
		unset($this->backupGlobalVariables);
	}

	/**
	 * @test
	 * @dataProvider qualifiedArgumentsForProcessingAreGivenDataProvider
	 * @return void
	 */
	public function qualifiedArgumentsForProcessingAreGiven($expected, $given) {
		$mockedTceMain = $this->getAccessibleMock('Tx_Newsdirsync_Hooks_Tcemain', array('dummy'));
		$result = $mockedTceMain->_call('isValidForProcessing', $given[0], $given[1]);

		$this->assertEquals($result, $expected);
	}

	public function qualifiedArgumentsForProcessingAreGivenDataProvider() {
		return array(
			'workingExample' => array(TRUE, array('tx_news_domain_model_news', 123)),
			'wrongTable' => array(FALSE, array('tt_content', 123)),
			'idIsZero' => array(FALSE, array('tx_news_domain_model_news', 0)),
			'idIsNegative' => array(FALSE, array('tx_news_domain_model_news', -10)),
			'idIsNotANumber' => array(FALSE, array('tx_news_domain_model_news', 'NEW1234')),
			'allWrong' => array(FALSE, array('pages', 'NEW1234')),
		);
	}

	/**
	 * @test
	 * @dataProvider isValidDirectoryReturnsCorrectValueDataProvider
	 * @return void
	 */
	public function isValidDirectoryReturnsCorrectValue($expected, $given) {
		$GLOBALS['FILEMOUNTS']['1a6bf46c985ab4976ff1e5e5b7b88603'] = array(
			'name' => 'fo',
			'path' => PATH_site . 'typo3conf/',
			'type' => ''
		);

		$mockedTceMain = $this->getAccessibleMock('Tx_Newsdirsync_Hooks_Tcemain', array('dummy'));
		$result = $mockedTceMain->_call('isValidDirectory', $given);

		$this->assertEquals($result, $expected);
	}

	public function isValidDirectoryReturnsCorrectValueDataProvider() {
		return array(
			'workingExampleSlashRight' => array(TRUE, 'typo3conf/ext/'),
			'workingExampleSlashLeft' => array(TRUE, '/typo3conf/ext/newsdirsync'),
			'workingExampleSlashLeftAndRight' => array(TRUE, '/typo3conf/ext/newsdirsync/'),
			'wrongPath' => array(FALSE, '/^somethingWhatIsNothThere/bar'),
			'invalidPath' => array(FALSE, '/fileadmin/'),
		);
	}
}

?>