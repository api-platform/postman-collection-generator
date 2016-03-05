<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CommandParser;

use PostmanGeneratorBundle\CommandParser\CommandParserChain;
use Prophecy\Prophecy\ObjectProphecy;

class CommandParserChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommandParserChain
     */
    private $commandParserChain;

    /**
     * @var ObjectProphecy
     */
    private $parserMock;

    /**
     * @var ObjectProphecy
     */
    private $inputMock;

    /**
     * @var ObjectProphecy
     */
    private $outputMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->parserMock = $this->prophesize('PostmanGeneratorBundle\CommandParser\CommandParserInterface');
        $this->inputMock = $this->prophesize('Symfony\Component\Console\Input\InputInterface');
        $this->outputMock = $this->prophesize('Symfony\Component\Console\Output\OutputInterface');

        $this->commandParserChain = new CommandParserChain([$this->parserMock->reveal()]);
    }

    public function testParse()
    {
        $this->parserMock->supports()->willReturn(true)->shouldBeCalledTimes(1);
        $this->parserMock->parse($this->inputMock->reveal(), $this->outputMock->reveal())->shouldBeCalledTimes(1);

        $this->commandParserChain->parse($this->inputMock->reveal(), $this->outputMock->reveal());
    }

    public function testParseNoSupports()
    {
        $this->parserMock->supports()->willReturn(false)->shouldBeCalledTimes(1);
        $this->parserMock->parse($this->inputMock->reveal(), $this->outputMock->reveal())->shouldNotBeCalled();

        $this->commandParserChain->parse($this->inputMock->reveal(), $this->outputMock->reveal());
    }

    public function testExecute()
    {
        $this->parserMock->supports()->willReturn(true)->shouldBeCalledTimes(1);
        $this->parserMock->execute($this->inputMock->reveal(), $this->outputMock->reveal())->shouldBeCalledTimes(1);

        $this->commandParserChain->execute($this->inputMock->reveal(), $this->outputMock->reveal());
    }

    public function testExecuteNoSupports()
    {
        $this->parserMock->supports()->willReturn(false)->shouldBeCalledTimes(1);
        $this->parserMock->execute($this->inputMock->reveal(), $this->outputMock->reveal())->shouldNotBeCalled();

        $this->commandParserChain->execute($this->inputMock->reveal(), $this->outputMock->reveal());
    }

    public function testSupports()
    {
        $this->assertTrue($this->commandParserChain->supports());
    }
}
