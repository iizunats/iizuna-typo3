<?php

namespace iizunats\iizuna\ViewHelpers\Uri;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;



/**
 * Class ApiViewHelper
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\ViewHelpers\Uri
 */
class ApiViewHelper extends AbstractViewHelper {

	/**
	 * @param string $partial
	 * @param string $extension
	 * @param bool $absolute
	 * @param array $arguments
	 *
	 * @return string
	 */
	public function render ($partial, $extension = null, $absolute = true, array $arguments = []) {
		return $this->buildIizunaPath($this->renderingContext, $partial, $extension, $absolute, $arguments);
	}


	/**
	 * Returns the current base url based by the TypoScript Configuration
	 *
	 * @return string
	 */
	private static function getBaseUrl () {
		$backendUtility = GeneralUtility::makeInstance(BackendUtility::class);
		$rootLine = $backendUtility->BEgetRootline(1);
		$TSObj = GeneralUtility::makeInstance(TemplateService::class);
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();

		$TS = $TSObj->setup;

		return !$TS['config.']['baseURL'] ? self::getProtocol() . $_SERVER['SERVER_NAME'] . '/' : $TS['config.']['baseURL'];
	}


	/**
	 * Returns the protocol with which the current request was dispatched
	 *
	 * @return string
	 */
	private static function getProtocol (): string {
		$secureRequest = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443);

		return 'http' . ($secureRequest ? 's' : '') . '://';
	}


	/**
	 * Returns the path to the iizuna api for the given rendering context and partial
	 *
	 * @param RenderingContextInterface $renderingContext
	 * @param string $partial
	 * @param string $extension
	 * @param bool $absolute
	 * @param array $arguments
	 *
	 * @return string
	 */
	private static function buildIizunaPath (RenderingContextInterface $renderingContext, string $partial, $extension = null, $absolute = true, array $arguments = []) {
		if ($extension !== null) {
			$pluginNameUnderscore = $extension;
		} else {
			$pluginName = $renderingContext->getControllerContext()->getRequest()->getPluginName();
			$pluginNameUnderscore = GeneralUtility::camelCaseToLowerCaseUnderscored($pluginName);
		}

		return ($absolute ? self::getBaseUrl() : '') . "iizuna/$pluginNameUnderscore/$partial" . (!empty($arguments) ? ('?' . http_build_query($arguments)) : '');
	}
}