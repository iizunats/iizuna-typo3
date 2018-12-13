<?php

namespace iizunats\IizunaExample\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;



class ProductRepository extends Repository {

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