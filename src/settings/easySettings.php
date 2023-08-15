<?php

namespace BPT\settings;

use CURLFile;
class easySettings {
    private array $settings = [];

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken (string $token): self {
        $this->settings['token'] = $token;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName (string $name): self {
        $this->settings['name'] = $name;
        return $this;
    }

    /**
     * @param bool $logger
     *
     * @return $this
     */
    public function setLogger (bool $logger): self {
        $this->settings['logger'] = $logger;
        return $this;
    }

    /**
     * @param int $log_size
     *
     * @return $this
     */
    public function setLogSize (int $log_size): self {
        $this->settings['log_size'] = $log_size;
        return $this;
    }

    /**
     * @param string|CURLFile $certificate
     *
     * @return $this
     */
    public function setCertificate (string|CURLFile $certificate): self {
        $this->settings['certificate'] = $certificate;
        return $this;
    }

    /**
     * @param bool $handler
     *
     * @return $this
     */
    public function setHandler (bool $handler): self {
        $this->settings['handler'] = $handler;
        return $this;
    }

    /**
     * @param bool $security
     *
     * @return $this
     */
    public function setSecurity (bool $security): self {
        $this->settings['security'] = $security;
        return $this;
    }

    /**
     * @param bool $secure_folder
     *
     * @return $this
     */
    public function setSecureFolder (bool $secure_folder): self {
        $this->settings['secure_folder'] = $secure_folder;
        return $this;
    }

    /**
     * @param bool $multi
     *
     * @return $this
     */
    public function setMulti (bool $multi): self {
        $this->settings['multi'] = $multi;
        return $this;
    }

    /**
     * @param bool $telegram_verify
     *
     * @return $this
     */
    public function setTelegramVerify (bool $telegram_verify): self {
        $this->settings['telegram_verify'] = $telegram_verify;
        return $this;
    }

    /**
     * @param bool $cloudflare_verify
     *
     * @return $this
     */
    public function setCloudflareVerify (bool $cloudflare_verify): self {
        $this->settings['cloudflare_verify'] = $cloudflare_verify;
        return $this;
    }

    /**
     * @param bool $arvancloud_verify
     *
     * @return $this
     */
    public function setArvancloudVerify (bool $arvancloud_verify): self {
        $this->settings['arvancloud_verify'] = $arvancloud_verify;
        return $this;
    }

    /**
     * @param bool $skip_old_updates
     *
     * @return $this
     */
    public function setSkipOldUpdates (bool $skip_old_updates): self {
        $this->settings['skip_old_updates'] = $skip_old_updates;
        return $this;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret (string $secret): self {
        $this->settings['secret'] = $secret;
        return $this;
    }

    /**
     * @param int $max_connection
     *
     * @return $this
     */
    public function setMaxConnection (int $max_connection): self {
        $this->settings['max_connection'] = $max_connection;
        return $this;
    }

    /**
     * @param string $base_url
     *
     * @return $this
     */
    public function setBaseUrl (string $base_url): self {
        $this->settings['base_url'] = $base_url;
        return $this;
    }

    /**
     * @param string $down_url
     *
     * @return $this
     */
    public function setDownUrl (string $down_url): self {
        $this->settings['down_url'] = $down_url;
        return $this;
    }

    /**
     * @param string $default_parse_mode
     *
     * @return $this
     */
    public function setDefaultParseMode (string $default_parse_mode): self {
        $this->settings['default_parse_mode'] = $default_parse_mode;
        return $this;
    }

    /**
     * @param bool $default_protect_content
     *
     * @return $this
     */
    public function setDefaultProtectContent (bool $default_protect_content): self {
        $this->settings['default_protect_content'] = $default_protect_content;
        return $this;
    }

    /**
     * @param int $ignore_updates_older_then
     *
     * @return $this
     */
    public function setIgnoreUpdatesOlderThen (int $ignore_updates_older_then): self {
        $this->settings['ignore_updates_older_then'] = $ignore_updates_older_then;
        return $this;
    }

    /**
     * @param int $forgot_time
     *
     * @return $this
     */
    public function setForgotTime (int $forgot_time): self {
        $this->settings['forgot_time'] = $forgot_time;
        return $this;
    }

    /**
     * @param int $base_timeout
     *
     * @return $this
     */
    public function setBaseTimeout (int $base_timeout): self {
        $this->settings['base_timeout'] = $base_timeout;
        return $this;
    }

    /**
     * @param string $receiver
     *
     * @return $this
     */
    public function setReceiver (string $receiver): self {
        $this->settings['receiver'] = $receiver;
        return $this;
    }

    /**
     * @param array $allowed_updates
     *
     * @return $this
     */
    public function setAllowedUpdates (array $allowed_updates): self {
        $this->settings['allowed_updates'] = $allowed_updates;
        return $this;
    }

    /**
     * @param bool $use_types_classes
     *
     * @return $this
     */
    public function setUseTypesClasses (bool $use_types_classes): self {
        $this->settings['use_types_classes'] = $use_types_classes;
        return $this;
    }

    /**
     * @param null|array|easySQL|easyJson $db
     *
     * @return $this
     */
    public function setDB (array|easySQL|easyJson|null $db): self {
        if (!is_array($db) && !empty($db)) {
            $db = $db->getSettings();
        }
        $this->settings['db'] = $db;
        return $this;
    }

    /**
     * @param array|easyPay $pay
     *
     * @return $this
     */
    public function setPay (array|easyPay $pay): self {
        if (!is_array($pay)) {
            $pay = $pay->getSettings();
        }
        $this->settings['pay'] = $pay;
        return $this;
    }

    /**
     * @return array
     */
    public function getSettings (): array {
        return $this->settings;
    }
}