<?php

namespace BPT\settings;
class easyPay {
    private array $settings = [];

    public function setCrypto (array|easyCrypto $crypto): self {
        if (!is_array($crypto) && !empty($crypto)) {
            $crypto = $crypto->getSettings();
        }
        $this->settings['crypto'] = $crypto;
        return $this;
    }

    public function setIdpay (array|easyIdpay $idpay): self {
        if (!is_array($idpay) && !empty($idpay)) {
            $idpay = $idpay->getSettings();
        }
        $this->settings['idpay'] = $idpay;
        return $this;
    }

    public function setZarinpal (array|easyZarinpal $zarinpal): self {
        if (!is_array($zarinpal) && !empty($zarinpal)) {
            $zarinpal = $zarinpal->getSettings();
        }
        $this->settings['zarinpal'] = $zarinpal;
        return $this;
    }

    public function getSettings (): array {
        return $this->settings;
    }
}