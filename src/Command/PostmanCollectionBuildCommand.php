<?php

namespace PostmanGeneratorBundle\Command;

use Doctrine\Common\Inflector\Inflector;
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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param CollectionGenerator $collectionGenerator
     * @param NormalizerInterface $normalizer
     * @param string              $name
     * @param string              $rootDir
     */
    public function __construct(
        CollectionGenerator $collectionGenerator,
        NormalizerInterface $normalizer,
        $name,
        $rootDir
    ) {
        parent::__construct('postman:collection:build');

        $this->collectionGenerator = $collectionGenerator;
        $this->normalizer = $normalizer;
        $this->name = $name;
        $this->rootDir = $rootDir;

        $this
            ->setDescription('Build Postman collection')
            ->setHelp(<<<EOT
The <info>postman:collection:build</info> command helps you generate a Postman
collection according to your project configuration. Provide the collection name
as the first argument and the url as the second argument:

<info>php app/console postman:collection:build "API Platform" http://192.168.99.100:8888</info>

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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = sprintf('%s.json', Inflector::camelize(strtolower($this->name)));
        $filepath = $this->rootDir.'/../'.$filename;

        file_put_contents($filepath, json_encode($this->normalizer->normalize($this->collectionGenerator->generate(), 'json')));

        $text = sprintf('Postman collection has been successfully built in file %s.', $filename);
        $this->writeSection($output, $text);
    }

    /**
     * @param OutputInterface $output
     * @param string          $text
     * @param string          $style
     */
    public function writeSection(OutputInterface $output, $text, $style = 'bg=blue;fg=white')
    {
        $output->writeln(array(
            '',
            $this->getHelperSet()->get('formatter')->formatBlock($text, $style, true),
            '',
        ));
    }
}
