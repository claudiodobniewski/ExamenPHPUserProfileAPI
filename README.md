# ExamenPHPUserProfileAPI
Developer PHP test - Users Managment

## Synopsis

At the top of the file there should be a short introduction and/ or overview that explains **what** the project is. This description should match descriptions added for package managers (Gemspec, package.json, etc.)

## Code Example

Show what the library does as concisely as possible, developers should be able to figure out **how** your project solves their problem by looking at the code example. Make sure the API you are showing off is obvious, and that your code is short and concise.

## Motivation

A short description of the motivation behind the creation and maintenance of the project. This should explain **why** the project exists.

## Installation

Develope on PHP 5.6.21 and Symfony 3.2.3, ORM Doctrine

Provide code examples and explanations of how to get the project.

Checkout (clone) from  GitHub - command line

verificar que los directorios (recursivo) tengan permisos totales (rw) para u-g-o

crear usuario y configurarlo en parameters.yml o parameters_dev.yml para entorno dev

generate BD

>php bin/console doctrine:database:create --env=dev

If you need re-generate database

> php bin/console doctrine:database:drop --force

> php bin/console doctrine:database:create

Check schema tables vs Entity classes

> php bin/console doctrine:schema:validate

>> [Mapping]  OK - The mapping files are correct.

>> [Database] FAIL - The database schema is not in sync with the current mapping file.

Then, sync tables from proyect entities (powered by doctrine)

> php bin/console doctrine:schema:update --force

>> Updating database schema...

>> Database schema updated successfully! "1" query was executed


Install via composer doctrine-fixtures-bundle for devel environment , needed for pre-populate tables

> composer require --dev doctrine/doctrine-fixtures-bundle  

NOTE: if you have xdebug enable on your PHP, and get an fatal error "proc_open(): fork failed - Cannot allocate memory", please disable xdebug (http://stackoverflow.com/questions/8754826/how-to-disable-xdebug ) while composer installs the asset

Load Fixtures

> php bin/console doctrine:fixtures:load --fixtures=src/IntrawayBundle/DataFixtures

>> Careful, database will be purged. Do you want to continue y/N ?y

>> purging database

>> loading Intraway\DataFixtures\ORM\LoadUserData

Install via composer serializer, used for Json serialize/deserialize and automapping class

> composer require symfony/serializer

  
## API Reference

Depending on the size of the project, if it is small and simple enough the reference docs can be added to the README. For medium size to larger projects it is important to at least provide a link to where the API reference docs live.

## Tests

Describe and show how to run the tests with code examples.

## Contributors

Let people know how they can dive into the project, include important links to things like issue trackers, irc, twitter accounts if applicable.

About REST API http://asiermarques.com/2013/conceptos-sobre-apis-rest/

## License

A short snippet describing the license (MIT, Apache, etc.)
