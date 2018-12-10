<?php

namespace iizunats\iizuna\Domain\Repository;

use iizunats\iizuna\Domain\Model\PartialCache;
use iizunats\iizuna\Utility\ApiUtility;
use iizunats\iizuna\Utility\PartialCacheUtility;
use iizunats\iizuna\Utility\RenderingContextReflector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;



/**
 * Class PartialCacheRepository
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Domain\Repository
 */
class PartialCacheRepository extends Repository {

	/**
	 * Gets or creates the cache hash of the current partial and returns it
	 *
	 * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @param string $partial
	 *
	 * @return string
	 */
	public function getCacheHashByRenderedPartial (RenderingContextInterface $renderingContext, string $partial): string {
		$Model = $this->createCacheEntry(
			RenderingContextReflector::getPartialSource($renderingContext, $partial),
			ApiUtility::buildIizunaPath($renderingContext, $partial, false)
		);

		return $Model->getHash();
	}


	/**
	 * Creates a new cache entry based by the passed partial name and the hash of the template
	 *
	 * @param string $template
	 * @param string $relativePath
	 *
	 * @return \iizunats\iizuna\Domain\Model\PartialCache
	 */
	public function createCacheEntry (string $template, string $relativePath): PartialCache {
		$hash = PartialCacheUtility::hash($template);
		/** @var PartialCache $Model */
		$Model = $this->findOneByHash($hash);
		if ($Model !== null) {
			return $Model;
		}
		/** @var PartialCache $PartialCache */
		$PartialCache = GeneralUtility::makeInstance(PartialCache::class);
		$PartialCache->setHash($hash);
		$PartialCache->setPartial(trim($template));
		$PartialCache->setClearPath($relativePath);
		$this->add($PartialCache);
		$this->persistenceManager->persistAll();

		return $PartialCache;
	}
}