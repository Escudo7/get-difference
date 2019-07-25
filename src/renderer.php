<?php

namespace Project\renderer;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT = '    ';

function render($ast, $indent = '')
{
    $nodes = array_keys($ast);
    $view = array_reduce($nodes, function ($acc, $node) use ($ast) {
        $sheet = $ast[$node];
        $keys = array_keys($sheet);
        switch ($node) {
            case 'added':
                $acc[] = array_reduce($keys, function ($viewAdded, $key) use ($sheet) {
                    ?
                }, []);
            $acc[] = renderAdded($node, $indent);
                break;
            case 'deleted':
                break;
            case 'modified':
                break;
            case 'unmodified':
                break;
            case 'nested':
        }
    }, []);
    
    
    

        if ($identifier === 'added') {
            foreach ($item as $key => $value) {
                if (!is_array($value)) {
                    $convertedValue = convertValue($value);
                    $view[] = $indent . ADDED . "$key: $convertedValue";
                } else {
                    $newIndent = $indent . INDENT . INDENT;
                    $view[] = $indent . ADDED . "$key: {";
                    $view[] = renderArray($value, $newIndent);
                    $view[] = $indent . INDENT . "}";
                }
            }
        } elseif ($identifier === 'deleted') {
            foreach ($item as $key => $value) {
                if (!is_array($value)) {
                    $convertedValue = convertValue($value);
                    $view[] = $indent . DELETED . "$key: $formatedValue";
                } else {
                    $newIndent = $indent . INDENT . INDENT;
                    $view[] = $indent . DELETED . "$key: {";
                    $view[] = renderArray($value, $newIndent);
                    $view[] = $indent . INDENT . "}";
                }
            }
        } elseif ($identifier === 'shared') {
            foreach ($item as $key => $value) {
                if (!is_array($value)) {
                    $convertedValue = convertValue($value);
                    $view[] = $indent . INDENT . "$key: $formatedValue";
                } else {
                    $newIndent = $indent . INDENT . INDENT;
                    $view[] = $indent . INDENT . "$key: {";
                    $view[] = renderArray($value, $newIndent);
                    $view[] = $indent . INDENT . "}";
                }
            }
        } elseif ($identifier === 'modified') {
            foreach ($item as $key => $value) {
                $convertedNewValue = convertValue($value['newValue']);
                $convertedOldValue = convertValue($value['oldValue']);
                $view[] = $indent . ADDED . "$key: $formatedNewValue";
                $view[] = $indent . DELETED . "$key: $formatedOldValue";
            }
        } elseif ($identifier === 'nested') {
            $newIndent = INDENT . $indent;
            foreach ($item as $key => $value) {
                $view[] = $newIndent . "$key: {";
                $view[] = render($value, $newIndent);
                $view[] = $newIndent . "}";
            }
        }
    }
    return implode("\n", $view);
}
function renderAdded($data, $indent)
{
    if (is_array($data)) {
        ?
    } else {
        $convertedValue = convertValue($$data);
        $view[] = $indent . ADDED . "$key: $convertedValue";
    }
}


function isBoolean($data)
{
    return gettype($data) === 'boolean';
}

function convertValue($data)
{
    if (isBoolean($data)) {
        return $data === true ? 'true' : 'false';
    }
    return $data;    
}

function renderArray($data, $indent)
{
    $view = [];
    foreach ($data as $key => $item) {
        if (is_array($item)) {
            $newIndent = $indent . INDENT;
            $view[] = renderArray($item, $newIndent);
        } else {
            $view[] = "{$indent}{$key}: $item";
        }
    }
    return implode("\n", $view);
}
