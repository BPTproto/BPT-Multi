<?php

namespace BPT\settings;
class easyIdpay {
    private array $settings = [];

    public function setApiKey (string $api_key): self {
        $this->settings['api_key'] = $api_key;
        return $this;
    }

    public function setSandbox (string $sandbox): self {
        $this->settings['sandbox'] = $sandbox;
        return $this;
    }

    public function getSettings (): array {
        return $this->settings;
    }
}