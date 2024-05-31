<?php

namespace BPT\types;

use stdClass;

/**
 * base class of all type classes
 */
class types {
    /**
     *  Return this object as json string
     *
     * @return string
     */
    public function __toString(): string {
        return $this->toJson();
    }

    /**
     * Return this object as json string
     *
     * @param int $flags
     *
     * @return false|string
     */
    public function toJson (int $flags = 0): false|string {
        $array = json_decode(json_encode($this), true);

        $cleanArray = function ($array) use (&$cleanArray) {
            return array_filter(array_map(fn($value) => is_array($value) ? $cleanArray($value) : $value, $array));
        };

        return json_encode($cleanArray($array), $flags);
    }

    public function __construct(stdClass $object, array $subs = []) {
        foreach ($object as $key=>$value) {
            if (isset($subs[$key])) {
                $this->{$key} = new ($subs[$key]) ($value);
            }
            elseif (is_array($value) && isset($subs['array'])) {
                foreach ($value as $sub_key => $sub_value) {
                    if (is_array($sub_value) && isset($subs['array']['array'])) {
                        foreach ($sub_value as $sub2_value) {
                            $this->{$key}[$sub_key][] = new ($subs['array']['array'][$key]) ($sub2_value);
                        }
                    }
                    elseif (isset($subs['array'][$key])) {
                        $this->{$key}[] = new ($subs['array'][$key]) ($sub_value);
                    }
                    else {
                        $this->{$key}[] = $sub_value;
                    }
                }
            }
            else {
                $this->{$key} = $value;
                if (ucfirst($key) === basename(get_class($this)) . '_id') {
                    $this->{'id'} = $value;
                }
            }
        }
    }

    public function __call(string $name, array $arguments) {
        $name = strtolower($name);
        if (str_starts_with($name, 'set')) {
            $name = substr($name,3);
            if (isset($arguments[0])) {
                $this->{$name} = $arguments[0];
            }
            elseif (isset($arguments['value'])) {
                $this->{$name} = $arguments['value'];
            }
        }
        return $this;
    }
}
