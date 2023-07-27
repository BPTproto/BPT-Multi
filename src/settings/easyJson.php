<?php

namespace BPT\settings;

use BPT\constants\dbTypes;

class easyJson {
    private array $settings = [
        'type' => dbTypes::JSON,
    ];

    /**
     * @param array $global
     *
     * @return $this
     */
    public function setGlobal (array $global): self {
        $this->settings['global'] = $global;
        return $this;
    }

    /**
     * @param array $group
     *
     * @return $this
     */
    public function setGroup (array $group): self {
        $this->settings['group'] = $group;
        return $this;
    }

    /**
     * @param array $supergroup
     *
     * @return $this
     */
    public function setSuperGroup (array $supergroup): self {
        $this->settings['supergroup'] = $supergroup;
        return $this;
    }

    /**
     * @param array $channel
     *
     * @return $this
     */
    public function setChannel (array $channel): self {
        $this->settings['channel'] = $channel;
        return $this;
    }

    /**
     * @param array $user
     *
     * @return $this
     */
    public function setUser (array $user): self {
        $this->settings['user'] = $user;
        return $this;
    }

    /**
     * @param array $group_user
     *
     * @return $this
     */
    public function setGroupUser (array $group_user): self {
        $this->settings['group_user'] = $group_user;
        return $this;
    }

    /**
     * @return array
     */
    public function getSettings (): array {
        return $this->settings;
    }
}