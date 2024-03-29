<?php

namespace Project\cli;

use function \Project\diff\getDiff;

function run()
{
    $doc = <<<DOC
Generate diff

Usage:
  gendiff (-h | --help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help         Show this screen.
  --format <fmt>    Report format [default: pretty]

DOC;

    $args = Docopt::handle($doc);
    $pathToFile1 = realpath($args['<firstFile>']);
    $pathToFile2 = realpath($args['<secondFile>']);
    $format = $args['--format'];
    $diff = getDiff($pathToFile1, $pathToFile2, $format);
    echo $diff;
}
