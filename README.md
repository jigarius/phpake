# Phpake

Phpake is a make-like utility built for PHP. It is pronounced the same as `fake`
because the second `p` is silent like in the world `elephpant`.

---

If you're working on a PHP project, most of your team members must already
know some PHP. I've always found writing a `Makefile` quite challenging
because its syntax is similar to the shell syntax, but it is very different
at the same time!

When I was working with Ruby, I was using `rake` and it was awesome because it
uses `ruby` syntax. But when it comes to PHP, I often used to miss having a
similar tool that is easy to install and use. Thus, Phpake was born. I hope
you like it and if you do, feel free to leave a `$tip`.

~ [Jigarius](https://jigarius.com/)

## Installation

Phpake can easily be installed with `composer`. Here are 2 ways in which you
can install Phpake depending on your needs.

In order to be able to run `phpake` from anywhere, Composer's `vendor/bin`
directory should be included in the the `PATH` variable.

### System-wide installation

To install Phpake globally on your system, use the following command:

    composer --global require jigarius/phpake

You should then be able to run `phpake` from anywhere, provided your `PATH`
variable is configured correctly.

### Project installation

To install Phpake in a particular project, run the following command:

    composer require jigarius/phpake

You should then be able to run it with `composer exec phpake`.

## Usage

To start using Phpake, you start by creating a `Phpakefile` where you can
define tasks as per your requirements. Each task is simply a PHP function.
You can read more on creating a `Phpakefile` in *Phpakefile* section.

Here are some common commands that you can run from a directory containing
a `Phpakefile`.

- `phpake` - shows a list of available commands.
- `phpake hello-world` - runs the command defined by `function hello_world()`.
- `phpake hello-human Bunny Wabbit` - passes 2 parameters to the task.
- `phpake hello-group --help` - shows help text for the task.

## Phpakefile

The following subheadings talk about writing tasks in a `Phpakefile`.  Here's
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

There are often tasks that can have an unlimited number of arguments. These
are handled with a `$rest` parameter. It **must be** defined as the last
argument to your task function.

```php
function hello_group(string $you, string $rest) {
  // Do something.
}
```

If a default value of `NULL` is assigned to `$rest`, it becomes optional.
Otherwise, it requires one or more values.

## Development

This project uses a Dockerized development environment. Run the project as you would any
other docker-compose based project. There might be some handy commands in the `Makefile`.
