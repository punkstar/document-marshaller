<?php

namespace Punkstar\DataMarshaller;

class Document
{
    /**
     * @var DocumentFragment[]
     */
    protected $fragments;

    /**
     * @param array $fragments
     */
    public function __construct(array $fragments)
    {
        $this->fragments = $fragments;
    }

    /**
     * @return DocumentFragment[]
     */
    public function getFragments() : array {
        return $this->fragments;
    }
}