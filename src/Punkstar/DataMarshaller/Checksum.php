<?php

namespace Punkstar\DataMarshaller;

class Checksum
{
    /**
     * @param string $subject
     * @return string
     */
    public function calculate(string $subject) : string {
        return hash('sha256', $subject);
    }

    /**
     * @param string $expectedChecksum
     * @param string $subject
     * @return bool
     */
    public function verify(string $expectedChecksum, string $subject) : bool {
        $actualChecksum = $this->calculate($subject);

        return $expectedChecksum == $actualChecksum;
    }
}