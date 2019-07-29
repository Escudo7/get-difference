<?php

namespace Project\getDiff;

use Funct\Collection;
use function Project\getData\getData;
use function Project\buildAst\buildAst;

function getDiff($pathToFile1, $pathToFile2, $format)
{
    $data1 = getData($pathToFile1);
    $data2 = getData($pathToFile2);
    $ast = buildAst($data1, $data2);
    $render = "Project\\renderer\\{$format}\\render";
    return $render($ast);
}
