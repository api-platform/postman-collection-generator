<?php

namespace PostmanGeneratorBundle\Command;

use Doctrine\Common\Inflector\Inflector;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class PostmanCollectionBuildCommand extends ContainerAwareCommand
{
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
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the Postman collection builder');

        // Name
        $name = $input->getArgument('name');
        if (null !== $name) {
            $output->writeln(sprintf('Collection name: %s', $name));
        } else {
            $question = new Question($questionHelper->getQuestion('Collection name', $name), $name);
            $question->setMaxAttempts(5);
            $input->setArgument('name', $questionHelper->ask($input, $output, $question));
        }

        // Url
        $url = $input->getArgument('url');
        if (null !== $url) {
            $output->writeln(sprintf('Collection url: %s', $url));
        } else {
            $question = new Question($questionHelper->getQuestion('Collection url', $url), $url);
            $question->setMaxAttempts(5);
            $input->setArgument('url', $questionHelper->ask($input, $output, $question));
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
        $this->getQuestionHelper()->writeSection($output, $text);
    }

    /**
     * @return QuestionHelper
     */
    protected function getQuestionHelper()
    {
        $question = $this->getHelperSet()->get('question');
        if (!$question || get_class($question) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper') {
            $this->getHelperSet()->set($question = new QuestionHelper());
        }

        return $question;
    }
}
