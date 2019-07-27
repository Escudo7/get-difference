<?php

namespace Project\getData;

use Symfony\Component\Yaml\Yaml;

function getData($pathToFile)
{
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    $content = file_get_contents($pathToFile);
    if ($extension === 'json') {
        $data = json_decode($content, true);
    } elseif ($extension === 'yml') {
        $data = Yaml::parse($content);
    }
    return $data;
}
