<?php

namespace Project\Tests;

use \PHPUnit\Framework\TestCase;
use function Project\Diff\getDiff;

class DiffTest extends TestCase
{
    public function testPrettyDiffFlat()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/flatBefore.json";
        $pathToFile2 = __DIR__ . "/testsFiles/flatAfter.json";
        $pathToResult = __DIR__ . "/testsFiles/resultFlatPretty.json";
        $result = json_decode(file_get_contents($pathToResult));

        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPrettyDiffNested()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/nestedBefore.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/nestedAfter.yml";
        $pathToResult = __DIR__ . "/testsFiles/resultNestedPretty.json";
        $result = json_decode(file_get_contents($pathToResult));
        
        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'pretty'));
    }

    public function testPlainDiffFlat()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/flatBefore.json";
        $pathToFile2 = __DIR__ . "/testsFiles/flatAfter.json";
        $pathToResult = __DIR__ . "/testsFiles/resultFlatPlain.json";
        $result = json_decode(file_get_contents($pathToResult));

        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }

    public function testPlainDiffNestes()
    {
        $pathToFile1 = __DIR__ . "/testsFiles/nestedBefore.yml";
        $pathToFile2 = __DIR__ . "/testsFiles/nestedAfter.yml";
        $pathToResult = __DIR__ . "/testsFiles/resultNestedPlain.json";
        $result = json_decode(file_get_contents($pathToResult));

        $this->assertEquals($result, getDiff($pathToFile1, $pathToFile2, 'plain'));
    }
}
