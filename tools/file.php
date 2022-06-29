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
     * e.g. => tools::zip('xFolder','yFolder/xFile.zip',false,true);
     *
     * @param string $path        your file or folder to be zipped
     * @param string $destination destination path for create file
     * @param bool   $self        set true for adding main folder to zip file
     * @param bool   $sub_folder  set false for not adding sub_folders and save all files in main folder
     *
     * @return bool
     * @throws bptException when zip extension not found
     */
    public static function zip (string $path, string $destination, bool $self = true, bool $sub_folder = true): bool {
        if (extension_loaded('zip')) {
            if (file_exists($destination)) unlink($destination);

            $path = realpath($path);
            $zip = new ZipArchive();
            $zip->open($destination, ZipArchive::CREATE);

            if (is_dir($path)){
                if ($self){
                    $dirs = explode('\\',$path);
                    $dir_count = count($dirs);
                    $main_dir = $dirs[$dir_count-1];

                    $path = '';
                    for ($i=0; $i < $dir_count - 1; $i++) {
                        $path .= '\\' . $dirs[$i];
                    }
                    $path = substr($path, 1);
                    $zip->addEmptyDir($main_dir);
                }

                $it = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
                foreach ($files as $file) {
                    if ($file->isFile()){
                        if ($sub_folder){
                            $zip->addFile($file, str_replace($path . '\\', '', $file));
                        }
                        else{
                            $zip->addFile($file, basename($file));
                        }
                    }
                    elseif ($file->isDir() && $sub_folder) {
                        $zip->addEmptyDir(str_replace($path . '\\', '', $file . '\\'));
                    }
                }
            }
            else{
                $zip->addFile($path, basename($path));
            }

            return $zip->close();
        }
        else {
            logger::write("tools::zip function used\nzip extension is not found , It may not be installed or enabled",loggerTypes::ERROR);
            throw new bptException('ZIP_EXTENSION_MISSING');
        }
    }
}