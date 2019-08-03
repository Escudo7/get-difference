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
    $view = array_reduce($ast, function ($acc, $node) use ($parentKey) {
        switch ($node['typeNode']) {
            case 'modified':
                $acc[] = renderNodesModified($node, $parentKey);
                break;
            case 'unmodified':
                break;
            case 'deleted':
                $acc[] = renderNodesDeleted($node, $parentKey);
                break;
            case 'added':
                $acc[] = renderNodesAdded($node, $parentKey);
                break;
            case 'nested':
                $acc[] = renderNodesNested($node, $parentKey);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesModified($node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    $oldValue = is_array($node['oldValue']) ? VALUE_IS_ARRAY : $node['oldValue'];
    $newValue = is_array($node['newValue']) ? VALUE_IS_ARRAY : $node['newValue'];
    return sprintf(MODIFIED, $keyWithParent, convertValue($oldValue), convertValue($newValue));
}

function renderNodesDeleted($node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    $value = is_array($node['oldValue']) ? VALUE_IS_ARRAY : $node['oldValue'];
    return sprintf(DELETED, $keyWithParent, convertValue($value));
}

function renderNodesAdded($node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    $value = is_array($node['newValue']) ? VALUE_IS_ARRAY : $node['newValue'];
    return sprintf(ADDED, $keyWithParent, convertValue($value));
}

function renderNodesNested($node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    return getView($node['ast'], $keyWithParent);
}
