<?php

namespace BPT;

use BPT\constants\loggerTypes;

/**
 * logger class , manage and write logs
 */
class logger {
    private static int $log_size;

    private static array $waited_logs = [];

    private static $handler;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init (int $log_size = 10): void {
        self::$log_size = $log_size;
        $log_file = realpath(settings::$name.'BPT.log');
        $mode = file_exists($log_file) && !(filesize($log_file) > self::$log_size * 1024 * 1024) ? 'a' : 'w';
        self::$handler = fopen(settings::$name.'BPT.log', $mode);
        if ($mode === 'w') {
            fwrite(self::$handler,"♥♥♥♥♥♥♥♥♥♥♥♥♥♥ BPT Library  ♥♥♥♥♥♥♥♥♥♥♥♥♥♥\nTnx for using our library\nSome information about us :\nAuthor : @Im_Miaad\nHelper : @A_LiReza_ME\nChannel : @BPT_CH\nOur Website : https://bptlib.ir\n\nIf you have any problem with our library\nContact to our supports\n♥♥♥♥♥♥♥♥♥♥♥♥♥♥ BPT Library  ♥♥♥♥♥♥♥♥♥♥♥♥♥♥\nINFO : BPT Library LOG STARTED ...\nwarning : this file automatically deleted when its size reached log_size setting, do not delete it manually\n\n");
        }
        if (self::$waited_logs != []) {
            foreach (self::$waited_logs as $log) {
                fwrite(self::$handler, $log);
            }
        }
    }

    /**
     * Use this for write in logger file
     *
     * It's better to not use it and lets library use it by it self
     *
     * @param string $data
     * @param string $type
     *
     * @return void
     */
    public static function write(string $data, string $type = loggerTypes::NONE): void {
        $text = date('Y/m/d H:i:s') . ($type !== loggerTypes::NONE ? " : ⤵\n$type" : '') . " : $data\n\n";
        if (!is_null(self::$handler)) {
            fwrite(self::$handler, $text);
        }
        else {
            self::$waited_logs[] = $text;
        }
    }
}