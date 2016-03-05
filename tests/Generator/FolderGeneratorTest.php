<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Generator;

use PostmanGeneratorBundle\Generator\FolderGenerator;

class FolderGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $resourceMock = $this->prophesize('Dunglas\ApiBundle\Api\ResourceInterface');
        $resourceMock->getShortName()->willReturn('Foo')->shouldBeCalledTimes(1);

        $generator = new FolderGenerator();

        $folder = $generator->generate($resourceMock->reveal());

        $this->assertRegExp('/([A-z\d]{8})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{4})-([A-z\d]{12})/', $folder->getId());
        $this->assertEquals('Foo', $folder->getName());
    }
}
