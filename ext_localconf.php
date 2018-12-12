<?php
if (!\defined('TYPO3_MODE')) {
	exit('Access denied.');
}
$partialCache = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(iizunats\iizuna\Utility\PartialRegistrationUtility::class);
$partialCache->register('example', 'Product/ListItem');//First argument is the extension name, the second one is the local partial name with path