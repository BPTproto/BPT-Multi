<?php

namespace BPT\settings;

use BPT\constants\dbTypes;
class easySQL {
    private array $settings = [
        'type' => dbTypes::MYSQL,
    ];

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost (string $host): self {
        $this->settings['host'] = $host;
        return $this;
    }

    /**
     * @param string $port
     *
     * @return $this
     */
    public function setPort (string $port): self {
        $this->settings['port'] = $port;
        return $this;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUsername (string $user): self {
        $this->settings['user'] = $user;
        return $this;
    }

    /**
     * @param string $pass
     *
     * @return $this
     */
    public function setPassword (string $pass): self {
        $this->settings['pass'] = $pass;
        return $this;
    }

    /**
     * @param string $dbname
     *
     * @return $this
     */
    public function setDBName (string $dbname): self {
        $this->settings['dbname'] = $dbname;
        return $this;
    }

    /**
     * @param bool $auto_process
     *
     * @return $this
     */
    public function setAutoProcess (bool $auto_process): self {
        $this->settings['auto_process'] = $auto_process;
        return $this;
    }

    /**
     * @param bool $auto_load
     *
     * @return $this
     */
    public function setAutoLoad (bool $auto_load): self {
        $this->settings['auto_load'] = $auto_load;
        return $this;
    }

    /**
     * @return array
     */
    public function getSettings (): array {
        return $this->settings;
    }
}