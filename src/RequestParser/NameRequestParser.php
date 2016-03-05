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

use Doctrine\Common\Inflector\Inflector;
use PostmanGeneratorBundle\Model\Request;

class NameRequestParser implements RequestParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(Request $request)
    {
        $shortName = Inflector::camelize($request->getResource()->getShortName());
        switch ($request->getMethod()) {
            case 'POST':
                $request->setName(sprintf('Create %s', $shortName));
                break;
            case 'PUT':
            case 'PATCH':
                $request->setName(sprintf('Update %s', $shortName));
                break;
            case 'DELETE':
                $request->setName(sprintf('Delete %s', $shortName));
                break;
            case 'GET':
                if (!preg_match(UriRequestParser::PATTERN, $request->getUrl())) {
                    $shortName = Inflector::pluralize($shortName).' list';
                }
                $request->setName(sprintf('Get %s', $shortName));
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return null === $request->getName();
    }
}
