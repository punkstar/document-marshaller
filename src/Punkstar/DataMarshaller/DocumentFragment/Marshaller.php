<?php

namespace Punkstar\DataMarshaller\DocumentFragment;

use Punkstar\DataMarshaller\DocumentFragment;

class Marshaller
{
    /**
     * @param DocumentFragment $fragment
     * @return string
     */
    public function marshall(DocumentFragment $fragment) : string {
        return sprintf(
            'NAME:%s;DATA:%s;',
            base64_encode($fragment->getName()),
            base64_encode(serialize($fragment->getData()))
        );
    }

    /**
     * @param string $data
     * @return DocumentFragment
     */
    public function unmarshall(string $data) : DocumentFragment {
        $matches = [];

        preg_match('/NAME:(.*);DATA:(.*);/', $data, $matches);

        list(, $name, $data) = $matches;

        $name = base64_decode($name);
        $value = unserialize(base64_decode($data));

        return new DocumentFragment($name, $value);
    }
}