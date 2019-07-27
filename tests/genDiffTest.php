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
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
  + verbose: true
}\n";
        $this->assertEquals($result, getDiff($file1, $file2));
    }

    public function testDiffNested()
    {
        $file1 = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "unmodifiedArray" => [
                "key1" => "value1",
                "key2" => "value2"
            ],
            "modifiedArray" => [
                "big" => 'bag',
                'cat' => 'dog'
            ]
        ];

        $file2 = [
            "timeout" => 20,
            "verbose" => true,
            "host" => "hexlet.io",
            "unmodifiedArray" => [
                "key1" => "value1",
                "key2" => "value2"
            ],
            "modifiedArray" => [
                "big" => "head",
                "newCat" => [
                    "one" => "pers",
                    "two" => "sfinks"
                    ],
                "cat" => "dog"
            ],
            "newArray" => [
                "newKey1" => "value1",
                "newKey2" => "value2"
                ]
        ];

        $result = "{
    host: hexlet.io
    unmodifiedArray: {
        key1: value1
        key2: value2
    }
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
  + verbose: true
  + newArray: {
        newKey1: value1
        newKey2: value2
    }
    modifiedArray: {
      - big: bag
      + big: head
      + newCat: {
            one: pers
            two: sfinks
        }
        cat: dog
    }
}\n";
        $this->assertEquals($result, getDiff($file1, $file2));
    }
}
