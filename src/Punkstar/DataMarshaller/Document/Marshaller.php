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
        $marshalledFragments = [];

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

        foreach ($unmarshalledFragments as $unmarshalledFragment) {
            $fragments[] = $this->fragmentMarshaller->unmarshall($unmarshalledFragment);
        }

        return new Document($fragments);
    }
}