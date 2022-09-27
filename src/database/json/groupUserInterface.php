<?php

namespace BPT\database\json;

/**
 * @property string $step        user step , default is 'none'
 * @property string $value       user value , default is empty
 * @property int    $last_active user last active time with timestamp format , default is 0
 * @property bool   $presence    user is in group or not , default is true
 * @property bool   $removed     user removed in group or not , default is false
 * @property int    $removed_by  user removed by , default is null
 * @property string $invite_link the link that user joined with it , default is null
 * @property int    $accepted_by user join request accepted by , default is null
 * @property int    $invited_by  user invited by , default is null
 */
interface groupUserInterface {
}