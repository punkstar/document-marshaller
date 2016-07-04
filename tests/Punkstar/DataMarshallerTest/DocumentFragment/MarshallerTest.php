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
     * @return array
     */
    public function scalarProvider()
    {
        $values = [
            "string",
            123456,
            123.456,
            true,
            false,
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
}