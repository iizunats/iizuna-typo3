<?php
declare(strict_types=1);

namespace iizunats\iizuna\Tests\Unit\Utility;

use iizunats\iizuna\Tests\Unit\AbstractUnitTest;
use iizunats\iizuna\Utility\TemplateUtility;
use Prophecy\Prophecy\MethodProphecy;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;



/**
 * Class TemplateUtilityTest
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Tests\Unit\Utility
 */
class TemplateUtilityTest extends AbstractUnitTest {

	/**
	 * @test
	 */
	public function testPartialRegistration () {
		$objectManagerProphecy = $this->prophesize(ObjectManagerInterface::class);

		$objectManagerProphecy->addMethodProphecy(new MethodProphecy($objectManagerProphecy, 'get', [
			'TYPO3\CMS\Fluid\View\StandaloneView',
		]));
		/** @var ObjectManagerInterface $objectManager */
		$objectManager = $objectManagerProphecy->reveal();
		$objectManagerProphecy->get('TYPO3\CMS\Fluid\View\StandaloneView')->willReturn(GeneralUtility::makeInstance(StandaloneView::class));

		TemplateUtility::setObjectManager($objectManager);

		TemplateUtility::registerPartial('Tests/Unit/Utility/TemplateUtilityTest.html', 'template-utility');
		$this->assertEquals('test content', TemplateUtility::getPartialForPath('template-utility'), 'Partial could not be retrieved!');
	}
}