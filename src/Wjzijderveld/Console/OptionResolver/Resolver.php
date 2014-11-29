<?php

namespace Wjzijderveld\Console\OptionResolver;

use InvalidArgumentException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Resolver
{
    private $input;
    private $output;
    private $questionHelper;

    public function __construct(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        $this->input          = $input;
        $this->output         = $output;
        $this->questionHelper = $questionHelper;
    }

    /**
     * @return mixed Value from given option, or answer on the question
     */
    public function getOptionValue(InputOption $option)
    {
        $value = $this->input->getOption($option->getName());

        if (null === $value) {
            $question = $this->createQuestionForOption($option);
            $value = $this->questionHelper->ask($this->input, $this->output, $question);
        }

        return $value;
    }

    private function createQuestionForOption(InputOption $option)
    {
        $question = new Question($option->getDescription() . ': ');

        return $question;
    }
}
