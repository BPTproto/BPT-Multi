<?php

namespace BPT\api;

use BPT\types\{botCommand,
    botCommandScope,
    chat,
    chatAdministratorRights,
    chatInviteLink,
    chatMember,
    chatPermissions,
    file,
    forceReply,
    gameHighScore,
    inlineKeyboardMarkup,
    inlineQueryResult,
    inputMedia,
    labeledPrice,
    maskPosition,
    menuButton,
    message,
    messageEntity,
    messageId,
    passportElementError,
    poll,
    replyKeyboardMarkup,
    replyKeyboardRemove,
    responseError,
    sentWebAppMessage,
    shippingOption,
    stickerSet,
    update,
    user,
    userProfilePhotos,
    webhookInfo};
use CURLFile;
use BPT\api\request\{answer, curl};
use BPT\BPT;
use BPT\constants\{chatActions, loggerTypes, updateTypes};
use BPT\exception\bptException;
use BPT\logger;
use stdClass;

/**
 * Manage and handle telegram request
 *
 * @method static update[]|responseError getUpdates (int|null|array $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
 * @method static update[]|responseError getUp (int|null|array $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
 * @method static update[]|responseError updates (int|null|array $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
 * @method static bool|responseError setWebhook (string|null|array $url = null, CURLFile|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success. If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError setWeb (string|null|array $url = null, CURLFile|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success. If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError webhook (string|null|array $url = null, CURLFile|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success. If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError deleteWebhook (bool|null|array $drop_pending_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static bool|responseError deleteWeb (bool|null|array $drop_pending_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static bool|responseError delWeb (bool|null|array $drop_pending_updates = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static webhookInfo|responseError getWebhookInfo (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static webhookInfo|responseError getWeb (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static user|responseError getMe (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) A simple method for testing your bot's authentication token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static user|responseError me (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) A simple method for testing your bot's authentication token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static bool|responseError logOut (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to log out from the cloud Bot API server before launching the bot locally. You must log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns True on success. Requires no parameters.
 * @method static bool|responseError close (string|null|array $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn't launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns True on success. Requires no parameters.
 * @method static message|responseError sendMessage (string|array $text, int|string|null $chat_id = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $entities = null, bool|null $disable_web_page_preview = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static message|responseError send (string|array $text, int|string|null $chat_id = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $entities = null, bool|null $disable_web_page_preview = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static message|responseError forwardMessage (int|string|array $chat_id, int|string|null $from_chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to forward messages of any kind. Service messages can't be forwarded. On success, the sent Message is returned.
 * @method static message|responseError forward (int|string|array $chat_id, int|string|null $from_chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to forward messages of any kind. Service messages can't be forwarded. On success, the sent Message is returned.
 * @method static messageId|responseError copyMessage (int|string|array $chat_id, int|string|null $from_chat_id = null, int|null $message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to copy messages of any kind. Service messages and invoice messages can't be copied. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 * @method static messageId|responseError copy (int|string|array $chat_id, int|string|null $from_chat_id = null, int|null $message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to copy messages of any kind. Service messages and invoice messages can't be copied. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 * @method static message|responseError sendPhoto (CURLFile|string|array $photo, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send photos. On success, the sent Message is returned.
 * @method static message|responseError photo (CURLFile|string|array $photo, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send photos. On success, the sent Message is returned.
 * @method static message|responseError sendAudio (CURLFile|string|array $audio, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, int|null $duration = null, string|null $performer = null, string|null $title = null, CURLFile|string|null $thumb = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .MP3 or .M4A format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future. For sending voice messages, use the sendVoice method instead.
 * @method static message|responseError audio (CURLFile|string|array $audio, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, int|null $duration = null, string|null $performer = null, string|null $title = null, CURLFile|string|null $thumb = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .MP3 or .M4A format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future. For sending voice messages, use the sendVoice method instead.
 * @method static message|responseError sendDocument (CURLFile|string|array $document, int|string|null $chat_id = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendDoc (CURLFile|string|array $document, int|string|null $chat_id = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError document (CURLFile|string|array $document, int|string|null $chat_id = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError doc (CURLFile|string|array $document, int|string|null $chat_id = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVideo (CURLFile|string|array $video, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError video (CURLFile|string|array $video, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendAnimation (CURLFile|string|array $animation, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError animation (CURLFile|string|array $animation, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendGif (CURLFile|string|array $animation, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError gif (CURLFile|string|array $animation, int|string|null $chat_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|null $thumb = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVoice (CURLFile|string|array $voice, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, int|null $duration = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError voice (CURLFile|string|array $voice, int|string|null $chat_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, int|null $duration = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVideoNote (CURLFile|string|array $video_note, int|string|null $chat_id = null, int|null $duration = null, int|null $length = null, CURLFile|string|null $thumb = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) As of v.4.0, Telegram clients support rounded square MPEG4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent Message is returned.
 * @method static message|responseError videoNote (CURLFile|string|array $video_note, int|string|null $chat_id = null, int|null $duration = null, int|null $length = null, CURLFile|string|null $thumb = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) As of v.4.0, Telegram clients support rounded square MPEG4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent Message is returned.
 * @method static message[]|responseError sendMediaGroup (inputMedia[]|array|stdClass[] $media, int|string|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message[]|responseError mediaGroup (inputMedia[]|array|stdClass[] $media, int|string|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message[]|responseError media (inputMedia[]|array|stdClass[] $media, int|string|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message|responseError sendLocation (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError sendLoc (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError location (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError loc (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|bool|responseError editMessageLiveLocation (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $heading = null, int|null $proximity_alert_radius = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editLiveLoc (float|array|stdClass $latitude, float|stdClass $longitude, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, float|null|stdClass|array $horizontal_accuracy = null, int|null $heading = null, int|null $proximity_alert_radius = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError stopMessageLiveLocation (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop updating a live location message before live_period expires. On success, if the message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError stopLiveLoc (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop updating a live location message before live_period expires. On success, if the message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|responseError sendVenue (int|string|array $chat_id, float|stdClass $latitude, float|stdClass $longitude, string $title, string $address, string|null $foursquare_id = null, string|null $foursquare_type = null, string|null $google_place_id = null, string|null $google_place_type = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static message|responseError venue (int|string|array $chat_id, float|stdClass $latitude, float|stdClass $longitude, string $title, string $address, string|null $foursquare_id = null, string|null $foursquare_type = null, string|null $google_place_id = null, string|null $google_place_type = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static message|responseError sendContact (string|array $phone_number, string $first_name, int|string|null $chat_id = null, string|null $last_name = null, string|null $vcard = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static message|responseError contact (string|array $phone_number, string $first_name, int|string|null $chat_id = null, string|null $last_name = null, string|null $vcard = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static message|responseError sendPoll (string|array $question, string[] $options, int|string|null $chat_id = null, bool|null $is_anonymous = null, string|null $type = null, bool|null $allows_multiple_answers = null, int|null $correct_option_id = null, string|null $explanation = null, string|null $explanation_parse_mode = null, messageEntity[]|null|stdClass[]|array $explanation_entities = null, int|null $open_period = null, int|null $close_date = null, bool|null $is_closed = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a native poll. On success, the sent Message is returned.
 * @method static message|responseError poll (string|array $question, string[] $options, int|string|null $chat_id = null, bool|null $is_anonymous = null, string|null $type = null, bool|null $allows_multiple_answers = null, int|null $correct_option_id = null, string|null $explanation = null, string|null $explanation_parse_mode = null, messageEntity[]|null|stdClass[]|array $explanation_entities = null, int|null $open_period = null, int|null $close_date = null, bool|null $is_closed = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a native poll. On success, the sent Message is returned.
 * @method static message|responseError sendDice (int|string|null|array $chat_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
 * @method static message|responseError dice (int|string|null|array $chat_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
 * @method static bool|responseError sendChatAction (int|string|null|array $chat_id = null, string|null $action = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success. We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static bool|responseError chatAction (int|string|null|array $chat_id = null, string|null $action = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success. We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static bool|responseError action (int|string|null|array $chat_id = null, string|null $action = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success. We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static userProfilePhotos|responseError getUserProfilePhotos (int|null|array $user_id = null, int|null $offset = null, int|null $limit = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static userProfilePhotos|responseError userPhotos (int|null|array $user_id = null, int|null $offset = null, int|null $limit = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static file|responseError getFile (string|null|array $file_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static file|responseError file (string|null|array $file_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static bool|responseError banChatMember (int|string|null|array $chat_id = null, int|null $user_id = null, int|null $until_date = null, bool|null $revoke_messages = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError ban (int|string|null|array $chat_id = null, int|null $user_id = null, int|null $until_date = null, bool|null $revoke_messages = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError kickChatMember (int|string|null|array $chat_id = null, int|null $user_id = null, int|null $until_date = null, bool|null $revoke_messages = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError kick (int|string|null|array $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError unbanChatMember (int|string|null|array $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError unban (int|string|null|array $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError restrictChatMember (chatPermissions|array|stdClass $permissions, int|string|null $chat_id = null, int|null $user_id = null, int|null $until_date = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 * @method static bool|responseError restrict (chatPermissions|array|stdClass $permissions, int|string|null $chat_id = null, int|null $user_id = null, int|null $until_date = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 * @method static bool|responseError promoteChatMember (int|string|null|array $chat_id = null, int|null $user_id = null, bool|null $is_anonymous = null, bool|null $can_manage_chat = null, bool|null $can_post_messages = null, bool|null $can_edit_messages = null, bool|null $can_delete_messages = null, bool|null $can_manage_video_chats = null, bool|null $can_restrict_members = null, bool|null $can_promote_members = null, bool|null $can_change_info = null, bool|null $can_invite_users = null, bool|null $can_pin_messages = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static bool|responseError promote (int|string|null|array $chat_id = null, int|null $user_id = null, bool|null $is_anonymous = null, bool|null $can_manage_chat = null, bool|null $can_post_messages = null, bool|null $can_edit_messages = null, bool|null $can_delete_messages = null, bool|null $can_manage_video_chats = null, bool|null $can_restrict_members = null, bool|null $can_promote_members = null, bool|null $can_change_info = null, bool|null $can_invite_users = null, bool|null $can_pin_messages = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static bool|responseError setChatAdministratorCustomTitle (string|array $custom_title, int|string|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static bool|responseError customTitle (string|array $custom_title, int|string|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static bool|responseError banChatSenderChat (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError banSender (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError unbanChatSenderChat (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError unbanSender (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setShatPermissions (chatPermissions|array|stdClass $permissions, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
 * @method static bool|responseError permissions (chatPermissions|array|stdClass $permissions, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
 * @method static string|responseError exportChatInviteLink (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
 * @method static string|responseError link (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
 * @method static chatInviteLink|responseError createChatInviteLink (int|string|null|array $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError crLink (int|string|null|array $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError editChatInviteLink (string|array $invite_link, int|string|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
 * @method static chatInviteLink|responseError edLink (string|array $invite_link, int|string|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
 * @method static chatInviteLink|responseError revokeChatInviteLink (string|array $invite_link, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError reLink (string|array $invite_link, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
 * @method static bool|responseError approveChatJoinRequest (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError acceptJoin (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError declineChatJoinRequest (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError denyJoin (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError setChatPhoto (CURLFile|array $photo, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError deleteChatPhoto (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setChatTitle (string|array $title, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError title (string|array $title, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setChatDescription (int|string|null|array $chat_id = null, string|null $description = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError description (int|string|null|array $chat_id = null, string|null $description = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError pinChatMessage (int|array $message_id, int|string|null $chat_id = null, bool|null $disable_notification = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a message to the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError pin (int|array $message_id, int|string|null $chat_id = null, bool|null $disable_notification = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a message to the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinChatMessage (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove a message from the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpin (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove a message from the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinAllChatMessages (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinAll (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError leaveChat (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static bool|responseError leave (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static chat|responseError getChat (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static chat|responseError chat (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static chatMember[]|responseError getChatAdministrators (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 * @method static chatMember[]|responseError admins (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 * @method static int|responseError getChatMembersCount (int|string|array $chat_id, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static int|responseError getChatMemberCount (int|string|array $chat_id, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static int|responseError membersCount (int|string|array $chat_id, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static chatMember|responseError getChatMember (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get information about a member of a chat. Returns a ChatMember object on success.
 * @method static chatMember|responseError member (int|string|null|array $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get information about a member of a chat. Returns a ChatMember object on success.
 * @method static bool|responseError setChatStickerSet (string|array $sticker_set_name, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError setSticker (string|array $sticker_set_name, int|string|null $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError deleteChatStickerSet (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError delSticker (int|string|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError answerCallbackQuery (string|null|array $callback_query_id = null, string|null $text = null, bool|null $show_alert = null, string|null $url = null, int|null $cache_time = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static bool|responseError answer (string|null|array $callback_query_id = null, string|null $text = null, bool|null $show_alert = null, string|null $url = null, int|null $cache_time = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static bool|responseError setMyCommands (botCommand[]|array|stdClass[] $commands, botCommandScope|null|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the list of the bot's commands. See https://core.telegram.org/bots#commands for more details about bot commands. Returns True on success.
 * @method static bool|responseError setCommands (botCommand[]|array|stdClass[] $commands, botCommandScope|null|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the list of the bot's commands. See https://core.telegram.org/bots#commands for more details about bot commands. Returns True on success.
 * @method static bool|responseError deleteMyCommands (botCommandScope|null|array|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 * @method static bool|responseError deleteCommands (botCommandScope|null|array|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 * @method static botCommand[]|responseError getMyCommands (botCommandScope|null|array|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current list of the bot's commands for the given scope and user language. Returns Array of BotCommand on success. If commands aren't set, an empty list is returned.
 * @method static botCommand[]|responseError getCommands (botCommandScope|null|array|stdClass|array $scope = null, string|null $language_code = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current list of the bot's commands for the given scope and user language. Returns Array of BotCommand on success. If commands aren't set, an empty list is returned.
 * @method static bool|responseError setChatMenuButton (int|null|array $chat_id = null, menuButton|null|stdClass|array $menu_button = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setMenuButton (int|null|array $chat_id = null, menuButton|null|stdClass|array $menu_button = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setMenu (int|null|array $chat_id = null, menuButton|null|stdClass|array $menu_button = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setButton (int|null|array $chat_id = null, menuButton|null|stdClass|array $menu_button = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static menuButton|responseError getChatMenuButton (int|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getMenuButton (int|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getMenu (int|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getButton (int|null|array $chat_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static bool|responseError setMyDefaultAdministratorRights (chatAdministratorRights|null|array|stdClass|array $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setMyDefaultAdminRights (chatAdministratorRights|null|array|stdClass|array $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setMyDefaultRights (chatAdministratorRights|null|array|stdClass|array $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setDefaultRights (chatAdministratorRights|null|array|stdClass|array $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are are free to modify the list before adding the bot. Returns True on success.
 * @method static chatAdministratorRights|responseError getMyDefaultAdministratorRights (bool|null|array $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getMyDefaultAdminRights (bool|null|array $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getMyDefaultRights (bool|null|array $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getDefaultRights (bool|null|array $for_channels = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static message|bool|responseError editMessageText (string|array $text, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $entities = null, bool|null $disable_web_page_preview = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit text and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editText (string|array $text, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $entities = null, bool|null $disable_web_page_preview = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit text and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageCaption (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editCap (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editCaption (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null|stdClass[]|array $caption_entities = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageMedia (inputMedia|array|stdClass $media, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit animation, audio, document, photo, or video messages. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo or a video otherwise. When an inline message is edited, a new file can't be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMedia (inputMedia|array|stdClass $media, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit animation, audio, document, photo, or video messages. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo or a video otherwise. When an inline message is edited, a new file can't be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageReplyMarkup (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editReply (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editKeyboard (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static poll|responseError stopPoll (int|string|null|array $chat_id = null, int|null $message_id = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop a poll which was sent by the bot. On success, the stopped Poll is returned.
 * @method static bool|responseError deleteMessage (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a message, including service messages, with the following limitations:- A message can only be deleted if it was sent less than 48 hours ago.- A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.- Bots can delete outgoing messages in private chats, groups, and supergroups.- Bots can delete incoming messages in private chats.- Bots granted can_post_messages permissions can delete outgoing messages in channels.- If the bot is an administrator of a group, it can delete any message there.- If the bot has can_delete_messages permission in a supergroup or a channel, it can delete any message there.Returns True on success.
 * @method static bool|responseError del (int|string|null|array $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a message, including service messages, with the following limitations:- A message can only be deleted if it was sent less than 48 hours ago.- A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.- Bots can delete outgoing messages in private chats, groups, and supergroups.- Bots can delete incoming messages in private chats.- Bots granted can_post_messages permissions can delete outgoing messages in channels.- If the bot is an administrator of a group, it can delete any message there.- If the bot has can_delete_messages permission in a supergroup or a channel, it can delete any message there.Returns True on success.
 * @method static message|responseError sendSticker (CURLFile|string|array $sticker, int|string|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send stable .WEBP, animated .TGS, or video .WEBM stickers. On success, the sent Message is returned.
 * @method static message|responseError sticker (CURLFile|string|array $sticker, int|string|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send stable .WEBP, animated .TGS, or video .WEBM stickers. On success, the sent Message is returned.
 * @method static stickerSet|responseError getStickerSet (string|array $name, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a sticker set. On success, a StickerSet object is returned.
 * @method static file|responseError uploadStickerFile (CURLFile|array $png_sticker, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to upload a .PNG file with a sticker for later use in createNewStickerSet and addStickerToSet methods (can be used multiple times). Returns the uploaded File on success.
 * @method static file|responseError uploadSticker (CURLFile|array $png_sticker, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to upload a .PNG file with a sticker for later use in createNewStickerSet and addStickerToSet methods (can be used multiple times). Returns the uploaded File on success.
 * @method static bool|responseError createNewStickerSet (string|array $name, string $title, string $emojis, int|null $user_id = null, CURLFile|string|null $png_sticker = null, CURLFile|null $tgs_sticker = null, CURLFile|null $webm_sticker = null, bool|null $contains_masks = null, maskPosition|null|stdClass|array $mask_position = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. You must use exactly one of the fields png_sticker, tgs_sticker, or webm_sticker. Returns True on success.
 * @method static bool|responseError createSticker (string|array $name, string $title, string $emojis, int|null $user_id = null, CURLFile|string|null $png_sticker = null, CURLFile|null $tgs_sticker = null, CURLFile|null $webm_sticker = null, bool|null $contains_masks = null, maskPosition|null|stdClass|array $mask_position = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. You must use exactly one of the fields png_sticker, tgs_sticker, or webm_sticker. Returns True on success.
 * @method static bool|responseError addStickerToSet (string|array $name, string $emojis, int|null $user_id = null, CURLFile|string|null $png_sticker = null, CURLFile|null $tgs_sticker = null, CURLFile|null $webm_sticker = null, maskPosition|null|stdClass|array $mask_position = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a new sticker to a set created by the bot. You must use exactly one of the fields png_sticker, tgs_sticker, or webm_sticker. Animated stickers can be added to animated sticker sets and only to them. Animated sticker sets can have up to 50 stickers. Stable sticker sets can have up to 120 stickers. Returns True on success.
 * @method static bool|responseError addSticker (string|array $name, string $emojis, int|null $user_id = null, CURLFile|string|null $png_sticker = null, CURLFile|null $tgs_sticker = null, CURLFile|null $webm_sticker = null, maskPosition|null|stdClass|array $mask_position = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a new sticker to a set created by the bot. You must use exactly one of the fields png_sticker, tgs_sticker, or webm_sticker. Animated stickers can be added to animated sticker sets and only to them. Animated sticker sets can have up to 50 stickers. Stable sticker sets can have up to 120 stickers. Returns True on success.
 * @method static bool|responseError setStickerPositionInSet (string|array $sticker, int $position, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError setStickerPosition (string|array $sticker, int $position, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError setStickerPos (string|array $sticker, int $position, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError deleteStickerFromSet (string|array $sticker, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static bool|responseError deleteSticker (string|array $sticker, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static bool|responseError setStickerSetThumb (string|array $name, int|null $user_id = null, CURLFile|string|null $thumb = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the thumbnail of a sticker set. Animated thumbnails can be set for animated sticker sets only. Video thumbnails can be set only for video sticker sets only. Returns True on success.
 * @method static bool|responseError setStickerThumb (string|array $name, int|null $user_id = null, CURLFile|string|null $thumb = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the thumbnail of a sticker set. Animated thumbnails can be set for animated sticker sets only. Video thumbnails can be set only for video sticker sets only. Returns True on success.
 * @method static bool|responseError answerInlineQuery (inlineQueryResult[]|array|stdClass[] $results, string|null $inline_query_id = null, int|null $cache_time = null, bool|null $is_personal = null, string|null $next_offset = null, string|null $switch_pm_text = null, string|null $switch_pm_parameter = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to an inline query. On success, True is returned.No more than 50 results per query are allowed.
 * @method static bool|responseError answerInline (inlineQueryResult[]|array|stdClass[] $results, string|null $inline_query_id = null, int|null $cache_time = null, bool|null $is_personal = null, string|null $next_offset = null, string|null $switch_pm_text = null, string|null $switch_pm_parameter = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to an inline query. On success, True is returned.No more than 50 results per query are allowed.
 * @method static sentWebAppMessage|responseError answerWebAppQuery (string|array $web_app_query_id, inlineQueryResult|stdClass $result, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static sentWebAppMessage|responseError answerWebapp (string|array $web_app_query_id, inlineQueryResult|stdClass $result, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static sentWebAppMessage|responseError answerWeb (string|array $web_app_query_id, inlineQueryResult|stdClass $result, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static message|responseError sendInvoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[]|stdClass[] $prices, int|string|null $chat_id = null, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $start_parameter = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static message|responseError invoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[]|stdClass[] $prices, int|string|null $chat_id = null, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $start_parameter = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static string|responseError createInvoiceLink (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[]|stdClass[] $prices, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a link for an invoice. Returns the created invoice link as String on success.
 * @method static message|responseError createInvoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[]|stdClass[] $prices, int|string|null $chat_id = null, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $start_parameter = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static bool|responseError answerShippingQuery (bool|array $ok, string|null $shipping_query_id = null, shippingOption[]|null|stdClass[]|array $shipping_options = null, string|null $error_message = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static bool|responseError answerShipping (bool|array $ok, string|null $shipping_query_id = null, shippingOption[]|null|stdClass[]|array $shipping_options = null, string|null $error_message = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static bool|responseError answerPreCheckoutQuery (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError answerPreCheckout (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError answerPreCheck (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError setPassportDataErrors (passportElementError[]|array|stdClass[] $errors, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success. Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 * @method static bool|responseError setPassport (passportElementError[]|array|stdClass[] $errors, int|null $user_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success. Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 * @method static message|responseError sendGame (string|array $game_short_name, int|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a game. On success, the sent Message is returned.
 * @method static message|responseError game (string|array $game_short_name, int|null $chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|null|stdClass|array $reply_markup = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a game. On success, the sent Message is returned.
 * @method static message|bool|responseError setGameScore (int|array $score, int|null $user_id = null, bool|null $force = null, bool|null $disable_edit_message = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static message|bool|responseError gameScore (int|array $score, int|null $user_id = null, bool|null $force = null, bool|null $disable_edit_message = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static gameHighScore[]|responseError getGameHighScores (int|null|array $user_id = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. On success, returns an Array of GameHighScore objects.
 * @method static gameHighScore[]|responseError getGameHigh (int|null|array $user_id = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $return_array = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. On success, returns an Array of GameHighScore objects.
 */
