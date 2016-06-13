<?php

namespace Punkstar\DataMarshallerTest\Document;

use PHPUnit\Framework\TestCase;
use Punkstar\DataMarshaller\Document;
use Punkstar\DataMarshaller\Document\Marshaller;
use Punkstar\DataMarshaller\DocumentFragment;

class MarshallerTest extends TestCase
{
    /**
     * @test
     */
    public function testMarshallUnmarshall()
    {
        $fragmentMarshaller = new DocumentFragment\Marshaller();
        $documentMarshaller = new Marshaller($fragmentMarshaller);

        $fragmentOne = new DocumentFragment("test", "test");
        $fragmentTwo = new DocumentFragment("test", "test");

        $document = new Document([$fragmentOne, $fragmentTwo]);

        $marshalledDocument = $documentMarshaller->marshall($document);
        $unmarshalledDocument = $documentMarshaller->unmarshall($marshalledDocument);
        $unmarshalledFragments = $unmarshalledDocument->getFragments();

        $this->assertCount(2, $unmarshalledFragments);
        $this->assertEquals($fragmentOne, $unmarshalledFragments[0]);
        $this->assertEquals($fragmentTwo, $unmarshalledFragments[1]);
    }
}