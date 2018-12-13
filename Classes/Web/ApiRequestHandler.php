<?php

namespace Iizunats\Iizuna\Web;

class ApiRequestHandler extends \TYPO3\CMS\Extbase\Mvc\Web\AbstractRequestHandler {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\ExtensionService
	 */
	protected $extensionService;


	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function injectConfigurationManager (\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}


	/**
	 * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
	 */
	public function injectExtensionService (\TYPO3\CMS\Extbase\Service\ExtensionService $extensionService) {
		$this->extensionService = $extensionService;
	}


	public function getPriority () {
		return 999999;
	}


	public function canHandleRequest () {
		$r = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
		$rr = parse_url($r);
		$path = trim($rr['path'], '/');

		return strpos($path, 'iizuna') === 0;
	}


	/**
	 * Handles a raw request and returns the respsonse.
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
		$request = $this->ddfd();
		$request->setIsCached(false);

		/** @var $response \TYPO3\CMS\Extbase\Mvc\ResponseInterface */
		$response = $this->objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Response::class);
		$this->dispatcher->dispatch($request, $response);

		return $response;
	}


	/**
	 * @return \TYPO3\CMS\Extbase\Mvc\Web\Request
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidActionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidControllerNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidRequestMethodException
	 */
	private function ddfd () {
		/** @var \TYPO3\CMS\Extbase\Mvc\Web\Request $request */
		$request = $this->objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Request::class);
		$request->setPluginName('PartialOutput');
		$request->setControllerExtensionName('Iizuna');
		$request->setControllerVendorName('Iizunats');
		$request->setControllerName('PartialOutput');
		$request->setControllerActionName('render');
		$request->setRequestUri(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
		$request->setBaseUri(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
		$request->setMethod($this->environmentService->getServerRequestMethod());
		$request->setFormat('html');

		return $request;
	}
}