<?php

namespace BPT\database;

use BPT\api\telegram;
use BPT\BPT;
use BPT\constants\chatMemberStatus;
use BPT\constants\chatType;
use BPT\constants\fields;
use BPT\database\json\groupUserInterface;
use BPT\database\json\userInterface;
use BPT\exception\bptException;
use BPT\settings;
use BPT\tools;
use BPT\types\callbackQuery;
use BPT\types\chatMemberUpdated;
use BPT\types\inlineQuery;
use BPT\types\message;

class json {
    /**
     * Global data will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Always loaded when db started, no matter what
     */
    public static ?object $global = null;
    private static ?object $old_global = null;

    /**
     * User data will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Will be loaded in Private chats. Will be loaded in Groups and SuperGroups if user started bot in private chat
     * @var userInterface $user
     */
    public static ?object $user = null;
    private static ?object $old_user = null;
    private static int $user_id;

    /**
     * Group data will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Will be loaded in groups only.
     */
    public static ?object $group = null;
    private static ?object $old_group = null;
    private static int $group_id;

    /**
     * Supergroup data will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Will be loaded in Supergroups only.
     */
    public static ?object $supergroup = null;
    private static ?object $old_supergroup = null;
    private static int $supergroup_id;

    /**
     * User data in group will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Will be loaded in group and supergroups
     * @var groupUserInterface $group_user
     */
    public static ?object $group_user = null;
    private static ?object $old_group_user = null;
    private static int $group_user_id;

    /**
     * Channel data will be loaded here automatically, And your changes will be saved automatically too
     *
     *  
     *
     * Will be loaded in Channel only.
     */
    public static ?object $channel = null;
    private static ?object $old_channel = null;
    private static int $channel_id;

    private static string $folder = '';

    private static array $ids = [];
    private static array $old_ids = [];

    private static array $group_ids = [];
    private static array $old_group_ids = [];

    private static array $global_default_data = [];
    private static array $user_default_data = ['step' => 'none', 'value' => '', 'phone_number' => '', 'first_active' => 0, 'last_active' => 0, 'referral' => null];
    private static array $group_user_default_data = ['step' => 'none', 'value' => '', 'last_active' => 0, 'presence' => true, 'removed' => false, 'removed_by' => null, 'invite_link' => null, 'accepted_by' => null, 'invited_by' => null];
    private static array $group_default_data = [];
    private static array $supergroup_default_data = [];
    private static array $channel_default_data = [];

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init (): void {
        self::$folder = settings::$name.'database';
        self::$global_default_data = settings::$db['global'] ?? self::$global_default_data;
        self::setUserDefaultData();
        self::setGroupUserDefaultData();
        self::$group_default_data = settings::$db['group'] ?? self::$group_default_data;
        self::$supergroup_default_data = settings::$db['supergroup'] ?? self::$supergroup_default_data;
        self::$channel_default_data = settings::$db['channel'] ?? self::$channel_default_data;
        self::create();
        self::load();
    }

    private static function setUserDefaultData (): void {
        self::$user_default_data = settings::$db['user'] ?? self::$user_default_data;
        if (!isset(self::$user_default_data['step'])) {
            self::$user_default_data['step'] = 'none';
        }
        if (!isset(self::$user_default_data['value'])) {
            self::$user_default_data['value'] = '';
        }
        if (!isset(self::$user_default_data['phone_number'])) {
            self::$user_default_data['phone_number'] = '';
        }
        if (!isset(self::$user_default_data['first_active'])) {
            self::$user_default_data['first_active'] = 0;
        }
        if (!isset(self::$user_default_data['last_active'])) {
            self::$user_default_data['last_active'] = 0;
        }
    }

    private static function setGroupUserDefaultData (): void {
        self::$group_user_default_data = settings::$db['group_user'] ?? self::$group_user_default_data;
        if (!isset(self::$group_user_default_data['step'])) {
            self::$group_user_default_data['step'] = 'none';
        }
        if (!isset(self::$group_user_default_data['value'])) {
            self::$group_user_default_data['value'] = '';
        }
    }

