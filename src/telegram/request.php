<?php

namespace BPT\telegram;

use BPT\BPT;
use BPT\types\botDescription;
use BPT\types\botShortDescription;
use BPT\types\inputSticker;
use BPT\constants\{chatActions, fields, fileTypes, loggerTypes, updateTypes};
use BPT\exception\bptException;
use BPT\logger;
use BPT\settings;
use BPT\telegram\request\{answer, curl};
use BPT\tools;
use BPT\types\botCommand;
use BPT\types\botCommandScope;
use BPT\types\chat;
use BPT\types\chatAdministratorRights;
use BPT\types\chatInviteLink;
use BPT\types\chatMember;
use BPT\types\chatPermissions;
use BPT\types\file;
use BPT\types\forceReply;
use BPT\types\forumTopic;
use BPT\types\gameHighScore;
use BPT\types\inlineKeyboardMarkup;
use BPT\types\inlineQueryResult;
use BPT\types\inputMedia;
use BPT\types\labeledPrice;
use BPT\types\maskPosition;
use BPT\types\menuButton;
use BPT\types\message;
use BPT\types\messageEntity;
use BPT\types\messageId;
use BPT\types\passportElementError;
use BPT\types\poll;
use BPT\types\replyKeyboardMarkup;
use BPT\types\replyKeyboardRemove;
use BPT\types\responseError;
use BPT\types\sentWebAppMessage;
use BPT\types\shippingOption;
use BPT\types\sticker;
use BPT\types\stickerSet;
use BPT\types\update;
use BPT\types\user;
use BPT\types\userProfilePhotos;
use BPT\types\webhookInfo;
use CURLFile;
use stdClass;

