<?php

namespace Punkstar\DataMarshallerTest\DocumentFragment;

use PHPUnit\Framework\TestCase;
use Punkstar\DataMarshaller\Checksum;
use Punkstar\DataMarshaller\DocumentFragment;

class ChecksumTest extends TestCase
{
    /**
     * @test
     */
    public function testValidData()
    {
        $calculator = new Checksum();

        $subject = 'HELLO WORLD';

        $checksum = $calculator->calculate($subject);

        $this->assertTrue($calculator->verify($checksum, $subject));
    }

    /**
     * @test
     */
    public function testInvalidData()
    {
        $calculator = new Checksum();

        $subject = 'HELLO WORLD';

        $checksum = $calculator->calculate($subject);

        $this->assertFalse($calculator->verify($checksum . "DUFF", $subject));
    }
}