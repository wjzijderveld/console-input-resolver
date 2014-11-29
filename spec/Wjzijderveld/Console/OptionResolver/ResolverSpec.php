<?php

namespace spec\Wjzijderveld\Console\OptionResolver;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ResolverSpec extends ObjectBehavior
{
    private $input;
    private $barOption;
    private $foobarOption;

    public function let(OutputInterface $output, QuestionHelper $questionHelper)
    {
        $this->barOption = new InputOption('bar', null, InputOption::VALUE_REQUIRED);
        $this->foobarOption = new InputOption('foobar', null, InputOption::VALUE_REQUIRED);

        $inputDefinition = new InputDefinition(array($this->barOption, $this->foobarOption));

        $this->input = new ArrayInput(array('--bar' => 'baz'), $inputDefinition);

        $this->beConstructedWith(
            $this->input,
            $output,
            $questionHelper
        );
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType('Wjzijderveld\Console\OptionResolver\Resolver');
    }

    public function it_throws_an_exception_for_unknown_options()
    {
        $dummyOption = new InputOption('foo');
        $this
            ->shouldThrow(new InvalidArgumentException('The "foo" option does not exist.'))
            ->during('getOptionValue', array($dummyOption));
    }

    public function it_returns_the_value_for_given_options()
    {
        $this->getOptionValue($this->barOption)->shouldReturn("baz");
    }

    public function it_asks_for_an_answer_when_no_option_value_given($output, $questionHelper)
    {
        $question = new Question($this->foobarOption->getDescription() . ': ');
        $questionHelper->ask($this->input, $output, $question)->shouldBeCalled()->willReturn('foobaz');

        $this->getOptionValue($this->foobarOption)->shouldReturn('foobaz');
    }
}
