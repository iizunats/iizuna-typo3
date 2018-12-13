<?php

namespace Iizunats\IiProduct\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;



/**
 * Class ProductRepository
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\IiProduct\Domain\Repository
 */
class ProductRepository extends Repository {

	/**
	 * Initialize the repository with the setting to ignore pages.
	 * This is just used for demonstration purposes (see other tutorial for how a plugin could be configured)
	 */
	public function initializeObject () {
		/** @var Typo3QuerySettings $defaultQuerySettings */
		$defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
		$defaultQuerySettings->setRespectStoragePage(false);
		$this->setDefaultQuerySettings($defaultQuerySettings);
	}


	/**
	 * Pretty stupid simple page example.
	 * Returns always a maximum of two Products (also for demonstration purposes).
	 * The offset of the database queries is defined by the passed page.
	 *
	 * @param int $page
	 *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByPage (int $page) {
		$query = $this->createQuery();
		$query->setLimit(2);
		$query->setOffset($page * 2);

		return $query->execute();
	}
}