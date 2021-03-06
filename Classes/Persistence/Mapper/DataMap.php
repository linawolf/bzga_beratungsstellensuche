<?php declare(strict_types = 1);

namespace Bzga\BzgaBeratungsstellensuche\Persistence\Mapper;

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
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;

/**
 * @author Sebastian Schreiber
 */
class DataMap
{

    /**
     * @var string[]
     */
    private $cachedTableNames = [];

    /**
     * @var DataMapFactory
     */
    private $dataMapFactory;

    public function __construct(DataMapFactory $dataMapFactory)
    {
        $this->dataMapFactory = $dataMapFactory;
    }

    public function getTableNameByClassName(string $className): string
    {
        if (!isset($this->cachedTableNames[$className])) {
            $dataMap = $this->dataMapFactory->buildDataMap($className);
            $this->cachedTableNames[$className] = $dataMap->getTableName();
        }

        return $this->cachedTableNames[$className];
    }
}
