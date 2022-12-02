<?php

namespace BPT\telegram;
use BPT\settings;
use BPT\tools;

/**
 * telegram class , Adding normal method call to request class and a simple name for being easy to call
 */
class telegram extends request {
    public function __call (string $name, array $arguments) {
        return request::$name(...$arguments);
    }

    /**
     * download telegram file with file_id to destination location
     *
     * It has 20MB download limit(same as telegram)
     *
     * e.g. => tools::downloadFile('test.mp4');
     *
     * e.g. => tools::downloadFile('test.mp4','file_id_asdadadadadadad);
     *
     * @param string|null $destination destination for save the file
     * @param string|null $file_id     file_id for download, if not set, will generate by request::catchFields method
     *
     * @return bool
     */
    public static function downloadFile (string|null $destination = null, string|null $file_id = null): bool {
        $file = telegram::getFile($file_id);
        if (isset($file->file_path)) {
            $file_path = settings::$down_url . 'bot' . settings::$token . '/' . $file->file_path;
            return tools::downloadFile($file_path, $destination);
        }
        else {
            return false;
        }
    }
}