<?php

namespace iizunats\iizuna\Decoder;

use iizunats\iizuna\Utility\PartialRegistrationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;



/**
 * Class TemplateUtility
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class UrlDecoder {

	/**
	 * pObject
	 *
	 * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected $pObject = null;
	/**
	 * partialCache
	 *
	 * @var PartialRegistrationUtility
	 */
	protected $partialCache = null;


	public function __construct () {
		$this->partialCache = GeneralUtility::makeInstance(PartialRegistrationUtility::class);
	}


	/**
	 * Decodes the URL. This function is called from \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::checkAlternativeIdMethods()
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function decodeUrl (array $params) {
		$this->pObject = $params['pObj'];
		if ($this->canDecodeUrl()) {
			$this->outputTemplateForUrl();
		}
	}


	/**
	 * Either outputs the content if the given partial directly or does nothing resulting in calling the next hook
	 */
	private function outputTemplateForUrl () {
		$partialContent = $this->getPartialForPath($this->pObject->siteScript);
		if ($partialContent !== null) {
			exit($partialContent);
		}
	}


	/**
	 * Tries to get a partial from the database based by the passed path
	 *
	 * @param string $path
	 *
	 * @return null|string
	 */
	private function getPartialForPath (string $path) {
		list($url, $arguments) = explode('?', $path);
		list($iizuna, $extension, $partial) = explode('/', $url, 3);
		if ($this->partialCache->isRegistered($extension, $partial)) {
			$configuration = $this->partialCache->getConfiguration($extension, $partial);
			$passingArguments = [];
			foreach ($configuration[2] as $allowedArguments) {
				if (isset($_GET[$allowedArguments])) {
					$passingArguments[$allowedArguments] = strip_tags(urldecode($_GET[$allowedArguments]));
				}
			}

			return $this->partialCache->getPartial($extension, $partial, $passingArguments);
		}

		return null;
	}


	/**
	 * Checks whether the url starts with "iizuna" or not.
	 * If it does, then the request is for us!
	 *
	 * @return bool
	 */
	private function canDecodeUrl () {
		return strpos($this->pObject->siteScript, 'iizuna') === 0;
	}
}