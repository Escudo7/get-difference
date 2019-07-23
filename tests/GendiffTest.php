<?php

namespace Project\tests;

use \PHPUnit\Framework\TestCase;
use function Project\getDiff\getDiff;

class MyTest extends TestCase
{
    public function testDiffFlat()
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
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}\n";
        $this->assertEquals($result, getDiff($file1, $file2));
    }
}
