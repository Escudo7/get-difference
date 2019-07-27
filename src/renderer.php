<?php

namespace Project\renderer;

use Funct\Collection;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT = '    ';

function render($ast, $indent = '')
{
    $identefiers = array_keys($ast);
    $view = array_reduce($identefiers, function ($acc, $identefier) use ($ast) {
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
    }, []);
    return implode("\n", $view);
}

function renderNodesModified($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function($acc, $key) use ($data) {
        $viewModifiedValue = [];
        $viewModifiedValue[] = $data[$key]['oldValue'] ? DELETED . convertValue($data['oldValue']) : '';
        $viewModifiedValue[] = $data[$key]['newValue'] ? ADDED . convertValue($data['newValue']) : '';
        $acc[] = trim(implode("\n", $viewModifiedValue));
    }, []);
    return implode("\n", $view);
}

function renderNodesUnmodified($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data) {
        $value = $data[$key];
        $acc[] = is_array($value) ? renderArray($key, $value, $indent) : ($indent . "$key: " . convertValue($value));
    }, []);
    return explode("\n", $view);
}

function renderNodesNested($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data) {
        $nestedValue = $data[$key];
        $newIndent = $indent . INDENT;
        $viewNestedValue = [];
        $viewNestedValue[] = $indent . "$key: {";
        $viewNestedValue[] = render($nestedValue, $newIndent);
        $viewNestedValue[] = $indent . "}";
        $acc[] = explode("\n", $viewNestedValue);
    }, []);
    return explode("\n", $view);
}

function renderArray($key, $value, $indent)
{
    $view = [];
    $view[] = $indent . "$key: {";
    $nestedKeys = array_keys($value);
    $viewValue = array_reduce($nestedKeys, function($acc, $nestedKey) use ($value, $indent) {
        $newIndent = $indent . INDENT;
        $nestedValue = $value[$nestedKey];
        if (is_array($nestedValue)) {
          $acc[] = renderArray($nestedKey, $value[$nestedKey], $newIndent);
        } else {
          $acc[] = $newIndent . "$nestedKey: " . convertValue($nestedValue);
        }
        return $acc;
      }, []);
    $view[] = implode("\n", $viewValue);
    $view[] = $indent . "}";
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
