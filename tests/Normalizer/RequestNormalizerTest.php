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
use PostmanGeneratorBundle\Model\Test;
use PostmanGeneratorBundle\Normalizer\RequestNormalizer;

class RequestNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsNormalization()
    {
        $normalizer = new RequestNormalizer();
        $this->assertTrue($normalizer->supportsNormalization(new Request(), 'json'));
    }

    public function testDoesNotSupportsNormalization()
    {
        $normalizer = new RequestNormalizer();
        $this->assertFalse($normalizer->supportsNormalization('', 'json'));
        $this->assertFalse($normalizer->supportsNormalization(new Request()));
        $this->assertFalse($normalizer->supportsNormalization(new Request(), 'xml'));
        $this->assertFalse($normalizer->supportsNormalization(new \DateTime(), 'json'));
    }

    public function testNormalize()
    {
        $collection = new Collection();
        $collection->setId('foo');

        $folder = new Folder();
        $folder->setId('bar');

        $request = new Request();
        $request->setId(42);
        $request->setName('Get users list');
        $request->setCollection($collection);
        $request->setDataMode(Request::DATA_MODE_RAW);
        $request->setDescription('This request returns a users list');
        $request->setFolder($folder);
        $request->setFromCollection(true);
        $request->addHeader('Authorization', 'Bearer access-token-admin');
        $request->addTest(new Test('Successful GET request', 'responseCode.code === 200'));
        $request->setResource($this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface')->reveal());
        $request->setMethod('GET');
        $request->setUrl('http://127.0.0.1/users');
        $request->setTime(12345);

        $normalizer = new RequestNormalizer();
        $this->assertEquals([
            'id' => 42,
            'url' => 'http://127.0.0.1/users',
            'method' => 'GET',
            'tests' => 'tests["Successful GET request"] = responseCode.code === 200;',
            'folder' => 'bar',
            'name' => 'Get users list',
            'description' => 'This request returns a users list',
            'preRequestScript' => '',
            'pathVariables' => new \stdClass(),
            'data' => [],
            'dataMode' => 'raw',
            'rawModeData' => '{}',
            'version' => 2,
            'currentHelper' => 'normal',
            'helperAttributes' => new \stdClass(),
            'time' => 12345,
            'fromCollection' => true,
            'collectionRequest' => null,
            'collectionId' => 'foo',
            'headers' => "Authorization: Bearer access-token-admin\n",
        ], $normalizer->normalize($request, 'json'));

        $request->setRawModeData([
            'foo' => 'bar',
        ]);
        $this->assertEquals([
            'id' => 42,
            'url' => 'http://127.0.0.1/users',
            'method' => 'GET',
            'tests' => 'tests["Successful GET request"] = responseCode.code === 200;',
            'folder' => 'bar',
            'name' => 'Get users list',
            'description' => 'This request returns a users list',
            'preRequestScript' => '',
            'pathVariables' => new \stdClass(),
            'data' => [],
            'dataMode' => 'raw',
            'rawModeData' => "{\n    \"foo\":\"bar\"\n}",
            'version' => 2,
            'currentHelper' => 'normal',
            'helperAttributes' => new \stdClass(),
            'time' => 12345,
            'fromCollection' => true,
            'collectionRequest' => null,
            'collectionId' => 'foo',
            'headers' => "Authorization: Bearer access-token-admin\n",
        ], $normalizer->normalize($request, 'json'));
    }
}
