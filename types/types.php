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


    public function __construct(stdClass $object, array $subs = []) {
        foreach ($object as $key=>$value) {
            if (isset($subs[$key])) {
                $this->$key = new ($subs[$key]) ($value);
            }
            else {
                if (is_array($value)) {
                    foreach ($value as $sub_key=>$sub_value) {
                        if (is_array($sub_value)) {
                            foreach ($sub_value as $sub2_value) {
                                $this->$key[$sub_key][] = new ($subs['array']['array'][$key]) ($sub2_value);
                            }
                        }
                        else{
                            $this->$key[] = new ($subs['array'][$key]) ($sub_value);
                        }
                    }
                }
                else{
                    $this->$key = $value;
                    if (ucfirst($key) === basename(get_class($this)).'_id') {
                        $this->{'id'} = $value;
                    }
                }
            }
        }
    }


    public function __call(string $name, array $arguments) {
        $name = strtolower($name);
        if (str_starts_with($name, 'set')) {
            $name = substr($name,3);
            if (isset($arguments[0])) {
                $this->$name = $arguments[0];
            }
            elseif (isset($arguments['value'])) {
                $this->$name = $arguments['value'];
            }
        }
        return $this;
    }
}