/**
 * Manage and handle telegram request
 *
 * @method static update[]|responseError getUpdates (int|array|null $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
 * @method static update[]|responseError getUp (int|array|null $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
 * @method static update[]|responseError updates (int|array|null $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
 * @method static update[]|responseError up (int|array|null $offset = null, int|null $limit = null, int|null $timeout = null, string[]|null $allowed_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
 * @method static bool|responseError setWebhook (string|array|null $url = null, CURLFile|object|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError setWeb (string|array|null $url = null, CURLFile|object|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError webhook (string|array|null $url = null, CURLFile|object|null $certificate = null, string|null $ip_address = null, int|null $max_connections = null, string[]|null $allowed_updates = null, bool|null $drop_pending_updates = null, string|null $secret_token = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
 * @method static bool|responseError deleteWebhook (bool|array|null $drop_pending_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static bool|responseError deleteWeb (bool|array|null $drop_pending_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static bool|responseError delWeb (bool|array|null $drop_pending_updates = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static webhookInfo|responseError getWebhookInfo (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static webhookInfo|responseError getWebhook (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static webhookInfo|responseError getWeb (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static user|responseError getMe (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) A simple method for testing your bot's authentication token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static user|responseError me (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) A simple method for testing your bot's authentication token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static bool|responseError logOut (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to log out from the cloud Bot API server before launching the bot locally. You must log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns True on success. Requires no parameters.
 * @method static bool|responseError close (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn't launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns True on success. Requires no parameters.
 * @method static message|responseError sendMessage (string|array $text, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $parse_mode = null, messageEntity[]|null $entities = null, bool|null $disable_web_page_preview = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static message|responseError sendText (string|array $text, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $parse_mode = null, messageEntity[]|null $entities = null, bool|null $disable_web_page_preview = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static message|responseError send (string|array $text, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $parse_mode = null, messageEntity[]|null $entities = null, bool|null $disable_web_page_preview = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static message|responseError forwardMessage (int|string|array $chat_id, int|null $message_thread_id = null, int|string|null $from_chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to forward messages of any kind. Service messages can't be forwarded. On success, the sent Message is returned.
 * @method static message|responseError forward (int|string|array $chat_id, int|null $message_thread_id = null, int|string|null $from_chat_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to forward messages of any kind. Service messages can't be forwarded. On success, the sent Message is returned.
 * @method static messageId|responseError copyMessage (int|string|array $chat_id, int|null $message_thread_id = null, int|string|null $from_chat_id = null, int|null $message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to copy messages of any kind. Service messages and invoice messages can't be copied. A quiz poll can be copied only if the value of the field correct_option_id is known to the bot. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 * @method static messageId|responseError copy (int|string|array $chat_id, int|null $message_thread_id = null, int|string|null $from_chat_id = null, int|null $message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to copy messages of any kind. Service messages and invoice messages can't be copied. A quiz poll can be copied only if the value of the field correct_option_id is known to the bot. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 * @method static message|responseError sendPhoto (CURLFile|string|array|object $photo, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send photos. On success, the sent Message is returned.
 * @method static message|responseError photo (CURLFile|string|array|object $photo, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send photos. On success, the sent Message is returned.
 * @method static message|responseError sendAudio (CURLFile|string|array|object $audio, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, int|null $duration = null, string|null $performer = null, string|null $title = null, CURLFile|string|object|null $thumbnail = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .MP3 or .M4A format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.For sending voice messages, use the sendVoice method instead.
 * @method static message|responseError audio (CURLFile|string|array|object $audio, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, int|null $duration = null, string|null $performer = null, string|null $title = null, CURLFile|string|object|null $thumbnail = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .MP3 or .M4A format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.For sending voice messages, use the sendVoice method instead.
 * @method static message|responseError sendDocument (CURLFile|string|array|object $document, int|string|null $chat_id = null, int|null $message_thread_id = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendDoc (CURLFile|string|array|object $document, int|string|null $chat_id = null, int|null $message_thread_id = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError document (CURLFile|string|array|object $document, int|string|null $chat_id = null, int|null $message_thread_id = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError doc (CURLFile|string|array|object $document, int|string|null $chat_id = null, int|null $message_thread_id = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $disable_content_type_detection = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVideo (CURLFile|string|array|object $video, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendMovie (CURLFile|string|array|object $video, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError video (CURLFile|string|array|object $video, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError movie (CURLFile|string|array|object $video, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $supports_streaming = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendAnimation (CURLFile|string|array|object $animation, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendGif (CURLFile|string|array|object $animation, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError animation (CURLFile|string|array|object $animation, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError gif (CURLFile|string|array|object $animation, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $width = null, int|null $height = null, CURLFile|string|object|null $thumbnail = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, bool|null $has_spoiler = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVoice (CURLFile|string|array|object $voice, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, int|null $duration = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError voice (CURLFile|string|array|object $voice, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, int|null $duration = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static message|responseError sendVideoNote (CURLFile|string|array|object $video_note, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $length = null, CURLFile|string|object|null $thumbnail = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) As of v.4.0, Telegram clients support rounded square MPEG4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent Message is returned.
 * @method static message|responseError videoNote (CURLFile|string|array|object $video_note, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $duration = null, int|null $length = null, CURLFile|string|object|null $thumbnail = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) As of v.4.0, Telegram clients support rounded square MPEG4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent Message is returned.
 * @method static message[]|responseError sendMediaGroup (inputMedia[]|array $media, int|string|null $chat_id = null, int|null $message_thread_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message[]|responseError mediaGroup (inputMedia[]|array $media, int|string|null $chat_id = null, int|null $message_thread_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message[]|responseError media (inputMedia[]|array $media, int|string|null $chat_id = null, int|null $message_thread_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a group of photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an array of Messages that were sent is returned.
 * @method static message|responseError sendLocation (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_thread_id = null, float|null $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError sendLoc (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_thread_id = null, float|null $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError location (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_thread_id = null, float|null $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError loc (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_thread_id = null, float|null $horizontal_accuracy = null, int|null $live_period = null, int|null $heading = null, int|null $proximity_alert_radius = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static message|responseError sendVenue (float|array $latitude, float $longitude, string $title, string $address, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $foursquare_id = null, string|null $foursquare_type = null, string|null $google_place_id = null, string|null $google_place_type = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static message|responseError venue (float|array $latitude, float $longitude, string $title, string $address, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $foursquare_id = null, string|null $foursquare_type = null, string|null $google_place_id = null, string|null $google_place_type = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static message|responseError sendContact (string|array $phone_number, string $first_name, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $last_name = null, string|null $vcard = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static message|responseError contact (string|array $phone_number, string $first_name, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $last_name = null, string|null $vcard = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static message|responseError sendPoll (string|array $question, string[] $options, int|string|null $chat_id = null, int|null $message_thread_id = null, bool|null $is_anonymous = null, string|null $type = null, bool|null $allows_multiple_answers = null, int|null $correct_option_id = null, string|null $explanation = null, string|null $explanation_parse_mode = null, messageEntity[]|null $explanation_entities = null, int|null $open_period = null, int|null $close_date = null, bool|null $is_closed = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a native poll. On success, the sent Message is returned.
 * @method static message|responseError poll (string|array $question, string[] $options, int|string|null $chat_id = null, int|null $message_thread_id = null, bool|null $is_anonymous = null, string|null $type = null, bool|null $allows_multiple_answers = null, int|null $correct_option_id = null, string|null $explanation = null, string|null $explanation_parse_mode = null, messageEntity[]|null $explanation_entities = null, int|null $open_period = null, int|null $close_date = null, bool|null $is_closed = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a native poll. On success, the sent Message is returned.
 * @method static message|responseError sendDice (int|string|array|null $chat_id = null, int|null $message_thread_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
 * @method static message|responseError dice (int|string|array|null $chat_id = null, int|null $message_thread_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
 * @method static bool|responseError sendChatAction (int|string|array|null $chat_id = null, int|null $message_thread_id = null, string|null $action = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static bool|responseError chatAction (int|string|array|null $chat_id = null, int|null $message_thread_id = null, string|null $action = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static bool|responseError action (int|string|array|null $chat_id = null, int|null $message_thread_id = null, string|null $action = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
 * @method static userProfilePhotos|responseError getUserProfilePhotos (int|array|null $user_id = null, int|null $offset = null, int|null $limit = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static userProfilePhotos|responseError userPhotos (int|array|null $user_id = null, int|null $offset = null, int|null $limit = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static file|responseError getFile (string|array|null $file_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot/, where  is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static file|responseError file (string|array|null $file_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot/, where  is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static bool|responseError banChatMember (int|string|array|null $chat_id = null, int|null $user_id = null, int|null $until_date = null, bool|null $revoke_messages = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError ban (int|string|array|null $chat_id = null, int|null $user_id = null, int|null $until_date = null, bool|null $revoke_messages = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the chat on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError unbanChatMember (int|string|array|null $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError kick (int|string|array|null $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError unban (int|string|array|null $chat_id = null, int|null $user_id = null, bool|null $only_if_banned = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
 * @method static bool|responseError restrictChatMember (chatPermissions|array|object $permissions, int|string|null $chat_id = null, int|null $user_id = null, bool|null $use_independent_chat_permissions = null, int|null $until_date = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 * @method static bool|responseError restrict (chatPermissions|array|object $permissions, int|string|null $chat_id = null, int|null $user_id = null, bool|null $use_independent_chat_permissions = null, int|null $until_date = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 * @method static bool|responseError promoteChatMember (int|string|array|null $chat_id = null, int|null $user_id = null, bool|null $is_anonymous = null, bool|null $can_manage_chat = null, bool|null $can_post_messages = null, bool|null $can_edit_messages = null, bool|null $can_delete_messages = null, bool|null $can_manage_video_chats = null, bool|null $can_restrict_members = null, bool|null $can_promote_members = null, bool|null $can_change_info = null, bool|null $can_invite_users = null, bool|null $can_pin_messages = null, bool|null $can_manage_topics = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static bool|responseError promote (int|string|array|null $chat_id = null, int|null $user_id = null, bool|null $is_anonymous = null, bool|null $can_manage_chat = null, bool|null $can_post_messages = null, bool|null $can_edit_messages = null, bool|null $can_delete_messages = null, bool|null $can_manage_video_chats = null, bool|null $can_restrict_members = null, bool|null $can_promote_members = null, bool|null $can_change_info = null, bool|null $can_invite_users = null, bool|null $can_pin_messages = null, bool|null $can_manage_topics = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static bool|responseError setChatAdministratorCustomTitle (string|array $custom_title, int|string|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static bool|responseError setCustomTitle (string|array $custom_title, int|string|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static bool|responseError banChatSenderChat (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError banSender (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError unbanChatSenderChat (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError unbanSender (int|array $sender_chat_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setChatPermissions (chatPermissions|array|object $permissions, int|string|null $chat_id = null, bool|null $use_independent_chat_permissions = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
 * @method static bool|responseError permissions (chatPermissions|array|object $permissions, int|string|null $chat_id = null, bool|null $use_independent_chat_permissions = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
 * @method static string|responseError exportChatInviteLink (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
 * @method static string|responseError link (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
 * @method static chatInviteLink|responseError createChatInviteLink (int|string|array|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError crLink (int|string|array|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError editChatInviteLink (string|array $invite_link, int|string|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
 * @method static chatInviteLink|responseError edLink (string|array $invite_link, int|string|null $chat_id = null, string|null $name = null, int|null $expire_date = null, int|null $member_limit = null, bool|null $creates_join_request = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
 * @method static chatInviteLink|responseError revokeChatInviteLink (string|array $invite_link, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
 * @method static chatInviteLink|responseError reLink (string|array $invite_link, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
 * @method static bool|responseError approveChatJoinRequest (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError acceptJoin (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError declineChatJoinRequest (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError denyJoin (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static bool|responseError setChatPhoto (CURLFile|array|object $photo, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError deleteChatPhoto (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setChatTitle (string|array $title, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError title (string|array $title, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError setChatDescription (int|string|array|null $chat_id = null, string|null $description = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError description (int|string|array|null $chat_id = null, string|null $description = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static bool|responseError pinChatMessage (int|string|array|null $chat_id = null, int|null $message_id = null, bool|null $disable_notification = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a message to the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError pin (int|string|array|null $chat_id = null, int|null $message_id = null, bool|null $disable_notification = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a message to the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinChatMessage (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove a message from the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpin (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to remove a message from the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinAllChatMessages (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError unpinAll (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' administrator right in a supergroup or 'can_edit_messages' administrator right in a channel. Returns True on success.
 * @method static bool|responseError leaveChat (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static bool|responseError leave (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static chat|responseError getChat (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static chat|responseError chat (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static chatMember[]|responseError getChatAdministrators (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of administrators in a chat, which aren't bots. Returns an Array of ChatMember objects.
 * @method static chatMember[]|responseError admins (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a list of administrators in a chat, which aren't bots. Returns an Array of ChatMember objects.
 * @method static int|responseError getChatMemberCount (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static int|responseError getChatMembersCount (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static int|responseError membersCount (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static chatMember|responseError getChatMember (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get information about a member of a chat. The method is only guaranteed to work for other users if the bot is an administrator in the chat. Returns a ChatMember object on success.
 * @method static chatMember|responseError member (int|string|array|null $chat_id = null, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get information about a member of a chat. The method is only guaranteed to work for other users if the bot is an administrator in the chat. Returns a ChatMember object on success.
 * @method static bool|responseError setChatStickerSet (string|array $sticker_set_name, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError setSticker (string|array $sticker_set_name, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError deleteChatStickerSet (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static bool|responseError delSticker (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static sticker[]|responseError getForumTopicIconStickers (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects.
 * @method static sticker[]|responseError getTopicStickers (string|array|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects.
 * @method static forumTopic|responseError createForumTopic (string|array $name, int|string|null $chat_id = null, int|null $icon_color = null, string|null $icon_custom_emoji_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns information about the created topic as a ForumTopic object.
 * @method static forumTopic|responseError createTopic (string|array $name, int|string|null $chat_id = null, int|null $icon_color = null, string|null $icon_custom_emoji_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns information about the created topic as a ForumTopic object.
 * @method static bool|responseError editForumTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $name = null, string|null $icon_custom_emoji_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit name and icon of a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError editTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $name = null, string|null $icon_custom_emoji_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit name and icon of a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError closeForumTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError closeTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError reopenForumTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to reopen a closed topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError reopenTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to reopen a closed topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static bool|responseError deleteForumTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a forum topic along with all its messages in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_delete_messages administrator rights. Returns True on success.
 * @method static bool|responseError deleteTopic (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a forum topic along with all its messages in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_delete_messages administrator rights. Returns True on success.
 * @method static bool|responseError unpinAllForumTopicMessages (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 * @method static bool|responseError unpinAllTopicMessages (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 * @method static bool|responseError unpinTopicMessages (int|array $message_thread_id, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to clear the list of pinned messages in a forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 * @method static bool|responseError editGeneralForumTopic (string|array $name, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit the name of the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError editGeneralTopic (string|array $name, int|string|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit the name of the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError closeGeneralForumTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close an open 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError closeGeneralTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to close an open 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError reopenGeneralForumTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to reopen a closed 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically unhidden if it was hidden. Returns True on success.
 * @method static bool|responseError reopenGeneralTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to reopen a closed 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically unhidden if it was hidden. Returns True on success.
 * @method static bool|responseError hideGeneralForumTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to hide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically closed if it was open. Returns True on success.
 * @method static bool|responseError hideGeneralTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to hide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically closed if it was open. Returns True on success.
 * @method static bool|responseError unhideGeneralForumTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unhide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError unhideGeneralTopic (int|string|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to unhide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
 * @method static bool|responseError answerCallbackQuery (string|array|null $callback_query_id = null, string|null $text = null, bool|null $show_alert = null, string|null $url = null, int|null $cache_time = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static bool|responseError answer (string|array|null $callback_query_id = null, string|null $text = null, bool|null $show_alert = null, string|null $url = null, int|null $cache_time = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static bool|responseError setMyCommands (botCommand[]|array $commands, botCommandScope|object|array|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the list of the bot's commands. See this manual for more details about bot commands. Returns True on success.
 * @method static bool|responseError setCommands (botCommand[]|array $commands, botCommandScope|object|array|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the list of the bot's commands. See this manual for more details about bot commands. Returns True on success.
 * @method static bool|responseError deleteMyCommands (botCommandScope|array|object|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 * @method static bool|responseError deleteCommands (botCommandScope|array|object|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 * @method static botCommand[]|responseError getMyCommands (botCommandScope|array|object|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current list of the bot's commands for the given scope and user language. Returns an Array of BotCommand objects. If commands aren't set, an empty list is returned.
 * @method static botCommand[]|responseError getCommands (botCommandScope|array|object|null $scope = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current list of the bot's commands for the given scope and user language. Returns an Array of BotCommand objects. If commands aren't set, an empty list is returned.
 * @method static bool|responseError setMyDescription (string|array|null $description = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's description, which is shown in the chat with the bot if the chat is empty. Returns True on success.
 * @method static bool|responseError setDescription (string|array|null $description = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's description, which is shown in the chat with the bot if the chat is empty. Returns True on success.
 * @method static botDescription|responseError getMyDescription (string|array|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current bot description for the given user language. Returns BotDescription on success.
 * @method static botDescription|responseError getDescription (string|array|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current bot description for the given user language. Returns BotDescription on success.
 * @method static bool|responseError setMyShortDescription (string|array|null $short_description = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's short description, which is shown on the bot's profile page and is sent together with the link when users share the bot. Returns True on success.
 * @method static bool|responseError setShortDescription (string|array|null $short_description = null, string|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's short description, which is shown on the bot's profile page and is sent together with the link when users share the bot. Returns True on success.
 * @method static botShortDescription|responseError getMyShortDescription (string|array|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current bot short description for the given user language. Returns BotShortDescription on success.
 * @method static botShortDescription|responseError getShortDescription (string|array|null $language_code = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current bot short description for the given user language. Returns BotShortDescription on success.
 * @method static bool|responseError setChatMenuButton (int|array|null $chat_id = null, menuButton|object|array|null $menu_button = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setMenuButton (int|array|null $chat_id = null, menuButton|object|array|null $menu_button = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setMenu (int|array|null $chat_id = null, menuButton|object|array|null $menu_button = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static bool|responseError setButton (int|array|null $chat_id = null, menuButton|object|array|null $menu_button = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static menuButton|responseError getChatMenuButton (int|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getMenuButton (int|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getMenu (int|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static menuButton|responseError getButton (int|array|null $chat_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static bool|responseError setMyDefaultAdministratorRights (chatAdministratorRights|array|object|null $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setMyDefaultAdminRights (chatAdministratorRights|array|object|null $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setMyDefaultRights (chatAdministratorRights|array|object|null $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
 * @method static bool|responseError setDefaultRights (chatAdministratorRights|array|object|null $rights = null, bool|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
 * @method static chatAdministratorRights|responseError getMyDefaultAdministratorRights (bool|array|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getMyDefaultAdminRights (bool|array|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getMyDefaultRights (bool|array|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static chatAdministratorRights|responseError getDefaultRights (bool|array|null $for_channels = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static message|bool|responseError editMessageText (string|array $text, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $parse_mode = null, messageEntity[]|null $entities = null, bool|null $disable_web_page_preview = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit text and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editText (string|array $text, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $parse_mode = null, messageEntity[]|null $entities = null, bool|null $disable_web_page_preview = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit text and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageCaption (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editCap (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editCaption (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $caption = null, string|null $parse_mode = null, messageEntity[]|null $caption_entities = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageMedia (inputMedia|array|object $media, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit animation, audio, document, photo, or video messages. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo or a video otherwise. When an inline message is edited, a new file can't be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMedia (inputMedia|array|object $media, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit animation, audio, document, photo, or video messages. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo or a video otherwise. When an inline message is edited, a new file can't be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageLiveLocation (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, float|null $horizontal_accuracy = null, int|null $heading = null, int|null $proximity_alert_radius = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editLiveLoc (float|array $latitude, float $longitude, int|string|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, float|null $horizontal_accuracy = null, int|null $heading = null, int|null $proximity_alert_radius = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError stopMessageLiveLocation (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop updating a live location message before live_period expires. On success, if the message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError stopLiveLoc (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop updating a live location message before live_period expires. On success, if the message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editMessageReplyMarkup (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editReply (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static message|bool|responseError editKeyboard (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
 * @method static poll|responseError stopPoll (int|string|array|null $chat_id = null, int|null $message_id = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to stop a poll which was sent by the bot. On success, the stopped Poll is returned.
 * @method static bool|responseError deleteMessage (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a message, including service messages, with the following limitations:- A message can only be deleted if it was sent less than 48 hours ago.- Service messages about a supergroup, channel, or forum topic creation can't be deleted.- A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.- Bots can delete outgoing messages in private chats, groups, and supergroups.- Bots can delete incoming messages in private chats.- Bots granted can_post_messages permissions can delete outgoing messages in channels.- If the bot is an administrator of a group, it can delete any message there.- If the bot has can_delete_messages permission in a supergroup or a channel, it can delete any message there.Returns True on success.
 * @method static bool|responseError del (int|string|array|null $chat_id = null, int|null $message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a message, including service messages, with the following limitations:- A message can only be deleted if it was sent less than 48 hours ago.- Service messages about a supergroup, channel, or forum topic creation can't be deleted.- A dice message in a private chat can only be deleted if it was sent more than 24 hours ago.- Bots can delete outgoing messages in private chats, groups, and supergroups.- Bots can delete incoming messages in private chats.- Bots granted can_post_messages permissions can delete outgoing messages in channels.- If the bot is an administrator of a group, it can delete any message there.- If the bot has can_delete_messages permission in a supergroup or a channel, it can delete any message there.Returns True on success.
 * @method static message|responseError sendSticker (CURLFile|string|array|object $sticker, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send stable .WEBP, animated .TGS, or video .WEBM stickers. On success, the sent Message is returned.
 * @method static message|responseError sticker (CURLFile|string|array|object $sticker, int|string|null $chat_id = null, int|null $message_thread_id = null, string|null $emoji = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|replyKeyboardMarkup|replyKeyboardRemove|forceReply|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send stable .WEBP, animated .TGS, or video .WEBM stickers. On success, the sent Message is returned.
 * @method static stickerSet|responseError getStickerSet (string|array $name, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get a sticker set. On success, a StickerSet object is returned.
 * @method static sticker[]|responseError getCustomEmojiStickers (string[]|array $custom_emoji_ids, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of Sticker objects.
 * @method static file|responseError uploadStickerFile (CURLFile|array|object $sticker, string $sticker_format, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to upload a file with a sticker for later use in the createNewStickerSet and addStickerToSet methods (the file can be used multiple times). Returns the uploaded File on success.
 * @method static file|responseError uploadSticker (CURLFile|array|object $sticker, string $sticker_format, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to upload a file with a sticker for later use in the createNewStickerSet and addStickerToSet methods (the file can be used multiple times). Returns the uploaded File on success.
 * @method static bool|responseError createNewStickerSet (string|array $name, string $title, inputSticker[] $stickers, string $sticker_format, int|null $user_id = null, string|null $sticker_type = null, bool|null $needs_repainting = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns True on success.
 * @method static bool|responseError createSticker (string|array $name, string $title, inputSticker[] $stickers, string $sticker_format, int|null $user_id = null, string|null $sticker_type = null, bool|null $needs_repainting = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns True on success.
 * @method static bool|responseError addStickerToSet (string|array $name, inputSticker|object|array $sticker, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a new sticker to a set created by the bot. The format of the added sticker must be same with the format of the other stickers in the set. Emoji sticker sets can have up to 200 stickers. Animated and video sticker sets can have up to 50 stickers. Stable sticker sets can have up to 120 stickers. Returns True on success.
 * @method static bool|responseError addSticker (string|array $name, inputSticker|object|array $sticker, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to add a new sticker to a set created by the bot. The format of the added sticker must be same with the format of the other stickers in the set. Emoji sticker sets can have up to 200 stickers. Animated and video sticker sets can have up to 50 stickers. Stable sticker sets can have up to 120 stickers. Returns True on success.
 * @method static bool|responseError setStickerPositionInSet (string|array $sticker, int $position, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError setStickerPosition (string|array $sticker, int $position, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError setStickerPos (string|array $sticker, int $position, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static bool|responseError deleteStickerFromSet (string|array $sticker, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static bool|responseError deleteSticker (string|array $sticker, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static bool|responseError setStickerEmojiList (string|array $sticker, string[] $emoji_list, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the list of emoji assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
 * @method static bool|responseError setStickerKeywords (string|array $sticker, string[]|null $keywords = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change search keywords assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
 * @method static bool|responseError setStickerMaskPosition (string|array $sticker, maskPosition|object|array|null $mask_position = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to change the mask position of a mask sticker. The sticker must belong to a sticker set that was created by the bot. Returns True on success.
 * @method static bool|responseError setStickerSetTitle (string|array $name, string $title, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the title of a created sticker set. Returns True on success.
 * @method static bool|responseError setStickerSetThumb (string|array $name, int|null $user_id = null, CURLFile|string|object|null $thumbnail = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the thumbnail of a regular or mask sticker set. The format of the thumbnail file must be same with the format of the stickers in the set. Returns True on success.
 * @method static bool|responseError setStickerThumb (string|array $name, int|null $user_id = null, CURLFile|string|object|null $thumbnail = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the thumbnail of a regular or mask sticker set. The format of the thumbnail file must be same with the format of the stickers in the set. Returns True on success.
 * @method static bool|responseError setCustomEmojiStickerSetThumbnail (string|array $name, string|null $custom_emoji_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the thumbnail of a custom emoji sticker set. Returns True on success.
 * @method static bool|responseError deleteStickerSet (string|array $name, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to delete a sticker set that was created by the bot. Returns True on success.
 * @method static bool|responseError answerInlineQuery (inlineQueryResult[]|array $results, string|null $inline_query_id = null, int|null $cache_time = null, bool|null $is_personal = null, string|null $next_offset = null, string|null $switch_pm_text = null, string|null $switch_pm_parameter = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to an inline query. On success, True is returned.No more than 50 results per query are allowed.
 * @method static bool|responseError answerInline (inlineQueryResult[]|array $results, string|null $inline_query_id = null, int|null $cache_time = null, bool|null $is_personal = null, string|null $next_offset = null, string|null $switch_pm_text = null, string|null $switch_pm_parameter = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send answers to an inline query. On success, True is returned.No more than 50 results per query are allowed.
 * @method static sentWebAppMessage|responseError answerWebAppQuery (string|array $web_app_query_id, inlineQueryResult|object|array $result, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static sentWebAppMessage|responseError answerWebApp (string|array $web_app_query_id, inlineQueryResult|object|array $result, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static sentWebAppMessage|responseError answerWeb (string|array $web_app_query_id, inlineQueryResult|object|array $result, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static message|responseError sendInvoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[] $prices, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $start_parameter = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static message|responseError invoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[] $prices, int|string|null $chat_id = null, int|null $message_thread_id = null, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $start_parameter = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static string|responseError createInvoiceLink (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[] $prices, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a link for an invoice. Returns the created invoice link as String on success.
 * @method static string|responseError createInvoice (string|array $title, string $description, string $payload, string $provider_token, string $currency, labeledPrice[] $prices, int|null $max_tip_amount = null, int[]|null $suggested_tip_amounts = null, string|null $provider_data = null, string|null $photo_url = null, int|null $photo_size = null, int|null $photo_width = null, int|null $photo_height = null, bool|null $need_name = null, bool|null $need_phone_number = null, bool|null $need_email = null, bool|null $need_shipping_address = null, bool|null $send_phone_number_to_provider = null, bool|null $send_email_to_provider = null, bool|null $is_flexible = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to create a link for an invoice. Returns the created invoice link as String on success.
 * @method static bool|responseError answerShippingQuery (bool|array $ok, string|null $shipping_query_id = null, shippingOption[]|null $shipping_options = null, string|null $error_message = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static bool|responseError answerShipping (bool|array $ok, string|null $shipping_query_id = null, shippingOption[]|null $shipping_options = null, string|null $error_message = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static bool|responseError answerPreCheckoutQuery (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError answerPreCheckout (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError answerPreCheck (bool|array $ok, string|null $pre_checkout_query_id = null, string|null $error_message = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static bool|responseError setPassportDataErrors (passportElementError[]|array $errors, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 * @method static bool|responseError setPassport (passportElementError[]|array $errors, int|null $user_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 * @method static message|responseError sendGame (string|array $game_short_name, int|null $chat_id = null, int|null $message_thread_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a game. On success, the sent Message is returned.
 * @method static message|responseError game (string|array $game_short_name, int|null $chat_id = null, int|null $message_thread_id = null, bool|null $disable_notification = null, bool|null $protect_content = null, int|null $reply_to_message_id = null, bool|null $allow_sending_without_reply = null, inlineKeyboardMarkup|object|array|null $reply_markup = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to send a game. On success, the sent Message is returned.
 * @method static message|bool|responseError setGameScore (int|array $score, int|null $user_id = null, bool|null $force = null, bool|null $disable_edit_message = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static message|bool|responseError gameScore (int|array $score, int|null $user_id = null, bool|null $force = null, bool|null $disable_edit_message = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static gameHighScore[]|responseError getGameHighScores (int|array|null $user_id = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of GameHighScore objects.
 * @method static gameHighScore[]|responseError getGameHigh (int|array|null $user_id = null, int|null $chat_id = null, int|null $message_id = null, string|null $inline_message_id = null, string|null $token = null, bool|null $forgot = null, bool|null $answer = null) Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of GameHighScore objects.
 */
