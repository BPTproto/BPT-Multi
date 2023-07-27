<?php

namespace BPT\constants;
class cryptoCallbackStatus {
    public const PARTIALLY_PAID = 'partially_paid';
    public const FINISHED       = 'finished';
    public const EXTRA_PAID     = 'extra_paid';
    /**
     * This will be returned when user redirected to success page
     *
     * In another word, You must show success page to user
     *
     * Note : Do not process it, Anything needed will be sent as other type of updates
     */
    public const SUCCESS = 'success';
}