<?php

namespace Project\renderer\plain;

use function Project\renderer\utilities\convertValue;

const ADDED = "Property '%s' was added with value: '%s'";
const DELETED = "Property '%s' was removed";
const MODIFIED = "Property '%s' was changed. From '%s' to '%s'";
const VALUE_IS_ARRAY = "complex value";

function render($ast)
{
    $renderer = getView($ast) . PHP_EOL;
    return $renderer;
}

function getView($ast, $parentKey = '')
{
    $identefiers = array_keys($ast);
    $view = array_reduce($identefiers, function ($acc, $identefier) use ($ast, $parentKey) {
        $data = $ast[$identefier];
        switch ($identefier) {
            case 'modified':
                $acc[] = renderNodesModified($data, $parentKey);
                break;
            case 'unmodified':
                break;
            case 'deleted':
                $acc[] = renderNodesDeleted($data, $parentKey);
                break;
            case 'added':
                $acc[] = renderNodesAdded($data, $parentKey);
                break;
            case 'nested':
                $acc[] = renderNodesNested($data, $parentKey);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesModified($data, $parentKey)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $parentKey) {
        $oldValue = is_array($data[$key]['oldValue']) ? VALUE_IS_ARRAY : $data[$key]['oldValue'];
        $newValue = is_array($data[$key]['newValue']) ? VALUE_IS_ARRAY : $data[$key]['newValue'];
        $keyWithParent = $parentKey == '' ? $key : "$parentKey.$key";
        $acc[] = sprintf(MODIFIED, $keyWithParent, convertValue($oldValue), convertValue($newValue));
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesDeleted($data, $parentKey)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $parentKey) {
        $keyWithParent = $parentKey == '' ? $key : "$parentKey.$key";
        $acc[] = sprintf(DELETED, $keyWithParent);
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesAdded($data, $parentKey)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $parentKey) {
        $value = is_array($data[$key]['newValue']) ? VALUE_IS_ARRAY : $data[$key]['newValue'];
        $keyWithParent = $parentKey == '' ? $key : "$parentKey.$key";
        $acc[] = sprintf(ADDED, $keyWithParent, convertValue($value));
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesNested($data, $parentKey)
{
    $keys = array_keys($data);
    $view = array_reduce($keys, function ($acc, $key) use ($data, $parentKey) {
        $value = $data[$key];
        $newParentKey = $parentKey == '' ? $key : "$parentKey.$key";
        $acc[] = getView($value, $newParentKey);
        return $acc;
    }, []);
    return implode("\n", $view);
}
