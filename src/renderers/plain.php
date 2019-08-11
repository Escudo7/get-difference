<?php

namespace Project\renderers\plain;

use function Project\renderers\utilities\convertValue;

const ADDED = "Property '%s' was added with value: '%s'";
const DELETED = "Property '%s' was removed";
const MODIFIED = "Property '%s' was changed. From '%s' to '%s'";
const VALUE_IS_ARRAY = "complex value";

function renderDiff($ast, $parentKey = '')
{
    $diff = array_reduce($ast, function ($acc, $node) use ($parentKey) {
        $keyWithParent = $parentKey == '' ? $node['key'] : "{$parentKey}.{$node['key']}";
        $oldValue = stringify($node['oldValue']);
        $newValue = stringify($node['newValue']);
        switch ($node['typeNode']) {
            case 'modified':
                $acc[] = sprintf(MODIFIED, $keyWithParent, $oldValue, $newValue);
                break;
            case 'unmodified':
                break;
            case 'deleted':
                $acc[] = sprintf(DELETED, $keyWithParent);
                break;
            case 'added':
                $acc[] = sprintf(ADDED, $keyWithParent, $newValue);
                break;
            case 'nested':
                $acc[] = renderDiff($node['nestedAst'], $keyWithParent);
        }
        return $acc;
    }, []);
    return implode("\n", $diff);
}

function stringify($data)
{
    return is_array($data) ? VALUE_IS_ARRAY : convertValue($data);
}
