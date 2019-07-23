<?php

namespace Project\getDiff;

use Funct\Collection;
use function Project\buildAst\buildAst;
use function Project\renderer\render;

function getDiff($data1, $data2)
{
    $ast = buildAst($data1, $data2);
    return '{' . PHP_EOL . render($ast)  . PHP_EOL . '}' . PHP_EOL;
}
