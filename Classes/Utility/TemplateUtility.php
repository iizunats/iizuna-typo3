<?php

namespace iizunats\iizuna\Utility;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;



/**
 * Class TemplateUtility
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class TemplateUtility {

	/**
	 * registeredPartials
	 *
	 * @var array
	 */
	protected static $registeredPartials = [];
	/**
	 * objectManager
	 *
	 * @var null
	 */
	protected static $objectManager = null;


	/**
	 * @param string $partialFile
	 * @param string $apiPath
	 *
	 * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
	 */
	public static function registerPartial (string $partialFile, string $apiPath) {
		$resolvedPath = GeneralUtility::getFileAbsFileName($partialFile);
		if (!file_exists($resolvedPath)) {
			throw new FileDoesNotExistException("Partial '$partialFile' could not be Found!");
		}
		self::$registeredPartials[$apiPath] = self::fff($partialFile);//@todo: directly resolve correct file path including EXT: path's
	}


	private static function fff (string $file) {
		$view = self::getObjectManager()->get(StandaloneView::class);
		$view->setTemplatePathAndFilename($file);

		return $view->render();
	}


	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected static function getObjectManager (): ObjectManagerInterface {
		if (self::$objectManager === null) {
			/** @var ObjectManager $objectManager */
			$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
			self::setObjectManager($objectManager);
		}

		return self::$objectManager;
	}


	/**
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public static function setObjectManager (ObjectManagerInterface $objectManager) {
		self::$objectManager = $objectManager;
	}


	/**
	 * @param string $apiPath
	 *
	 * @return string
	 */
	public static function getPartialForPath (string $apiPath): string {
		return self::$registeredPartials[$apiPath];
	}
}