class request {
    /** Result of telegram request */
    public static bool $status;

    /** Telegram request response without any change  */
    public static stdClass $pure_response;

    private const METHODS_ACTION = [
        'getupdates'                      => 'getUpdates',
        'getup'                           => 'getUpdates',
        'updates'                         => 'getUpdates',
        'setwebhook'                      => 'setWebhook',
        'setweb'                          => 'setWebhook',
        'webhook'                         => 'setWebhook',
        'deletewebhook'                   => 'deleteWebhook',
        'deleteweb'                       => 'deleteWebhook',
        'delweb'                          => 'deleteWebhook',
        'getwebhookinfo'                  => 'getWebhookInfo',
        'getweb'                          => 'getWebhookInfo',
        'getme'                           => 'getMe',
        'me'                              => 'getMe',
        'logout'                          => 'logOut',
        'close'                           => 'close',
        'sendmessage'                     => 'sendMessage',
        'send'                            => 'sendMessage',
        'forwardmessage'                  => 'forwardMessage',
        'forward'                         => 'forwardMessage',
        'copymessage'                     => 'copyMessage',
        'copy'                            => 'copyMessage',
        'sendphoto'                       => 'sendPhoto',
        'photo'                           => 'sendPhoto',
        'sendaudio'                       => 'sendAudio',
        'audio'                           => 'sendAudio',
        'senddocument'                    => 'sendDocument',
        'senddoc'                         => 'sendDocument',
        'document'                        => 'sendDocument',
        'doc'                             => 'sendDocument',
        'sendvideo'                       => 'sendVideo',
        'video'                           => 'sendVideo',
        'sendanimation'                   => 'sendAnimation',
        'animation'                       => 'sendAnimation',
        'sendgif'                         => 'sendAnimation',
        'gif'                             => 'sendAnimation',
        'sendvoice'                       => 'sendVoice',
        'voice'                           => 'sendVoice',
        'sendvideonote'                   => 'sendVideoNote',
        'videonote'                       => 'sendVideoNote',
        'sendmediagroup'                  => 'sendMediaGroup',
        'mediagroup'                      => 'sendMediaGroup',
        'media'                           => 'sendMediaGroup',
        'sendlocation'                    => 'sendLocation',
        'sendloc'                         => 'sendLocation',
        'location'                        => 'sendLocation',
        'loc'                             => 'sendLocation',
        'editmessagelivelocation'         => 'editMessageLiveLocation',
        'editliveloc'                     => 'editMessageLiveLocation',
        'stopmessagelivelocation'         => 'stopMessageLiveLocation',
        'stopliveloc'                     => 'stopMessageLiveLocation',
        'sendvenue'                       => 'sendVenue',
        'venue'                           => 'sendVenue',
        'sendcontact'                     => 'sendContact',
        'contact'                         => 'sendContact',
        'sendpoll'                        => 'sendPoll',
        'poll'                            => 'sendPoll',
        'senddice'                        => 'sendDice',
        'dice'                            => 'sendDice',
        'sendchataction'                  => 'sendChatAction',
        'chataction'                      => 'sendChatAction',
        'action'                          => 'sendChatAction',
        'getuserprofilephotos'            => 'getUserProfilePhotos',
        'userphotos'                      => 'getUserProfilePhotos',
        'getfile'                         => 'getFile',
        'file'                            => 'getFile',
        'banchatmember'                   => 'banChatMember',
        'ban'                             => 'banChatMember',
        'kickchatmember'                  => 'banChatMember',
        'kick'                            => 'unbanChatMember',
        'unbanchatmember'                 => 'unbanChatMember',
        'unban'                           => 'unbanChatMember',
        'restrictchatmember'              => 'restrictChatMember',
        'restrict'                        => 'restrictChatMember',
        'promotechatmember'               => 'promoteChatMember',
        'promote'                         => 'promoteChatMember',
        'setchatadministratorcustomtitle' => 'setChatAdministratorCustomTitle',
        'customtitle'                     => 'setChatAdministratorCustomTitle',
        'banchatsenderchat'               => 'banChatSenderChat',
        'bansender'                       => 'banChatSenderChat',
        'unbanchatsenderchat'             => 'unbanChatSenderChat',
        'unbansender'                     => 'unbanChatSenderChat',
        'setchatpermissions'              => 'setChatPermissions',
        'permissions'                     => 'setChatPermissions',
        'exportchatinvitelink'            => 'exportChatInviteLink',
        'link'                            => 'exportChatInviteLink',
        'createchatinvitelink'            => 'createChatInviteLink',
        'crlink'                          => 'createChatInviteLink',
        'editchatinvitelink'              => 'editChatInviteLink',
        'edlink'                          => 'editChatInviteLink',
        'revokechatinvitelink'            => 'revokeChatInviteLink',
        'relink'                          => 'revokeChatInviteLink',
        'approvechatjoinrequest'          => 'approveChatJoinRequest',
        'acceptjoin'                      => 'approveChatJoinRequest',
        'declinechatjoinrequest'          => 'declineChatJoinRequest',
        'denyjoin'                        => 'declineChatJoinRequest',
        'setchatphoto'                    => 'setChatPhoto',
        'deletechatphoto'                 => 'deleteChatPhoto',
        'setchattitle'                    => 'setChatTitle',
        'title'                           => 'setChatTitle',
        'setchatdescription'              => 'setChatDescription',
        'description'                     => 'setChatDescription',
        'pinchatmessage'                  => 'pinChatMessage',
        'pin'                             => 'pinChatMessage',
        'unpinchatmessage'                => 'unpinChatMessage',
        'unpin'                           => 'unpinChatMessage',
        'unpinallchatmessages'            => 'unpinAllChatMessages',
        'unpinall'                        => 'unpinAllChatMessages',
        'leavechat'                       => 'leaveChat',
        'leave'                           => 'leaveChat',
        'getchat'                         => 'getChat',
        'chat'                            => 'getChat',
        'getchatadministrators'           => 'getChatAdministrators',
        'admins'                          => 'getChatAdministrators',
        'getchatmembercount'              => 'getChatMemberCount',
        'getchatmemberscount'             => 'getChatMemberCount',
        'memberscount'                    => 'getChatMemberCount',
        'getchatmember'                   => 'getChatMember',
        'member'                          => 'getChatMember',
        'setchatstickerset'               => 'setChatStickerSet',
        'setsticker'                      => 'setChatStickerSet',
        'deletechatstickerset'            => 'deleteChatStickerSet',
        'delsticker'                      => 'deleteChatStickerSet',
        'answercallbackquery'             => 'answerCallbackQuery',
        'answer'                          => 'answerCallbackQuery',
        'setmycommands'                   => 'setMyCommands',
        'setcommands'                     => 'setMyCommands',
        'deletemycommands'                => 'deleteMyCommands',
        'deletecommands'                  => 'deleteMyCommands',
        'getmycommands'                   => 'getMyCommands',
        'getcommands'                     => 'getMyCommands',
        'setchatmenubutton'               => 'setChatMenuButton',
        'setmenubutton'                   => 'setChatMenuButton',
        'setmenu'                         => 'setChatMenuButton',
        'setbutton'                       => 'setChatMenuButton',
        'getchatmenubutton'               => 'getChatMenuButton',
        'getmenubutton'                   => 'getChatMenuButton',
        'getmenu'                         => 'getChatMenuButton',
        'getbutton'                       => 'getChatMenuButton',
        'setmydefaultadministratorrights' => 'setMyDefaultAdministratorRights',
        'setmydefaultadminrights'         => 'setMyDefaultAdministratorRights',
        'setmydefaultrights'              => 'setMyDefaultAdministratorRights',
        'setdefaultrights'                => 'setMyDefaultAdministratorRights',
        'getmydefaultadministratorrights' => 'getMyDefaultAdministratorRights',
        'getmydefaultadminrights'         => 'getMyDefaultAdministratorRights',
        'getmydefaultrights'              => 'getMyDefaultAdministratorRights',
        'getdefaultrights'                => 'getMyDefaultAdministratorRights',
        'editmessagetext'                 => 'editMessageText',
        'edittext'                        => 'editMessageText',
        'editmessagecaption'              => 'editMessageCaption',
        'editcap'                         => 'editMessageCaption',
        'editcaption'                     => 'editMessageCaption',
        'editmessagemedia'                => 'editMessageMedia',
        'editmedia'                       => 'editMessageMedia',
        'editmessagereplymarkup'          => 'editMessageReplyMarkup',
        'editreply'                       => 'editMessageReplyMarkup',
        'editkeyboard'                    => 'editMessageReplyMarkup',
        'stoppoll'                        => 'stopPoll',
        'deletemessage'                   => 'deleteMessage',
        'del'                             => 'deleteMessage',
        'sendsticker'                     => 'sendSticker',
        'sticker'                         => 'sendSticker',
        'getstickerset'                   => 'getStickerSet',
        'uploadstickerfile'               => 'uploadStickerFile',
        'uploadsticker'                   => 'uploadStickerFile',
        'createnewstickerset'             => 'createNewStickerSet',
        'createsticker'                   => 'createNewStickerSet',
        'addstickertoset'                 => 'addStickerToSet',
        'addsticker'                      => 'addStickerToSet',
        'setstickerpositioninset'         => 'setStickerPositionInSet',
        'setstickerposition'              => 'setStickerPositionInSet',
        'setstickerpos'                   => 'setStickerPositionInSet',
        'deletestickerfromset'            => 'deleteStickerFromSet',
        'deletesticker'                   => 'deleteStickerFromSet',
        'setstickersetthumb'              => 'setStickerSetThumb',
        'setstickerthumb'                 => 'setStickerSetThumb',
        'answerinlinequery'               => 'answerInlineQuery',
        'answerinline'                    => 'answerInlineQuery',
        'answerwebappquery'               => 'answerWebAppQuery',
        'answerwebapp'                    => 'answerWebAppQuery',
        'answerweb'                       => 'answerWebAppQuery',
        'sendinvoice'                     => 'sendInvoice',
        'invoice'                         => 'sendInvoice',
        'createinvoicelink'               => 'createInvoiceLink',
        'createinvoice'                   => 'sendInvoice',
        'answershippingquery'             => 'answerShippingQuery',
        'answershipping'                  => 'answerShippingQuery',
        'answerprecheckoutquery'          => 'answerPreCheckoutQuery',
        'answerprecheckout'               => 'answerPreCheckoutQuery',
        'answerprecheck'                  => 'answerPreCheckoutQuery',
        'setpassportdataerrors'           => 'setPassportDataErrors',
        'setpassport'                     => 'setPassportDataErrors',
        'sendgame'                        => 'sendGame',
        'game'                            => 'sendGame',
        'setgamescore'                    => 'setGameScore',
        'gamescore'                       => 'setGameScore',
        'getgamehighscores'               => 'getGameHighScores',
        'getgamehigh'                     => 'getGameHighScores'
    ];

