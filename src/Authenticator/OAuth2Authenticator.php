<?php

namespace PostmanGeneratorBundle\Authenticator;

use PostmanGeneratorBundle\Model\Request;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class OAuth2Authenticator implements AuthenticatorInterface, CommandAuthenticatorInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * {@inheritdoc}
     */
    public function generate(Request $request)
    {
        if (null !== $this->token) {
            $request->addHeader('Authorization', sprintf('Bearer %s', $this->token));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = new QuestionHelper();
        $this->token = $questionHelper->ask(
            $input,
            $output,
            new Question('[OAuth2 authentication] Please fill a token:')
        );
    }
}
