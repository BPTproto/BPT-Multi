<?php

namespace BPT\tools;

use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\logger;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use BPT\tools;
use ZipArchive;

trait file {
    /**
     * receive size from path(can be url or file path)
     *
     * NOTE : some url will not return real size!
     *
     * e.g. => tools::size('xFile.zip');
     *
     * e.g. => tools::size(path: 'xFile.zip');
     *
     * @param string $path   file path, could be url
     * @param bool   $format if you set this true , you will receive symbolic string like 2.76MB for return
     *
     * @return string|int|false string for formatted data , int for normal data , false when size can not be found(file not found or ...)
     */
    public static function size (string $path, bool $format = true): string|int|false {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $ch = curl_init($path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            curl_close($ch);
        }
        else {
            $size = file_exists($path) ? filesize($path) : false;
        }
        if (isset($size) && is_numeric($size)) {
            return $format ? tools::byteFormat($size) : $size;
        }
        else return false;
    }

    /**
     * Delete a folder or file if exist
     *
     * e.g. => tools::delete(path: 'xfolder/yfolder');
     *
     * e.g. => tools::delete('xfolder/yfolder',false);
     *
     * @param string $path folder or file path
     * @param bool   $sub  set true for removing subFiles too, if folder has subFiles and this set to false , you will receive error
     *
     * @return bool
     * @throws bptException
     */
    public static function delete (string $path, bool $sub = true): bool {
        if (is_dir($path)) {
            if (count(scandir($path)) > 2) {
                if ($sub) {
                    $it = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $file) {
                        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
                    }
                    return rmdir($path);
                }
                else {
                    logger::write("tools::delete function used\ndelete function cannot delete folder because its have subFiles and sub parameter haven't true value",loggerTypes::ERROR);
                    throw new bptException('DELETE_FOLDER_HAS_SUB');
                }
            }
            else return rmdir($path);
        }
        else return unlink($path);
    }

    /**
     * convert all files in selected path to zip and then save it in dest path
     *
     * e.g. => tools::zip('xFolder','yFolder/xFile.zip');
     *
     * @param string $path        your file or folder to be zipped
     * @param string $destination destination path for create file
     *
     * @return bool
     * @throws bptException when zip extension not found
     */
    public static function zip (string $path, string $destination): bool {
        if (extension_loaded('zip')) {
            $rootPath = realpath($path);
            $zip = new ZipArchive();
            $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if (is_dir($path)) {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
                $root_len = strlen($rootPath) + 1;
                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $zip->addFile($filePath, substr($filePath, $root_len));
                    }
                }
            }
            else {
                $zip->addFile($path, basename($path));
            }
            return $zip->close();
        }
        else {
            logger::write("tools::zip function used\nzip extension is not found , It may not be installed or enabled", loggerTypes::ERROR);
            throw new bptException('ZIP_EXTENSION_MISSING');
        }
    }

    /**
     * download url and save it to path
     *
     * e.g. => tools::downloadFile('http://example.com/exmaple.mp4','movie.mp4');
     *
     * @param string $url your url to be downloaded
     * @param string $path destination path for saving url
     * @param int $chunk_size size of each chunk of data (in KB)
     *
     * @return bool true on success and false in failure
     */
    public static function downloadFile (string $url, string $path,int $chunk_size = 512): bool {
        $file = fopen($url, 'rb');
        if (!$file) return false;
        $path = fopen($path, 'wb');
        if (!$path) return false;

        $length = $chunk_size * 1024;
        while (!feof($file)){
            fwrite($path, fread($file, $length), $length);
        }
        fclose($path);
        fclose($file);
        return true;
    }
}