    private const METHODS_KEYS = [
        'getUpdates'                      => ['offset','limit','timeout','allowed_updates','token','return_array','forgot','answer'],
        'setWebhook'                      => ['url','certificate','ip_address','max_connections','allowed_updates','drop_pending_updates','secret_token','token','return_array','forgot','answer'],
        'deleteWebhook'                   => ['drop_pending_updates','token','return_array','forgot','answer'],
        'getWebhookInfo'                  => ['token','return_array','forgot','answer'],
        'getMe'                           => ['token','return_array','forgot','answer'],
        'logOut'                          => ['token','return_array','forgot','answer'],
        'close'                           => ['token','return_array','forgot','answer'],
        'sendMessage'                     => ['text','chat_id','parse_mode','entities','disable_web_page_preview','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'forwardMessage'                  => ['chat_id','from_chat_id','disable_notification','protect_content','message_id','token','return_array','forgot','answer'],
        'copyMessage'                     => ['chat_id','from_chat_id','message_id','caption','parse_mode','caption_entities','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendPhoto'                       => ['photo','chat_id','caption','parse_mode','caption_entities','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendAudio'                       => ['audio','chat_id','caption','parse_mode','caption_entities','duration','performer','title','thumb','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendDocument'                    => ['document','chat_id','thumb','caption','parse_mode','caption_entities','disable_content_type_detection','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendVideo'                       => ['video','chat_id','duration','width','height','thumb','caption','parse_mode','caption_entities','supports_streaming','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendAnimation'                   => ['animation','chat_id','duration','width','height','thumb','caption','parse_mode','caption_entities','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendVoice'                       => ['voice','chat_id','caption','parse_mode','caption_entities','duration','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendVideoNote'                   => ['video_note','chat_id','duration','length','thumb','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendMediaGroup'                  => ['media','chat_id','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','token','return_array','forgot','answer'],
        'sendLocation'                    => ['latitude','longitude','chat_id','horizontal_accuracy','live_period','heading','proximity_alert_radius','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'editMessageLiveLocation'         => ['latitude','longitude','chat_id','message_id','inline_message_id','horizontal_accuracy','heading','proximity_alert_radius','reply_markup','token','return_array','forgot','answer'],
        'stopMessageLiveLocation'         => ['chat_id','message_id','inline_message_id','reply_markup','token','return_array','forgot','answer'],
        'sendVenue'                       => ['chat_id','latitude','longitude','title','address','foursquare_id','foursquare_type','google_place_id','google_place_type','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendContact'                     => ['phone_number','first_name','chat_id','last_name','vcard','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendPoll'                        => ['question','options','chat_id','is_anonymous','type','allows_multiple_answers','correct_option_id','explanation','explanation_parse_mode','explanation_entities','open_period','close_date','is_closed','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendDice'                        => ['chat_id','emoji','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'sendChatAction'                  => ['chat_id','action','token','return_array','forgot','answer'],
        'getUserProfilePhotos'            => ['user_id','offset','limit','token','return_array','forgot','answer'],
        'getFile'                         => ['file_id','token','return_array','forgot','answer'],
        'banChatMember'                   => ['chat_id','user_id','until_date','revoke_messages','token','return_array','forgot','answer'],
        'unbanChatMember'                 => ['chat_id','user_id','only_if_banned','token','return_array','forgot','answer'],
        'restrictChatMember'              => ['permissions','chat_id','user_id','until_date','token','return_array','forgot','answer'],
        'promoteChatMember'               => ['chat_id','user_id','is_anonymous','can_manage_chat','can_post_messages','can_edit_messages','can_delete_messages','can_manage_video_chats','can_restrict_members','can_promote_members','can_change_info','can_invite_users','can_pin_messages','token','return_array','forgot','answer'],
        'setChatAdministratorCustomTitle' => ['custom_title','chat_id','user_id','token','return_array','forgot','answer'],
        'banChatSenderChat'               => ['sender_chat_id','chat_id','token','return_array','forgot','answer'],
        'unbanChatSenderChat'             => ['sender_chat_id','chat_id','token','return_array','forgot','answer'],
        'setChatPermissions'              => ['permissions','chat_id','token','return_array','forgot','answer'],
        'exportChatInviteLink'            => ['chat_id','token','return_array','forgot','answer'],
        'createChatInviteLink'            => ['chat_id','name','expire_date','member_limit','creates_join_request','token','return_array','forgot','answer'],
        'editChatInviteLink'              => ['invite_link','chat_id','name','expire_date','member_limit','creates_join_request','token','return_array','forgot','answer'],
        'revokeChatInviteLink'            => ['invite_link','chat_id','token','return_array','forgot','answer'],
        'approveChatJoinRequest'          => ['chat_id','user_id','token','return_array','forgot','answer'],
        'declineChatJoinRequest'          => ['chat_id','user_id','token','return_array','forgot','answer'],
        'setChatPhoto'                    => ['photo','chat_id','token','return_array','forgot','answer'],
        'deleteChatPhoto'                 => ['chat_id','token','return_array','forgot','answer'],
        'setChatTitle'                    => ['title','chat_id','token','return_array','forgot','answer'],
        'setChatDescription'              => ['chat_id','description','token','return_array','forgot','answer'],
        'pinChatMessage'                  => ['message_id','chat_id','disable_notification','token','return_array','forgot','answer'],
        'unpinChatMessage'                => ['chat_id','message_id','token','return_array','forgot','answer'],
        'unpinAllChatMessages'            => ['chat_id','token','return_array','forgot','answer'],
        'leaveChat'                       => ['chat_id','token','return_array','forgot','answer'],
        'getChat'                         => ['chat_id','token','return_array','forgot','answer'],
        'getChatAdministrators'           => ['chat_id','token','return_array','forgot','answer'],
        'getChatMemberCount'              => ['chat_id','token','return_array','forgot','answer'],
        'getChatMember'                   => ['chat_id','user_id','token','return_array','forgot','answer'],
        'setChatStickerSet'               => ['sticker_set_name','chat_id','token','return_array','forgot','answer'],
        'deleteChatStickerSet'            => ['chat_id','token','return_array','forgot','answer'],
        'answerCallbackQuery'             => ['callback_query_id','text','show_alert','url','cache_time','token','return_array','forgot','answer'],
        'setMyCommands'                   => ['commands','scope','language_code','token','return_array','forgot','answer'],
        'deleteMyCommands'                => ['scope','language_code','token','return_array','forgot','answer'],
        'getMyCommands'                   => ['scope','language_code','token','return_array','forgot','answer'],
        'setChatMenuButton'               => ['chat_id','menu_button','token','return_array','forgot','answer'],
        'getChatMenuButton'               => ['chat_id','token','return_array','forgot','answer'],
        'setMyDefaultAdministratorRights' => ['rights','for_channels','token','return_array','forgot','answer'],
        'getMyDefaultAdministratorRights' => ['for_channels','token','return_array','forgot','answer'],
        'editMessageText'                 => ['text','chat_id','message_id','inline_message_id','parse_mode','entities','disable_web_page_preview','reply_markup','token','return_array','forgot','answer'],
        'editMessageCaption'              => ['chat_id','message_id','inline_message_id','caption','parse_mode','caption_entities','reply_markup','token','return_array','forgot','answer'],
        'editMessageMedia'                => ['media','chat_id','message_id','inline_message_id','reply_markup','token','return_array','forgot','answer'],
        'editMessageReplyMarkup'          => ['chat_id','message_id','inline_message_id','reply_markup','token','return_array','forgot','answer'],
        'stopPoll'                        => ['chat_id','message_id','reply_markup','token','return_array','forgot','answer'],
        'deleteMessage'                   => ['chat_id','message_id','token','return_array','forgot','answer'],
        'sendSticker'                     => ['sticker','chat_id','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'getStickerSet'                   => ['name','token','return_array','forgot','answer'],
        'uploadStickerFile'               => ['png_sticker','user_id','token','return_array','forgot','answer'],
        'createNewStickerSet'             => ['name','title','emojis','user_id','png_sticker','tgs_sticker','webm_sticker','contains_masks','mask_position','token','return_array','forgot','answer'],
        'addStickerToSet'                 => ['name','emojis','user_id','png_sticker','tgs_sticker','webm_sticker','mask_position','token','return_array','forgot','answer'],
        'setStickerPositionInSet'         => ['sticker','position','token','return_array','forgot','answer'],
        'deleteStickerFromSet'            => ['sticker','token','return_array','forgot','answer'],
        'setStickerSetThumb'              => ['name','user_id','thumb','token','return_array','forgot','answer'],
        'answerInlineQuery'               => ['results','inline_query_id','cache_time','is_personal','next_offset','switch_pm_text','switch_pm_parameter','token','return_array','forgot','answer'],
        'answerWebAppQuery'               => ['web_app_query_id','result','token','return_array','forgot','answer'],
        'sendInvoice'                     => ['title','description','payload','provider_token','currency','prices','chat_id','max_tip_amount','suggested_tip_amounts','start_parameter','provider_data','photo_url','photo_size','photo_width','photo_height','need_name','need_phone_number','need_email','need_shipping_address','send_phone_number_to_provider','send_email_to_provider','is_flexible','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'createInvoiceLink'               => ['title','description','payload','provider_token','currency','prices','max_tip_amount','suggested_tip_amounts','provider_data','photo_url','photo_size','photo_width','photo_height','need_name','need_phone_number','need_email','need_shipping_address','send_phone_number_to_provider','send_email_to_provider','is_flexible','token','return_array','forgot','answer'],
        'answerShippingQuery'             => ['ok','shipping_query_id','shipping_options','error_message','token','return_array','forgot','answer'],
        'answerPreCheckoutQuery'          => ['ok','pre_checkout_query_id','error_message','token','return_array','forgot','answer'],
        'setPassportDataErrors'           => ['errors','user_id','token','return_array','forgot','answer'],
        'sendGame'                        => ['game_short_name','chat_id','disable_notification','protect_content','reply_to_message_id','allow_sending_without_reply','reply_markup','token','return_array','forgot','answer'],
        'setGameScore'                    => ['score','user_id','force','disable_edit_message','chat_id','message_id','inline_message_id','token','return_array','forgot','answer'],
        'getGameHighScores'               => ['user_id','chat_id','message_id','inline_message_id','token','return_array','forgot','answer'],
    ];

    private const METHODS_WITH_FILE = [
        'setWebhook'          => ['certificate'],
        'sendPhoto'           => ['photo'],
        'sendAudio'           => ['audio', 'thumb'],
        'sendDocument'        => ['document', 'thumb'],
        'sendVideo'           => ['video', 'thumb'],
        'sendAnimation'       => ['animation', 'thumb'],
        'sendVoice'           => ['voice', 'thumb'],
        'sendVideoNote'       => ['video_note', 'thumb'],
        'setChatPhoto'        => ['photo'],
        'sendSticker'         => ['sticker'],
        'uploadStickerFile'   => ['png_sticker'],
        'createNewStickerSet' => ['png_sticker', 'tgs_sticker'],
        'addStickerToSet'     => ['png_sticker', 'tgs_sticker'],
        'setStickerSetThumb'  => ['thumb'],
    ];

    private const METHODS_EXTRA_DEFAULTS = [
        'getUpdates'                      => [],
        'setWebhook'                      => ['url'],
        'deleteWebhook'                   => [],
        'getWebhookInfo'                  => [],
        'getMe'                           => [],
        'logOut'                          => [],
        'close'                           => [],
        'sendMessage'                     => ['chat_id'],
        'forwardMessage'                  => ['from_chat_id','message_id'],
        'copyMessage'                     => ['from_chat_id','message_id'],
        'sendPhoto'                       => ['chat_id'],
        'sendAudio'                       => ['chat_id'],
        'sendDocument'                    => ['chat_id'],
        'sendVideo'                       => ['chat_id'],
        'sendAnimation'                   => ['chat_id'],
        'sendVoice'                       => ['chat_id'],
        'sendVideoNote'                   => ['chat_id'],
        'sendMediaGroup'                  => ['chat_id'],
        'sendLocation'                    => ['chat_id'],
        'editMessageLiveLocation'         => [],
        'stopMessageLiveLocation'         => [],
        'sendVenue'                       => [],
        'sendContact'                     => ['chat_id'],
        'sendPoll'                        => ['chat_id'],
        'sendDice'                        => ['chat_id'],
        'sendChatAction'                  => ['chat_id','action'],
        'getUserProfilePhotos'            => ['user_id'],
        'getFile'                         => ['file_id'],
        'banChatMember'                   => ['chat_id','user_id'],
        'kickChatMember'                  => ['chat_id','user_id'],
        'unbanChatMember'                 => ['chat_id','user_id'],
        'restrictChatMember'              => ['chat_id','user_id'],
        'promoteChatMember'               => ['chat_id','user_id'],
        'setChatAdministratorCustomTitle' => ['chat_id','user_id'],
        'banChatSenderChat'               => ['chat_id'],
        'unbanChatSenderChat'             => ['chat_id'],
        'setChatPermissions'              => ['chat_id'],
        'exportChatInviteLink'            => ['chat_id'],
        'createChatInviteLink'            => ['chat_id'],
        'editChatInviteLink'              => ['chat_id'],
        'revokeChatInviteLink'            => ['chat_id'],
        'approveChatJoinRequest'          => ['chat_id','user_id'],
        'declineChatJoinRequest'          => ['chat_id','user_id'],
        'setChatPhoto'                    => ['chat_id'],
        'deleteChatPhoto'                 => ['chat_id'],
        'setChatTitle'                    => ['chat_id'],
        'setChatDescription'              => ['chat_id'],
        'pinChatMessage'                  => ['chat_id'],
        'unpinChatMessage'                => ['chat_id'],
        'unpinAllChatMessages'            => ['chat_id'],
        'leaveChat'                       => ['chat_id'],
        'getChat'                         => ['chat_id'],
        'getChatAdministrators'           => ['chat_id'],
        'getChatMembersCount'             => ['chat_id'],
        'getChatMember'                   => ['chat_id','user_id'],
        'setChatStickerSet'               => ['chat_id'],
        'deleteChatStickerSet'            => ['chat_id'],
        'answerCallbackQuery'             => ['callback_query_id'],
        'setMyCommands'                   => [],
        'deleteMyCommands'                => [],
        'getMyCommands'                   => [],
        'setChatMenuButton'               => [],
        'getChatMenuButton'               => [],
        'setMyDefaultAdministratorRights' => [],
        'getMyDefaultAdministratorRights' => [],
        'editMessageText'                 => ['inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']],
        'editMessageCaption'              => ['inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']],
        'editMessageMedia'                => ['inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']],
        'editMessageReplyMarkup'          => ['inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']],
        'stopPoll'                        => ['chat_id','message_id'],
        'deleteMessage'                   => ['chat_id','message_id'],
        'sendSticker'                     => ['chat_id'],
        'getStickerSet'                   => [],
        'uploadStickerFile'               => ['user_id'],
        'createNewStickerSet'             => ['user_id'],
        'addStickerToSet'                 => ['user_id'],
        'setStickerPositionInSet'         => [],
        'deleteStickerFromSet'            => [],
        'setStickerSetThumb'              => ['user_id'],
        'answerInlineQuery'               => ['inline_query_id'],
        'sendInvoice'                     => ['chat_id'],
        'answerWebAppQuery'               => [],
        'answerShippingQuery'             => ['shipping_query_id'],
        'answerPreCheckoutQuery'          => ['pre_checkout_query_id'],
        'setPassportDataErrors'           => ['user_id'],
        'sendGame'                        => ['chat_id'],
        'setGameScore'                    => ['user_id','inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']],
        'getGameHighScores'               => ['user_id','inline_query'=>['inline_message_id'],'other'=>['chat_id','message_id']]
    ];

    private const METHODS_RETURN = [
        'getUpdates' => ['BPT\types\update'],
        'getWebhookInfo' => 'BPT\types\webhookInfo',
        'getMe' => 'BPT\types\user',
        'sendMessage' => 'BPT\types\message',
        'forwardMessage' => 'BPT\types\message',
        'copyMessage' => 'BPT\types\messageId',
        'sendPhoto' => 'BPT\types\message',
        'sendAudio' => 'BPT\types\message',
        'sendDocument' => 'BPT\types\message',
        'sendVideo' => 'BPT\types\message',
        'sendAnimation' => 'BPT\types\message',
        'sendVoice' => 'BPT\types\message',
        'sendVideoNote' => 'BPT\types\message',
        'sendMediaGroup' => ['BPT\types\message'],
        'sendLocation' => 'BPT\types\message',
        'editMessageLiveLocation' => 'BPT\types\message',
        'stopMessageLiveLocation' => 'BPT\types\message',
        'sendVenue' => 'BPT\types\message',
        'sendContact' => 'BPT\types\message',
        'sendPoll' => 'BPT\types\message',
        'sendDice' => 'BPT\types\message',
        'getUserProfilePhotos' => 'BPT\types\userProfilePhotos',
        'getFile' => 'BPT\types\file',
        'createChatInviteLink' => 'BPT\types\chatInviteLink',
        'editChatInviteLink' => 'BPT\types\chatInviteLink',
        'revokeChatInviteLink' => 'BPT\types\chatInviteLink',
        'getChat' => 'BPT\types\chat',
        'getChatAdministrators' => ['BPT\types\chatMember'],
        'getChatMember' => 'BPT\types\chatMember',
        'getMyCommands' => ['BPT\types\botCommand'],
        'getChatMenuButton' => 'BPT\types\menuButton',
        'getMyDefaultAdministratorRights' => 'BPT\types\chatAdministratorRights',
        'editMessageText' => 'BPT\types\message',
        'editMessageCaption' => 'BPT\types\message',
        'editMessageMedia' => 'BPT\types\message',
        'editMessageReplyMarkup' => 'BPT\types\message',
        'stopPoll' => 'BPT\types\poll',
        'sendSticker' => 'BPT\types\message',
        'getStickerSet' => 'BPT\types\stickerSet',
        'uploadStickerFile' => 'BPT\types\file',
        'answerWebAppQuery' => 'BPT\types\sentWebAppMessage',
        'sendInvoice' => 'BPT\types\message',
        'sendGame' => 'BPT\types\message',
        'setGameScore' => 'BPT\types\message',
        'getGameHighScores' => ['BPT\types\gameHighScore']
    ];


    public static function __callStatic (string $name, array $arguments) {
        if ($action = self::methodAction($name)) {
            self::keysName($action,$arguments);
            self::readyFile($action,$arguments);
            self::setDefaults($action,$arguments);
            if (isset($arguments['answer'])) {
                return answer::init($action,$arguments);
            }
            else {
                return self::processResponse($action,curl::init($action,$arguments));
            }
        }
        else {
            logger::write("$name method is not supported",loggerTypes::ERROR);
            throw new bptException('METHOD_NOT_FOUND');
        }
    }

    private static function keysName (string $name, array &$arguments): void {
        foreach ($arguments as $key => $argument) {
            if (is_numeric($key) && isset(self::METHODS_KEYS[$name][$key])) {
                $arguments[self::METHODS_KEYS[$name][$key]] = $argument;
                unset($arguments[$key]);
            }
        }
    }

    private static function methodAction(string $name): string|false {
        return self::METHODS_ACTION[str_replace('_', '', strtolower($name))] ?? false;
    }

    private static function readyFile(string $name, array &$arguments): void {
        if ($name === 'sendMediaGroup') {
            foreach ($arguments['media'] as $key => $media) {
                if (file_exists($media['media'])) {
                    $arguments['media'][$key]['media'] = new CURLFile($media['media']);
                }
            }
        }
        elseif ($file_params = self::methodFile($name)) {
            foreach ($file_params as $param) {
                if (isset($arguments[$param]) && file_exists($arguments[$param])) {
                    $arguments[$param] = new CURLFile($arguments[$param]);
                }
            }
        }
    }

    private static function methodFile(string $name): array|false {
        return self::METHODS_WITH_FILE[$name] ?? false;
    }

    private static function methodReturn(string $name,stdClass $response) {
        if (isset(self::METHODS_RETURN[$name])) {
            $return = self::METHODS_RETURN[$name];
            if (is_array($return)) {
                $response = $response->result;
                foreach ($response as &$value) {
                    $value = new ($return[0]) ($value);
                }
                return $response;
            }
            else {
                return new ($return) ($response->result);
            }
        }
        else {
            return $response->result;
        }
    }

    private static function setDefaults(string $name, array &$arguments): void {
        $defaults = self::METHODS_EXTRA_DEFAULTS[$name];
        foreach ($defaults as $key => $default) {
            if (is_numeric($key)) {
                if (!isset($arguments[$default])){
                    $arguments[$default] = self::catchFields($default);
                }
            }
            elseif (isset(BPT::$update->$key) || $key === 'other') {
                foreach ($default as $def) {
                    if (!isset($arguments[$def])){
                        $arguments[$def] = self::catchFields($def);
                    }
                }
                break;
            }
        }
    }

    private static function processResponse(string $name, stdClass $response) {
        if ($response->ok) {
            self::$status = true;
            self::$pure_response = $response;
            return self::methodReturn($name,$response);
        }
        else {
            self::$status = false;
            self::$pure_response = $response;
            return new responseError($response);
        }
    }

    /**
     * easy method for getting fields from update
     *
     * @param string $field
     *
     * @return int|string|bool
     */
    public static function catchFields (string $field): int|string|bool {
        switch ($field) {
            case 'chat_id' :
            case 'from_chat_id' :
                return match (true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->id,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->id,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->id,
                    default => false
                };
            case 'user_id' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->id,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->id,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->id,
                    default => false
                };
            case 'message_id' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->message_id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->message_id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->message_id,
                    default => false
                };
            case 'file_id' :
                if (isset(BPT::$update->message)) $type = 'message';
                elseif (isset(BPT::$update->edited_message)) $type = 'edited_message';
                else return false;

                return match(true) {
                    isset(BPT::$update->$type->animation) => BPT::$update->$type->animation->file_id,
                    isset(BPT::$update->$type->audio) => BPT::$update->$type->audio->file_id,
                    isset(BPT::$update->$type->document) => BPT::$update->$type->document->file_id,
                    isset(BPT::$update->$type->photo) => end(BPT::$update->$type->photo)->file_id,
                    isset(BPT::$update->$type->sticker) => BPT::$update->$type->sticker->file_id,
                    isset(BPT::$update->$type->video) => BPT::$update->$type->video->file_id,
                    isset(BPT::$update->$type->video_note) => BPT::$update->$type->video_note->file_id,
                    isset(BPT::$update->$type->voice) => BPT::$update->$type->voice->file_id,
                    default => false
                };
            case 'callback_query_id' :
                return match (true) {
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->id,
                    default => false
                };
            case 'shipping_query_id' :
                return match(true) {
                    isset(BPT::$update->shipping_query) => BPT::$update->shipping_query->id,
                    default => false
                };
            case 'pre_checkout_query_id' :
                return match(true) {
                    isset(BPT::$update->pre_checkout_query) => BPT::$update->pre_checkout_query->id,
                    default => false
                };
            case 'inline_query_id' :
                return match(true) {
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->id,
                    default => false
                };
            case 'type' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->type,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->type,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->chat_type,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->type,
                    default => false
                };
            case 'action' :
                return chatActions::TYPING;
            case 'name' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->first_name,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->first_name,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->first_name,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->first_name,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->first_name,
                    default => false
                };
            case 'last_name' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->last_name ?? '',
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->last_name ?? '',
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->last_name ?? '',
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->last_name ?? '',
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->last_name ?? '',
                    default => false
                };
            case 'username' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->username ?? '',
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->username ?? '',
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->username ?? '',
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->username ?? '',
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->username ?? '',
                    default => false
                };
            case 'group_name' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->first_name,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->first_name,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->first_name,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->first_name,
                    default => false
                };
            case 'group_username' :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->username,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->username,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->username,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->username,
                    default => false
                };
            case 'update_type' :
                return match(true) {
                    isset(BPT::$update->message) => updateTypes::MESSAGE,
                    isset(BPT::$update->edited_message) => updateTypes::EDITED_MESSAGE,
                    isset(BPT::$update->inline_query) => updateTypes::INLINE_QUERY,
                    isset(BPT::$update->callback_query) => updateTypes::CALLBACK_QUERY,
                    isset(BPT::$update->chat_join_request) => updateTypes::CHAT_JOIN_REQUEST,
                    isset(BPT::$update->my_chat_member) => updateTypes::MY_CHAT_MEMBER,
                    isset(BPT::$update->chat_member) => updateTypes::CHAT_MEMBER,
                    isset(BPT::$update->channel_post) => updateTypes::CHANNEL_POST,
                    isset(BPT::$update->edited_channel_post) => updateTypes::EDITED_CHANNEL_POST,
                    isset(BPT::$update->chosen_inline_result) => updateTypes::CHOSEN_INLINE_RESULT,
                    isset(BPT::$update->shipping_query) => updateTypes::SHIPPING_QUERY,
                    isset(BPT::$update->pre_checkout_query) => updateTypes::PRE_CHECKOUT_QUERY,
                    isset(BPT::$update->poll) => updateTypes::POLL,
                    isset(BPT::$update->poll_answer) => updateTypes::POLL_ANSWER,
                    default => false
                };
            case 'url' :
                return 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            default:
                return false;
        }
    }
}