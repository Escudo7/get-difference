<?php

namespace Project\parser;

use Symfony\Component\Yaml\Yaml;

function getData($pathToFile)
{
    $content = getContent($pathToFile);
    $extension = getExtension($pathToFile);
    $data = getParse($extension, $content);
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

function getParse($extension, $content)
{
    $mapping = [
        'json' => function ($content) {
            return json_decode($content, true);
        },
        'yml' => function ($content) {
            return Yaml::parse($content);
        }
    ];
    return $mapping[$extension]($content);
}
