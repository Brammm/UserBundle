<?php

namespace Brammm\UserBundle\Services;

class Canonicalizer implements CanonicalizerInterface
{
    public function canonicalize($string)
    {
        return null !== $string
            ? mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string))
            : null;
    }
} 