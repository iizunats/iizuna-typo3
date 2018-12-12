<?php

namespace iizunats\IizunaExample\View\Product;

class Page extends \TYPO3\CMS\Extbase\Mvc\View\AbstractView {

	public function render () {
		header('Content-type:application/json; charset=UTF-8');
		die(json_encode($this->variables['products']));
	}
}