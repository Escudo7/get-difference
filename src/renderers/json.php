<?php
namespace Project\renderers\json;

function renderDiff($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
