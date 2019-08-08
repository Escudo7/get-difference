<?php

namespace Project\Parser;

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
    
    $content = getContent($pathToFile);
    $extension = getExtension($pathToFile);
    $data = $mapping[$extension]($content);
    return $data;
}

function getContent($pathToFile)
{
    return file_get_contents($pathToFile);
}

function getExtension($pathToFile)
{
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    return $extension;
}
