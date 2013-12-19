.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt
.. include:: ../Images.txt


=============================================================
Newssdirsync: Synchronize an image folder with media records
=============================================================

:Language: en
:Rendered: |today|

What does it do?
^^^^^^^^^^^^^^^^

This extension implements an easy way to add multiple images to a news record of EXT:news by providing the path to a folder.
During saving the news record all images will be added as media element.

To avoid duplicates images are not added if those are either already existing as media element or are a duplicate of another file in the given directory.


Requirements
"""""""""""""""
- TYPO3 4.5+
- Extension "news" 2.2.1+


Screenshots
^^^^^^^^^^^^^^^^

|img-1|

|img-2|


How to use
^^^^^^^^^^^^^^^^


Using this extension is easy! Just follow this steps:

- Install the extension.
- Edit any news record and provide the path to a directory.
- Save the record and a flash message will tell you how many media files have been included to the news record.

Author
^^^^^^^^^^^^^^

Author of this extension is Cyberhouse GmbH - Full service agency www.cyberhouse.at

