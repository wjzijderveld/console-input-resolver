# Console options resolver

[![Build Status](https://travis-ci.org/wjzijderveld/console-input-resolver.svg?branch=master)](https://travis-ci.org/wjzijderveld/console-input-resolver)

A simple library for [Symfony Console].

It allows to ask the user for options if not provided, this way you can use a command both interactive as in a bash oneliner.

[Symfony Console]: https://github.com/symfony/Console

### Installation

```bash
composer require wjzijderveld/console-input-resolver 1.*@dev
```

### Usage

```php
class GenerateCommand extends Command
{
    private $inputResolver;

    public function __construct(Resolver $inputResolver)
    {
        parent::__construct();

        $this->inputResolver = $inputResolver;
    }

    public function configure()
    {
        $this->setName('generate');

        $this
            ->addArgument('class', InputArgument::OPTIONAL, 'The name of the class to generate')
            ->addOption('namespace', null, InputOption::VALUE_REQUIRED, 'The namespace to generate the class in');
    }

    public function execut(InputInterface $input, OutputInterface $output)
    {
        // values will now contain values for namespace and class
        // for each option or argument that is not given when running this command
        // it will interactivily ask for a value
        $values = $this->inputResolver->resolveInputDefinition($this->getDefinition(), array('namespace', 'class'));

        var_dump($values);
    }
}
```

Example output:

```bash
$ ./console generate --namespace Acme
> The name of the class to generate: Foo
> array(2) {
  'namespace' =>
  string(4) "Acme"
  'class' =>
  string(3) "Foo"
}
```
