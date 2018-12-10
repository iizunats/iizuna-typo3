<?php

namespace iizunats\iizuna\ViewHelpers;

use iizunats\iizuna\Domain\Model\PartialCache;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;



/**
 * Class RenderViewHelper
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\ViewHelpers
 */
class RenderViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper {

	/**
	 * partialCacheRepository
	 *
	 * @var \iizunats\iizuna\Domain\Repository\PartialCacheRepository
	 * @inject
	 */
	protected $partialCacheRepository = null;


	public function initializeArguments () {
		$this->registerArgument('iizunaComponent', 'string', 'Name of the iizuna component selector', true);
	}


	/**
	 * @param string $section
	 * @param string $partial
	 * @param array $arguments
	 * @param bool $optional
	 *
	 * @return string
	 */
	public function render ($section = null, $partial = null, $arguments = [], $optional = false) {
		return static::renderStatic(
			[
				'section'         => $section,
				'partial'         => $partial,
				'arguments'       => $arguments,
				'optional'        => $optional,
				'iizunaComponent' => $this->arguments['iizunaComponent'],
				'cacheHash'       => $this->getCacheHash($partial),
			],
			$this->buildRenderChildrenClosure(),
			$this->renderingContext
		);
	}


	private function getCacheHash ($partial) {
		$viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
		$view = $viewHelperVariableContainer->getView();
		$method = new \ReflectionMethod(\TYPO3\CMS\Fluid\View\TemplateView::class, 'getPartialSource');
		$method->setAccessible(true);
		$template = $method->invoke($view, $partial);
		$this->createCacheEntry($template);

		return md5($template);
	}


	private function createCacheEntry ($template) {
		$hash = md5($template);
		$Model = $this->partialCacheRepository->findOneByHash($hash);
		if ($Model === null) {
			/** @var PartialCache $PartialCache */
			$PartialCache = GeneralUtility::makeInstance(PartialCache::class);
			$PartialCache->setHash($hash);
			$PartialCache->setPartial(trim($template));
			$PartialCache->setClearPath(self::buildIizunaPath($this->renderingContext, $this->arguments['partial'], false));
			$this->partialCacheRepository->add($PartialCache);
			/** @var PersistenceManager $PersistenceManager */
			$PersistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
			$PersistenceManager->persistAll();
		}
	}


	private static function getBaseUrl () {
		$backendUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Utility\\BackendUtility');
		$rootLine = $backendUtility->BEgetRootline(1);
		$TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();

		$TS = $TSObj->setup;

		return !$TS['config.']['baseURL'] ? 'http://' . $_SERVER['SERVER_NAME'] . '/' : $TS['config.']['baseURL'];
	}


	private static function buildIizunaPath (RenderingContextInterface $renderingContext, $cacheHash, $df = true) {
		$pluginName = $renderingContext->getControllerContext()->getRequest()->getPluginName();
		$pluginNameUnderscore = GeneralUtility::camelCaseToLowerCaseUnderscored($pluginName);

		return ($df ? self::getBaseUrl() : '') . "iizuna/$pluginNameUnderscore/$cacheHash";
	}


	public static function renderStatic (array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		return '<div ' . $arguments['iizunaComponent'] . ' template-path="' . self::buildIizunaPath($renderingContext, $arguments['partial']) . '">' .
			parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext)
			. '</div>';
	}
}