<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Section extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['title', 'subtitle', 'description', 'audio', 'qrcode'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image');
        $this->addMediaCollection('video');
        $this->addMediaCollection('audio');
        $this->addMediaCollection('qrcode');

    }
    public function getAudio(?string $lang = null)
    {
        if (!$lang) {
            return $this->getMedia('audio')->first(fn(Media $media) => $media->getCustomProperty('lang') === app()->getLocale());
        } else {
            return $this->getMedia('audio')->first(fn(Media $media) => $media->getCustomProperty('lang') === $lang);
        }
    }
    public function getQrCode(?string $lang = null)
    {
        if (!$lang) {
            return $this->getMedia('qrcode')->first(fn(Media $media) => $media->getCustomProperty('lang') === app()->getLocale());
        } else {
            return $this->getMedia('qrcode')->first(fn(Media $media) => $media->getCustomProperty('lang') === $lang);
        }
    }
}
