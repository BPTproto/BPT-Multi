<?php

use BPT\BPT;
use BPT\constants\cryptoCallbackStatus;
use BPT\constants\dbTypes;
use BPT\database\mysql;
use BPT\pay\crypto;
use BPT\types\cryptoCallback;
use BPT\types\message;
use function BPT\strReplace;

if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}
else {
    if (!file_exists('BPT.phar')) {
        copy('https://dl.bptlib.ir/BPT.phar', 'BPT.phar');
    }
    require 'BPT.phar';
}

class handler extends BPT {
    const ADMIN = 123456789;

    const MIN_TRANSFER = 0;
    const MAX_TRANSFER = 100000;

    const MAX_MONEY_DECIMAL = 2;
    const MAX_MONEY_LENGTH = 10;

    public function __construct (array $settings) {
        parent::__construct($settings);
    }

    public function cryptoCallback (cryptoCallback $cryptoData) {
        $paid_amount = $cryptoData->paid_amount;
        $user_id = $cryptoData->user_id;
        $real_amount = $cryptoData->real_amount;
        $total_paid = $cryptoData->total_paid;
        if ($cryptoData->status !== cryptoCallbackStatus::FINISHED) {
            $order_id = $cryptoData->order_id;
            mysql::insert('history', ['type', 'amount', 'date', 'user_id', 'order_id'], ['deposit', $paid_amount, time(), $user_id, $order_id]);
        }
        if ($cryptoData->status === cryptoCallbackStatus::PARTIALLY_PAID) {
            $need_to_pay = $real_amount - $total_paid;
            mysql::update('users', ['balance' => '+=' . $paid_amount], ['id' => $user_id], 1);
            return $this->sendMessage(strReplace(['$amount' => $paid_amount, '$real_amount' => $real_amount, '$need_amount' => $need_to_pay], texts::PARTIALLY_PAID), $user_id);
        }
        if ($cryptoData->status === cryptoCallbackStatus::FINISHED) {
            if ($paid_amount != $total_paid) {
                $old_amount = $total_paid - $paid_amount;
                mysql::update('users', ['balance' => '+=' . $paid_amount], ['id' => $user_id], 1);
                return $this->sendMessage(strReplace(['$amount' => $total_paid, '$old_amount' => $old_amount, '$new_amount' => $paid_amount], texts::FINISHED_PARTIALLY), $user_id);
            }
            mysql::update('users', ['balance' => '+=' . $paid_amount], ['id' => $user_id], 1);
            return $this->sendMessage(strReplace(['$amount' => $paid_amount], texts::FINISHED), $user_id);
        }
        if ($cryptoData->status === cryptoCallbackStatus::EXTRA_PAID) {
            if ($paid_amount != $total_paid) {
                $old_amount = $total_paid - $paid_amount;
                mysql::update('users', ['balance' => '+=' . $paid_amount], ['id' => $user_id], 1);
                return $this->sendMessage(strReplace(['$amount' => $total_paid, '$real_amount' => $real_amount, '$old_amount' => $old_amount, '$new_amount' => $paid_amount], texts::EXTRA_PAID_PARTIALLY), $user_id);
            }
            mysql::update('users', ['balance' => '+=' . $paid_amount], ['id' => $user_id], 1);
            return $this->sendMessage(strReplace(['$amount' => $paid_amount, '$real_amount' => $real_amount], texts::EXTRA_PAID), $user_id);
        }
        if ($cryptoData->status === cryptoCallbackStatus::SUCCESS) {
            die('<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <title>Success</title>
</head>
<style>
    body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
    }
    h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }
    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size:20px;
        margin: 0;
    }
    i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
    }
    .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>
<body>
<div class="card">
    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
    </div>
    <h1>Success</h1>
    <p>Check our bot for more information</p>
