<?php

namespace Iizunats\Iizuna\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;



class RenderVariableViewHelper extends AbstractViewHelper {

	/**
	 * @return void
	 */
	public function initializeArguments () {
		$this->registerArgument('name', 'string', 'Name of variable to render', true);
	}


	/**
	 * Default render method to render ViewHelper with
	 * first defined optional argument as content.
	 *
	 * @return string Rendered string
	 * @api
	 */
	public function render () {
		return static::renderStatic(
			$this->arguments,
			$this->buildRenderChildrenClosure(),
			$this->renderingContext
		);
	}


	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 *
	 * @return mixed
	 */
	public static function renderStatic (array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		return $renderingContext->getVariableProvider()->get($arguments['name']);
	}
}