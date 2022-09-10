<?php

namespace App\Traits;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\File;

trait MediaProcessors {
    public function verifyAudio($fileName) {
        return ($this->getAppEnvironment() === 'dev' ? 'http://127.0.0.2' : 'https://rx931.com') . '/audios/'.$fileName;
    }

    public function verifyPhoto($fileName, $directory, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        if($fileName === null || $fileName === "") {
            $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        } else {
            $photoDirectory = '/images/'.$directory.'/'.$fileName;

            if(!File::exists($photoDirectory)) {
                return ($this->getAppEnvironment() === 'dev' ? 'http://127.0.0.2' : 'https://rx931.com') . '/images/'.$directory.'/'.$fileName;
            } else {
                $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }
        }

        return $fileName;
    }

    public function getFileName(string $longPhoto, string $banner, string $banner500, string $mobileWallpaper, string $desktopWallpaper): string
    {
        $fileName = 'default.png';

        if ($longPhoto) {
            $fileName = 'default-long.png';
        } elseif ($banner) {
            $fileName = 'default-banner.png';
        } elseif ($banner500) {
            $fileName = 'default-banner-sm.png';
        } elseif ($mobileWallpaper) {
            $fileName = 'mobile-wallpaper-missing.png';
        } elseif ($desktopWallpaper) {
            $fileName = 'desktop-wallpaper-missing.png';
        }

        return ($this->getAppEnvironment() === 'dev' ? 'http://127.0.0.2' : 'https://rx931.com') . '/images/_assets/' . $fileName;
    }
}
