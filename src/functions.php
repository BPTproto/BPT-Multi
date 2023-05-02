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

/**
 * Same as str_replace but with a small different
 *
 * str_replace usage :
 * ```
 * str_replace(
 * [
 *     'test1',
 *     'test2'
 * ],
 * [
 *     'test3',
 *     'test4'
 * ], $test_text);
 * ```
 *
 * strReplace usage :
 * ```
 * strReplace(
 * [
 *     'test1' => 'test3',
 *     'test2' => 'test4'
 * ], $test_text);
 * ```
 *
 * @param array        $replacements Contain key => value array for needle => replacement
 * @param string|array $subject The string or array being searched and replaced on, otherwise known as the haystack.
 *
 * @return array|string
 */
function strReplace(array $replacements, string|array $subject): array|string {
    return str_replace(array_keys($replacements), array_values($replacements), $subject);
}