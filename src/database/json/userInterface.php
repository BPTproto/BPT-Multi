<?php

namespace BPT\database\json;

/**
 * @property string $step         user step , default is 'none'
 * @property string $value        user value , default is empty
 * @property string $phone_number user phone , default is empty
 * @property int    $first_active user first active time with timestamp format , default is 0
 * @property int    $last_active  user last active time with timestamp format , default is 0
 * @property int    $referral     user referral , default is null
 */
interface userInterface {
}