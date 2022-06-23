<?php

namespace BPT\api;

use BPT\api\request\answer;
use BPT\api\request\curl;
use BPT\BPT;
use BPT\constants\chatActions;
use BPT\constants\loggerTypes;
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


    public static function __callStatic (string $name, array $arguments) {
        if ($action = self::methodAction($name)) {
            self::keysName($action,$arguments);
            self::readyFile($action,$arguments);
            self::setDefaults($action,$arguments);
            if (isset($arguments['answer'])) {
                return answer::init($action,$arguments);
            }
            else {
                return curl::init($action,$arguments);
            }
        }
        else {
            logger::write("$name method is not supported",loggerTypes::ERROR);
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
        return self::METHODS_ACTION[str_replace('_', '', strtolower($name))] ?? false;
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

    private static function setDefaults(string $name, array &$arguments) {
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
                    isset(BPT::$update->message) => 'message',
                    isset(BPT::$update->edited_message) => 'edited_message',
                    isset(BPT::$update->inline_query) => 'inline_query',
                    isset(BPT::$update->callback_query) => 'callback_query',
                    isset(BPT::$update->chat_join_request) => 'chat_join_request',
                    isset(BPT::$update->my_chat_member) => 'my_chat_member',
                    isset(BPT::$update->chat_member) => 'chat_member',
                    isset(BPT::$update->channel_post) => 'channel_post',
                    isset(BPT::$update->edited_channel_post) => 'edited_channel_post',
                    isset(BPT::$update->chosen_inline_result) => 'chosen_inline_result',
                    isset(BPT::$update->shipping_query) => 'shipping_query',
                    isset(BPT::$update->pre_checkout_query) => 'pre_checkout_query',
                    isset(BPT::$update->poll) => 'poll',
                    isset(BPT::$update->poll_answer) => 'poll_answer',
                    default => false
                };
            case 'url' :
                return 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            default:
                return false;
        }
    }
}