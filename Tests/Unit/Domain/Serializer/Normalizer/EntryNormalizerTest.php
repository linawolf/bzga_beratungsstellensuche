<?php


namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Domain\Serializer\Normalizer;

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
use Bzga\BzgaBeratungsstellensuche\Domain\Model\Entry;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\ValueObject\ImageLink;
use Bzga\BzgaBeratungsstellensuche\Domain\Repository\CategoryRepository;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\NameConverter\EntryNameConverter;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Normalizer\EntryNormalizer;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SJBR\StaticInfoTables\Domain\Model\CountryZone;
use SJBR\StaticInfoTables\Domain\Repository\CountryZoneRepository;
use Symfony\Component\Serializer\SerializerInterface;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * @author Sebastian Schreiber
 */
class EntryNormalizerTest extends UnitTestCase
{

    /**
     * @var EntryNormalizer
     */
    protected $subject;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var CountryZoneRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $countryZoneRepository;

    /**
     * @var CategoryRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $categoryRepository;

    /**
     * @var Dispatcher|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $signalSlotDispatcher;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->signalSlotDispatcher = $this->getMock(Dispatcher::class);
        $this->countryZoneRepository = $this->getMock(CountryZoneRepository::class, ['findOneByExternalId'],
            [], '', false);
        $this->categoryRepository = $this->getMock(CategoryRepository::class, ['findOneByExternalId'], [], '',
            false);
        $this->serializer = $this->getMock(SerializerNormalizer::class);
        $this->subject = new EntryNormalizer(null, new EntryNameConverter());
        $this->subject->setSerializer($this->serializer);
        $this->inject($this->subject, 'signalSlotDispatcher', $this->signalSlotDispatcher);
        $this->inject($this->subject, 'countryZoneRepository', $this->countryZoneRepository);
        $this->inject($this->subject, 'categoryRepository', $this->categoryRepository);
    }

    /**
     * @test
     */
    public function denormalizeEntryWithEntryNameConverter()
    {
        $data = [
            'bundesland' => 2,
            'traeger' => 'Institution',
            'titel' => 'Title',
            'untertitel' => 'Subtitle',
            'ansprechpartner' => 'Contact Person',
            'mapy' => 'Latitude',
            'mapx' => 'Longitude',
            'kurztext' => 'Teaser',
            'plz' => 'Zip',
            'ort' => 'City',
            'logo' => 'Image',
            'strasse' => 'Street',
            'telefon' => 'Telephone',
            'fax' => 'Telefax',
            'email' => 'Email',
            'link' => 'Link',
            'website' => 'Website',
            'beratertelefon' => 'Hotline',
            'hinweistext' => 'Notice',
            'beratungsschein' => 1,
            'angebot' => 'Description',
            'verband' => 'Association',
            'kontaktemail' => 'Contact email',
            'suchcontent' => 'Keywords',
            'beratungsart' => [],
        ];

        $countryZoneMock = $this->getMock(CountryZone::class);
        $this->countryZoneRepository->expects($this->once())->method('findOneByExternalId')->willReturn($countryZoneMock);

        $object = $this->subject->denormalize($data, Entry::class);
        /* @var $object Entry */
        self::assertSame($countryZoneMock, $object->getState());
        self::assertSame('Institution', $object->getInstitution());
        self::assertSame('Title', $object->getTitle());
        self::assertSame('Subtitle', $object->getSubtitle());
        self::assertSame('Contact Person', $object->getContactPerson());
        self::assertSame('Latitude', $object->getLatitude());
        self::assertSame('Longitude', $object->getLongitude());
        self::assertSame('Teaser', $object->getTeaser());
        self::assertSame('Zip', $object->getZip());
        self::assertSame('City', $object->getCity());
        self::assertInstanceOf(ImageLink::class, $object->getImage());
        self::assertSame('Street', $object->getStreet());
        self::assertSame('Telephone', $object->getTelephone());
        self::assertSame('Telefax', $object->getTelefax());
        self::assertSame('Email', $object->getEmail());
        self::assertSame('Website', $object->getWebsite());
        self::assertSame('Hotline', $object->getHotline());
        self::assertSame('Notice', $object->getNotice());
        self::assertSame('Description', $object->getDescription());
        self::assertSame('Association', $object->getAssociation());
        self::assertSame('Contact email', $object->getContactEmail());
        self::assertSame('Keywords', $object->getKeywords());
    }
}
