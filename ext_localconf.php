<?php
if (!\defined('TYPO3_MODE')) {
	exit('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Iizunats.iizuna',
	'PartialOutput',
	['PartialOutput' => 'render'],
	['PartialOutput' => 'render']
);