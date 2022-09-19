<?php

namespace BPT;

/**
 * easy function for creating inline object
 *
 *  
 *
 * e.g. => object(key: 'value', key2: 1234, key3: object(bool: true));
 *
 * same as (object) ['key' => 'value', 'key2' => 1234, 'key3' => ['bool' => true]]
 *
 * @param ...$argument
 *
 * @return object
 */
function object(... $argument): object {
    return (object) $argument;
}