<?php

namespace Iizunats\IiProduct\View\Product;

use TYPO3\CMS\Extbase\Mvc\View\AbstractView;



/**
 * Class Page
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\IiProduct\View\Product
 */
class Page extends AbstractView {

	/**
	 * This view is also just for demonstration purposes.
	 * It simply serializes the products and returns them as json.
	 */
	public function render () {
		header('Content-type:application/json; charset=UTF-8');
		$return = [];
		/** @var \Iizunats\IiProduct\Domain\Model\Product $product */
		foreach ($this->variables['products'] as $product) {
			$return[] = $product->jsonSerialize();
		}
		die(json_encode($return));
	}
}