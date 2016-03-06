<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use PostmanGeneratorBundle\CommandParser\CommandParserChain;
use PostmanGeneratorBundle\Generator\CollectionGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostmanCollectionBuildCommand extends Command
{
    /**
     * @var CollectionGenerator
     */
    private $collectionGenerator;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var CommandParserChain
     */
    private $commandParserChain;

    /**
     * @var string
     */
    private $collectionName;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param CollectionGenerator $collectionGenerator
     * @param NormalizerInterface $normalizer
     * @param CommandParserChain  $commandParserChain
     * @param string              $collectionName
     * @param string              $rootDir
     */
    public function __construct(
        CollectionGenerator $collectionGenerator,
        NormalizerInterface $normalizer,
        CommandParserChain $commandParserChain,
        $collectionName,
        $rootDir
    ) {
        parent::__construct('postman:collection:build');

        $this->collectionGenerator = $collectionGenerator;
        $this->normalizer = $normalizer;
        $this->commandParserChain = $commandParserChain;
        $this->collectionName = $collectionName;
        $this->rootDir = $rootDir;

        $this
            ->setDescription('Build Postman collection')
            ->setHelp(<<<EOT
The <info>postman:collection:build</info> command helps you generate a Postman
collection according to your project configuration:

<info>php app/console postman:collection:build</info>

If any of the options is missing, the command will ask for their values interactively.
If you want to disable any user interaction, use <comment>--no-interaction</comment>,
but don't forget to pass all required arguments.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->commandParserChain->parse($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandParserChain->execute($input, $output);

        $filename = sprintf('%s.json', Inflector::camelize(strtolower($this->collectionName)));
        $filepath = $this->rootDir.'/../'.$filename;

        file_put_contents($filepath, json_encode($this->normalizer->normalize($this->collectionGenerator->generate(), 'json')));

        $text = sprintf('Postman collection has been successfully built in file %s.', $filename);
        $output->writeln([
            '',
            $this->getHelperSet()->get('formatter')->formatBlock($text, 'bg=blue;fg=white', true),
            '',
        ]);
    }
}