</div>
</body>
</html>');
        }
    }

    public function message (message $update) {
        $text = $update->text ?? '';

        $user_id = $update->from->id;

        $user = mysql::select('users', '*', ['id' => $user_id]);
        if ($user->num_rows < 1) {
            mysql::insert('users', ['id'], [$user_id]);
            $user = mysql::select('users', '*', ['id' => $user_id]);
        }
        $user = $user->fetch_object();

        if ($text === '/start') {
            mysql::update('users', ['step' => 'main', 'value' => ''], ['id' => $user_id], 1);
            return $this->sendMessage(texts::START, reply_markup: keyboards::START, answer: true);
        }

        if ($text === '/help') {
            return $this->sendMessage(texts::HELP, answer: true);
        }

        if ($text === '/back') {
            $text = buttons::BACK;
        }

        if ($user->step === 'main') {
            if ($text === buttons::BALANCE) {
                return $this->sendMessage(strReplace(['$balance' => $user->balance, '$coin' => texts::COIN], texts::BALANCE), answer: true);
            }
            if ($text === buttons::DEPOSIT) {
                mysql::update('users', ['step' => 'deposit'], ['id' => $user_id], 1);
                return $this->sendMessage(texts::DEPOSIT, reply_markup: keyboards::BACK, answer: true);
            }
            if ($text === buttons::TRANSFER) {
                if ($user->balance <= self::MIN_TRANSFER) {
                    return $this->sendMessage(strReplace(['$min_transfer' => self::MIN_TRANSFER], texts::MIN_BALANCE), answer: true);
                }

                mysql::update('users', ['step' => 'transfer'], ['id' => $user_id], 1);
                return $this->sendMessage(texts::TRANSFER, reply_markup: keyboards::SHARE_USER, answer: true);
            }
            if ($text === buttons::HISTORY) {
                return $this->sendMessage(texts::SOON, answer: true);
            }
            if ($text === buttons::SUPPORT) {
                return $this->sendMessage(texts::SOON, answer: true);
            }

            return $this->sendMessage(texts::UNKNOWN, answer: true);
        }
        if ($user->step === 'transfer') {
            if ($text === buttons::BACK) {
                mysql::update('users', ['step' => 'main', 'value' => ''], ['id' => $user_id], 1);
                return $this->sendMessage(texts::START, reply_markup: keyboards::START, answer: true);
            }
            if (isset($update->user_shared)) {
                $target_id = $update->user_shared->user_id;
            }
            elseif (isset($update->forward_date)) {
                if (!isset($update->forward_from)) {
                    return $this->sendMessage(texts::USER_FORWARD_CLOSED, answer: true);
                }
                $target_id = $update->forward_from->id;
            }
            else {
                if (!is_numeric($text) || $text != floor($text)) {
                    return $this->sendMessage(texts::ONLY_INT, answer: true);
                }
                $target_id = $text;
            }

            $target_user = mysql::select('users', '*', ['id' => $target_id], 1);
            if ($target_user->num_rows < 1) {
                return $this->sendMessage(texts::USER_NOT_FOUND, answer: true);
            }

            mysql::update('users', ['step' => 'transfer_money', 'value' => $target_id], ['id' => $user_id], 1);
            return $this->sendMessage(strReplace(['$balance' => min($user->balance, self::MAX_TRANSFER)], texts::TRANSFER_MONEY), reply_markup: keyboards::BACK, answer: true);
        }
        if ($user->step === 'transfer_money') {
            if ($text === buttons::BACK) {
                mysql::update('users', ['step' => 'transfer'], ['id' => $user_id], 1);
                return $this->sendMessage(texts::TRANSFER, reply_markup: keyboards::SHARE_USER, answer: true);
            }

            if (!is_numeric($text)) {
                return $this->sendMessage(texts::ONLY_NUMBER, answer: true);
            }

            $text = floor($text * pow(10, self::MAX_MONEY_DECIMAL)) / pow(10, self::MAX_MONEY_DECIMAL);

            if ($user->balance < $text) {
                return $this->sendMessage(texts::NOT_ENOUGH_BALANCE, answer: true);
            }

            if ($text > self::MAX_TRANSFER) {
                return $this->sendMessage(strReplace(['$max_transfer' => self::MAX_TRANSFER], texts::MAX_TRANSFER), answer: true);
            }

            mysql::update('users', ['balance' => '+=' . $text], ['id' => $user->value], 1);
            $this->sendMessage(strReplace(['$amount' => $text, '$id' => $user_id], texts::MONEY_RECEIVED), $user->value);

            mysql::insert('history', ['type', 'amount', 'date', 'user_id', 'target_id'], ['transfer', $text, time(), $user_id, $user->value]);

            mysql::update('users', ['balance' => '-=' . $text, 'step' => 'main', 'value' => ''], ['id' => $user_id], 1);
            return $this->sendMessage(texts::TRANSFER_DONE, reply_markup: keyboards::START, answer: true);
        }
        if ($user->step === 'deposit') {
            if ($text === buttons::BACK) {
                mysql::update('users', ['step' => 'main', 'value' => ''], ['id' => $user_id], 1);
                return $this->sendMessage(texts::START, reply_markup: keyboards::START, answer: true);
            }

            if (!is_numeric($text)) {
                return $this->sendMessage(texts::ONLY_NUMBER, answer: true);
            }

            $text = floor($text * pow(10, self::MAX_MONEY_DECIMAL)) / pow(10, self::MAX_MONEY_DECIMAL);

            $max_deposit = pow(10, self::MAX_MONEY_LENGTH - self::MAX_MONEY_DECIMAL - 1);
            if ($text > pow(10, self::MAX_MONEY_LENGTH - self::MAX_MONEY_DECIMAL - 1)) {
                return $this->sendMessage(strReplace(['$max_deposit' => $max_deposit], texts::MAX_DEPOSIT), answer: true);
            }

            $max_balance = pow(10, self::MAX_MONEY_LENGTH - self::MAX_MONEY_DECIMAL) - 1;
            if ($user->balance + $text >= $max_balance) {
                return $this->sendMessage(strReplace(['$max_balance' => $max_balance], texts::MAX_BALANCE), answer: true);
            }

            $url = crypto::ezPay($text, $user_id, one_time_url: false);
            mysql::update('users', ['step' => 'main', 'value' => ''], ['id' => $user_id], 1);
            return $this->sendMessage(strReplace(['$url' => $url], texts::INVOICE_CREATED), reply_markup: keyboards::START, answer: true);
        }
    }
}

