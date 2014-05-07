<?php

namespace Brammm\UserBundle\Tests\Services;

use Brammm\UserBundle\Util\Canonicalizer;

class CanonicalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanonicalize()
    {
        $canonicalizer = new Canonicalizer();

        $canonicalized = $canonicalizer->canonicalize('JohnDoe@example.COM');
        $this->assertEquals('johndoe@example.com', $canonicalized);
    }
} 