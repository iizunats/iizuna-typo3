<?php
if (!\defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['iizuna'] = \iizunats\iizuna\Decoder\UrlDecoder::class . '->decodeUrl';