<?php

declare(strict_types=1);

namespace Devanych\Tests\Mime;

use PHPUnit\Framework\TestCase;
use Devanych\Mime\MimeTypes;
use InvalidArgumentException;

class MimeTypesTest extends TestCase
{
    /**
     * @var array
     */
    private $customMap;

    public function setUp(): void
    {
        $this->customMap = [
            'custom/mime1' => ['ext1'],
            'custom/mime2' => ['ext1', 'ext2'],
        ];
    }

    public function testGetExtensions(): void
    {
        $mimeTypes = new MimeTypes();
        self::assertEquals(['jpeg', 'jpg', 'jpe'], $mimeTypes->getExtensions('image/jpeg'));
        self::assertEquals(MimeTypes::EXTENSIONS['image/jpeg'], $mimeTypes->getExtensions('image/jpeg'));
        self::assertEquals([], $mimeTypes->getExtensions('not/found'));
    }

    public function testGetMimeTypes(): void
    {
        $mimeTypes = new MimeTypes();
        self::assertEquals(['image/jpeg', 'image/pjpeg'], $mimeTypes->getMimeTypes('jpeg'));
        self::assertEquals(MimeTypes::MIME_TYPES['jpeg'], $mimeTypes->getMimeTypes('jpeg'));
        self::assertEquals([], $mimeTypes->getMimeTypes('notfound'));
    }

    public function testAddMap(): void
    {
        $mimeTypes = new MimeTypes();
        $mimeTypes->addMap($this->customMap);
        self::assertEquals(['ext1'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime2'));
        self::assertNotEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext1'));
        self::assertEquals(['custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
        self::assertNotEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
    }

    public function testAddMapThroughConstructor(): void
    {
        $mimeTypes = new MimeTypes($this->customMap);
        self::assertEquals(['ext1'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime2'));
        self::assertNotEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext1'));
        self::assertEquals(['custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
        self::assertNotEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
    }

    /**
     * @return array
     */
    public function invalidCustomMapProvider(): array
    {
        return [
            'Not Declare Mime Type' => [ ['ext1', 'ext2'] ],
            'Not String Mime Type' => [ [111 => ['ext1', 'ext2']] ],

            'Not Array Extensions (string)' => [ ['custom/mime' => 'ext'] ],
            'Not Array Extensions (integer)' => [ ['custom/mime' => 111] ],
            'Not Array Extensions (boolean)' => [ ['custom/mime' => true] ],
            'Not Array Extensions (null)' => [ ['custom/mime' => null] ],

            'Not String Extension (ext2 - array)' => [ ['custom/mime' => ['ext1', ['ext2']]] ],
            'Not String Extension (ext2 - integer)' => [ ['custom/mime' => ['ext1', 111]] ],
            'Not String Extension (ext2 - boolean)' => [ ['custom/mime' => ['ext1', false]] ],
            'Not String Extension (ext2 - null)' => [ ['custom/mime' => ['ext1', null]] ],
        ];
    }

    /**
     * @dataProvider invalidCustomMapProvider
     * @param mixed $customMap
     */
    public function testAddMapThrowExceptionForInvalidCustomMap($customMap): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new MimeTypes())->addMap($customMap);
    }

    /**
     * @dataProvider invalidCustomMapProvider
     * @param mixed $customMap
     */
    public function testAddMapThroughConstructorThrowExceptionForInvalidCustomMap($customMap): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MimeTypes($customMap);
    }
}
