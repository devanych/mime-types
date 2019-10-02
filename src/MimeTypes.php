<?php

declare(strict_types=1);

namespace Devanych\Mime;

use InvalidArgumentException;

use function array_unique;
use function array_merge;
use function strtolower;
use function is_string;
use function is_array;
use function sprintf;
use function gettype;
use function trim;

class MimeTypes implements MimeTypesInterface, MimeTypesMapsInterface
{
    /**
     * @var array
     */
    private $extensions = [];

    /**
     * @var array
     */
    private $mimeTypes = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $map = [])
    {
        $this->addMap($map);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions(string $mimeType): array
    {
        $lowerMime = strtolower(trim($mimeType));
        $extensions = self::EXTENSIONS[$lowerMime] ?? self::EXTENSIONS[$mimeType] ?? [];

        if ($this->extensions) {
            $customExtensions = $this->extensions[$lowerMime] ?? $this->extensions[$mimeType] ?? [];
            $extensions = $customExtensions ? array_unique(array_merge($customExtensions, $extensions)) : $extensions;
        }

        return $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeTypes(string $extension): array
    {
        $lowerExt = strtolower(trim($extension));
        $mimeTypes = self::MIME_TYPES[$lowerExt] ?? self::MIME_TYPES[$extension] ?? [];

        if ($this->mimeTypes) {
            $customMimeTypes = $this->mimeTypes[$lowerExt] ?? $this->mimeTypes[$extension] ?? [];
            $mimeTypes = $customMimeTypes ? array_unique(array_merge($customMimeTypes, $mimeTypes)) : $mimeTypes;
        }

        return $mimeTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function addMap(array $map): void
    {
        foreach ($map as $mimeType => $extensions) {
            if (!is_string($mimeType)) {
                throw new InvalidArgumentException(sprintf(
                    'MIME type MUST be string, received `%s`',
                    gettype($mimeType)
                ));
            }

            if (!is_array($extensions)) {
                throw new InvalidArgumentException(sprintf(
                    'Extensions MUST be array, received `%s`',
                    gettype($extensions)
                ));
            }

            $this->extensions[$mimeType] = $extensions;

            foreach ($extensions as $extension) {
                if (!is_string($extension)) {
                    throw new InvalidArgumentException(sprintf(
                        'Extension MUST be string, received `%s`',
                        gettype($extension)
                    ));
                }

                $this->mimeTypes[$extension][] = $mimeType;
            }
        }
    }
}
