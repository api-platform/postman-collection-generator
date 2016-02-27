<?php

namespace PostmanGeneratorBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostmanCollectionBuildCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return ContainerInterface
     *
     * @throws \LogicException
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $application = $this->getApplication();
            if (null === $application) {
                throw new \LogicException('The container cannot be retrieved as the application instance is not yet set.');
            }

            $this->container = $application->getKernel()->getContainer();
        }

        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('postman:collection:build')
            ->setDescription('Build Postman collection')
            ->setDefinition([
                new InputArgument('name', InputArgument::OPTIONAL, 'The collection name (e.g. "API Platform")'),
                new InputArgument('url', InputArgument::OPTIONAL, 'The API url'),
                new InputOption('public', null, InputOption::VALUE_NONE, 'Is collection public?'),
            ])
            ->setHelp(<<<EOT
The <info>postman:collection:build</info> command helps you generate a Postman
collection according to your project configuration. Provide the collection name
as the first argument and the url as the second argument:

<info>php app/console postman:collection:build "API Platform" http://192.168.99.100:8888</info>

If any of the options is missing, the command will ask for their values interactively.
If you want to disable any user interaction, use <comment>--no-interaction</comment>,
but don't forget to pass all needed arguments.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $this->writeSection($output, 'Welcome to the Postman collection builder');

        // Name
        $name = $input->getArgument('name');
        if (null !== $name) {
            $output->writeln(sprintf('Collection name: %s', $name));
        } else {
            $question = new Question('<info>Collection name</info>: ', $name);
            $question->setMaxAttempts(5);
            $input->setArgument('name', $this->getQuestionHelper()->ask($input, $output, $question));
        }

        // Url
        $url = $input->getArgument('url');
        if (null !== $url) {
            $output->writeln(sprintf('Collection url: %s', $url));
        } else {
            $question = new Question('<info>Collection url</info>: ', $url);
            $question->setMaxAttempts(5);
            $input->setArgument('url', $this->getQuestionHelper()->ask($input, $output, $question));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = sprintf('%s.json', Inflector::camelize(strtolower($input->getArgument('name'))));
        $filepath = $this->getContainer()->getParameter('kernel.root_dir').'/../'.$filename;

        file_put_contents($filepath, json_encode($this->getContainer()->get('postman.generator.collection')->generate(
            $input->getArgument('url'),
            $input->getArgument('name'),
            $input->getOption('public')
        )));

        $text = sprintf('Postman collection has been successfully built in file %s.', $filename);
        $this->writeSection($output, $text);
    }

    /**
     * @return QuestionHelper
     */
    private function getQuestionHelper()
    {
        $question = $this->getHelperSet()->get('question');
        if (!$question) {
            $this->getHelperSet()->set($question = new QuestionHelper());
        }

        return $question;
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
