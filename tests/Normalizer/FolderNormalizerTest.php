<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Normalizer;

use PostmanGeneratorBundle\Model\Collection;
use PostmanGeneratorBundle\Model\Folder;
use PostmanGeneratorBundle\Model\Request;
use PostmanGeneratorBundle\Normalizer\FolderNormalizer;

class FolderNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsNormalization()
    {
        $normalizer = new FolderNormalizer();
        $this->assertTrue($normalizer->supportsNormalization(new Folder(), 'json'));
    }

    public function testDoesNotSupportsNormalization()
    {
        $normalizer = new FolderNormalizer();
        $this->assertFalse($normalizer->supportsNormalization('', 'json'));
        $this->assertFalse($normalizer->supportsNormalization(new Folder()));
        $this->assertFalse($normalizer->supportsNormalization(new Folder(), 'xml'));
        $this->assertFalse($normalizer->supportsNormalization(new \DateTime(), 'json'));
    }

    public function testNormalize()
    {
        $collection = new Collection();
        $collection->setId('foo');

        $request = new Request();
        $request->setId(42);

        $folder = new Folder();
        $folder->setId('bar');
        $folder->setCollection($collection);
        $folder->setName('Foo');
        $folder->setRequests([$request]);

        $normalizer = new FolderNormalizer();
        $this->assertEquals([
            'id' => 'bar',
            'collectionId' => 'foo',
            'name' => 'Foo',
            'owner' => 0,
            'order' => [42],
        ], $normalizer->normalize($folder, 'json'));
    }
}