    private static function create(): void {
        if (!is_dir(self::$folder)) {
            mkdir(self::$folder);
        }
        if (!is_dir(self::$folder.'/privates')) {
            mkdir(self::$folder.'/privates');
        }
        if (!is_dir(self::$folder.'/groups')) {
            mkdir(self::$folder.'/groups');
        }
        if (!is_dir(self::$folder.'/supergroups')) {
            mkdir(self::$folder.'/supergroups');
        }
        if (!is_dir(self::$folder.'/channels')) {
            mkdir(self::$folder.'/channels');
        }

        if (!file_exists(self::$folder.'/global.json')) {
            file_put_contents(self::$folder.'/global.json',self::$global_default_data);
        }
        if (!file_exists(self::$folder.'/ids.json')) {
            file_put_contents(self::$folder.'/ids.json',json_encode([
                'privates' => [],
                'groups' => [],
                'supergroups' => [],
                'channels' => []
            ]));
        }
    }

    private static function load (): void {
        self::$global = json_decode(file_get_contents(self::$folder.'/global.json'));
        self::$old_global = clone self::$global;
        self::$ids = json_decode(file_get_contents(self::$folder.'/ids.json'),true);
        self::$old_ids = clone self::$ids;
    }

    private static function read (string $address) {
        return file_exists(self::$folder.'/'.$address.'.json') ? json_decode(file_get_contents(self::$folder.'/'.$address.'.json'), false) : null;
    }

