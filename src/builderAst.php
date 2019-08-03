<?php

namespace Project\BuilderAst;

use Funct\Collection;

function buildAst($data1, $data2)
{
    $keys = Collection\union(array_keys($data1), array_keys($data2));
    $ast = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (isset($data1[$key]) && isset($data2[$key])) {
            if ($data1[$key] === $data2[$key]) {
                $node = [];
                $node['typeNode'] = 'unmodified';
                $node['key'] = $key;
                $node['oldValue'] = $data1[$key];
                $node['newValue'] = $data2[$key];
                $acc[] = $node;
            } else {
                if (is_array($data1[$key]) && is_array($data2[$key])) {
                    $node = [];
                    $node['typeNode'] = 'nested';
                    $node['key'] = $key;
                    $node['ast'] = buildAst($data1[$key], $data2[$key]);
                    $acc[] = $node;
                } else {
                    $node = [];
                    $node['typeNode'] = 'modified';
                    $node['key'] = $key;
                    $node['oldValue'] = $data1[$key];
                    $node['newValue'] = $data2[$key];
                    $acc[] = $node;
                }
            }
        } elseif (isset($data1[$key])) {
            $node = [];
            $node['typeNode'] = 'deleted';
            $node['key'] = $key;
            $node['oldValue'] = $data1[$key];
            $node['newValue'] = '';
            $acc[] = $node;
        } else {
            $node = [];
            $node['typeNode'] = 'added';
            $node['key'] = $key;
            $node['oldValue'] = '';
            $node['newValue'] = $data2[$key];
            $acc[] = $node;
        }
        return $acc;
    }, []);
    return $ast;
}
