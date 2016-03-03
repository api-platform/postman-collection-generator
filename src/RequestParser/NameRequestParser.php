<?php

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
                $request->setName(sprintf('Get %s', $shortName));
                if (!preg_match('/\/({id})/', $request->getUrl())) {
                    $request->setName($request->getName().' list');
                }
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
