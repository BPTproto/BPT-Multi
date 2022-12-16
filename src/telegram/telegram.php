<?php

namespace BPT\telegram;
use BPT\constants\fileTypes;
use BPT\tools;
use BPT\types\forceReply;
use BPT\types\inlineKeyboardMarkup;
use BPT\types\message;
use BPT\types\replyKeyboardMarkup;
use BPT\types\replyKeyboardRemove;
use BPT\types\responseError;
use stdClass;

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
        return tools::downloadFile(self::fileLink($file_id), $destination);
    }

    public static function fileLink(string|null $file_id = null): bool|string {
        $file = self::getFile($file_id);
        if (!isset($file->file_path)) {
            return false;
        }
        return $file->link();
    }

    /**
     * send file with only file_id
     *
     * e.g. => tools::sendFile('file_id_asdadsadadadadadada');
     *
     * e.g. => tools::sendFile('file_id_asdadsadadadadadada','hello');
     *
     * @param string          $file_id
     * @param int|string|null $chat_id
     * @param int|null        $message_thread_id default : null
     * @param string|null     $caption
     *
     * @return message|bool|responseError
     */
    public static function sendFile (string $file_id, int|string $chat_id = null, int $message_thread_id = null, string $caption = null, string $parse_mode = null, array $caption_entities = null, bool $disable_notification = null, bool $protect_content = null, int $reply_to_message_id = null, bool $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|stdClass|array $reply_markup = null, string $token = null, bool $forgot = null, bool $answer = null): message|bool|responseError {
        $type = tools::fileType($file_id);
        if ($type === fileTypes::VIDEO) {
            return self::sendVideo($file_id,$chat_id,$message_thread_id,null,null,null,null,$caption,$parse_mode,$caption_entities,null,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::VIDEO_NOTE) {
            return self::sendVideoNote($file_id,$chat_id,$message_thread_id,null,null,null,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::ANIMATION) {
            return self::sendAnimation($file_id,$chat_id,$message_thread_id,null,null,null,null,$caption,$parse_mode,$caption_entities,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::AUDIO) {
            return self::sendAudio($file_id,$chat_id,$message_thread_id,$caption,$parse_mode,$caption_entities,null,null,null,null,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::PHOTO || $type === fileTypes::PROFILE_PHOTO) {
            return self::sendPhoto($file_id,$chat_id,$message_thread_id,$caption,$parse_mode,$caption_entities,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::VOICE) {
            return self::sendVoice($file_id,$chat_id,$message_thread_id,$caption,$parse_mode,$caption_entities,null,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::STICKER) {
            return self::sendSticker($file_id,$chat_id,$message_thread_id,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        elseif ($type === fileTypes::DOCUMENT) {
            return self::sendDocument($file_id,$chat_id,$message_thread_id,null,$caption,$parse_mode,$caption_entities,null,$disable_notification,$protect_content,$reply_to_message_id,$allow_sending_without_reply,$reply_markup,$token,$forgot,$answer);
        }
        else return false;
    }
}