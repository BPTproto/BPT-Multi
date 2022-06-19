<?php

namespace BPT\api;

use BPT\logger;
use CURLFile;

class request {
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
        'sendMessage' => ['text', 'parse_mode']
    ];

    private const METHODS_WITH_FILE = [
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


    public static function __callStatic (string $name, array $arguments) {
        if ($action = self::methodAction($name)) {
            self::keysName($action,$arguments);
            self::readyFile($action,$arguments);
            print_r($arguments);
        }
        else {
            logger::write("$name method is not supported",'error');
        }
    }

    private static function keysName (string $name, array &$arguments) {
        foreach ($arguments as $key => $argument) {
            if (is_numeric($key) && isset(self::METHODS_KEYS[$name][$key])) {
                $arguments[self::METHODS_KEYS[$name][$key]] = $argument;
                unset($arguments[$key]);
            }
        }
    }

    private static function methodAction(string $name): string|false {
        return self::METHODS_ACTION[strtolower($name)] ?? false;
    }

    private static function readyFile(string $name, array &$arguments) {
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
}