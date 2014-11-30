<?php

namespace Wjzijderveld\Console\OptionResolver;

use InvalidArgumentException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
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
            $value = $this->questionHelper->ask(
                $this->input,
                $this->output,
                $this->createQuestionForOption($option)
            );
        }

        return $value;
    }

    /**
     * @return array Values for given InputDefinition, optionally filtered
     */
    public function resolveInputDefinition(InputDefinition $inputDefinition, array $filter = [])
    {
        $values    = [];

        $arguments = array_filter($inputDefinition->getArguments(), function (InputArgument $argument) use ($filter) {
            return ! count($filter) || in_array($argument->getName(), $filter);
        });

        $options = array_filter($inputDefinition->getOptions(), function (InputOption $option) use ($filter) {
            return ! count($filter) || in_array($option->getName(), $filter);
        });

        foreach ($arguments as $argument) {
            $values[$argument->getName()] = $this->input->getArgument($argument->getName());
        }

        foreach ($options as $option) {
            $values[$option->getName()] = $this->getOptionValue($option);
        }

        return $values;
    }

    private function createQuestionForOption(InputOption $option)
    {
        $question = new Question($option->getDescription() . ': ');

        return $question;
    }
}
