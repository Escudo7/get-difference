<?php

namespace Project\getDiff;

use Funct\Collection;
use function Project\buildAst\buildAst;

function getDiff($data1, $data2, $format = "pretty")
{
    $ast = buildAst($data1, $data2);
    $render = "Project\\renderer\\{$format}\\render";
    return $render($ast);
}
