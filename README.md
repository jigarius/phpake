# Phpake

Phpake is a make-like utility built on PHP. It is pronounced the same as `fake`
because the second `p` is silent like in the world `elephpant`.

---

If you're working on a PHP project, most of your team members must already know some PHP.
I've always found writing a `Makefile` quite challenging because its syntax is similar to
the shell syntax, but it is very different at the same time!

When I was working with Ruby, I was using `rake` and it was awesome because it uses `ruby`
syntax. But when it comes to PHP, I often used to miss having a similar tool that is
easy to install and use. Thus, Phpake was born. I hope you like it and if you do, feel free
to leave a `$tip` 😉

~ [Jigarius](https://jigarius.com/)

## Installation

Phpake can easily be installed with `composer`. Here are 2 ways in which you can
install Phpake depending on your needs.

In order to be able to run `phpake` from anywhere, Composer's `vendor/bin` directory
should be included in the the `PATH` variable.

### System-wide installation

To install Phpake globally on your system, use the following command:

    composer --global require jigarius/phpake

You should then be able to run `phpake` from anywhere, provided your `PATH` variable
is configured correctly.

### Project installation

To install Phpake in a particular project, run the following command:

    composer require jigarius/phpake

You should then be able to run it with `composer exec phpake`.

## Usage

If you're working on a PHP project, most of your team members must already know some PHP.
I've always found writing a `Makefile` quite challenging because its syntax is similar to
the shell syntax, but it is very different at the same time!

When I was working with Ruby

## Development

This project uses a Dockerized development environment. Run the project as you would any
other docker-compose based project. There might be some handy commands in the `Makefile`.
