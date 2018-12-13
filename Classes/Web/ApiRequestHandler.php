<?php

namespace Iizunats\Iizuna\Web;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Web\AbstractRequestHandler;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Service\ExtensionService;



/**
 * Class ApiRequestHandler
 *
 * This handler looks for any request starting with "iizuna" and expects them to belong to this extension.
 * (I mean ... common, how probably is it that any normal url would start with exactly this word)
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\Iizuna\Web
 */
class ApiRequestHandler extends AbstractRequestHandler {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager = null;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\ExtensionService
	 */
	protected $extensionService = null;


	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function injectConfigurationManager (ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}


	/**
	 * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
	 */
	public function injectExtensionService (ExtensionService $extensionService) {
		$this->extensionService = $extensionService;
	}


	/**
	 * This handler has a unbelievable high priority (of OVER 9000!!!) because, when the canHandleRequest method returns true,
	 * then the request is definitely for us.
	 *
	 * @return int
	 */
	public function getPriority () {
		return 9001;
	}


	/**
	 * Check whether the url path starts with iizuna
	 *
	 * @return bool
	 */
	public function canHandleRequest () {
		$requestUrl = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
		$parsedRequest = parse_url($requestUrl);
		$path = trim($parsedRequest['path'], '/');

		return strpos($path, 'iizuna') === 0;
	}


	/**
	 * Handles a raw request and returns the response.
	 *
	 * @return \TYPO3\CMS\Extbase\Mvc\ResponseInterface
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InfiniteLoopException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidControllerNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidRequestMethodException
	 * @api
	 */
	public function handleRequest () {
		$request = $this->createPartialOutputRequest();
		$request->setIsCached(false);

		/** @var $response \TYPO3\CMS\Extbase\Mvc\ResponseInterface */
		$response = $this->objectManager->get(Response::class);
		$this->dispatcher->dispatch($request, $response);

		return $response;
	}


	/**
	 * Creates a specialized request just for the renderAction of the PartialOutputController.
	 *
	 * @return \TYPO3\CMS\Extbase\Mvc\Web\Request
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidControllerNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidRequestMethodException
	 */
	private function createPartialOutputRequest () {
		/** @var \TYPO3\CMS\Extbase\Mvc\Web\Request $request */
		$request = $this->objectManager->get(Request::class);
		$request->setPluginName('PartialOutput');
		$request->setControllerExtensionName('Iizuna');
		$request->setControllerVendorName('Iizunats');
		$request->setControllerName('PartialOutput');
		$request->setControllerActionName('render');
		$request->setRequestUri(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
		$request->setBaseUri(GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
		$request->setMethod($this->environmentService->getServerRequestMethod());
		$request->setFormat('html');

		return $request;
	}
}