<?php

namespace Project\diff;

use Funct\Collection;
use function Project\parser\getData;
use function Project\builderAst\buildAst;

function getDiff($pathToFile1, $pathToFile2, $format)
{
    $data1 = getData($pathToFile1);
    $data2 = getData($pathToFile2);
    $ast = buildAst($data1, $data2);
    $renderer = "Project\\renderers\\{$format}\\renderDiff";
    return $renderer($ast) . PHP_EOL;
}
