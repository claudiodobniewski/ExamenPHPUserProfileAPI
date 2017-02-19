# ExamenPHPUserProfileAPI
Developer PHP test - Users Managment  
By Claudio Dobniewski  
email claudiojd@gmail.com  
linkedIn https://ar.linkedin.com/in/claudio-j-dobniewski-7297aa13

## Synopsis

Example of API REST implemented over PHP Symfony/doctrine/composer

## Installation

Develope on PHP 5.6.21 and Symfony 3.2.3, ORM Doctrine for Linux, test over Ubuntu 14.10

Require software: 

git

Apache2 - modules required modphp modrewrite

PHP 5.6 - modules required php-pdo php-mysql php-xml

Mysql server (local or remote) & cli 

### step-by-step

**1) Move to project deployment folder**

cd <PATH/TO/PROJECT/DEPLOY>

**2) clone the git project**

git clone https://github.com/claudiodobniewski/ExamenPHPUserProfileAPI.git

now you find a folder ExamenPHPUserProfileAPI/

if you like, can rename this folder, in this case, you can replace "ExamenPHPUserProfileAPI" for the new folder name on next steps.

**3) move into the project**

cd ExamenPHPUserProfileAPI/

**4) select to the branch (opctional), project has master and trunk branches**

git checkout <branch>

**5) need execute permision for index files**

web/app.php

web/app_dev.php

check it, if it's need, use chmod command, example

$ chmod +x web/app.php 

**6) check writting perms into folder var/  (and all subfolders!)**

if you have written problems into that folder, change perms, example

