<?php

namespace iizunats\iizuna\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;



/**
 * Class PartialCache
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Domain\Model
 */
class PartialCache extends AbstractEntity {

	/**
	 * path
	 *
	 * @var string
	 */
	protected $clearPath = '';
	/**
	 * hash
	 *
	 * @var string
	 */
	protected $hash = '';

	/**
	 * partial
	 *
	 * @var string
	 */
	protected $partial = '';


	/**
	 * @return string
	 */
	public function getClearPath () {
		return $this->clearPath;
	}


	/**
	 * @param string $clearPath
	 */
	public function setClearPath ($clearPath) {
		$this->clearPath = $clearPath;
	}


	/**
	 * @return string
	 */
	public function getHash () {
		return $this->hash;
	}


	/**
	 * @param string $hash
	 */
	public function setHash ($hash) {
		$this->hash = $hash;
	}


	/**
	 * @return string
	 */
	public function getPartial () {
		return $this->partial;
	}


	/**
	 * @param string $partial
	 */
	public function setPartial ($partial) {
		$this->partial = $partial;
	}
}