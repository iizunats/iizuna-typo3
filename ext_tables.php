<?php
if (!\defined('TYPO3_MODE')) {
	exit('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'ii_product',
	'Products',
	'Produkte (Iizuna Example)'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('ii_product', 'Configuration/TypoScript', 'iizuna example extension');