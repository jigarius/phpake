# Phpake

Phpake is a make-like utility built for PHP. It is pronounced *fake* because
the second *p* is silent just like the word *elephpant*.

I've always found writing a `Makefile` quite challenging because its syntax
is similar to the shell syntax, but it is very different at the same time.
When I'm working on Ruby, I use `rake` and it was awesome because it uses
Ruby syntax. But when I work on PHP, I often miss having a similar tool that
is easy to install, easy to use, and allows full-fledged PHP syntax. Thus,
Phpake was born.

I hope you like it and if you do, feel free to leave a `$tip`.

~ [Jigarius](https://jigarius.com/)

## Installation

Phpake can easily be installed with `composer`, either within a project or
globally on your system.

### System-wide installation

To install Phpake globally on your system, use the following command:

    composer global require jigarius/phpake

Now, to run `phpake` from anywhere on your system, Composer's
`vendor/bin` directory needs to be included in the the `PATH` variable.

### Project installation

To install Phpake in a particular project, run the following command:

    composer require jigarius/phpake

You should then be able to run it with `composer exec phpake`.

## Usage

To use Phpake, you start by creating a `Phpakefile` where you define tasks.
Each task is simply a PHP function. You can read more on creating a
`Phpakefile` under the [Phpakefile](#Phpakefile) section.

Here are some common Phpake commands. You need to run them from a directory
containing a `Phpakefile`.

- `phpake` - shows a list of available commands.
- `phpake hello-world` - runs the command defined by `function hello_world()`.
- `phpake hello-human Bunny Wabbit` - passes 2 parameters to the task.
- `phpake hello-group --help` - shows help text for the task.

## Phpakefile

The following subheadings talk about writing tasks in a `Phpakefile`. A
Phpake task definition is simply a PHP function (a task callback). Here's
a sample [Phpakefile](Phpakefile) to inspire you.

## Simple tasks

Here's a simple task that takes no input and prints some output. Just make
sure that the function name doesn't coincide with any existing functions.

```php
/**
 * Say hello world.
 */
function hello_world() {
  echo 'Hello world' . PHP_EOL;
}
```

This task can then be executed as `phpake hello-world`.

## Regular Parameters

If your task needs some input from the user, simply introduce one or more
arguments in the function definition.

```php
function hello_human($fname, $lname = NULL) {
  // Do something
}
```

Since `$lname` has a default value, it is treated as an optional argument. So,
this task can be executed as `phpake Niki` or `phpake Niki Martin`.

## Special parameters

Phpake is build with [Symfony Console](https://symfony.com/doc/current/components/console.html),
which provides certain special parameters that can help you enrich your
application even further. If your task has a  parameter with one of these
special names, it will behave specially. You can read more about some of these
objects in the Symfony Console documentation.

### $input

A Symfony Console input object.

### $output

A Symfony Console output object that makes it easier to generate
well-formatted, colorful output.

```php
function hello_joey($output) {
  $output->writeln('Hello <info>Joey</info>!');
}
```

The text included in `<info></info>` will appear in color.

### $command

Name of the Symfony Console command that is being executed. It looks like the
task function name with some minor differences.

### $rest

There are often tasks that can accept an unlimited number of arguments. These
can be handled with a `$rest` parameter. It **must be** defined as the last
argument to your function.

```php
function hello_group(string $you, string $rest) {
  // Do something.
}
```

If a default value of `NULL` is assigned to `$rest`, it becomes optional,
otherwise, it requires one or more values.

## Development

This project uses a Dockerized development environment. Run the project as
you would any other docker-compose based project.
