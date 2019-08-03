<?php

namespace Project\BuilderAst;

use Funct\Collection;

function buildAst($data1, $data2)
{
    $keys = Collection\union(array_keys($data1), array_keys($data2));
    $ast = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (isset($data1[$key]) && isset($data2[$key])) {
            if ($data1[$key] === $data2[$key]) {
                $acc[] = buildNode('unmodified', $key, $data1[$key], $data2[$key]);
            } else {
                if (is_array($data1[$key]) && is_array($data2[$key])) {
                    $node = [];
                    $node['typeNode'] = 'nested';
                    $node['key'] = $key;
                    $node['ast'] = buildAst($data1[$key], $data2[$key]);
                    $acc[] = $node;
                } else {
                    $acc[] = buildNode('modified', $key, $data1[$key], $data2[$key]);
                }
            }
        } elseif (isset($data1[$key])) {
            $acc[] = buildNode('deleted', $key, $data1[$key], '');
        } else {
            $acc[] = buildNode('added', $key, '', $data2[$key]);
        }
        return $acc;
    }, []);
    return $ast;
}

function buildNode($typeNode, $key, $oldValue, $newValue)
{
    $node = [];
    $node['typeNode'] = $typeNode;
    $node['key'] = $key;
    $node['oldValue'] = $oldValue;
    $node['newValue'] = $newValue;
    return $node;
}
