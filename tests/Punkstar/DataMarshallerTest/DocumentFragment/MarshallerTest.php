<?php

namespace Punkstar\DataMarshallerTest\DocumentFragment;

use PHPUnit\Framework\TestCase;
use Punkstar\DataMarshaller\DocumentFragment;

class MarshallerTest extends TestCase
{
    /**
     * @test
     * @dataProvider scalarProvider
     */
    public function testScalars($testValue)
    {
        $marshaller = new DocumentFragment\Marshaller();

        $fragment = new DocumentFragment("test", $testValue);

        $marshalledFragment = $marshaller->marshall($fragment);
        $unmarshalledFragment = $marshaller->unmarshall($marshalledFragment);

        $this->assertEquals("test", $unmarshalledFragment->getName());
        $this->assertEquals($testValue, $unmarshalledFragment->getData());
    }

    /**
     * @test
     * @dataProvider arrayProvider
     */
    public function testArrays($testValue)
    {
        $marshaller = new DocumentFragment\Marshaller();

        $fragment = new DocumentFragment("test", $testValue);

        $marshalledFragment = $marshaller->marshall($fragment);
        $unmarshalledFragment = $marshaller->unmarshall($marshalledFragment);

        $this->assertEquals("test", $unmarshalledFragment->getName());
        $this->assertInternalType("array", $unmarshalledFragment->getData());
        $this->assertEquals($testValue, $unmarshalledFragment->getData());
    }

    /**
     * @test
     * @dataProvider boolProvider
     */
    public function testBooleans($testValue)
    {
        $marshaller = new DocumentFragment\Marshaller();

        $fragment = new DocumentFragment("test", $testValue);

        $marshalledFragment = $marshaller->marshall($fragment);
        $unmarshalledFragment = $marshaller->unmarshall($marshalledFragment);

        $this->assertEquals("test", $unmarshalledFragment->getName());
        $this->assertEquals($testValue ? 1 : 0, $unmarshalledFragment->getData());
    }

    /**
     * @return array
     */
    public function scalarProvider()
    {
        $values = [
            "string",
            123456,
            123.456,
            null,
            "true",
            "false",
            "123456",
            "123.456"
        ];

        $formatted = [];

        foreach ($values as $value) {
            $formatted[] = [$value];
        }

        return $formatted;
    }

    /**
     * @return array
     */
    public function boolProvider()
    {
        $values = [
            true,
            false
        ];

        $formatted = [];

        foreach ($values as $value) {
            $formatted[] = [$value];
        }

        return $formatted;
    }

    /**
     * @return array
     */
    public function arrayProvider()
    {
        $values = [
            [1, 2, 3],
            ["a", "b", "c"],
            ["a", [1, 2, 3], "c"],
            ["d" => "a", [1, 2, 3], "c"],
            []
        ];

        $formatted = [];

        foreach ($values as $value) {
            $formatted[] = [$value];
        }

        return $formatted;
    }
}