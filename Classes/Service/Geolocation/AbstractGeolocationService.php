<?php declare(strict_types = 1);

namespace Bzga\BzgaBeratungsstellensuche\Service\Geolocation;

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
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeoPositionDemandInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeopositionInterface;
use Bzga\BzgaBeratungsstellensuche\Factories\GeocoderFactory;
use Bzga\BzgaBeratungsstellensuche\Factories\HttpClientFactory;
use Bzga\BzgaBeratungsstellensuche\Service\SettingsService;
use Geocoder\Geocoder;
use Geocoder\Provider\Provider;

/**
 * @author Sebastian Schreiber
 */
abstract class AbstractGeolocationService implements GeolocationServiceInterface
{

    /**
     * @var string
     */
    public const DISTANCE_SQL_FIELD = '(6371.01 * acos(cos(radians(%1$f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$f)) + sin(radians(%1$f) ) * sin(radians(latitude))))';

    /**
     * @var float
     */
    public const EARTH_RADIUS = 6371.01;

    /**
     * @var int
     */
    public const DEFAULT_RADIUS = 10;

    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var Provider
     */
    protected $geocoder;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
        $adapter = HttpClientFactory::createInstance();
        $this->geocoder = GeocoderFactory::createInstance(
            $this->settingsService->getByPath('geocoder') ?? GeocoderFactory::TYPE_OPEN_STREET_MAP,
            $adapter,
            $this->settingsService->getByPath('map.region'),
            $this->settingsService->getByPath('map.apiKey')
        );
    }

    public function calculateDistance(GeopositionInterface $demandPosition, GeopositionInterface $locationPosition): float
    {
        return self::EARTH_RADIUS * acos(
                cos(deg2rad($demandPosition->getLatitude())) * cos(deg2rad($locationPosition->getLatitude())) * cos(
                    deg2rad($locationPosition->getLongitude()) - deg2rad($demandPosition->getLongitude())
                ) + sin(deg2rad($demandPosition->getLatitude())) * sin(deg2rad($locationPosition->getLatitude()))
            );
    }

    public function getDistanceSqlField(GeoPositionDemandInterface $demandPosition, string $table, string $alias = 'distance'): string
    {
        return sprintf(
                   self::DISTANCE_SQL_FIELD,
                   $demandPosition->getLatitude(),
                   $demandPosition->getLongitude(),
                   $demandPosition->getKilometers()
               ) . ' AS ' . $alias;
    }
}
