<?php

namespace Iizunats\IiProduct\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;



class ProductRepository extends Repository {

	/**
	 * Initialize the repository with default query settings
	 */
	public function initializeObject () {
		/** @var Typo3QuerySettings $defaultQuerySettings */
		$defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
		$defaultQuerySettings->setRespectStoragePage(false);
		$this->setDefaultQuerySettings($defaultQuerySettings);
	}


	/**
	 * Pretty stupid simple page example
	 *
	 * @param $page
	 *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByPage ($page) {
		$query = $this->createQuery();
		$query->setLimit(2);
		$query->setOffset($page * 2);

		return $query->execute();
	}
}