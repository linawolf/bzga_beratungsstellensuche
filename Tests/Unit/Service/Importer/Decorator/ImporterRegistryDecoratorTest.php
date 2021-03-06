<?php declare(strict_types = 1);

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Service\Importer\Decorator;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use Bzga\BzgaBeratungsstellensuche\Service\Importer\Decorator\ImporterRegistryDecorator;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\ImporterInterface;
use TYPO3\CMS\Core\Registry;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @author Sebastian Schreiber
 */
class ImporterRegistryDecoratorTest extends UnitTestCase
{

    /**
     * @var ImporterRegistryDecorator
     */
    protected $subject;

    /**
     * @var ImporterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $importer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Registry
     */
    protected $registry;

    /**
     */
    protected function setUp()
    {
        $this->importer = $this->getMockBuilder(ImporterInterface::class)->getMock();
        $this->registry = $this->getMockBuilder(Registry::class)->getMock();
        $this->subject = new ImporterRegistryDecorator($this->importer, $this->registry);
    }

    /**
     * @test
     * @dataProvider contentDataProvider
     */
    public function importWithAlreadyImportedContent($content)
    {
        $hash = md5($content);
        $this->registry->expects($this->once())->method('get')->willReturn($hash);
        $this->registry->expects($this->never())->method('set');
        $this->importer->expects($this->never())->method('import');

        $this->subject->import($content);
    }

    /**
     * @test
     * @dataProvider contentDataProvider
     * @param $content
     */
    public function importUpdatedContent($content)
    {
        $hash = md5('other content');
        $this->registry->expects($this->once())->method('get')->willReturn($hash);
        $this->registry->expects($this->once())->method('set');
        $this->importer->expects($this->once())->method('import');
        $this->subject->import($content);
    }

    /**
     * @return array
     */
    public function contentDataProvider()
    {
        return [
            ['some fake content'],
        ];
    }
}
