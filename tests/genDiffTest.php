<?php

namespace Project\tests;

use \PHPUnit\Framework\TestCase;
use function Project\getDiff\getDiff;

class MyTest extends TestCase
{
    public function testPrettyDiffFlat()
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
        $this->assertEquals($result, getDiff($file1, $file2, 'pretty'));
    }

    public function testPrettyDiffNested()
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
    modifiedArray: {
      - big: bag
      + big: head
        cat: dog
      + newCat: {
            one: pers
            two: sfinks
        }
    }
  + verbose: true
  + newArray: {
        newKey1: value1
        newKey2: value2
    }
}\n";
        $this->assertEquals($result, getDiff($file1, $file2, 'pretty'));
    }

    public function testPlainDiffFlat()
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
        $result = "Property 'timeout' was changed. From '50' to '20'
Property 'proxy' was removed
Property 'verbose' was added with value: 'true'\n";
        $this->assertEquals($result, getDiff($file1, $file2, 'plain'));
    }

    public function testPlainDiffNestes()
    {
        $file1 = [
            "common" => [
                "setting1" => "Value 1",
                "setting2" => "200",
                "setting3" => true,
                "setting6" => [
                    "key" => "value"
                ]
            ],
            "group1" => [
                "baz" => "bas",
                "foo" => "bar"
            ],
            "group2" => [
                "abc" => "12345"
            ]
        ];
        $file2 = [
            "common" => [
                "setting1" => "Value 1",
                "setting3" => true,
                "setting4" => "blah blah",
                "setting5" => [
                    "key5" => "value5"
                ]
            ],
            "group1" => [
                "foo" => "bar",
                "baz" => "bars"
            ],
            "group3" => [
                "fee" => "100500"
            ]
        ];
        $result = "Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed. From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'\n";
        $this->assertEquals($result, getDiff($file1, $file2, 'plain'));
    }
}
