<?php

namespace Punkstar\DataMarshaller\Document;

use Punkstar\DataMarshaller\Checksum;
use Punkstar\DataMarshaller\Document;
use Punkstar\DataMarshaller\DocumentFragment;
use Punkstar\DataMarshaller\DocumentFragment\Marshaller as DocumentFragmentMarshaller;
use Punkstar\DataMarshaller\Exception\ChecksumVerificationException;
use Punkstar\DataMarshaller\Exception\UnsupportedVersionException;

class Marshaller
{
    const KEY_VERSION = 'v';
    const KEY_FRAGMENTS = 'f';
    const KEY_CHECKSUM = 'c';

    const VERSION_1 = 1;

    /**
     * @var DocumentFragmentMarshaller
     */
    protected $fragmentMarshaller;

    /**
     * @var Checksum
     */
    protected $checksumCalculator;

    public function __construct(DocumentFragmentMarshaller $fragmentMarshaller, Checksum $checksumCalculator)
    {
        $this->fragmentMarshaller = $fragmentMarshaller;
        $this->checksumCalculator = $checksumCalculator;
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

        $document[self::KEY_CHECKSUM] = $this->calculateFragmentsChecksum($fragments);

        return json_encode($document);
    }

    /**
     * @param string $data
     * @return Document
     * @throws \Exception
     */
    public function unmarshall(string $data) : Document {
        $document = json_decode($data, true);

        $documentVersion = isset($document[self::KEY_VERSION]) ? intval($document[self::KEY_VERSION]) : self::VERSION_1;
        $unmarshalledFragments = isset($document[self::KEY_FRAGMENTS]) ? $document[self::KEY_FRAGMENTS] : [];
        $fragments = [];

        if ($documentVersion != self::VERSION_1) {
            throw new UnsupportedVersionException(
                sprintf(
                    "Expected a version identifier of '%s', got '%s'",
                    self::VERSION_1,
                    $documentVersion
                )
            );
        }

        if (count($unmarshalledFragments) > 0) {
            foreach ($unmarshalledFragments as $unmarshalledFragment) {
                $fragments[] = $this->fragmentMarshaller->unmarshall($unmarshalledFragment);
            }
        }

        if (isset($document[self::KEY_CHECKSUM])) {
            $expectedChecksum = $this->calculateFragmentsChecksum($fragments);
            $actualChecksum = $document[self::KEY_CHECKSUM];

            if ($expectedChecksum != $actualChecksum) {
                throw new ChecksumVerificationException(
                    sprintf(
                        "The calculated checksum (%s) does not match the expected checksum (%s)",
                        $expectedChecksum,
                        $actualChecksum
                    )
                );
            }
        }

        return new Document($fragments);
    }

    /**
     * @param DocumentFragment[] $fragments
     * @return string
     */
    protected function calculateFragmentsChecksum(array $fragments) : string {
        $this->sortDocumentFragments($fragments);

        return $this->checksumCalculator->calculate(
            json_encode($fragments)
        );
    }

    /**
     * @param DocumentFragment[] $fragments
     */
    protected function sortDocumentFragments(array &$fragments) {
        usort($fragments, function ($a, $b) {
            return $a->getName() <=> $b->getName();
        });
    }
}