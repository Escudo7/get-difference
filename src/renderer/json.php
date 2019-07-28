<?php

namespace Project\renderer\json;

function render($ast)
{
    $view = json_encode($ast);
    return $view . PHP_EOL;
}
