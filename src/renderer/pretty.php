<?php

namespace Project\renderer\pretty;

use Funct\Collection;
use function Project\renderer\utilities\convertValue;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT = '    ';
const UNMODIFIED = '    ';

function render($ast, $indent = '')
{
    $renderer = '{' . PHP_EOL . getView($ast)  . PHP_EOL . '}' . PHP_EOL;
    return $renderer;
}

function getView($ast, $indent = '')
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
            case 'deleted':
                $acc[] = renderNodesDeleted($data, $indent);
                break;
            case 'added':
                $acc[] = renderNodesAdded($data, $indent);
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
        $oldValue = $data[$key]['oldValue'];
        $newValue = $data[$key]['newValue'];
        if (is_array($oldValue)) {
            $acc[] = renderArray($key, $oldValue, $indent, DELETED);
        } else {
            $acc[] = $indent . DELETED . "$key: " . convertValue($oldValue);
        }
        if (is_array($newValue)) {
            $acc[] = renderArray($key, $newValue, $indent, ADDED);
        } else {
            $acc[] = $indent . ADDED . "$key: " . convertValue($newValue);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesDeleted($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
        $oldValue = $data[$key]['oldValue'];
        if (is_array($oldValue)) {
            $acc[] = renderArray($key, $oldValue, $indent, DELETED);
        } else {
            $acc[] = $indent . DELETED . "$key: " . convertValue($oldValue);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesAdded($data, $indent)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $indent) {
        $newValue = $data[$key]['newValue'];
        if (is_array($newValue)) {
            $acc[] = renderArray($key, $newValue, $indent, ADDED);
        } else {
            $acc[] = $indent . ADDED . "$key: " . convertValue($newValue);
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
        $viewNestedValue[] = getView($nestedValue, $newIndent);
        $viewNestedValue[] = $indent . UNMODIFIED . "}";
        $acc[] = implode("\n", $viewNestedValue);
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
