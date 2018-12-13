<?php

namespace Iizunats\IiProduct\View\Product;

use TYPO3\CMS\Extbase\Mvc\View\AbstractView;



class Page extends AbstractView {

	public function render () {
		header('Content-type:application/json; charset=UTF-8');
		$print = [];
		/** @var \Iizunats\IiProduct\Domain\Model\Product $product */
		foreach ($this->variables['products'] as $product) {
			$print[] = $product->jsonSerialize();
		}
		die(json_encode($print));
	}
}