<?php
if (!\defined('TYPO3_MODE')) {
	exit('Access denied.');
}

//Register a hook that allows us to listen for requests made to any path starting with "/iizuna/".
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkAlternativeIdMethods-PostProc']['iizuna'] = \iizunats\iizuna\Decoder\UrlDecoder::class . '->decodeUrl';