class request {
    /** Result of telegram request */
    public static bool $status = false;

    /** Telegram request response without any change  */
    public static stdClass $pure_response;

    private const METHODS_ACTION = [
        'getupdates'                        => 'getUpdates',
        'getup'                             => 'getUpdates',
        'updates'                           => 'getUpdates',
        'up'                                => 'getUpdates',
        'setwebhook'                        => 'setWebhook',
        'setweb'                            => 'setWebhook',
        'webhook'                           => 'setWebhook',
        'deletewebhook'                     => 'deleteWebhook',
        'deleteweb'                         => 'deleteWebhook',
        'delweb'                            => 'deleteWebhook',
        'getwebhookinfo'                    => 'getWebhookInfo',
        'getwebhook'                        => 'getWebhookInfo',
        'getweb'                            => 'getWebhookInfo',
        'getme'                             => 'getMe',
        'me'                                => 'getMe',
        'logout'                            => 'logOut',
        'close'                             => 'close',
        'sendmessage'                       => 'sendMessage',
        'sendtext'                          => 'sendMessage',
        'send'                              => 'sendMessage',
        'forwardmessage'                    => 'forwardMessage',
        'forward'                           => 'forwardMessage',
        'copymessage'                       => 'copyMessage',
        'copy'                              => 'copyMessage',
        'sendphoto'                         => 'sendPhoto',
        'photo'                             => 'sendPhoto',
        'sendaudio'                         => 'sendAudio',
        'audio'                             => 'sendAudio',
        'senddocument'                      => 'sendDocument',
        'senddoc'                           => 'sendDocument',
        'document'                          => 'sendDocument',
        'doc'                               => 'sendDocument',
        'sendvideo'                         => 'sendVideo',
        'sendmovie'                         => 'sendVideo',
        'video'                             => 'sendVideo',
        'movie'                             => 'sendVideo',
        'sendanimation'                     => 'sendAnimation',
        'sendgif'                           => 'sendAnimation',
        'animation'                         => 'sendAnimation',
        'gif'                               => 'sendAnimation',
        'sendvoice'                         => 'sendVoice',
        'voice'                             => 'sendVoice',
        'sendvideonote'                     => 'sendVideoNote',
        'videonote'                         => 'sendVideoNote',
        'sendmediagroup'                    => 'sendMediaGroup',
        'mediagroup'                        => 'sendMediaGroup',
        'media'                             => 'sendMediaGroup',
        'sendlocation'                      => 'sendLocation',
        'sendloc'                           => 'sendLocation',
        'location'                          => 'sendLocation',
        'loc'                               => 'sendLocation',
        'sendvenue'                         => 'sendVenue',
        'venue'                             => 'sendVenue',
        'sendcontact'                       => 'sendContact',
        'contact'                           => 'sendContact',
        'sendpoll'                          => 'sendPoll',
        'poll'                              => 'sendPoll',
        'senddice'                          => 'sendDice',
        'dice'                              => 'sendDice',
        'sendchataction'                    => 'sendChatAction',
        'chataction'                        => 'sendChatAction',
        'action'                            => 'sendChatAction',
        'getuserprofilephotos'              => 'getUserProfilePhotos',
        'userphotos'                        => 'getUserProfilePhotos',
        'getfile'                           => 'getFile',
        'file'                              => 'getFile',
        'banchatmember'                     => 'banChatMember',
        'ban'                               => 'banChatMember',
        'unbanchatmember'                   => 'unbanChatMember',
        'kick'                              => 'unbanChatMember',
        'unban'                             => 'unbanChatMember',
        'restrictchatmember'                => 'restrictChatMember',
        'restrict'                          => 'restrictChatMember',
        'promotechatmember'                 => 'promoteChatMember',
        'promote'                           => 'promoteChatMember',
        'setchatadministratorcustomtitle'   => 'setChatAdministratorCustomTitle',
        'setcustomtitle'                    => 'setChatAdministratorCustomTitle',
        'banchatsenderchat'                 => 'banChatSenderChat',
        'bansender'                         => 'banChatSenderChat',
        'unbanchatsenderchat'               => 'unbanChatSenderChat',
        'unbansender'                       => 'unbanChatSenderChat',
        'setchatpermissions'                => 'setChatPermissions',
        'permissions'                       => 'setChatPermissions',
        'exportchatinvitelink'              => 'exportChatInviteLink',
        'link'                              => 'exportChatInviteLink',
        'createchatinvitelink'              => 'createChatInviteLink',
        'crlink'                            => 'createChatInviteLink',
        'editchatinvitelink'                => 'editChatInviteLink',
        'edlink'                            => 'editChatInviteLink',
        'revokechatinvitelink'              => 'revokeChatInviteLink',
        'relink'                            => 'revokeChatInviteLink',
        'approvechatjoinrequest'            => 'approveChatJoinRequest',
        'acceptjoin'                        => 'approveChatJoinRequest',
        'declinechatjoinrequest'            => 'declineChatJoinRequest',
        'denyjoin'                          => 'declineChatJoinRequest',
        'setchatphoto'                      => 'setChatPhoto',
        'deletechatphoto'                   => 'deleteChatPhoto',
        'setchattitle'                      => 'setChatTitle',
        'title'                             => 'setChatTitle',
        'setchatdescription'                => 'setChatDescription',
        'description'                       => 'setChatDescription',
        'pinchatmessage'                    => 'pinChatMessage',
        'pin'                               => 'pinChatMessage',
        'unpinchatmessage'                  => 'unpinChatMessage',
        'unpin'                             => 'unpinChatMessage',
        'unpinallchatmessages'              => 'unpinAllChatMessages',
        'unpinall'                          => 'unpinAllChatMessages',
        'leavechat'                         => 'leaveChat',
        'leave'                             => 'leaveChat',
        'getchat'                           => 'getChat',
        'chat'                              => 'getChat',
        'getchatadministrators'             => 'getChatAdministrators',
        'admins'                            => 'getChatAdministrators',
        'getchatmembercount'                => 'getChatMemberCount',
        'getchatmemberscount'               => 'getChatMemberCount',
        'memberscount'                      => 'getChatMemberCount',
        'getchatmember'                     => 'getChatMember',
        'member'                            => 'getChatMember',
        'setchatstickerset'                 => 'setChatStickerSet',
        'setsticker'                        => 'setChatStickerSet',
        'deletechatstickerset'              => 'deleteChatStickerSet',
        'delsticker'                        => 'deleteChatStickerSet',
        'getforumtopiciconstickers'         => 'getForumTopicIconStickers',
        'gettopicstickers'                  => 'getForumTopicIconStickers',
        'createforumtopic'                  => 'createForumTopic',
        'createtopic'                       => 'createForumTopic',
        'editforumtopic'                    => 'editForumTopic',
        'edittopic'                         => 'editForumTopic',
        'closeforumtopic'                   => 'closeForumTopic',
        'closetopic'                        => 'closeForumTopic',
        'reopenforumtopic'                  => 'reopenForumTopic',
        'reopentopic'                       => 'reopenForumTopic',
        'deleteforumtopic'                  => 'deleteForumTopic',
        'deletetopic'                       => 'deleteForumTopic',
        'unpinallforumtopicmessages'        => 'unpinAllForumTopicMessages',
        'unpinalltopicmessages'             => 'unpinAllForumTopicMessages',
        'unpintopicmessages'                => 'unpinAllForumTopicMessages',
        'editgeneralforumtopic'             => 'editGeneralForumTopic',
        'editgeneraltopic'                  => 'editGeneralForumTopic',
        'closegeneralforumtopic'            => 'closeGeneralForumTopic',
        'closegeneraltopic'                 => 'closeGeneralForumTopic',
        'reopengeneralforumtopic'           => 'reopenGeneralForumTopic',
        'reopengeneraltopic'                => 'reopenGeneralForumTopic',
        'hidegeneralforumtopic'             => 'hideGeneralForumTopic',
        'hidegeneraltopic'                  => 'hideGeneralForumTopic',
        'unhidegeneralforumtopic'           => 'unhideGeneralForumTopic',
        'unhidegeneraltopic'                => 'unhideGeneralForumTopic',
        'answercallbackquery'               => 'answerCallbackQuery',
        'answer'                            => 'answerCallbackQuery',
        'setmycommands'                     => 'setMyCommands',
        'setcommands'                       => 'setMyCommands',
        'deletemycommands'                  => 'deleteMyCommands',
        'deletecommands'                    => 'deleteMyCommands',
        'getmycommands'                     => 'getMyCommands',
        'getcommands'                       => 'getMyCommands',
        'setmydescription'                  => 'setMyDescription',
        'setdescription'                    => 'setMyDescription',
        'getmydescription'                  => 'getMyDescription',
        'getdescription'                    => 'getMyDescription',
        'setmyshortdescription'             => 'setMyShortDescription',
        'setshortdescription'               => 'setMyShortDescription',
        'getmyshortdescription'             => 'getMyShortDescription',
        'getshortdescription'               => 'getMyShortDescription',
        'setchatmenubutton'                 => 'setChatMenuButton',
        'setmenubutton'                     => 'setChatMenuButton',
        'setmenu'                           => 'setChatMenuButton',
        'setbutton'                         => 'setChatMenuButton',
        'getchatmenubutton'                 => 'getChatMenuButton',
        'getmenubutton'                     => 'getChatMenuButton',
        'getmenu'                           => 'getChatMenuButton',
        'getbutton'                         => 'getChatMenuButton',
        'setmydefaultadministratorrights'   => 'setMyDefaultAdministratorRights',
        'setmydefaultadminrights'           => 'setMyDefaultAdministratorRights',
        'setmydefaultrights'                => 'setMyDefaultAdministratorRights',
        'setdefaultrights'                  => 'setMyDefaultAdministratorRights',
        'getmydefaultadministratorrights'   => 'getMyDefaultAdministratorRights',
        'getmydefaultadminrights'           => 'getMyDefaultAdministratorRights',
        'getmydefaultrights'                => 'getMyDefaultAdministratorRights',
        'getdefaultrights'                  => 'getMyDefaultAdministratorRights',
        'editmessagetext'                   => 'editMessageText',
        'edittext'                          => 'editMessageText',
        'editmessagecaption'                => 'editMessageCaption',
        'editcap'                           => 'editMessageCaption',
        'editcaption'                       => 'editMessageCaption',
        'editmessagemedia'                  => 'editMessageMedia',
        'editmedia'                         => 'editMessageMedia',
        'editmessagelivelocation'           => 'editMessageLiveLocation',
        'editliveloc'                       => 'editMessageLiveLocation',
        'stopmessagelivelocation'           => 'stopMessageLiveLocation',
        'stopliveloc'                       => 'stopMessageLiveLocation',
        'editmessagereplymarkup'            => 'editMessageReplyMarkup',
        'editreply'                         => 'editMessageReplyMarkup',
        'editkeyboard'                      => 'editMessageReplyMarkup',
        'stoppoll'                          => 'stopPoll',
        'deletemessage'                     => 'deleteMessage',
        'del'                               => 'deleteMessage',
        'sendsticker'                       => 'sendSticker',
        'sticker'                           => 'sendSticker',
        'getstickerset'                     => 'getStickerSet',
        'getcustomemojistickers'            => 'getCustomEmojiStickers',
        'uploadstickerfile'                 => 'uploadStickerFile',
        'uploadsticker'                     => 'uploadStickerFile',
        'createnewstickerset'               => 'createNewStickerSet',
        'createsticker'                     => 'createNewStickerSet',
        'addstickertoset'                   => 'addStickerToSet',
        'addsticker'                        => 'addStickerToSet',
        'setstickerpositioninset'           => 'setStickerPositionInSet',
        'setstickerposition'                => 'setStickerPositionInSet',
        'setstickerpos'                     => 'setStickerPositionInSet',
        'deletestickerfromset'              => 'deleteStickerFromSet',
        'deletesticker'                     => 'deleteStickerFromSet',
        'setstickeremojilist'               => 'setStickerEmojiList',
        'setstickerkeywords'                => 'setStickerKeywords',
        'setstickermaskposition'            => 'setStickerMaskPosition',
        'setstickersettitle'                => 'setStickerSetTitle',
        'setstickersetthumb'                => 'setStickerSetThumbnail',
        'setstickerthumb'                   => 'setStickerSetThumbnail',
        'setcustomemojistickersetthumbnail' => 'setCustomEmojiStickerSetThumbnail',
        'deletestickerset'                  => 'deleteStickerSet',
        'answerinlinequery'                 => 'answerInlineQuery',
        'answerinline'                      => 'answerInlineQuery',
        'answerwebappquery'                 => 'answerWebAppQuery',
        'answerwebapp'                      => 'answerWebAppQuery',
        'answerweb'                         => 'answerWebAppQuery',
        'sendinvoice'                       => 'sendInvoice',
        'invoice'                           => 'sendInvoice',
        'createinvoicelink'                 => 'createInvoiceLink',
        'createinvoice'                     => 'createInvoiceLink',
        'answershippingquery'               => 'answerShippingQuery',
        'answershipping'                    => 'answerShippingQuery',
        'answerprecheckoutquery'            => 'answerPreCheckoutQuery',
        'answerprecheckout'                 => 'answerPreCheckoutQuery',
        'answerprecheck'                    => 'answerPreCheckoutQuery',
        'setpassportdataerrors'             => 'setPassportDataErrors',
        'setpassport'                       => 'setPassportDataErrors',
        'sendgame'                          => 'sendGame',
        'game'                              => 'sendGame',
        'setgamescore'                      => 'setGameScore',
        'gamescore'                         => 'setGameScore',
        'getgamehighscores'                 => 'getGameHighScores',
        'getgamehigh'                       => 'getGameHighScores',
    ];

