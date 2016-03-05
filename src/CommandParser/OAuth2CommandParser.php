<?php

/*
 * This file is part of the PostmanGeneratorBundle package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PostmanGeneratorBundle\CommandParser;

use PostmanGeneratorBundle\Model\Authentication;
use PostmanGeneratorBundle\Model\AuthenticationValue;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OAuth2CommandParser implements CommandParserInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var string
     */
    private $authentication;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param NormalizerInterface $normalizer
     * @param string              $rootDir
     * @param string              $authentication
     */
    public function __construct(NormalizerInterface $normalizer, $rootDir, $authentication = null)
    {
        $this->normalizer = $normalizer;
        $this->rootDir = $rootDir;
        $this->authentication = $authentication;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = new QuestionHelper();
        $this->token = $questionHelper->ask($input, $output, new Question('[OAuth2] Please provide an access token: '));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $authentication = new Authentication();
        $authentication->setId((string) Uuid::uuid4());
        $authentication->setName('OAuth2 authentication');

        $value = new AuthenticationValue();
        $value->setKey('oauth2_access_token');
        $value->setValue($this->token);
        $value->setName('OAuth2 access token');
        $authentication->addValue($value);

        $filename = 'oauth2.json';
        $filepath = $this->rootDir.'/../'.$filename;

        file_put_contents($filepath, json_encode($this->normalizer->normalize($authentication, 'json')));

        $formatterHelper = new FormatterHelper();
        $text = sprintf('Postman authentication environment has been successfully built in file %s.', $filename);
        $output->writeln([
            '',
            $formatterHelper->formatBlock($text, 'bg=blue;fg=white', true),
            '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports()
    {
        return 'oauth2' === strtolower($this->authentication);
    }
}
