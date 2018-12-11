<?php

namespace iizunats\iizuna\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;



/**
 * Class ApiUtility
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class ApiUtility {


	/**
	 * Creates the public api path for iizuna.
	 *
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @param string $cacheHash
	 * @param bool $absolute
	 *
	 * @return string
	 */
	public static function buildIizunaPath (RenderingContextInterface $renderingContext, string $cacheHash, bool $absolute = true): string {
		$pluginName = $renderingContext->getControllerContext()->getRequest()->getPluginName();
		$pluginNameUnderscore = GeneralUtility::camelCaseToLowerCaseUnderscored($pluginName);

		return ($absolute ? self::getBaseUrl() : '') . "iizuna/$pluginNameUnderscore/$cacheHash";
	}



	/**
	 * Returns the current base url of the framework
	 *
	 * @return string
	 */
	private static function getBaseUrl (): string {
		$backendUtility = GeneralUtility::makeInstance(BackendUtility::class);
		$rootLine = $backendUtility->BEgetRootline(1);
		$TSObj = GeneralUtility::makeInstance(TemplateService::class);
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();

		$TS = $TSObj->setup;

		return $TS['config.']['baseURL'] ? $TS['config.']['baseURL'] : 'http://' . $_SERVER['SERVER_NAME'] . '/';
	}
}