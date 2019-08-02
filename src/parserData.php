<?php

namespace Project\ParserData;

use Symfony\Component\Yaml\Yaml;

function getData($pathToFile)
{
    $mapping = [
        'json' => function ($content) {
            return json_decode($content, true);
        },
        'yml' => function ($content) {
            return Yaml::parse($content);
        }
    ];
    
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    $content = file_get_contents($pathToFile);
    return $mapping[$extension]($content);
}
