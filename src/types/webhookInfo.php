<?php

namespace BPT\types;

use stdClass;

/**
 * Describes the current status of a webhook.
 */
class webhookInfo extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Webhook URL, may be empty if webhook is not set up */
    public string $url = '';

    /** True, if a custom certificate was provided for webhook certificate checks */
    public null|bool $has_custom_certificate = null;

    /** Number of updates awaiting delivery */
    public int $pending_update_count;

    /** Optional. Currently used webhook IP address */
    public null|string $ip_address = null;

    /** Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook */
    public null|int $last_error_date = null;

    /**
     * Optional. Error message in human-readable format for the most recent error that happened when trying to
     * deliver an update via webhook
     */
    public null|string $last_error_message = null;

    /**
     * Optional. Unix time of the most recent error that happened when trying to synchronize available updates with
     * Telegram datacenters
     */
    public null|int $last_synchronization_error_date = null;

    /** Optional. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery */
    public null|int $max_connections = null;

    /**
     * Optional. A list of update types the bot is subscribed to. Defaults to all update types except chat_member
     * @var string[]
     */
    public null|array $allowed_updates = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
