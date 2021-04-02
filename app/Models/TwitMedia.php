<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TwitMedia extends Model
{
    use HasFactory, SoftDeletes;

    const MEDIA_TYPE_IMAGE = 'image';
    const MEDIA_TYPE_VIDEO = 'video';
    const MEDIA_TYPES = [self::MEDIA_TYPE_IMAGE, self::MEDIA_TYPE_VIDEO];

    const IMAGE_MIME_TYPE = [
        'image/bmp',
        'image/gif',
        'image/vnd.microsoft.icon',
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/tiff',
        'image/webp',
    ];
    const VIDEO_MIME_TYPE = [
        'video/x-msvideo',
        'video/mpeg',
        'video/ogg',
        'video/mp2t',
        'video/webm',
        'video/3gpp',
        'video/3gpp2',
        'video/mp4',
        'video/x-matroska',
    ];
}
