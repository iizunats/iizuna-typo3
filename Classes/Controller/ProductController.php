<?php

namespace iizunats\IizunaExample\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;



class ProductController extends ActionController {

	/**
	 * productRepository
	 *
	 * @var \iizunats\IizunaExample\Domain\Repository\ProductRepository
	 */
	protected $productRepository = null;


	/**
	 * Simply passes the products to the view
	 */
	public function listAction () {
		$arguments = $this->request->getArguments();
		$this->view->assign('products', $this->productRepository->findByPage($arguments['page'] ?? 0));
	}


	/**
	 * Returns the next products as json based by the passed page
	 */
	public function pageAction () {
		$this->listAction();
	}
}