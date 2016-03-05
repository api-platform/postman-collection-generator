<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\RequestParser;

use PostmanGeneratorBundle\Model\Request;

class UriRequestParser implements RequestParserInterface
{
    const PATTERN = '/\/({id})$/';

    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        $request->setUrl(preg_replace(self::PATTERN, '/1', $request->getUrl()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return 1 === preg_match(self::PATTERN, $request->getUrl());
    }
}
