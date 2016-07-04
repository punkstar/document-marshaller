<?php

namespace Punkstar\DataMarshaller;

class DocumentFragment
{
    /** @var string */
    protected $name;

    /** @var mixed */
    protected $data;

    /**
     * DocumentFragment constructor.
     * @param string $name
     * @param mixed $data
     */
    public function __construct(string $name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
