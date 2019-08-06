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
                $acc[] = stringify(MODIFIED, $node, $parentKey);
                break;
            case 'unmodified':
                break;
            case 'deleted':
                $acc[] = stringify(DELETED, $node, $parentKey);
                break;
            case 'added':
                $acc[] = stringify(ADDED, $node, $parentKey);
                break;
            case 'nested':
                $acc[] = renderNodesNested($node, $parentKey);
        }
        return $acc;
    }, []);
    return implode("\n", $view);
}

function renderNodesNested($node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    return getView($node['ast'], $keyWithParent);
}

function stringify($template, $node, $parentKey)
{
    $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
    switch ($template) {
        case MODIFIED:
            $oldValue = is_array($node['oldValue']) ? VALUE_IS_ARRAY : $node['oldValue'];
            $newValue = is_array($node['newValue']) ? VALUE_IS_ARRAY : $node['newValue'];
            return sprintf($template, $keyWithParent, convertValue($oldValue), convertValue($newValue));
        case ADDED:
            $value = is_array($node['newValue']) ? VALUE_IS_ARRAY : $node['newValue'];
            return sprintf($template, $keyWithParent, convertValue($value));
        case DELETED:
            return sprintf($template, $keyWithParent);
    }
}
