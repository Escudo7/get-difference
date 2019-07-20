<?php

namespace Project\getDiff;

use Funct\Collection;

function getDiff($date1, $date2)
{
    $keys = Collection\union(array_keys($date1), array_keys($date2));
    $diff = array_reduce($keys, function ($acc, $key) use ($date1, $date2) {
        if (isset($date1[$key]) && isset($date2[$key])) {
            if ($date1[$key] === $date2[$key]) {
                $value = isBoolean($date2[$key]) ? convertBoolToStr($date2[$key]) : $date2[$key];
                $newStr = "    {$key}: {$value}\n";
            } else {
                $value1 = isBoolean($date1[$key]) ? convertBoolToStr($date1[$key]) : $date1[$key];
                $value2 = isBoolean($date2[$key]) ? convertBoolToStr($date2[$key]) : $date2[$key];
                $newStr = "  - {$key}: {$value1}\n  + {$key}: {$value2}\n";
            }
        } else {
            if (isset($date1[$key])) {
                $value = isBoolean($date1[$key]) ? convertBoolToStr($date1[$key]) : $date1[$key];
                $newStr = "  - {$key}: {$value}\n";
            } else {
                $value = isBoolean($date2[$key]) ? convertBoolToStr($date2[$key]) : $date2[$key];
                $newStr = "  + {$key}: {$value}\n";
            }
        }
        return "{$acc}{$newStr}";
    }, "");
    $result = "{\n{$diff}}\n";
    return $result;
}

function isBoolean($date)
{
    return gettype($date) === 'boolean';
}

function convertBoolToStr($date)
{
    return $date === true ? 'true' : 'false';
}
