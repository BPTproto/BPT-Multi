<?php

namespace BPT;

use BPT\constants\loggerTypes;

class logger {
    private static int $log_size;

    private static $handler;


    public static function init (int $log_size = 10) {
        self::$log_size = $log_size;
        if (file_exists('BPT.log') && !(filesize('BPT.log') > self::$log_size * 1024 * 1024)) {
            $mode = 'a';
            $write = false;
        }
        else {
            $mode = 'w';
            $write = true;
        }

        self::$handler = fopen('BPT.log', $mode);

        if ($write) {
            fwrite(self::$handler,"♥♥♥♥♥♥♥♥♥♥♥♥♥♥ BPT Library  ♥♥♥♥♥♥♥♥♥♥♥♥♥♥\nTnx for using our library\nSome information about us :\nAuthor : @Im_Miaad\nHelper : @A_LiReza_ME\nChannel : @BPT_CH\nOur Website : https://bptlib.ir\n\nIf you have any problem with our library\nContact to our supports\n♥♥♥♥♥♥♥♥♥♥♥♥♥♥ BPT Library  ♥♥♥♥♥♥♥♥♥♥♥♥♥♥\nINFO : BPT Library LOG STARTED ...\nwarning : this file automatically deleted when its size reached log_size setting, do not delete it manually\n\n");
        }
    }

    public static function write(string $data, string $type = loggerTypes::NONE) {
        if (!is_null(self::$handler)) {
            $text = date('Y/m/d H:i:s') . ( $type === loggerTypes::NONE ? " : $data\n\n" : " : ⤵\n$type : $data\n\n" );
            fwrite(self::$handler, $text);
        }
    }
}