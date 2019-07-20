<?php

namespace Project\getData;

use Symfony\Component\Yaml\Yaml;

function getData($file, $format)
{
    $content = file_get_contents($file);
    if ($format === 'pretty') {
        $data = json_decode($content, true);
    } elseif ($format === 'yaml') {
        $data = Yaml::parse($content);
    }
    return $data;
}
