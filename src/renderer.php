<?php

namespace Project\renderer;

use Funct\Collection;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT = '    ';
const UNMODIFIED = '    ';

function render($ast, $indent = '')
{
    $identefiers = array_keys($ast);
    $view = array_reduce($identefiers, function ($acc, $identefier) use ($ast, $indent) {
        $data = $ast[$identefier];
        switch ($identefier) {
            case 'modified':
                $acc[] = renderNodesModified($data, $indent);
                break;
            case 'unmodified':
                $acc[] = renderNodesUnmodified($data, $indent);
                break;
            case 'nested':
                $acc[] = renderNodesNested($data, $indent);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesModified($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
        $value = $data[$key];
        $viewModifiedValue = [];
        if ($value['oldValue']) {
            if (is_array($value['oldValue'])) {
                $viewModifiedValue[] = $indent . renderArray($key, $value['oldValue'], $indent, DELETED);
            } else {
                $viewModifiedValue[] = $indent . DELETED . "$key: " . convertValue($value['oldValue']);
            }
        }
        if ($value['newValue']) {
            if (is_array($value['newValue'])) {
                $viewModifiedValue[] = $indent . renderArray($key, $value['newValue'], $indent, ADDED);
            } else {
                $viewModifiedValue[] = $indent . ADDED . "$key: " . convertValue($value['newValue']);
            }
        }
        $acc[] = implode("\n", $viewModifiedValue);
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesUnmodified($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
        $value = $data[$key];
        if (is_array($value)) {
            $acc[] = renderArray($key, $value, $indent, UNMODIFIED);
        } else {
            $acc[] = $indent . UNMODIFIED . "$key: " . convertValue($value);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesNested($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
        $nestedValue = $data[$key];
        $newIndent = $indent . INDENT;
        $viewNestedValue = [];
        $viewNestedValue[] = $indent . UNMODIFIED . "$key: {";
        $viewNestedValue[] = render($nestedValue, $newIndent);
        $viewNestedValue[] = $indent . UNMODIFIED . "}";
        $acc[] = implode("\n", $viewNestedValue);
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderArray($key, $value, $indent, $modifier = UNMODIFIED)
{
    $view = [];
    $view[] = $indent . $modifier . "$key: {";
    $nestedKeys = array_keys($value);
    $viewValue = array_reduce($nestedKeys, function ($acc, $nestedKey) use ($value, $indent) {
        $newIndent = $indent . INDENT;
        $nestedValue = $value[$nestedKey];
        if (is_array($nestedValue)) {
            $acc[] = renderArray($nestedKey, $value[$nestedKey], $newIndent);
        } else {
            $acc[] = $newIndent . UNMODIFIED . "$nestedKey: " . convertValue($nestedValue);
        }
        return $acc;
    }, []);
    $view[] = implode("\n", $viewValue);
    $view[] = $indent . UNMODIFIED . "}";
    return implode("\n", $view);
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
