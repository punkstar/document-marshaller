<?php

namespace Punkstar\DataMarshallerTest\Document;

use PHPUnit\Framework\TestCase;
use Punkstar\DataMarshaller\Checksum;
use Punkstar\DataMarshaller\Document;
use Punkstar\DataMarshaller\Document\Marshaller;
use Punkstar\DataMarshaller\DocumentFragment;

class MarshallerTest extends TestCase
{
    protected $fragmentOne;
    protected $fragmentTwo;
    protected $document;

    public function setUp()
    {
        $this->fragmentOne = new DocumentFragment("test1", "test");
        $this->fragmentTwo = new DocumentFragment("test2", "test");

        $this->document = new Document([$this->fragmentOne, $this->fragmentTwo]);
    }

    /**
     * @test
     */
    public function testMarshallUnmarshall()
    {
        $checksumCalculator = new Checksum();
        $fragmentMarshaller = new DocumentFragment\Marshaller();
        $documentMarshaller = new Marshaller($fragmentMarshaller, $checksumCalculator);

        $marshalledDocument = $documentMarshaller->marshall($this->document);

        $unmarshalledDocument = $documentMarshaller->unmarshall($marshalledDocument);
        $unmarshalledFragments = $unmarshalledDocument->getFragments();

        $this->assertCount(2, $unmarshalledFragments);

        $this->assertEquals($this->fragmentOne, $unmarshalledFragments[0]);
        $this->assertEquals($this->fragmentTwo, $unmarshalledFragments[1]);
    }
}