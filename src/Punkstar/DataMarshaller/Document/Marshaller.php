<?php

namespace Punkstar\DataMarshaller\Document;

use Punkstar\DataMarshaller\Document;
use Punkstar\DataMarshaller\DocumentFragment\Marshaller as DocumentFragmentMarshaller;

class Marshaller
{
    /**
     * @var DocumentFragmentMarshaller
     */
    protected $fragmentMarshaller;

    public function __construct(DocumentFragmentMarshaller $fragmentMarshaller)
    {
        $this->fragmentMarshaller = $fragmentMarshaller;
    }

    /**
     * @param Document $document
     * @return string
     */
    public function marshall(Document $document) : string {
        $fragments = $document->getFragments();
        $marshalledFragments = [
            "VERSION:1"
        ];

        foreach ($fragments as $fragment) {
            $marshalledFragments[] = $this->fragmentMarshaller->marshall($fragment);
        }

        return join("\n", $marshalledFragments);
    }

    /**
     * @param string $data
     * @return Document
     */
    public function unmarshall(string $data) : Document {
        $unmarshalledFragments = explode("\n", $data);
        $fragments = [];

        if (count($unmarshalledFragments) == 0) {
            throw new \Exception("Expected at least one line in the document");
        }

        $expectedVersion = $this->getVersionString("1");
        $versionHeader = array_shift($unmarshalledFragments);

        if ($versionHeader != $expectedVersion) {
            throw new \Exception(
                sprintf(
                    "Expected a version identifier of 'VERSION:1', got '%s'",
                    $expectedVersion
                )
            );
        }

        foreach ($unmarshalledFragments as $unmarshalledFragment) {
            $fragments[] = $this->fragmentMarshaller->unmarshall($unmarshalledFragment);
        }

        return new Document($fragments);
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getVersionString(string $version = "1") : string
    {
        return sprintf("VERSION:%s", $version);
    }
}