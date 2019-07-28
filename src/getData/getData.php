<?php

namespace Project\getData;

use Symfony\Component\Yaml\Yaml;

function getData($pathToFile)
{
    $pathInfo = pathinfo($pathToFile);
    $extension = $pathInfo['extension'];
    $content = file_get_contents($pathToFile);
    $getData = "Project\\getData\\{$extension}\\getDataN";
    return $getData($content);
}
