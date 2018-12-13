<?php
if (!\defined('TYPO3_MODE')) {
	exit('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Iizunats.ii_product',
	'Products',
	['Product' => 'list, page'],
	['Product' => 'list, page']
);
$partialCache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Iizunats\Iizuna\Utility\PartialRegistrationUtility::class);
$partialCache->register('ii_product', 'Product/ListItem');//First argument is the extension name, the second one is the local partial name with path