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
}