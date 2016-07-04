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

    /**
     * @test
     * @dataProvider poorDocumentFormatsProvider
     */
    public function testPoorDocumentFormats($marshalledDocument)
    {
        $checksumCalculator = new Checksum();
        $fragmentMarshaller = new DocumentFragment\Marshaller();
        $documentMarshaller = new Marshaller($fragmentMarshaller, $checksumCalculator);

        $unmarshalledDocument = $documentMarshaller->unmarshall($marshalledDocument);
        $unmarshalledFragments = $unmarshalledDocument->getFragments();

        $this->assertCount(2, $unmarshalledFragments);
    }

    /**
     * @return array
     */
    public function poorDocumentFormatsProvider()
    {
        $data = [
            // Full document
            '{"v":1,"f":[{"n":"Qm9keQ==","d":"V29ybGQ="},{"n":"SGVhZGVy","d":"SGVsbG8="}],"c":"d827049039f82a8b65a9b0f52e637cf3bc5d1e0dec7d6198edf901a5e3dcae7d"}',

            // Swapped fragments
            '{"v":1,"f":[{"n":"SGVhZGVy","d":"SGVsbG8="},{"n":"Qm9keQ==","d":"V29ybGQ="}],"c":"d827049039f82a8b65a9b0f52e637cf3bc5d1e0dec7d6198edf901a5e3dcae7d"}',

            // No checksum
            '{"v":1,"f":[{"n":"Qm9keQ==","d":"V29ybGQ="},{"n":"SGVhZGVy","d":"SGVsbG8="}]}',

            // No version
            '{"f":[{"n":"Qm9keQ==","d":"V29ybGQ="},{"n":"SGVhZGVy","d":"SGVsbG8="}],"c":"d827049039f82a8b65a9b0f52e637cf3bc5d1e0dec7d6198edf901a5e3dcae7d"}',

            // No checksum, no version
            '{"f":[{"n":"Qm9keQ==","d":"V29ybGQ="},{"n":"SGVhZGVy","d":"SGVsbG8="}]}'
        ];

        $formatted = [];

        foreach ($data as $item) {
            $formatted[] = [$item];
        }

        return $formatted;
    }
}