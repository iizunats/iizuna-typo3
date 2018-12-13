<?php

namespace Iizunats\Iizuna\ViewHelpers\Uri;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;



/**
 * Class ApiViewHelper
 *
 * This ViewHelper returns the url to the iizuna partial api.
 * The only required argument is the partial name which is exactly the same as the one passed to <f:render />.
 *
 * If the controller context is not correct, then set the "extension" argument and pass the name of the extension,
 * from which the partial should be rendered
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\Iizuna\ViewHelpers\Uri
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
		$TemplateService = GeneralUtility::makeInstance(TemplateService::class);
		$TemplateService->tt_track = 0;
		$TemplateService->init();
		$TemplateService->runThroughTemplates($rootLine);
		$TemplateService->generateConfig();

		$typoScript = $TemplateService->setup;

		$serverName = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL);

		return !$typoScript['config.']['baseURL'] ? self::getProtocol() . $serverName . '/' : $typoScript['config.']['baseURL'];
	}


	/**
	 * Returns the protocol with which the current request was dispatched
	 *
	 * @return string
	 */
	private static function getProtocol (): string {
		$https = filter_input(INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING);
		$port = filter_input(INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_NUMBER_INT);
		$secureRequest = ((!empty($https) && $https != 'off') || $port == 443);

		return 'http' . ($secureRequest ? 's' : '') . '://';
	}


	/**
	 * Returns the path to the iizuna api for the given rendering context and partial
	 *
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContext $renderingContext
	 * @param string $partial
	 * @param string $extension
	 * @param bool $absolute
	 * @param array $arguments
	 *
	 * @return string
	 */
	private static function buildIizunaPath (RenderingContext $renderingContext, string $partial, $extension = null, $absolute = true, array $arguments = []) {
		if ($extension === null) {
			$pluginName = $renderingContext->getControllerContext()->getRequest()->getControllerExtensionName();
			$extension = GeneralUtility::camelCaseToLowerCaseUnderscored($pluginName);
		}

		return ($absolute ? self::getBaseUrl() : '') . "iizuna/$extension/$partial" . (!empty($arguments) ? ('?' . http_build_query($arguments)) : '');
	}
}