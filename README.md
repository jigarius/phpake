# Phpake

Phpake is a make-like utility built for PHP. It is pronounced *fake* because
the second *p* is silent just like the second *p* in the word *elephpant*.

I've always found writing a `Makefile` quite challenging because the syntax
is similar to the shell syntax, but quite different at the same time.
When I'm working with Ruby, I use [rake](https://github.com/ruby/rake) and
it's awesome because it uses Ruby syntax. When I work with PHP, I often
miss having a similar tool that is easy to install, easy to use, and allows
full-fledged PHP syntax. Thus, Phpake was born.

I invite you to use it, and I hope you like it.

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

To use Phpake, start by creating a `Phpakefile` to define some tasks.
Each task is simply a PHP function. You can read more on creating a
`Phpakefile` under the [Phpakefile](#Phpakefile) section.

Here are some common Phpake commands. You need to run them from a directory
containing a `Phpakefile`.

- `phpake` - shows a list of available commands.
- `phpake hello-world` - runs the command defined by `function hello_world()`.
- `phpake hello-human Bunny Wabbit` - passes 2 parameters to the task.
- `phpake hello-group --help` - shows help text for the task.

## Phpakefile

A *Phpakefile* contains definitions of tasks that can be executed by Phpake. 
The following subheadings are about defining such tasks. A Phpake task definition
is simply a PHP function (a task callback). Here's are some examples:

- [Hello world](examples/hello-world.phpakefile)
- [Input Output](examples/input-output.phpakefile)
- [Namespaces](examples/fizzbuzz.phpakefile)
- [Variadic arguments](examples/variadic.phpakefile)
- [Shell commands](examples/shell.phpakefile)
- [Including commands](Phpakefile)

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

This task can then be executed as `phpake hello-world`. You can also organize
functions with PHP namespaces.

## Regular Parameters

If your task needs some input from the user, simply introduce one or more
arguments in the function definition.

```php
function hello_human($fname, $lname = NULL) {
  // Do something
}
```

Since `$lname` has a default value, it is treated as an optional argument.

## Special parameters

Phpake is built with [Symfony Console](https://symfony.com/doc/current/components/console.html),
which provides certain special parameters that can help you enrich your
application even further. If your task has a parameter with one of these
special names, it will behave specially. For more info on these objects,
please refer to the Symfony Console documentation.

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

- For a `function hello_world()` the command becomes `hello-world`
- If defined in a namespace, it becomes `namespace:hello-world`.

### $rest

Often there are tasks that can accept an unlimited number of arguments. These
can be handled with a `$rest` parameter. It **must be** defined as the last
argument to your function.

```php
function hello_group(string $you, string $rest) {
  // Do something.
}
```

If a default value of `NULL` is assigned to `$rest`, it becomes optional,
otherwise, it requires one or more values.

### Helpers

Say, you have a function that helps other tasks but it is not a command by
itself. Such functions can be put in a `Phpakefile` too. However, so that
Phpake doesn't confuse them for commands, the function name must begin with
an underscore. For example a function named `_foo()` will not result in a
command named `phpake foo` because the function name starts with an underscore.

## Development

This project uses a Dockerized development environment. Run the project as
you would any other docker-compose based project.

When developing for the first time,

- Clone the repository with `git clone`
- `cd` into the cloned repository
- Build Docker images: `docker compose build`
- Bring up the containers: `docker compose up -d`

After the initial setup, you can use the following commands:

- `docker compose start`: Start the project's containers
- `docker compose exec main sh`: Launch a shell inside the project's container
  - You'll spend most of your time here
  - The command `phpake` should be available
- `docker compose stop`: Stops the project's containers when you're done

## Links

- [Phpake on Packagist](https://packagist.org/packages/jigarius/phpake)
- [Phpake: A Tool like Make/Rake Built for PHP](https://jigarius.com/blog/phpake) article on Jigarius.com
- Phpake video tutorial (coming soon)