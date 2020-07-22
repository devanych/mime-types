<?php

declare(strict_types=1);

namespace Devanych\Tests\Mime;

use Devanych\Mime\MimeTypesAllowed;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

class MimeTypesAllowedTest extends TestCase
{
    /**
     * @var array
     */
    private array $map;

    public function setUp(): void
    {
        $this->map = [
            'custom/mime1' => ['ext1'],
            'custom/mime2' => ['ext1', 'ext2'],
        ];
    }

    public function testMimeTypesInterfaceMethods(): void
    {
        $mimeTypes = new MimeTypesAllowed($this->map);
        self::assertEquals(['ext1'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime2'));
        self::assertNotEquals(['ext1', 'ext2'], $mimeTypes->getExtensions('custom/mime1'));
        self::assertEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext1'));
        self::assertEquals(['custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
        self::assertNotEquals(['custom/mime1', 'custom/mime2'], $mimeTypes->getMimeTypes('ext2'));
    }

    public function testConstructorThrowExceptionForEmptyMap(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Map with allowed mime types cannot be empty');
        (new MimeTypesAllowed([]));
    }

    public function testAddMapThrowExceptionForNotCalledInConstructor(): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Map with allowed mime types already added');
        (new MimeTypesAllowed($this->map))->addMap($this->map);
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
     * @param mixed $map
     */
    public function testAddMapThroughConstructorThrowExceptionForInvalidCustomMap($map): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MimeTypesAllowed($map);
    }
}
