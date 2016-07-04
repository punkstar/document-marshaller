<?php

namespace Punkstar\DataMarshaller\DocumentFragment;

use Punkstar\DataMarshaller\DocumentFragment;

class Marshaller
{
    const KEY_NAME = 'n';
    const KEY_DATA = 'd';

    /**
     * @param DocumentFragment $fragment
     * @return array
     */
    public function marshall(DocumentFragment $fragment) : array {
        return [
            self::KEY_NAME => base64_encode($fragment->getName()),
            self::KEY_DATA => base64_encode($fragment->getData()),
        ];
    }

    /**
     * @param array $fragment
     * @return DocumentFragment
     */
    public function unmarshall(array $fragment) : DocumentFragment {
        $name = base64_decode($fragment[self::KEY_NAME]);
        $value = base64_decode($fragment[self::KEY_DATA]);

        return new DocumentFragment($name, $value);
    }
}