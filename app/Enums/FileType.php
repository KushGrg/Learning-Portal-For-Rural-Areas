<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class FileType extends Enum
{
    const Pdf = 'pdf';
    const Video = 'video';
    const Image = 'image';

    public static function labels(): array
    {
        return [
            self::Pdf => 'PDF Document',
            self::Video => 'Video',
            self::Image => 'Image',
        ];
    }
}
