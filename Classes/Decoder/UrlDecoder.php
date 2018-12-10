<?php

namespace iizunats\iizuna\Decoder;

/**
 * Class UrlDecoder
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Decoder
 */
class UrlDecoder {

	/**
	 * typoScriptFrontendController
	 *
	 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected $typoScriptFrontendController = null;


	/**
	 * Decodes the URL.
	 * This function is called from \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::checkAlternativeIdMethods()
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function decodeUrl (array $params) {
		$this->typoScriptFrontendController = $params['pObj'];
		if ($this->canDecodeUrl()) {
			$this->outputTemplateForUrl();
		}
	}


	/**
	 * Either outputs the content if the given partial directly or does nothing resulting in calling the next hook.
	 * We don't interfere in any other logic by this concept
	 */
	private function outputTemplateForUrl () {
		$partialTemplate = $this->getPartialForPath($this->typoScriptFrontendController->siteScript);
		if ($partialTemplate !== null) {
			exit($partialTemplate);
		}
	}


	/**
	 * Tries to get a partial from the database based by the passed path.
	 * If we can't find any cached partial, return null
	 *
	 * @param string $path
	 *
	 * @return null|string
	 */
	private function getPartialForPath (string $path) {
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('partial', 'tx_iizuna_domain_model_partialcache',
			'clear_path=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($path, 'tx_iizuna_domain_model_partialcache')
		);
		foreach ($rows as $row) {
			if ($row['partial'] !== '') {
				return $row['partial'];
			}
			break;
		}

		return null;
	}


	/**
	 * Checks whether the url starts with "iizuna" or not.
	 * If it does, then the request is for us!
	 *
	 * @return bool
	 */
	private function canDecodeUrl (): bool {
		return strpos($this->typoScriptFrontendController->siteScript, 'iizuna') === 0;
	}
}