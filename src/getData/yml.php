<?php

namespace Project\getData\yml;

use Symfony\Component\Yaml\Yaml;

function getDataN($content)
{
    return Yaml::parse($content);
}
