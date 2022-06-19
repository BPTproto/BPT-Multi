<?php

namespace BPT\types;

use stdClass;

/**
 * base class of all type classes
 */
class types {
    public function __toString(): string {
        return json_encode($this);
    }


    public function __construct(stdClass $update, array $subs = []) {
        foreach ($update as $key=>$value) {
            if (isset($subs[$key])) {
                $this->$key = new ($subs[$key]) ($value);
            }
            else {
                $this->$key = $value;
                if (ucfirst($key) === basename(get_class($this)).'_id') {
                    $this->{'id'} = $value;
                }
            }
        }
    }
}
