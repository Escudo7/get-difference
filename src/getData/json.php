<?php

namespace Project\getData\json;

function getDataN($content)
{
    return json_decode($content, true);
}
