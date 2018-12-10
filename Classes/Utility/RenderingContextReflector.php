<?php

namespace iizunats\iizuna\Utility;

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\View\TemplateView;



/**
 * Class RenderingContextReflector
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class RenderingContextReflector {

	/**
	 * Returns the results of the "getPartialSource" method of the passed RenderingContextInterface
	 *
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @param string $partial
	 *
	 * @return mixed
	 */
	public static function getPartialSource (RenderingContextInterface $renderingContext, string $partial) {
		$viewHelperVariableContainer = $renderingContext->getViewHelperVariableContainer();
		$view = $viewHelperVariableContainer->getView();
		$method = new \ReflectionMethod(TemplateView::class, 'getPartialSource');
		$method->setAccessible(true);

		return $method->invoke($view, $partial);
	}
}