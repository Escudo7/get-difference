<?php

namespace Project\renderer\utilities;

function convertValue($data)
{
    if (isBoolean($data)) {
        return $data === true ? 'true' : 'false';
    }
    return $data;
}

function isBoolean($data)
{
    return gettype($data) === 'boolean';
}
