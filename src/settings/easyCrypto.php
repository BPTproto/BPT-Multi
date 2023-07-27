<?php

namespace BPT\settings;
class easyCrypto {
    private array $settings = [];

    public function setApiKey (string $api_key): self {
        $this->settings['api_key'] = $api_key;
        return $this;
    }

    public function setIpnSecret (string $ipn_secret): self {
        $this->settings['ipn_secret'] = $ipn_secret;
        return $this;
    }

    public function setRoundDecimal (int $round_decimal): self {
        $this->settings['round_decimal'] = $round_decimal;
        return $this;
    }

    public function getSettings (): array {
        return $this->settings;
    }
}