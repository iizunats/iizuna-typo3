<?php

namespace iizunats\iizuna\ViewHelpers;

use iizunats\iizuna\Utility\ApiUtility;
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


	/**
	 * We add another argument to the base f:render viewhelper.
	 */
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
		$cHash = $this->partialCacheRepository->getCacheHashByRenderedPartial($this->renderingContext, $partial);

		return static::renderStatic(
			[
				'section'         => $section,
				'partial'         => $partial,
				'arguments'       => $arguments,
				'optional'        => $optional,
				'iizunaComponent' => $this->arguments['iizunaComponent'],
				'cacheHash'       => $cHash,
			],
			$this->buildRenderChildrenClosure(),
			$this->renderingContext
		);
	}


	/**
	 * Adds the wrapper to the partial that is later used by iizuna zu receive the template information
	 *
	 * @param string $iizunaComponent
	 * @param string $templatePath
	 * @param string $childContent
	 *
	 * @return string
	 */
	private static function wrapChildrenInComponentWrapper (string $iizunaComponent, string $templatePath, string $childContent): string {
		return "<div $iizunaComponent template-path=\"$templatePath\">$childContent</div>";
	}


	/**
	 * Renders the children of this viewhelper (basically the content of the partial that should be used) and adds a wrapper to the content
	 * for iizuna to get the templates from an api
	 *
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 *
	 * @return string
	 */
	public static function renderStatic (array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		return self::wrapChildrenInComponentWrapper(
			$arguments['iizunaComponent'],
			ApiUtility::buildIizunaPath($renderingContext, $arguments['partial']),
			parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext)
		);
	}
}