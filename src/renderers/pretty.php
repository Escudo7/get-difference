<?php

namespace Project\renderers\pretty;

use Funct\Collection;
use function Project\renderers\utilities\convertValue;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT_STANDART = '    ';
const UNMODIFIED = '    ';

function renderDiff($ast)
{
    $initialString = '{' . PHP_EOL;
    $bodyDiff =  getBodyDiff($ast);
    $endString = PHP_EOL . '}';
    return "{$initialString}{$bodyDiff}{$endString}";
}

function getBodyDiff($ast, $depth = 0)
{
    $bodyDiff = array_reduce($ast, function ($acc, $data) use ($depth) {
        switch ($data['typeNode']) {
            case 'modified':
                $acc[] = renderNodesDeleted($data, $depth);
                $acc[] = renderNodesAdded($data, $depth);
                break;
            case 'unmodified':
                $acc[] = renderNodesUnmodified($data, $depth);
                break;
            case 'deleted':
                $acc[] = renderNodesDeleted($data, $depth);
                break;
            case 'added':
                $acc[] = renderNodesAdded($data, $depth);
                break;
            case 'nested':
                $acc[] = renderNodesNested($data, $depth);
        }
        return $acc;
    }, []);
    return implode("\n", $bodyDiff);
}

function renderNodesDeleted($data, $depth)
{
    $prefix = getIndent($depth) . DELETED;
    $value = getValue($data['oldValue'], $depth);
    $view = "{$prefix}{$data['key']}: $value";
    return $view;
}

function renderNodesAdded($data, $depth)
{
    $prefix = getIndent($depth) . ADDED;
    $value = getValue($data['newValue'], $depth);
    $view = "{$prefix}{$data['key']}: $value";
    return $view;
}

function renderNodesUnmodified($data, $depth)
{
    $prefix = getIndent($depth) . UNMODIFIED;
    $value = getValue($data['newValue'], $depth);
    $view = "{$prefix}{$data['key']}: $value";
    return $view;
}

function renderNodesNested($data, $depth)
{
    $prefix = getIndent($depth) . UNMODIFIED;
    $initialString = "{$prefix}{$data['key']}: {\n";
    $body = getBodyDiff($data['nestedAst'], $depth + 1);
    $endString = "\n" . getIndent($depth + 1) . "}";
    return "{$initialString}{$body}{$endString}";
}

function renderArray($array, $depth)
{
    $keys = array_keys($array);
    $viewArray = array_map(function ($key) use ($array, $depth) {
        $prefix = getIndent($depth) . UNMODIFIED;
        $value = getValue($array[$key], $depth);
        return "{$prefix}{$key}: $value";
    }, $keys);
    $initialString = "{\n";
    $endString = "\n" . getIndent($depth) . "}";
    $body = implode("\n", $viewArray);
    return "{$initialString}{$body}{$endString}";
}

function getIndent($depth)
{
    $lengthIndent = strlen(INDENT_STANDART) * $depth;
    $indent = str_pad('', $lengthIndent, INDENT_STANDART);
    return $indent;
}

function getValue($data, $depth)
{
    switch (gettype($data)) {
        case 'boolean':
            return convertValue($data);
        case 'array':
            return renderArray($data, $depth + 1);
        default:
            return $data;
    }
}
