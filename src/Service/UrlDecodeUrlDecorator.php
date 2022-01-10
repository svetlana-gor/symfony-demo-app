<?php

namespace App\Service;

class UrlDecodeUrlDecorator extends BaseUrlDecorator
{
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        return urldecode(parent::generate($name, $parameters, $referenceType));
    }
}
