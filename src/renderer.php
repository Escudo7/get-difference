<?php

namespace Project\renderer;

const ADDED = '  + ';
const DELETED = '  - ';
const INDENT = '    ';

function render($diff, $indent = '')
{
    $view = [];
    foreach ($diff as $identifier => $item) {
        if ($identifier === 'added') {
            foreach ($item as $key => $value) {
                if (!is_array($value)) {
                    $formatedValue = isBoolean($value) ? convertBoolToStr($value) : $value;
                    $view[] = $indent . ADDED . "$key: $formatedValue";
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
                    $formatedValue = isBoolean($value) ? convertBoolToStr($value) : $value;
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
                    $formatedValue = isBoolean($value) ? convertBoolToStr($value) : $value;
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
                $formatedNewValue = isBoolean($value['newValue']) ? convertBoolToStr($value) : $value['newValue'];
                $formatedOldValue = isBoolean($value['oldValue']) ? convertBoolToStr($value) : $value['oldValue'];
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

function isBoolean($data)
{
    return gettype($data) === 'boolean';
}

function convertBoolToStr($data)
{
    return $data === true ? 'true' : 'false';
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
