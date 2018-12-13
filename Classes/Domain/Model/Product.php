<?php

namespace Iizunats\IiProduct\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;



/**
 * Class Product
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\IiProduct\Domain\Model
 */
class Product extends AbstractEntity implements \JsonSerializable {

	/**
	 * title
	 *
	 * @var string
	 */
	protected $title = '';
	/**
	 * description
	 *
	 * @var string
	 */
	protected $description = '';


	/**
	 * @return string
	 */
	public function getTitle (): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle (string $title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription (): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription (string $description) {
		$this->description = $description;
	}


	/**
	 * Returns the properties of this model as array.
	 * Used by \Iizunats\IiProduct\View\Product\Page
	 *
	 * @return array
	 */
	public function jsonSerialize () {
		return [
			'uid'         => $this->getUid(),
			'title'       => $this->getTitle(),
			'description' => $this->getDescription(),
		];
	}
}