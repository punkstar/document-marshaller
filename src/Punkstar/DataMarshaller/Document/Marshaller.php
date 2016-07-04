<?php

namespace Punkstar\DataMarshaller\Document;

use Punkstar\DataMarshaller\Document;
use Punkstar\DataMarshaller\DocumentFragment\Marshaller as DocumentFragmentMarshaller;

class Marshaller
{
    const KEY_VERSION = 'v';
    const KEY_FRAGMENTS = 'f';

    const VERSION_1 = 1;

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

        $document = [
            self::KEY_VERSION => self::VERSION_1,
            self::KEY_FRAGMENTS => []
        ];

        foreach ($fragments as $fragment) {
            $document[self::KEY_FRAGMENTS][] = $this->fragmentMarshaller->marshall($fragment);
        }

        return json_encode($document);
    }

    /**
     * @param string $data
     * @return Document
     * @throws \Exception
     */
    public function unmarshall(string $data) : Document {
        $document = json_decode($data, true);

        $documentVersion = $document[self::KEY_VERSION];
        $unmarshalledFragments = $document[self::KEY_FRAGMENTS];
        $fragments = [];

        if (count($unmarshalledFragments) == 0) {
            throw new \Exception("Expected at least one line in the document");
        }

        if ($documentVersion != self::VERSION_1) {
            throw new \Exception(
                sprintf(
                    "Expected a version identifier of '%s', got '%s'",
                    self::VERSION_1,
                    $documentVersion
                )
            );
        }

        foreach ($unmarshalledFragments as $unmarshalledFragment) {
            $fragments[] = $this->fragmentMarshaller->unmarshall($unmarshalledFragment);
        }

        return new Document($fragments);
    }
}