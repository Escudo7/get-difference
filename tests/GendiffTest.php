<?php

namespace Project\tests;

use \PHPUnit\Framework\TestCase;
use function Project\getdiff\getdiff;

class MyTest extends TestCase
{
    public function testDiff()
    {
        $file1 = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22"
        ];

        $file2 = [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io"
        ];
        $result = "{
    host: hexlet.io
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
  + verbose: true
}\n";
        $this->assertEquals($result, getDiff($file1, $file2));
    }
}