    private static function write (string $address,string $data): void {
        file_put_contents(self::$folder.'/'.$address.'.json',$data);
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function process (): void {
        if (isset(BPT::$update->message)) {
            self::processMessage(BPT::$update->message);
        }
        elseif (isset(BPT::$update->edited_message)) {
            self::processMessage(BPT::$update->edited_message);
        }
        elseif (isset(BPT::$update->callback_query)) {
            self::processCallbackQuery(BPT::$update->callback_query);
        }
        elseif (isset(BPT::$update->inline_query)) {
            self::processInlineQuery(BPT::$update->inline_query);
        }
        elseif (isset(BPT::$update->chat_member)) {
            self::processChatMember(BPT::$update->chat_member);
        }
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function save(): void {
        if (self::$user !== self::$old_user && !empty(self::$user_id)) {
            self::write('privates/' . self::$user_id,json_encode(self::$user));
        }
        if (self::$group !== self::$old_group && !empty(self::$group_id)) {
            self::write('groups/' . self::$group_id,json_encode(self::$group));
        }
        if (self::$supergroup !== self::$old_supergroup && !empty(self::$supergroup_id)) {
            self::write('supergroups/' . self::$supergroup_id,json_encode(self::$supergroup));
        }
        if (self::$channel !== self::$old_channel && !empty(self::$channel_id)) {
            self::write('channels/' . self::$channel_id,json_encode(self::$channel));
        }
        if (self::$group_user !== self::$old_group_user && !empty(self::$group_user_id)) {
            if (empty(self::$group)) {
                $category = 'supergroups';
                $group_id = self::$supergroup_id;
            }
            else {
                $category = 'groups';
                $group_id = self::$group_id;
            }
            self::write($category . '/' . $group_id . '/' . self::$group_user_id,json_encode(self::$group_user));
        }
        if (self::$ids !== self::$old_ids) {
            self::write('ids',json_encode(self::$ids));
        }
        if (self::$global !== self::$old_global) {
            self::write('global',json_encode(self::$global));
        }
        if (self::$group_ids !== self::$old_group_ids) {
            if (empty(self::$group)) {
                $category = 'supergroups';
                $group_id = self::$supergroup_id;
            }
            else {
                $category = 'groups';
                $group_id = self::$group_id;
            }
            self::write($category . '/' . $group_id . '/users',json_encode(self::$group_user));
        }
    }

    private static function processMessage(message $update): void {
        $type = $update->chat->type;
        $category = $type . 's';
        if ($type === chatType::PRIVATE) {
            self::$user_id = $update->from->id;
            if (in_array(self::$user_id,self::$ids[$category])) {
                self::$user = self::read($category . '/' . self::$user_id);
                self::$old_user = clone self::$user;
                self::$user->last_active = time();
            }
            else {
                self::$ids[$category][] = self::$user_id;
                self::$user = (object) self::$user_default_data;
                self::$user->first_active = self::$user->last_active = time();
                if (isset($update->commend) && isset($update->commend_payload) && $update->commend === 'start' && str_starts_with($update->commend_payload,'ref_')) {
                    if (tools::isShorted(substr($update->commend_payload,4))) {
                        $referral = tools::shortDecode(substr($update->commend_payload,4));
                        if (in_array($referral,self::$ids[$category])) {
                            self::$user->referral = $referral;
                        }
                    }
                }
            }
        }
        elseif ($type === chatType::CHANNEL) {
            self::$channel_id = $update->chat->id;
            if (in_array(self::$channel_id,self::$ids[$category])) {
                self::$channel = self::read($category . '/' . self::$channel_id);
                self::$old_channel = clone self::$channel;
            }
            else {
                self::$ids[$category][] = self::$channel_id;
                self::$channel = (object) self::$channel_default_data;
            }
        }
        else {
            self::$user_id = $update->from->id;
            self::$group_user_id = self::$user_id;
            $chat_id = $update->chat->id;
            if ($type === chatType::SUPERGROUP) {
                self::$supergroup_id = $update->chat->id;
                if (in_array($chat_id,self::$ids[$category])) {
                    self::$supergroup = self::read($category . '/' . $chat_id);
                    self::$old_supergroup = clone self::$supergroup;
                    self::$group_ids = self::read($category . '/' . $chat_id . '/users');
                    self::$old_group_ids = clone self::$group_ids;
                }
                else {
                    self::$ids[$category][] = $chat_id;
                    self::$supergroup = (object) self::$supergroup_default_data;
                    self::$group_ids = [];
                }
            }
            else {
                self::$group_id = $update->chat->id;
                if (in_array($chat_id,self::$ids[$category])) {
                    self::$group = self::read($category . '/' . $chat_id);
                    self::$old_group = clone self::$group;
                    self::$group_ids = self::read($category . '/' . $chat_id . '/users');
                    self::$old_group_ids = clone self::$group_ids;
                }
                else {
                    self::$ids[$category][] = $chat_id;
                    self::$group = (object) self::$group_default_data;
                    self::$group_ids = [];
                }
            }

            if (in_array(self::$user_id,self::$group_ids)) {
                self::$group_user = self::read($category . '/' . $chat_id . '/' . self::$user_id);
                self::$old_group_user = clone self::$group_user;
            }
            else {
                self::$group_ids[] = self::$user_id;
                self::$group_user = (object) self::$group_user_default_data;
            }

            self::$user = self::read($category.'/'.self::$user_id);
            self::$old_user = clone self::$user;
            if (!empty(self::$user)) self::$user->last_active = time();
        }
    }

    private static function processCallbackQuery(callbackQuery $update): void {
        $type = $update->message->chat->type;
        $category = $type . 's';
        if ($type === chatType::PRIVATE) {
            self::$user_id = $update->from->id;
            if (in_array(self::$user_id,self::$ids[$category])) {
                self::$user = self::read($category . '/' . self::$user_id);
                self::$old_user = clone self::$user;
                self::$user->last_active = time();
            }
        }
        elseif ($type === chatType::CHANNEL) {
            self::$channel_id = $update->message->chat->id;
            if (in_array(self::$channel_id,self::$ids[$category])) {
                self::$channel = self::read($category . '/' . self::$channel_id);
                self::$old_channel = clone self::$channel;
            }
        }
        else {
            self::$user_id = $update->from->id;
            $chat_id = $update->message->chat->id;
            if (in_array($chat_id,self::$ids[$category])) {
                if ($type === chatType::SUPERGROUP) {
                    self::$supergroup_id = $update->message->chat->id;
                    self::$supergroup = self::read($category . '/' . $chat_id);
                    self::$old_supergroup = clone self::$supergroup;
                }
                else {
                    self::$group_id = $update->message->chat->id;
                    self::$group = self::read($category . '/' . $chat_id);
                    self::$old_group = clone self::$group;
                }
                self::$group_ids = self::read($category . '/' . $chat_id . '/users');
                self::$old_group_ids = clone self::$group_ids;
            }

            if (in_array(self::$user_id,self::$group_ids)) {
                self::$group_user = self::read($category . '/' . $chat_id . '/' . self::$user_id);
                self::$group_user_id = self::$user_id;
                self::$old_group_user = clone self::$group_user;
            }

            self::$user = self::read($category.'/'.self::$user_id);
            self::$old_user = clone self::$user;
            if (!empty(self::$user)) self::$user->last_active = time();
        }
    }

    private static function processInlineQuery(inlineQuery $update): void {
        $type = $update->chat_type;
        if ($type === chatType::PRIVATE || $type === chatType::SENDER) {
            $category = chatType::PRIVATE . 's';
            self::$user_id = $update->from->id;
            if (in_array(self::$user_id,self::$ids[$category])) {
                self::$user = self::read($category . '/' . self::$user_id);
                self::$old_user = clone self::$user;
                self::$user->last_active = time();
            }
        }
    }

    private static function processChatMember(chatMemberUpdated $update): void {
        $type = $update->chat->type;

        $category = $type . 's';
        if ($type === chatType::CHANNEL) {
            self::$channel_id = $update->chat->id;
            if (in_array(self::$channel_id,self::$ids[$category])) {
                self::$channel = self::read($category . '/' . self::$channel_id);
                self::$old_channel = clone self::$channel;
            }
            else {
                self::$ids[$category][] = self::$channel_id;
                self::$channel = (object) self::$channel_default_data;
            }
        }
        else {
            $chat_id = $update->chat->id;
            $by_id = $update->from->id;
            $old_user = $update->old_chat_member;
            $new_user = $update->new_chat_member;
            $user_id = $old_user->user->id;
            $invite_link = $update->invite_link;


            if ($type === chatType::SUPERGROUP) {
                self::$supergroup_id = $chat_id;
                if (in_array($chat_id,self::$ids[$category])) {
                    self::$supergroup = self::read($category . '/' . $chat_id);
                    self::$old_supergroup = clone self::$supergroup;
                    self::$group_ids = self::read($category . '/' . $chat_id . '/users');
                    self::$old_group_ids = clone self::$group_ids;
                }
                else {
                    self::$ids[$category][] = $chat_id;
                    self::$supergroup = (object) self::$supergroup_default_data;
                    self::$group_ids = [];
                }
            }
            elseif ($type === chatType::GROUP) {
                self::$group_id = $chat_id;
                if (in_array($chat_id,self::$ids[$category])) {
                    self::$group = self::read($category . '/' . $chat_id);
                    self::$old_group = clone self::$group;
                    self::$group_ids = self::read($category . '/' . $chat_id . '/users');
                    self::$old_group_ids = clone self::$group_ids;
                }
                else {
                    self::$ids[$category][] = $chat_id;
                    self::$group = (object) self::$group_default_data;
                    self::$group_ids = [];
                }
            }

            if (!in_array($user_id,self::$group_ids)) {
                self::$group_ids[] = $user_id;
                self::$group_user = (object) self::$group_user_default_data;
            }
            else {
                self::$group_user_id = $by_id == $user_id ? $user_id : $by_id;
                self::$group_user = self::read($category . '/' . $chat_id . '/' . self::$group_user_id);
            }

            if ($new_user->status === chatMemberStatus::LEFT || $new_user->status === chatMemberStatus::KICKED) {
                if ($old_user->status !== chatMemberStatus::LEFT && $old_user->status !== chatMemberStatus::KICKED) {
                    self::$group_user->presence = false;
                    self::$group_user->removed = $new_user->status === chatMemberStatus::KICKED;
                    if ($new_user->status === chatMemberStatus::KICKED) {
                        self::$group_user->removed_by = $by_id;
                    }
                }
            }
            else {
                if ($old_user->status === chatMemberStatus::LEFT || $old_user->status === chatMemberStatus::KICKED) {
                    self::$group_user->presence = true;
                    self::$group_user->removed = false;
                    self::$group_user->removed_by = null;
                    self::$group_user->invite_link = !empty($invite_link) ? $invite_link->invite_link : null;
                    if ($by_id !== $user_id) {
                        if (!empty($invite_link)) {
                            self::$group_user->accepted_by = $by_id;
                            self::$group_user->invited_by = null;
                        }
                        else {
                            self::$group_user->invited_by = $by_id;
                            self::$group_user->accepted_by = null;
                        }
                    }
                }
            }
        }
    }

    /**
     * delete user completely from database
     *
     * @param null|int $user_id
     *
     * @return bool
     * @throws bptException
     */
    public static function deleteUser (int $user_id = null): bool {
        if (empty($user_id)) $user_id = telegram::catchFields(fields::USER_ID);
        if (file_exists(self::$folder . '/privates/' . $user_id . '.json')) {
            unset(self::$ids['privates'][array_search($user_id, self::$ids['privates'])]);
            sort(self::$ids['privates']);
            if ($user_id === self::$user_id) {
                self::$user = self::$old_user = null;
            }
            return tools::delete(self::$folder . '/privates/' . $user_id . '.json');
        }
        return false;
    }

    /**
     * delete group and its user from database
     *
     * @param null|int $group_id
     *
     * @return bool
     * @throws bptException
     */
    public static function deleteGroup (int $group_id = null): bool {
        if (empty($group_id)) $group_id = telegram::catchFields(fields::CHAT_ID);
        if (file_exists(self::$folder . '/groups/' . $group_id . '.json')) {
            unset(self::$ids['groups'][array_search($group_id, self::$ids['groups'])]);
            sort(self::$ids['groups']);
            tools::delete(self::$folder . '/groups/' . $group_id);
            if ($group_id === self::$group_id) {
                self::$group = self::$old_group = null;
            }
            return tools::delete(self::$folder . '/groups/' . $group_id . '.json');
        }
        return false;
    }

    /**
     * delete supergroup and its user from database
     *
     * @param null|int $group_id
     *
     * @return bool
     * @throws bptException
     */
    public static function deleteSuperGroup (int $group_id = null): bool {
        if (empty($group_id)) $group_id = telegram::catchFields(fields::CHAT_ID);
        if (file_exists(self::$folder . '/supergroups/' . $group_id . '.json')) {
            unset(self::$ids['supergroups'][array_search($group_id, self::$ids['supergroups'])]);
            sort(self::$ids['supergroups']);
            tools::delete(self::$folder . '/supergroups/' . $group_id);
            if ($group_id === self::$supergroup_id) {
                self::$supergroup = self::$old_supergroup = null;
            }
            return tools::delete(self::$folder . '/supergroups/' . $group_id . '.json');
        }
        return false;
    }

    /**
     * delete channel from database
     *
     * @param null|int $channel_id
     *
     * @return bool
     * @throws bptException
     */
    public static function deleteChannel (int $channel_id = null): bool {
        if (empty($channel_id)) $channel_id = telegram::catchFields(fields::CHAT_ID);
        if (file_exists(self::$folder . '/channels/' . $channel_id . '.json')) {
            unset(self::$ids['channels'][array_search($channel_id, self::$ids['channels'])]);
            sort(self::$ids['channels']);
            if ($channel_id === self::$channel_id) {
                self::$channel = self::$old_channel = null;
            }
            return tools::delete(self::$folder . '/channels/' . $channel_id . '.json');
        }
        return false;
    }

    /**
     * get users list
     *
     * @return int[]
     */
    public static function getUsers (): array {
        return self::$ids['privates'] ?? [];
    }

    /**
     * get groups list
     *
     * @return int[]
     */
    public static function getGroups (): array {
        return self::$ids['groups'] ?? [];
    }

    /**
     * get supergroups list
     *
     * @return int[]
     */
    public static function getSuperGroups (): array {
        return self::$ids['supergroups'] ?? [];
    }

    /**
     * get channels list
     *
     * @return int[]
     */
    public static function getChannels (): array {
        return self::$ids['channels'] ?? [];
    }
}
