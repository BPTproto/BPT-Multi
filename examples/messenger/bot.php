<?php

use BPT\BPT;
use BPT\constants\dbTypes;
use BPT\database\mysql;
use BPT\types\message;

if (file_exists('vendor/autoload.php')){
    require 'vendor/autoload.php';
}
else{
    if(!file_exists('BPT.phar')) {
        copy('https://dl.bptlib.ir/BPT.phar', 'BPT.phar');
    }
    require 'BPT.phar';
}

class handler extends BPT {
    const ADMIN = 123456789;

    const SHOW_STATUS = true;

    const HELP = 'Hello dear admin 
This is a simple messenger bot which also support reply for users and admin itself
For answering an user message , You must reply on it
If you want to reply , You must first use /reply_on command and this will apply on your next message
If you want to cancel reply , use /reply_off
If you reply on yourself message , it will be replied without checking reply_on status
Good luck and be nice';
    const REPLY_ON = 'Ok dear admin , Your next message will be replied';
    const REPLY_OFF = 'Ok dear admin , Reply canceled';
    const NOT_FOUND = 'I didn\'t found this message in my database';
    const START_TEXT = 'Hello dear user
Send your message and I will deliver it to my admin';
    const SEND_FAILED = 'Failed!';
    const SEND_SUCCESSFUL = 'Done!';

    public function __construct(array $settings){
        parent::__construct($settings);
    }

    public function message(message $update){
        $text = $update->text ?? '';
        $user_id = $update->from->id;

        if ($text === '/start') {
            $this->sendMessage(self::START_TEXT,answer: true);
        }
        else {
            /** You could use both style */
            $message_id = $update->message_id;
            #$message_id = $update->id;

            if (self::ADMIN === $user_id) {
                if ($text === '/help') {
                    $this->sendMessage(self::HELP, answer: true);
                }
                elseif ($text === '/reply_on') {
                    mysql::update('users',['value'=>'reply_on'],['id'=>$user_id],1);
                    $this->sendMessage(self::REPLY_ON, answer: true);
                }
                elseif ($text === '/reply_off') {
                    mysql::update('users',['value'=>'reply_off'],['id'=>$user_id],1);
                    $this->sendMessage(self::REPLY_OFF, answer: true);
                }
                elseif (isset($update->reply_to_message)) {
                    $reply_message_id = $update->reply_to_message->message_id;

                    if ($update->reply_to_message->from->id === $user_id) {
                        $check_message = mysql::select('messages', ['receiver_message_id','receiver_id'], [
                            'sender_message_id' => $reply_message_id,
                            'sender_id'         => $user_id
                        ],1);

                        if ($check_message->num_rows > 0) {
                            $data = $check_message->fetch_object();
                            $receiver_id = $data->receiver_id;
                            $result = $this->copyMessage($receiver_id, reply_to_message_id: $data->receiver_message_id);
                        }
                        else {
                            $this->sendMessage(self::NOT_FOUND, answer: true);
                            return;
                        }
                    }
                    else {
                        $data = mysql::select('messages', ['sender_message_id','sender_id'], [
                            'receiver_message_id' => $reply_message_id,
                            'receiver_id'         => $user_id
                        ],1)->fetch_object();

                        $value = mysql::select('users','value',['id'=>$user_id])->fetch_object()->value;
                        $receiver_id = $data->sender_id;
                        if ($value === 'reply_on') {
                            mysql::update('users',['value'=>''],['id'=>$user_id]);
                            $result = $this->copyMessage($receiver_id, reply_to_message_id: $data->sender_message_id);
                        }
                        else {
                            $result = $this->copyMessage($receiver_id);
                        }
                    }

                    if (self::$status) {
                        mysql::insert('messages',
                            ['sender_message_id','sender_id','receiver_message_id','receiver_id'],
                            [$message_id,$user_id,$result->message_id,$receiver_id]
                        );
                        if (self::SHOW_STATUS) {
                            $this->sendMessage(self::SEND_SUCCESSFUL, answer: true);
                        }
                    }
                    else {
                        $this->sendMessage(self::SEND_FAILED, answer: true);
                    }
                }
            }
            else {
                $username = $update->from->username;
                if (empty($username)) {
                    $name = $update->from->first_name . (!empty($update->from->last_name) ? (' ' . $update->from->last_name) : '');
                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                [
                                    "text" => 'User ID', 'url' => "tg://user?id=$user_id"
                                ],
                                [
                                    "text" => $user_id, 'url' => "tg://user?id=$user_id"
                                ]
                            ],
                            [
                                [
                                    "text" => 'Name', 'callback_data' => 'none'
                                ],
                                [
                                    "text" => $name, 'callback_data' => 'none'
                                ]
                            ]
                        ]
                    ];
                }
                else {
                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                [
                                    "text" => 'Username', 'url' => "https://t.me/$username"
                                ],
                                [
                                    "text" => $username, 'url' => "https://t.me/$username"
                                ]
                            ]
                        ]
                    ];
                }
                if (isset($update->reply_to_message)) {
                    $reply_message_id = $update->reply_to_message->message_id;
                    if ($update->reply_to_message->from->id === $user_id){
                        $check_message = mysql::select('messages', 'receiver_message_id', [
                            'sender_message_id' => $reply_message_id,
                            'sender_id'         => $user_id
                        ],1);
                        if ($check_message->num_rows > 0) {
                            $result = $this->copyMessage(self::ADMIN, reply_to_message_id: $check_message->fetch_object()->receiver_message_id,reply_markup: $keyboard);
                        }
                        else {
                            $result = $this->copyMessage(self::ADMIN,reply_markup: $keyboard);
                        }
                    }
                    else {
                        $check_message = mysql::select('messages', 'sender_message_id', [
                            'receiver_message_id' => $reply_message_id,
                            'receiver_id'         => $user_id
                        ],1);
                        if ($check_message->num_rows > 0) {
                            $result = $this->copyMessage(self::ADMIN, reply_to_message_id: $check_message->fetch_object()->sender_message_id,reply_markup: $keyboard);
                        }
                        else {
                            $result = $this->copyMessage(self::ADMIN,reply_markup: $keyboard);
                        }
                    }
                }
                else {
                    $result = $this->copyMessage(self::ADMIN,reply_markup: $keyboard);
                }

                /**
                 * This is status of last called telegram method
                 * You can use telegram::$status or request::$status either
                 */
                if (self::$status) {
                    mysql::insert('messages',
                        ['sender_message_id','sender_id','receiver_message_id','receiver_id'],
                        [$message_id,$user_id,$result->message_id,self::ADMIN]
                    );
                    if (self::SHOW_STATUS) {
                        $this->sendMessage(self::SEND_SUCCESSFUL, answer: true);
                    }
                }
                else {
                    $this->sendMessage(self::SEND_FAILED, answer: true);
                }
            }
        }
    }
}

/**
 * BPT settings
 *
 * @link https://bptlib.ir/multi
 */
$BPT = new handler([
    'token' => 'YOUR_BOT_TOKEN',
    'db' => [
        'type' => dbTypes::MYSQL,
        'user' => 'dbUser',
        'pass' => 'dbPassword',
        'dbname' => 'dbName'
    ],
    'allowed_updates' => ['message']
]);