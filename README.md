# Console options resolver

[![Build Status](https://travis-ci.org/wjzijderveld/console-option-resolver.svg?branch=master)](https://travis-ci.org/wjzijderveld/console-option-resolver)

A simple library for [Symfony Console].

It allows to ask the user for options if not provided, this way you can use a command both interactive as in a bash oneliner.

[Symfony Console]: https://github.com/symfony/Console

### Installation

```bash
composer require wjzijderveld/console-option-resolver 1.*@dev
```

### Usage

```php
class GenerateCommand extends Command
{
    private $optionResolver;

    public function __construct(Resolver $optionResolver)
    {
        parent::__construct();

        $this->optionResolver = $optionResolver;
    }

    public function configure()
    {
        $this->setName('generate');

        $this
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, 'The namespace to generate the class in')
            ->addOption('class'. null, InputOption::VALUE_REQUIRED, 'THe name of the class to generate')
            ->addOption('abstract', null, InputOption::VALUE_NONE, 'Make class abstract');
    }

    public function execut(InputInterface $input, OutputInterface $output)
    {
        // options will now contain values for each given option
        // for each option that is not given when running this command
        // it will interactivily ask for a question
        $options = $this->optionResolver->resolveInputDefinition($this->getDefinition(), array('namespace', 'class', 'abstract'));

        var_dump($options);
    }
}
```
