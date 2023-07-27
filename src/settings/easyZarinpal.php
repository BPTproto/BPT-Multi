<?php

namespace BPT\settings;
class easyZarinpal {
    private array $settings = [];

    public function setSandbox (string $sandbox): self {
        $this->settings['sandbox'] = $sandbox;
        return $this;
    }

    public function setZarinGate (string $zarin_gate): self {
        $this->settings['zarin_gate'] = $zarin_gate;
        return $this;
    }

    public function setMerchantId (int $merchant_id): self {
        $this->settings['merchant_id'] = $merchant_id;
        return $this;
    }

    public function getSettings (): array {
        return $this->settings;
    }
}