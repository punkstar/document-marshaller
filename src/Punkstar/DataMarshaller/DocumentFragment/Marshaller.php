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
    public function marshall(DocumentFragment $fragment) : array
    {
        return [
            self::KEY_NAME => base64_encode($this->encode($fragment->getName())),
            self::KEY_DATA => base64_encode($this->encode($fragment->getData())),
        ];
    }

    /**
     * @param array $fragment
     * @return DocumentFragment
     */
    public function unmarshall(array $fragment) : DocumentFragment
    {
        $name = $this->decode(base64_decode($fragment[self::KEY_NAME]));
        $value = $this->decode(base64_decode($fragment[self::KEY_DATA]));

        return new DocumentFragment($name, $value);
    }

    /**
     * @param $input
     * @return string
     */
    protected function encode($input)
    {
        if (is_bool($input) === true) {
            $input = intval($input);
        }

        if (is_scalar($input) || $input === null) {
            return $input;
        } elseif (is_array($input)) {
            return json_encode($input);
        }
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function decode($input)
    {
        if ($input == "true" || $input == "false") {
            return $input;
        }

        $json = json_decode($input, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $json;
        } else {
            return $input;
        }
    }
}
