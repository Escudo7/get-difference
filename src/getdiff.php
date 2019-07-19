<?php

namespace Project\getDiff;

function getDiff($date1, $date2)
{
    $keys1 = array_keys($date1);
    $keys2 = array_keys($date2);
    $diffPartOne = array_reduce($keys1, function ($acc, $key) use ($date1, $date2) {
        if (gettype($date1[$key]) == 'boolean') {
            $value1 = $date1[$key] === true ? 'true' : 'false';
        } else {
            $value1 = $date1[$key];
        }
        if (array_key_exists($key, $date2)) {
            if (gettype($date2[$key]) == 'boolean') {
                $value2 = $date2[$key] === true ? 'true' : 'false';
            } else {
                $value2 = $date2[$key];
            }
            if ($date1[$key] === $date2[$key]) {
                $str = "    {$key}: {$value1}\n";
            } else {
                $str = "  - {$key}: {$value1}\n  + {$key}: {$value2}\n";
            }
        } else {
            $str = "  - {$key}: {$date1[$key]}\n";
        }
        return "{$acc}{$str}";
    }, '');
    $diffPartTwo = array_reduce($keys2, function ($acc, $key) use ($date1, $date2) {
        if (!array_key_exists($key, $date1)) {
            if (gettype($date2[$key]) == 'boolean') {
                $value2 = $date2[$key] === true ? 'true' : 'false';
            } else {
                $value2 = $date2[$key];
            }
            $str = "  + {$key}: {$value2}";
            return "{$acc}{$str}";
        }
        return $acc;
    }, $diffPartOne);
    return "{\n{$diffPartTwo}\n}\n";
}