$ chmod +w -r var/*

YOUR user and APACHE user SHOULD can write and read into var/cache, var/logs and var/sessions

TIP: set your user and apache group  

$ chown $USER:apache-www    or whatever apache user on your system maybe can help you.

**7) install composer**

https://getcomposer.org/download/

**8) install dependecies, use composer**

$ composer install

$ composer update

NOTE: if you have xdebug enable on your PHP, and get an fatal error "proc_open(): fork failed - Cannot allocate memory", please disable xdebug (http://stackoverflow.com/questions/8754826/how-to-disable-xdebug ) while composer installs the asset, and close web browser or another memory eater app, an run composer again.

**8) configure database**  

set production database on  
app/config/parameters.yml  

and devel database on  
app/config/parameters_dev.yml

**9) if you need a fresh database , you need say to symfony "create database scheme" ( --en=dev is for use development environment settings)**  

if you want drop current database (NOT UNDO!!!)

$ php bin/console doctrine:database:drop --force --env=dev

$ php bin/console doctrine:database:create --env=dev

CAUTION: this destroy your previus database! 

if you need check db status, only run

$ php bin/console doctrine:schema:validate

and, for create (or recreate) scheme (delete all data on tables!)

$ php bin/console doctrine:schema:update --force --env=dev

**10) configure web server**

You can use internal on-the.box develoment server (not use on production environment!)

or configure apache web server (other web server flavours can be use, search in google please)

http://symfony.com/doc/current/setup/web_server_configuration.html

**11) check in your browser URL of project. The "server part" of url, depend of web server configuration**

http://<IP>/ExamenPHPUserProfileAPI/app_dev.php/

or, if use a more fine tune

http://<IP>/ExamenPHPUserProfileAPI/

**12) if you need pre-charged information (usefull on devel environment) for testing**

$ php bin/console doctrine:fixtures:load --fixtures=src/IntrawayBundle/DataFixtures

if you run this on production environment, remember delete users table records after tests end

**13) Auto tests - phpuni**t

vendor/bin/phpunit -c ./phpunit.xml

some tests need the table fixture data of 12) to result OK (no auto load fixtures)

**14) Ready!**

Now, you can use this app! red API for more information.
  
## API Reference

This project implement a REST API

The app receive a http request , the http method IS IMPORTANT, in combination whith url define the interface invoked, on some interfaces accept http params.

On bad process (user id not found, no user_id provide on request, etc) you get a response different of 200 OK (descriptive of problem), and a Json document example:  
status 404 Not Found  
json {"message":"NOT FOUND USER \[Id:1d8fef6858a93dca1e1c5] [UserId:22]"}  

Currently, not have an API to create a new record, this is not on requeriments.  
The records contain four fields: id, name, email and image. Image is an optional field, and have a separate interface to upload or replace. Image field can be null. If is, Json dco return Image value of url to access the image file.  

The field Id is different on each request, and you can found on logs, use this for filter relevant logs

If process end OK, the Json document contain the current status on table, except DELETE what describe the record before erase.

Action: GET  
Use: to get a record  
Http Method: GET  
Path: /userProfile/{USER_ID}  ej /userProfile/3
Params: no  
Response: a Json doc,example  {"id":2,"name":"pedro artrero","email":"anagutierrez@gmail.com","Image":"http:\/\/localhost\/userprofapi\/bundles\/IntrawayBundle\/uploadUserImages\/2.jpg"}

Action: DELETE  
Use: to get ERASE a record  
Http Method: DELETE  
Path: /userProfile/{USER_ID}  ej /userProfile/3
Params: no  
Response: a Json doc,example  {"id":1,"name":"Jorge Perez","email":"jorge.perez73@hotmail.com","Image":null}

Action: EDIT/MODIFY  
Use: to update name or email  
Http Method: PUT  
Path: /userProfile/{USER_ID}  ej /userProfile/3
Params: name=<NEW_NAME>&email=<NEW_EMAIL>  example name=Pedro+Cortez&email=pedro.cortez@gmail.com  you can ommit once or both safetly  
Response: a Json doc,example  {"id":2,"name":"Pedro Cortez","email":"pedro.cortez@gmail.com","Image":"http:\/\/localhost\/userprofapi\/bundles\/IntrawayBundle\/uploadUserImages\/2.jpg"}
NOTE: name require al least 10 chars length, and email a valid email format, other way the param is ignored. Check logs if edit no modify the record.

curl -v -X PUT 'localhost/userprofapi/app_dev.php/userProfile/2/addPicture?imageUrl=https%3A%2F%2Fs-media-cache-ak0.pinimg.com%2F736x%2F43%2Fa7%2F56%2F43a75665c0b59f406f6129be67e23f5e.jpg'
Action: UPLOAD IMAGE  
Use: to load (new or replace) user profile image  
Http Method: PUT  
Path: /userProfile/{USER_ID}/addPicture  ej /userProfile/3/addPicture
Params: imageUrl=<URL_TO_IMAGE_URLENCODE>  example imageUrl=https%3A%2F%2Fs-media-cache-ak0.pinimg.com%2F736x%2F43%2Fa7%2F56%2F43a75665c0b59f406f6129be67e23f5e.jpg  
Response: a Json doc,example  {"id":2,"name":"Pedro Cortez","email":"pedro.cortez@gmail.com","Image":"http:\/\/localhost\/userprofapi\/bundles\/IntrawayBundle\/uploadUserImages\/2.jpg"}
NOTE: url is format-checked before upload, and only accept url's ending on .jpg .png or .gif , the file uploaded has name <ID_USER>.<IMG_TYPE> example  2.jpg, and url returned "http://localhost/userprofapi/bundles/IntrawayBundle/uploadUserImages/2.jpg"  

NOTE ABOUT PUT AND PATCH HTTP METHODS: upload one record field should be PATCH method, and not PUT, but symfony doesn't support PATCH method today, For this cause, I decide use PUT for this operations.

The app support change of image folder (custom parameter), on this case you need move the image files from older folder to the new folder, you can verify written action posible, and accessible url	 
If the policy of names of upload files change on the future, or new image have a different image type, the app delete older image after upload (successfully) the new, but  if the new name is exactly same de older, only replace the image on folder.

## Tests

The tests are only examples of unit tests, funcional test and code validation test

Run all tests
vendor/bin/phpunit -c ./phpunit.xml

Run only some test
vendor/bin/phpunit -c ./phpunit.xml <FILE OR FOLDER>

## References

About Symfony  http://symfony.com  
About Symfony Doctrine http://symfony.com/doc/current/doctrine.html  
About REST API http://asiermarques.com/2013/conceptos-sobre-apis-rest/  
About PSR-2  
About PSR-3 Monolog (Symfony logger) compliance https://www.loggly.com/ultimate-guide/php-logging-libraries/  
About PSR-4 https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md and https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md  

## License

This git repo is distributed under Symfony licence http://symfony.com/doc/current/contributing/code/license.html
Feel free to use, modify and distribute the content.

