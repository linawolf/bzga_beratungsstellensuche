<?php declare(strict_types = 1);

namespace Bzga\BzgaBeratungsstellensuche\Property\TypeConverter;

/*
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

use Bzga\BzgaBeratungsstellensuche\Property\TypeConverterBeforeInterface;

final class BoolConverter implements TypeConverterBeforeInterface
{

    /**
     * @inheritDoc
     */
    public function supports($source, string $type = self::CONVERT_BEFORE)
    {
        return is_bool($source);
    }

    /**
     * @param mixed $source
     */
    public function convert($source, array $configuration = null): string
    {
        return ($source === true)?'1':'0';
    }
}
