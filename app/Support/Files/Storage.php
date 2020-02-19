<?php

declare(strict_types=1);

namespace App\Support\Files;

use Storage as NativeStorage;

class Storage
{
    const NESTED_FOLDER_NAME_LENGTH = 2;

    /**
     * @param string $contents
     * @param string $name
     * @param string $extension
     * @param string $folderPath
     * @param string $disk
     * @param int $nestedFolderLevels
     * @return string
     */
    public function buildPathAndPutContents(
        string $contents,
        string $name,
        string $extension,
        string $folderPath,
        string $disk = DISK_LOCAL,
        int $nestedFolderLevels = 0
    ) : string {
        $path = $this->buildPath($name, $extension, $folderPath, $nestedFolderLevels);

        $this->putContentsToPath($contents, $path, $disk);

        return $path;
    }

    /**
     * @param string $name
     * @param string $extension
     * @param string $folderPath
     * @param int $nestedFolderLevels
     * @return string
     */
    protected function buildPath(
        string $name,
        string $extension,
        string $folderPath,
        int $nestedFolderLevels = 0
    ) : string {
        $folderPath = \trim($folderPath, '/');
        if ($nestedFolderLevels > 0) {
            $nestedFolders = [];
            for ($i = 1; $i <= $nestedFolderLevels && $i <= 2; ++$i) {
                if (\mb_strlen($name) >= $i * self::NESTED_FOLDER_NAME_LENGTH) {
                    $nestedFolders[] = \mb_substr(
                        $name,
                        ($i - 1) * self::NESTED_FOLDER_NAME_LENGTH,
                        self::NESTED_FOLDER_NAME_LENGTH
                    );
                }
            }
            if (\count($nestedFolders) > 0) {
                $folderPath .= '/' . \implode('/', $nestedFolders);
            }
        }

        return "$folderPath/$name.$extension";
    }

    /**
     * @param string $contents
     * @param string $path
     * @param string $disk
     */
    public function putContentsToPath(
        string $contents,
        string $path,
        string $disk = DISK_LOCAL
    ) : void {
        NativeStorage::disk($disk)->put($path, $contents);
    }

    /**
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function pathExists(
        string $path,
        string $disk = DISK_LOCAL
    ) : bool {
        return NativeStorage::disk($disk)->exists($path);
    }

    /**
     * @param string $path
     * @param string $disk
     * @param bool $deleteEmptyFolders
     * @return bool
     */
    public function deleteFromPath(string $path, string $disk = DISK_LOCAL, bool $deleteEmptyFolders = true) : bool
    {
        $result = NativeStorage::disk($disk)->delete($path);
        if ($deleteEmptyFolders) {
            $folderArray = \explode('/', $path);
            \array_pop($folderArray);
            $this->deleteEmptyFolders($folderArray, $disk);
        }

        return $result;
    }

    /**
     * @param array $folderArray
     * @param string $disk
     */
    public function deleteEmptyFolders(array $folderArray, string $disk = DISK_LOCAL) : void
    {
        if (\count($folderArray) > 0) {
            $folderPath = \implode('/', $folderArray);
            if (
                \count(NativeStorage::disk($disk)->files($folderPath)) === 0
                && \count(NativeStorage::disk($disk)->directories($folderPath)) === 0
            ) {
                NativeStorage::disk($disk)->deleteDirectory($folderPath);
                \array_pop($folderArray);
                $this->deleteEmptyFolders($folderArray, $disk);
            }
        }
    }
}