    private const METHODS_KEYS = [
        'getUpdates'                        => ['offset', 'limit', 'timeout', 'allowed_updates', 'token', 'forgot', 'answer'],
        'setWebhook'                        => ['url', 'certificate', 'ip_address', 'max_connections', 'allowed_updates', 'drop_pending_updates', 'secret_token', 'token', 'forgot', 'answer'],
        'deleteWebhook'                     => ['drop_pending_updates', 'token', 'forgot', 'answer'],
        'getWebhookInfo'                    => ['token', 'forgot', 'answer'],
        'getMe'                             => ['token', 'forgot', 'answer'],
        'logOut'                            => ['token', 'forgot', 'answer'],
        'close'                             => ['token', 'forgot', 'answer'],
        'sendMessage'                       => ['text', 'chat_id', 'message_thread_id', 'parse_mode', 'entities', 'disable_web_page_preview', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'forwardMessage'                    => ['chat_id', 'message_thread_id', 'from_chat_id', 'disable_notification', 'protect_content', 'message_id', 'token', 'forgot', 'answer'],
        'copyMessage'                       => ['chat_id', 'message_thread_id', 'from_chat_id', 'message_id', 'caption', 'parse_mode', 'caption_entities', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendPhoto'                         => ['photo', 'chat_id', 'message_thread_id', 'caption', 'parse_mode', 'caption_entities', 'has_spoiler', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendAudio'                         => ['audio', 'chat_id', 'message_thread_id', 'caption', 'parse_mode', 'caption_entities', 'duration', 'performer', 'title', 'thumbnail', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendDocument'                      => ['document', 'chat_id', 'message_thread_id', 'thumbnail', 'caption', 'parse_mode', 'caption_entities', 'disable_content_type_detection', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendVideo'                         => ['video', 'chat_id', 'message_thread_id', 'duration', 'width', 'height', 'thumbnail', 'caption', 'parse_mode', 'caption_entities', 'has_spoiler', 'supports_streaming', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendAnimation'                     => ['animation', 'chat_id', 'message_thread_id', 'duration', 'width', 'height', 'thumbnail', 'caption', 'parse_mode', 'caption_entities', 'has_spoiler', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendVoice'                         => ['voice', 'chat_id', 'message_thread_id', 'caption', 'parse_mode', 'caption_entities', 'duration', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendVideoNote'                     => ['video_note', 'chat_id', 'message_thread_id', 'duration', 'length', 'thumbnail', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendMediaGroup'                    => ['media', 'chat_id', 'message_thread_id', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'token', 'forgot', 'answer'],
        'sendLocation'                      => ['latitude', 'longitude', 'chat_id', 'message_thread_id', 'horizontal_accuracy', 'live_period', 'heading', 'proximity_alert_radius', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendVenue'                         => ['latitude', 'longitude', 'title', 'address', 'chat_id', 'message_thread_id', 'foursquare_id', 'foursquare_type', 'google_place_id', 'google_place_type', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendContact'                       => ['phone_number', 'first_name', 'chat_id', 'message_thread_id', 'last_name', 'vcard', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendPoll'                          => ['question', 'options', 'chat_id', 'message_thread_id', 'is_anonymous', 'type', 'allows_multiple_answers', 'correct_option_id', 'explanation', 'explanation_parse_mode', 'explanation_entities', 'open_period', 'close_date', 'is_closed', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendDice'                          => ['chat_id', 'message_thread_id', 'emoji', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'sendChatAction'                    => ['chat_id', 'message_thread_id', 'action', 'token', 'forgot', 'answer'],
        'getUserProfilePhotos'              => ['user_id', 'offset', 'limit', 'token', 'forgot', 'answer'],
        'getFile'                           => ['file_id', 'token', 'forgot', 'answer'],
        'banChatMember'                     => ['chat_id', 'user_id', 'until_date', 'revoke_messages', 'token', 'forgot', 'answer'],
        'unbanChatMember'                   => ['chat_id', 'user_id', 'only_if_banned', 'token', 'forgot', 'answer'],
        'restrictChatMember'                => ['permissions', 'chat_id', 'user_id', 'use_independent_chat_permissions', 'until_date', 'token', 'forgot', 'answer'],
        'promoteChatMember'                 => ['chat_id', 'user_id', 'is_anonymous', 'can_manage_chat', 'can_post_messages', 'can_edit_messages', 'can_delete_messages', 'can_manage_video_chats', 'can_restrict_members', 'can_promote_members', 'can_change_info', 'can_invite_users', 'can_pin_messages', 'can_manage_topics', 'token', 'forgot', 'answer'],
        'setChatAdministratorCustomTitle'   => ['custom_title', 'chat_id', 'user_id', 'token', 'forgot', 'answer'],
        'banChatSenderChat'                 => ['sender_chat_id', 'chat_id', 'token', 'forgot', 'answer'],
        'unbanChatSenderChat'               => ['sender_chat_id', 'chat_id', 'token', 'forgot', 'answer'],
        'setChatPermissions'                => ['permissions', 'chat_id', 'use_independent_chat_permissions', 'token', 'forgot', 'answer'],
        'exportChatInviteLink'              => ['chat_id', 'token', 'forgot', 'answer'],
        'createChatInviteLink'              => ['chat_id', 'name', 'expire_date', 'member_limit', 'creates_join_request', 'token', 'forgot', 'answer'],
        'editChatInviteLink'                => ['invite_link', 'chat_id', 'name', 'expire_date', 'member_limit', 'creates_join_request', 'token', 'forgot', 'answer'],
        'revokeChatInviteLink'              => ['invite_link', 'chat_id', 'token', 'forgot', 'answer'],
        'approveChatJoinRequest'            => ['chat_id', 'user_id', 'token', 'forgot', 'answer'],
        'declineChatJoinRequest'            => ['chat_id', 'user_id', 'token', 'forgot', 'answer'],
        'setChatPhoto'                      => ['photo', 'chat_id', 'token', 'forgot', 'answer'],
        'deleteChatPhoto'                   => ['chat_id', 'token', 'forgot', 'answer'],
        'setChatTitle'                      => ['title', 'chat_id', 'token', 'forgot', 'answer'],
        'setChatDescription'                => ['chat_id', 'description', 'token', 'forgot', 'answer'],
        'pinChatMessage'                    => ['chat_id', 'message_id', 'disable_notification', 'token', 'forgot', 'answer'],
        'unpinChatMessage'                  => ['chat_id', 'message_id', 'token', 'forgot', 'answer'],
        'unpinAllChatMessages'              => ['chat_id', 'token', 'forgot', 'answer'],
        'leaveChat'                         => ['chat_id', 'token', 'forgot', 'answer'],
        'getChat'                           => ['chat_id', 'token', 'forgot', 'answer'],
        'getChatAdministrators'             => ['chat_id', 'token', 'forgot', 'answer'],
        'getChatMemberCount'                => ['chat_id', 'token', 'forgot', 'answer'],
        'getChatMember'                     => ['chat_id', 'user_id', 'token', 'forgot', 'answer'],
        'setChatStickerSet'                 => ['sticker_set_name', 'chat_id', 'token', 'forgot', 'answer'],
        'deleteChatStickerSet'              => ['chat_id', 'token', 'forgot', 'answer'],
        'getForumTopicIconStickers'         => ['token', 'forgot', 'answer'],
        'createForumTopic'                  => ['name', 'chat_id', 'icon_color', 'icon_custom_emoji_id', 'token', 'forgot', 'answer'],
        'editForumTopic'                    => ['message_thread_id', 'chat_id', 'name', 'icon_custom_emoji_id', 'token', 'forgot', 'answer'],
        'closeForumTopic'                   => ['message_thread_id', 'chat_id', 'token', 'forgot', 'answer'],
        'reopenForumTopic'                  => ['message_thread_id', 'chat_id', 'token', 'forgot', 'answer'],
        'deleteForumTopic'                  => ['message_thread_id', 'chat_id', 'token', 'forgot', 'answer'],
        'unpinAllForumTopicMessages'        => ['message_thread_id', 'chat_id', 'token', 'forgot', 'answer'],
        'editGeneralForumTopic'             => ['name', 'chat_id', 'token', 'forgot', 'answer'],
        'closeGeneralForumTopic'            => ['chat_id', 'token', 'forgot', 'answer'],
        'reopenGeneralForumTopic'           => ['chat_id', 'token', 'forgot', 'answer'],
        'hideGeneralForumTopic'             => ['chat_id', 'token', 'forgot', 'answer'],
        'unhideGeneralForumTopic'           => ['chat_id', 'token', 'forgot', 'answer'],
        'answerCallbackQuery'               => ['callback_query_id', 'text', 'show_alert', 'url', 'cache_time', 'token', 'forgot', 'answer'],
        'setMyCommands'                     => ['commands', 'scope', 'language_code', 'token', 'forgot', 'answer'],
        'deleteMyCommands'                  => ['scope', 'language_code', 'token', 'forgot', 'answer'],
        'getMyCommands'                     => ['scope', 'language_code', 'token', 'forgot', 'answer'],
        'setMyDescription'                  => ['description', 'language_code', 'token', 'forgot', 'answer'],
        'getMyDescription'                  => ['language_code', 'token', 'forgot', 'answer'],
        'setMyShortDescription'             => ['short_description', 'language_code', 'token', 'forgot', 'answer'],
        'getMyShortDescription'             => ['language_code', 'token', 'forgot', 'answer'],
        'setChatMenuButton'                 => ['chat_id', 'menu_button', 'token', 'forgot', 'answer'],
        'getChatMenuButton'                 => ['chat_id', 'token', 'forgot', 'answer'],
        'setMyDefaultAdministratorRights'   => ['rights', 'for_channels', 'token', 'forgot', 'answer'],
        'getMyDefaultAdministratorRights'   => ['for_channels', 'token', 'forgot', 'answer'],
        'editMessageText'                   => ['text', 'chat_id', 'message_id', 'inline_message_id', 'parse_mode', 'entities', 'disable_web_page_preview', 'reply_markup', 'token', 'forgot', 'answer'],
        'editMessageCaption'                => ['chat_id', 'message_id', 'inline_message_id', 'caption', 'parse_mode', 'caption_entities', 'reply_markup', 'token', 'forgot', 'answer'],
        'editMessageMedia'                  => ['media', 'chat_id', 'message_id', 'inline_message_id', 'reply_markup', 'token', 'forgot', 'answer'],
        'editMessageLiveLocation'           => ['latitude', 'longitude', 'chat_id', 'message_id', 'inline_message_id', 'horizontal_accuracy', 'heading', 'proximity_alert_radius', 'reply_markup', 'token', 'forgot', 'answer'],
        'stopMessageLiveLocation'           => ['chat_id', 'message_id', 'inline_message_id', 'reply_markup', 'token', 'forgot', 'answer'],
        'editMessageReplyMarkup'            => ['chat_id', 'message_id', 'inline_message_id', 'reply_markup', 'token', 'forgot', 'answer'],
        'stopPoll'                          => ['chat_id', 'message_id', 'reply_markup', 'token', 'forgot', 'answer'],
        'deleteMessage'                     => ['chat_id', 'message_id', 'token', 'forgot', 'answer'],
        'sendSticker'                       => ['sticker', 'chat_id', 'message_thread_id', 'emoji', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'getStickerSet'                     => ['name', 'token', 'forgot', 'answer'],
        'getCustomEmojiStickers'            => ['custom_emoji_ids', 'token', 'forgot', 'answer'],
        'uploadStickerFile'                 => ['sticker', 'sticker_format', 'user_id', 'token', 'forgot', 'answer'],
        'createNewStickerSet'               => ['name', 'title', 'stickers', 'sticker_format', 'user_id', 'sticker_type', 'needs_repainting', 'token', 'forgot', 'answer'],
        'addStickerToSet'                   => ['name', 'sticker', 'user_id', 'token', 'forgot', 'answer'],
        'setStickerPositionInSet'           => ['sticker', 'position', 'token', 'forgot', 'answer'],
        'deleteStickerFromSet'              => ['sticker', 'token', 'forgot', 'answer'],
        'setStickerEmojiList'               => ['sticker', 'emoji_list', 'token', 'forgot', 'answer'],
        'setStickerKeywords'                => ['sticker', 'keywords', 'token', 'forgot', 'answer'],
        'setStickerMaskPosition'            => ['sticker', 'mask_position', 'token', 'forgot', 'answer'],
        'setStickerSetTitle'                => ['name', 'title', 'token', 'forgot', 'answer'],
        'setStickerSetThumbnail'            => ['name', 'user_id', 'thumbnail', 'token', 'forgot', 'answer'],
        'setCustomEmojiStickerSetThumbnail' => ['name', 'custom_emoji_id', 'token', 'forgot', 'answer'],
        'deleteStickerSet'                  => ['name', 'token', 'forgot', 'answer'],
        'answerInlineQuery'                 => ['results', 'inline_query_id', 'cache_time', 'is_personal', 'next_offset', 'switch_pm_text', 'switch_pm_parameter', 'token', 'forgot', 'answer'],
        'answerWebAppQuery'                 => ['web_app_query_id', 'result', 'token', 'forgot', 'answer'],
        'sendInvoice'                       => ['title', 'description', 'payload', 'provider_token', 'currency', 'prices', 'chat_id', 'message_thread_id', 'max_tip_amount', 'suggested_tip_amounts', 'start_parameter', 'provider_data', 'photo_url', 'photo_size', 'photo_width', 'photo_height', 'need_name', 'need_phone_number', 'need_email', 'need_shipping_address', 'send_phone_number_to_provider', 'send_email_to_provider', 'is_flexible', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'createInvoiceLink'                 => ['title', 'description', 'payload', 'provider_token', 'currency', 'prices', 'max_tip_amount', 'suggested_tip_amounts', 'provider_data', 'photo_url', 'photo_size', 'photo_width', 'photo_height', 'need_name', 'need_phone_number', 'need_email', 'need_shipping_address', 'send_phone_number_to_provider', 'send_email_to_provider', 'is_flexible', 'token', 'forgot', 'answer'],
        'answerShippingQuery'               => ['ok', 'shipping_query_id', 'shipping_options', 'error_message', 'token', 'forgot', 'answer'],
        'answerPreCheckoutQuery'            => ['ok', 'pre_checkout_query_id', 'error_message', 'token', 'forgot', 'answer'],
        'setPassportDataErrors'             => ['errors', 'user_id', 'token', 'forgot', 'answer'],
        'sendGame'                          => ['game_short_name', 'chat_id', 'message_thread_id', 'disable_notification', 'protect_content', 'reply_to_message_id', 'allow_sending_without_reply', 'reply_markup', 'token', 'forgot', 'answer'],
        'setGameScore'                      => ['score', 'user_id', 'force', 'disable_edit_message', 'chat_id', 'message_id', 'inline_message_id', 'token', 'forgot', 'answer'],
        'getGameHighScores'                 => ['user_id', 'chat_id', 'message_id', 'inline_message_id', 'token', 'forgot', 'answer']
    ];

    private const METHODS_WITH_FILE = [
        'setWebhook'             => ['certificate'],
        'sendPhoto'              => ['photo'],
        'sendAudio'              => ['audio', 'thumbnail'],
        'sendDocument'           => ['document', 'thumbnail'],
        'sendVideo'              => ['video', 'thumbnail'],
        'sendAnimation'          => ['animation', 'thumbnail'],
        'sendVoice'              => ['voice'],
        'sendVideoNote'          => ['video_note', 'thumbnail'],
        'setChatPhoto'           => ['photo'],
        'sendSticker'            => ['sticker'],
        'uploadStickerFile'      => ['sticker'],
        'setStickerSetThumbnail' => ['thumbnail']
    ];

    private const METHODS_EXTRA_DEFAULTS = [
        'setWebhook'                        => ['url'],
        'sendMessage'                       => ['chat_id'],
        'forwardMessage'                    => ['from_chat_id', 'message_id'],
        'copyMessage'                       => ['from_chat_id', 'message_id'],
        'sendPhoto'                         => ['chat_id'],
        'sendAudio'                         => ['chat_id'],
        'sendDocument'                      => ['chat_id'],
        'sendVideo'                         => ['chat_id'],
        'sendAnimation'                     => ['chat_id'],
        'sendVoice'                         => ['chat_id'],
        'sendVideoNote'                     => ['chat_id'],
        'sendMediaGroup'                    => ['chat_id'],
        'sendLocation'                      => ['chat_id'],
        'sendVenue'                         => ['chat_id'],
        'sendContact'                       => ['chat_id'],
        'sendPoll'                          => ['chat_id'],
        'sendDice'                          => ['chat_id'],
        'sendChatAction'                    => ['action', 'chat_id'],
        'getUserProfilePhotos'              => ['user_id'],
        'getFile'                           => ['file_id'],
        'banChatMember'                     => ['user_id', 'chat_id'],
        'unbanChatMember'                   => ['user_id', 'chat_id'],
        'restrictChatMember'                => ['user_id', 'chat_id'],
        'promoteChatMember'                 => ['user_id', 'chat_id'],
        'setChatAdministratorCustomTitle'   => ['user_id', 'chat_id'],
        'banChatSenderChat'                 => ['chat_id'],
        'unbanChatSenderChat'               => ['chat_id'],
        'setChatPermissions'                => ['chat_id'],
        'exportChatInviteLink'              => ['chat_id'],
        'createChatInviteLink'              => ['chat_id'],
        'editChatInviteLink'                => ['chat_id'],
        'revokeChatInviteLink'              => ['chat_id'],
        'approveChatJoinRequest'            => ['user_id', 'chat_id'],
        'declineChatJoinRequest'            => ['user_id', 'chat_id'],
        'setChatPhoto'                      => ['chat_id'],
        'deleteChatPhoto'                   => ['chat_id'],
        'setChatTitle'                      => ['chat_id'],
        'setChatDescription'                => ['chat_id'],
        'pinChatMessage'                    => ['chat_id', 'message_id'],
        'unpinChatMessage'                  => ['chat_id', 'message_id'],
        'unpinAllChatMessages'              => ['chat_id'],
        'leaveChat'                         => ['chat_id'],
        'getChat'                           => ['chat_id'],
        'getChatAdministrators'             => ['chat_id'],
        'getChatMemberCount'                => ['chat_id'],
        'getChatMember'                     => ['user_id', 'chat_id'],
        'setChatStickerSet'                 => ['chat_id'],
        'deleteChatStickerSet'              => ['chat_id'],
        'createForumTopic'                  => ['chat_id'],
        'editForumTopic'                    => ['chat_id'],
        'closeForumTopic'                   => ['chat_id'],
        'reopenForumTopic'                  => ['chat_id'],
        'deleteForumTopic'                  => ['chat_id'],
        'unpinAllForumTopicMessages'        => ['chat_id'],
        'editGeneralForumTopic'             => ['chat_id'],
        'closeGeneralForumTopic'            => ['chat_id'],
        'reopenGeneralForumTopic'           => ['chat_id'],
        'hideGeneralForumTopic'             => ['chat_id'],
        'unhideGeneralForumTopic'           => ['chat_id'],
        'answerCallbackQuery'               => ['callback_query_id'],
        'setChatMenuButton'                 => ['chat_id'],
        'getChatMenuButton'                 => ['chat_id'],
        'editMessageText'                   => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'editMessageCaption'                => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'editMessageMedia'                  => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'editMessageLiveLocation'           => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'stopMessageLiveLocation'           => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'editMessageReplyMarkup'            => ['inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'stopPoll'                          => ['chat_id', 'message_id'],
        'deleteMessage'                     => ['chat_id', 'message_id'],
        'sendSticker'                       => ['chat_id'],
        'uploadStickerFile'                 => ['user_id'],
        'createNewStickerSet'               => ['user_id'],
        'addStickerToSet'                   => ['user_id'],
        'setStickerSetThumbnail'            => ['user_id'],
        'answerInlineQuery'                 => ['inline_query_id'],
        'sendInvoice'                       => ['chat_id'],
        'answerShippingQuery'               => ['shipping_query_id'],
        'answerPreCheckoutQuery'            => ['pre_checkout_query_id'],
        'setPassportDataErrors'             => ['user_id'],
        'sendGame'                          => ['chat_id'],
        'setGameScore'                      => ['user_id', 'inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
        'getGameHighScores'                 => ['user_id', 'inline_query' => ['inline_message_id'], 'other' => ['chat_id', 'message_id']],
    ];

    private const METHODS_RETURN = [
        'getUpdates'                      => ['BPT\types\update'],
        'getWebhookInfo'                  => 'BPT\types\webhookInfo',
        'getMe'                           => 'BPT\types\user',
        'sendMessage'                     => 'BPT\types\message',
        'forwardMessage'                  => 'BPT\types\message',
        'copyMessage'                     => 'BPT\types\messageId',
        'sendPhoto'                       => 'BPT\types\message',
        'sendAudio'                       => 'BPT\types\message',
        'sendDocument'                    => 'BPT\types\message',
        'sendVideo'                       => 'BPT\types\message',
        'sendAnimation'                   => 'BPT\types\message',
        'sendVoice'                       => 'BPT\types\message',
        'sendVideoNote'                   => 'BPT\types\message',
        'sendMediaGroup'                  => ['BPT\types\message'],
        'sendLocation'                    => 'BPT\types\message',
        'sendVenue'                       => 'BPT\types\message',
        'sendContact'                     => 'BPT\types\message',
        'sendPoll'                        => 'BPT\types\message',
        'sendDice'                        => 'BPT\types\message',
        'getUserProfilePhotos'            => 'BPT\types\userProfilePhotos',
        'getFile'                         => 'BPT\types\file',
        'createChatInviteLink'            => 'BPT\types\chatInviteLink',
        'editChatInviteLink'              => 'BPT\types\chatInviteLink',
        'revokeChatInviteLink'            => 'BPT\types\chatInviteLink',
        'getChat'                         => 'BPT\types\chat',
        'getChatAdministrators'           => ['BPT\types\chatMember'],
        'getChatMember'                   => 'BPT\types\chatMember',
        'getForumTopicIconStickers'       => ['BPT\types\sticker'],
        'createForumTopic'                => 'BPT\types\forumTopic',
        'getMyCommands'                   => ['BPT\types\botCommand'],
        'getMyDescription'                => 'BPT\types\botDescription',
        'getMyShortDescription'           => 'BPT\types\botShortDescription',
        'getChatMenuButton'               => 'BPT\types\menuButton',
        'getMyDefaultAdministratorRights' => 'BPT\types\chatAdministratorRights',
        'editMessageText'                 => 'BPT\types\message',
        'editMessageCaption'              => 'BPT\types\message',
        'editMessageMedia'                => 'BPT\types\message',
        'editMessageLiveLocation'         => 'BPT\types\message',
        'stopMessageLiveLocation'         => 'BPT\types\message',
        'editMessageReplyMarkup'          => 'BPT\types\message',
        'stopPoll'                        => 'BPT\types\poll',
        'sendSticker'                     => 'BPT\types\message',
        'getStickerSet'                   => 'BPT\types\stickerSet',
        'getCustomEmojiStickers'          => ['BPT\types\sticker'],
        'uploadStickerFile'               => 'BPT\types\file',
        'answerWebAppQuery'               => 'BPT\types\sentWebAppMessage',
        'sendInvoice'                     => 'BPT\types\message',
        'sendGame'                        => 'BPT\types\message',
        'setGameScore'                    => 'BPT\types\message',
        'getGameHighScores'               => ['BPT\types\gameHighScore']
    ];

    public static function __callStatic (string $name, array $arguments) {
        if (!$action = self::methodAction($name)) {
            logger::write("$name method is not supported",loggerTypes::ERROR);
            throw new bptException('METHOD_NOT_FOUND');
        }
        self::checkArguments($arguments);
        self::keysName($action,$arguments);
        self::readyFile($action,$arguments);
        self::cleanArguments($arguments);
        self::setDefaults($action,$arguments);

        if (isset($arguments['answer']) && $arguments['answer'] === true) {
            unset($arguments['answer']);
            if (!answer::isAnswered()) {
                return answer::init($action,$arguments);
            }
            logger::write('you can use answer mode only once for each webhook update, Others will be called like normal',loggerTypes::WARNING);
        }
        $result = curl::init($action,$arguments);
        if (!is_object($result)) {
            logger::write("Telegram $action method failed : " . $result, loggerTypes::WARNING);
            return false;
        }
        return self::processResponse($action,$result);
    }

    private static function checkArguments(array &$arguments): void {
        if (isset($arguments[0]) && count($arguments) === 1 && is_array($arguments[0]) && isset($arguments[0][0])) {
            $arguments = $arguments[0];
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
                if ($media['media'] instanceof CURLFile) {
                    $remove_answer = true;
                }
                elseif (is_string($media['media']) && file_exists(realpath($media['media']))) {
                    $arguments['media'][$key]['media'] = new CURLFile($media['media']);
                    $remove_answer = true;
                }
            }
        }
        elseif ($file_params = self::methodFile($name)) {
            foreach ($file_params as $param) {
                if ($arguments[$param] instanceof CURLFile) {
                    $remove_answer = true;
                }
                elseif (isset($arguments[$param]) && is_string($arguments[$param]) && file_exists(realpath($arguments[$param]))) {
                    $arguments[$param] = new CURLFile($arguments[$param]);
                    $remove_answer = true;
                }
            }
        }
        if (isset($remove_answer) && isset($arguments['answer'])) {
            unset($arguments['answer']);
            logger::write("You can not use answer while sending file", loggerTypes::WARNING);
        }
    }

    private static function methodFile(string $name): array|false {
        return self::METHODS_WITH_FILE[$name] ?? false;
    }

    private static function methodReturn(string $name,stdClass $response) {
        if (!isset(self::METHODS_RETURN[$name])) {
            return $response->result;
        }
        $return = self::METHODS_RETURN[$name];
        if (!is_array($return)) {
            return new ($return) ($response->result);
        }
        $response = $response->result;
        foreach ($response as &$value) {
            $value = new ($return[0]) ($value);
        }
        return $response;
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
        if (!isset($arguments['parse_mode']) && !empty(settings::$default_parse_mode) && in_array('parse_mode', self::METHODS_KEYS[$name])) {
            $arguments['parse_mode'] = settings::$default_parse_mode;
        }
        if (!isset($arguments['protect_content']) && !empty(settings::$default_protect_content) && in_array('protect_content', self::METHODS_KEYS[$name])) {
            $arguments['protect_content'] = settings::$default_protect_content;
        }
    }

    private static function processResponse(string $name, stdClass $response) {
        self::$status = $response->ok;
        self::$pure_response = $response;
        if (!$response->ok) {
            logger::write("Telegram $name method failed : " . json_encode($response), loggerTypes::WARNING);
            return new responseError($response);
        }
        return self::methodReturn($name,$response);
    }

    private static function cleanArguments (array &$arguments): void {
        foreach ($arguments as $key => $argument) {
            if ($argument == [] || $argument === null) {
                unset($arguments[$key]);
            }
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
            case fields::CHAT_ID :
            case fields::FROM_CHAT_ID :
                return match (true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->id,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->id,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->id,
                    default => false
                };
            case fields::USER_ID :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->id,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->id,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->id,
                    default => false
                };
            case fields::MESSAGE_ID :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->message_id,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->message_id,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->message_id,
                    default => false
                };
            case fields::FILE_ID :
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
            case fields::CALLBACK_QUERY_ID :
                return match (true) {
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->id,
                    default => false
                };
            case fields::SHIPPING_QUERY_ID :
                return match(true) {
                    isset(BPT::$update->shipping_query) => BPT::$update->shipping_query->id,
                    default => false
                };
            case fields::PRE_CHECKOUT_QUERY_ID :
                return match(true) {
                    isset(BPT::$update->pre_checkout_query) => BPT::$update->pre_checkout_query->id,
                    default => false
                };
            case fields::INLINE_QUERY_ID :
                return match(true) {
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->id,
                    default => false
                };
            case fields::TYPE :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->type,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->type,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->chat_type,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->type,
                    default => false
                };
            case fields::ACTION :
                return chatActions::TYPING;
            case fields::NAME :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->first_name,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->first_name,
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->first_name,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->first_name,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->first_name,
                    default => false
                };
            case fields::LAST_NAME :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->last_name ?? '',
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->last_name ?? '',
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->last_name ?? '',
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->last_name ?? '',
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->last_name ?? '',
                    default => false
                };
            case fields::USERNAME :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->from->username ?? '',
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->from->username ?? '',
                    isset(BPT::$update->inline_query) => BPT::$update->inline_query->from->username ?? '',
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->from->username ?? '',
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->from->username ?? '',
                    default => false
                };
            case fields::GROUP_NAME :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->first_name,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->first_name,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->first_name,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->first_name,
                    default => false
                };
            case fields::GROUP_USERNAME :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->chat->username,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->chat->username,
                    isset(BPT::$update->callback_query) => BPT::$update->callback_query->message->chat->username,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->chat->username,
                    default => false
                };
            case fields::UPDATE_TYPE :
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
            case fields::UPDATE_DATE :
                return match(true) {
                    isset(BPT::$update->message) => BPT::$update->message->date,
                    isset(BPT::$update->edited_message) => BPT::$update->edited_message->date,
                    isset(BPT::$update->chat_join_request) => BPT::$update->chat_join_request->date,
                    isset(BPT::$update->my_chat_member) => BPT::$update->my_chat_member->date,
                    isset(BPT::$update->chat_member) => BPT::$update->chat_member->date,
                    isset(BPT::$update->channel_post) => BPT::$update->channel_post->date,
                    isset(BPT::$update->edited_channel_post) => BPT::$update->edited_channel_post->date,
                    default => false
                };
            case fields::URL :
                return 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            default:
                return false;
        }
    }

    public static function fileLink (string|null $file_id = null): bool|string {
        $file = request::getFile($file_id);
        if (!isset($file->file_path)) {
            return false;
        }
        return $file->link();
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
        return match ($type) {
            fileTypes::VIDEO => request::sendVideo($file_id, $chat_id, $message_thread_id, null, null, null, null, $caption, $parse_mode, $caption_entities, false,null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::VIDEO_NOTE => request::sendVideoNote($file_id, $chat_id, $message_thread_id, null, null, null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::ANIMATION => request::sendAnimation($file_id, $chat_id, $message_thread_id, null, null, null, null, $caption, $parse_mode, $caption_entities, false, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::AUDIO => request::sendAudio($file_id, $chat_id, $message_thread_id, $caption, $parse_mode, $caption_entities, null, null, null, null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::PHOTO, fileTypes::PROFILE_PHOTO => request::sendPhoto($file_id, $chat_id, $message_thread_id, $caption, $parse_mode, $caption_entities, false, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::VOICE => request::sendVoice($file_id, $chat_id, $message_thread_id, $caption, $parse_mode, $caption_entities, null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::STICKER => request::sendSticker($file_id, $chat_id, $message_thread_id, null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            fileTypes::DOCUMENT => request::sendDocument($file_id, $chat_id, $message_thread_id, null, $caption, $parse_mode, $caption_entities, null, $disable_notification, $protect_content, $reply_to_message_id, $allow_sending_without_reply, $reply_markup, $token, $forgot, $answer),
            default => false,
        };
    }
}