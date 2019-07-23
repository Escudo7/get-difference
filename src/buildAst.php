<?php

namespace Project\buildAst;

use Funct\Collection;

function buildAst($data1, $data2)
{
    $keys = Collection\union(array_keys($data1), array_keys($data2));
    $ast = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (isset($data1[$key]) && isset($data2[$key])) {
            if ($data1[$key] === $data2[$key]) {
                $acc['shared'][$key] = $data1[$key];
            } else {
                if (is_array($data1[$key]) && is_array($data2[$key])) {
                    $acc['nested'][$key] = buildAst($data1[$key], $data2[$key]);
                } else {
                    $acc['modified'][$key]['oldValue'] = $data1[$key];
                    $acc['modified'][$key]['newValue'] = $data2[$key];
                }
            }
        } elseif (isset($data1[$key])) {
            $acc['deleted'][$key] = $data1[$key];
        } else {
            $acc['added'][$key] = $data2[$key];
        }
        return $acc;
    }, []);
    return $ast;
}