class texts {
    const SOON                = 'Will completed soon';
    const UNKNOWN             = 'Command does not found, please use buttons or send /start';
    const ONLY_NUMBER         = 'Only number is allowed';
    const ONLY_INT            = 'Only integer is allowed';
    const START               = 'Hello dear user
Welcome to our bot
If this is your first time, please send /help command';
    const HELP                = 'Hi, This is a simple wallet bot created by BPT library
With this bot, you can easily deposit crypto, transfer it to another person.
It is useful for saving crypto and trade with it at anytime you want';
    const BALANCE             = 'Your balance is : $balance$coin';
    const COIN                = '$';
    const DEPOSIT             = 'How much do you want to deposit?';
    const MAX_DEPOSIT         = 'You can not deposit more then $max_deposit';
    const MAX_BALANCE         = 'With this deposit, your balance will reach our max balance which is $max_balance';
    const INVOICE_CREATED     = 'Your payment created successfully
Payment url :
$url';
    const MIN_BALANCE         = 'You can not transfer when your balance is not more then $min_transfer';
    const TRANSFER            = 'Please send user id :
You can forward a message too';
    const USER_FORWARD_CLOSED = 'User forward is closed, Please send its user id';
    const USER_NOT_FOUND      = 'User not found, He/She must be a member of bot';
    const TRANSFER_MONEY      = 'How much do you want to transfer?
Max is : $balance
Note : Be aware that we do not get a confirmation from you!
Transfer will be done after sending amount';
    const NOT_ENOUGH_BALANCE = 'Your balance is not enough for this much transfer';
    const MAX_TRANSFER = 'You can not transfer more then $max_transfer';
    const MONEY_RECEIVED = 'Hello, You received $amount from $id';
    const TRANSFER_DONE = 'Transfer is done successfully.';

    const PARTIALLY_PAID = 'Dear user, You paid $amount instead of your requested $real_amount
Any how we added your paid amount to your balance
If you want to complete your payment, you need to pay $need_amount more';

    const FINISHED = 'Dear user, You paid $amount and it added to your balance';
    const FINISHED_PARTIALLY = 'Dear user, You paid $amount and it added to your balance
You paid $old_amount before, so you got only $new_amount now';

    const EXTRA_PAID = 'Dear user, You paid $amount instead of your requested $real_amount
Any how we added your paid amount to your balance';

    const EXTRA_PAID_PARTIALLY = 'Dear user, You paid $amount instead of your requested $real_amount
Any how we added your paid amount to your balance
You paid $old_amount before, so you got only $new_amount now';
}

class buttons {
    const BACK     = 'Back';
    const BALANCE  = 'Balance';
    const DEPOSIT  = 'Deposit';
    const TRANSFER = 'Transfer';
    const HISTORY  = 'History';
    const SUPPORT  = 'Support';
}

class keyboards {
    const BACK = [
        'resize_keyboard' => true,
        'keyboard' => [
            [
                ['text' => buttons::BACK]
            ]
        ]
    ];

    const SHARE_USER = [
        'resize_keyboard' => true,
        'keyboard' => [
            [
                ['text' => 'Choose user', 'request_user' => ['request_id' => 12, 'user_is_bot' => false]]
            ],
            [
                ['text' => buttons::BACK]
            ]
        ]
    ];
    const START = [
        'resize_keyboard' => true,
        'keyboard' => [
            [
                ['text' => buttons::BALANCE]
            ],
            [
                ['text' => buttons::DEPOSIT], ['text' => buttons::TRANSFER]
            ],
            [
                ['text' => buttons::HISTORY], ['text' => buttons::SUPPORT]
            ]
        ]
    ];
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
        'dbname' => 'dbName',
        'auto_process' => false,
        'auto_load' => true
    ],
    'pay' => [
        'crypto' => [
            'api_key' => 'API_KEY',
            'ipn_secret' => 'IPN_SECRET',
            'round_decimal' => handler::MAX_MONEY_DECIMAL
        ]
    ],
    'allowed_updates' => ['message']
]);
