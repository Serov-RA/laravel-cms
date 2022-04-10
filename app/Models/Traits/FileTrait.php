<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    public static function getMaxSize(): int
    {
        $max_size = strtolower(ini_get('upload_max_filesize'));

        if (strrpos($max_size, 'k') !== false) {
            return str_replace('k', '', $max_size) * 1024;
        }

        if (strrpos($max_size, 'm') !== false) {
            return str_replace('m', '', $max_size) * 1024 * 1024;
        }

        if (strrpos($max_size, 'g') !== false) {
            return str_replace('g', '', $max_size) * 1024 * 1024 * 1024;
        }

        return (int)$max_size;
    }

    public function getUploadDir(?string $subdirs = null): string
    {
        $dir = 'public'.DIRECTORY_SEPARATOR.strtolower($this->getModelName());

        if ($subdirs !== null) {
            $hash = md5($subdirs);
            $dir .= DIRECTORY_SEPARATOR.substr($hash, 0, 2).
                DIRECTORY_SEPARATOR.substr($hash, 2, 2);
        }

        self::createDirIfNotExists($dir);

        return $dir;
    }

    public function deleteFile(string $path): void
    {
        if (!str_starts_with($path, 'http')) {
            $filepath = $this->getStoragePathByPublic($path);

            if (Storage::exists($filepath)) {
                Storage::delete($filepath);
            }
        }
    }

    // FIXME
    public function getWebPath(string $path): string
    {
        return str_replace(App::basePath(), '', $path);
    }

    public function getPublicPathByStorage(string $path): string
    {
        return str_replace('public', '/storage', trim($path, '/'));
    }

    public function getStoragePathByPublic(string $path): string
    {
        return str_replace('storage', 'public', trim($path, '/'));
    }

    public function getAttrDir(string $attribute): string
    {
        $id_dirs = str_pad((string)($this->id ?? 0), 4, '0', STR_PAD_LEFT);

        $dir = $this->getUploadDir() . DIRECTORY_SEPARATOR . $attribute .
            DIRECTORY_SEPARATOR . substr($id_dirs, 0, 2) .
            DIRECTORY_SEPARATOR . substr($id_dirs, 2, 2);

        self::createDirIfNotExists($dir);

        return $dir;
    }

    public static function createDirIfNotExists(string $dir): void
    {
        if (!Storage::exists($dir)) {
            Storage::makeDirectory($dir);

            $parts = explode(DIRECTORY_SEPARATOR, $dir);
            $path = '';

            foreach ($parts as $part) {
                $path .= $part;
                Storage::setVisibility($path, 'public');
                $path .= DIRECTORY_SEPARATOR;
            }
        }
    }

    public function getFileNameById(): string
    {
        $item_id = (string)($this->id ?? uniqid(Auth::user()->id, true));

        return substr(md5($item_id), 16) . substr(md5($item_id), 0, 16);
    }

    public function getAttrImage(string $attribute): string
    {
        return $this->getAttrDir($attribute) . DIRECTORY_SEPARATOR . $this->getFileNameById().'.png';
    }

    public function getAttrFile(string $attribute, string $ext): string
    {
        return $this->getAttrDir($attribute) . DIRECTORY_SEPARATOR . $this->getFileNameById().'.'.$ext;
    }

    /**
     * @throws Exception
     * FIXME
     */
    public function saveAttrImage(string $path, string $attribute): bool
    {
        $path = !str_contains($path, Yii::getAlias('@webroot')) ? Yii::getAlias('@webroot').$path : $path;

        if (!file_exists($path)) {
            return false;
        }

        Image::getImagine()->open($path)->save($this->getAttrImage($attribute));
        FileHelper::unlink($path);

        return true;
    }

    /**
     * @throws Exception
     * FIXME
     */
    public function getAttrPreview(
        string $attribute,
        ?int $width = 250,
        ?int $height = 250,
        bool $refresh = false): ?string
    {
        $path = $this->getAttrImage($attribute);
        return $this->getPreview($path, $width, $height, $refresh);
    }

    // FIXME
    public function getPreview(
        string $path,
        ?int $width = 250,
        ?int $height = 250,
        bool $refresh = false): ?string
    {
        if (!file_exists($path)) {
            return null;
        }

        if ($width === null || $height === null) {
            $size = Image::getImagine()->open($path)->getSize();

            if ($width === null && $height === null) {
                $width = $size->getWidth();
                $height = $size->getHeight();
            }

            if ($width === null) {
                $width = (int)ceil($size->getWidth() / ($size->getHeight() / $height));
            }

            if ($height === null) {
                $height = (int)ceil($size->getHeight() / ($size->getWidth() / $width));
            }
        }

        $preview_path = dirname($path).'/'.$this->getFileNameById().'_'.$width.'_'.$height.'.png';

        if ($refresh || !file_exists($preview_path)) {
            Image::thumbnail($path, $width, $height)->save($preview_path);
        }

        return $this->getWebPath($preview_path);
    }

    /**
     * @throws Exception
     * FIXME
     */
    public function getAllPreviews(string $attribute): array
    {
        $dir = $this->getAttrDir($attribute);
        $files = scandir($dir);
        $filename = $this->getFileNameById();
        $previews = [];

        foreach ($files AS $file) {
            if (preg_match('/^'.$filename.'_(\d+)_(\d+)\./U', $file, $matches)) {
                $previews[] = [
                    'path'   => $dir.'/'.$file,
                    'width'  => $matches[1],
                    'height' => $matches[2],
                ];
            }
        }

        return $previews;
    }
}
