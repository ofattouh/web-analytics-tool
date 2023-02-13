<?php

namespace GFPDF_Vendor\DeepCopy\Matcher;

interface Matcher
{
    /**
     * @param object $object
     * @param string $property
     *
     * @return boolean
     */
    public function matches($object, $property);
}
