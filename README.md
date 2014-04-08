pastey
======

Pastebin built with:
* Slim - PHP micro REST framework
* Ractive - Javascript data binding

Install
-------

Make sure you install the following package managers:

1. JS (Bower) for the client side dependencies

	npm install -g bower

2. PHP (Composer) for the server side PHP dependencies

	curl -sS https://getcomposer.org/installer | php

Setup with:

	bower install
	composer install

Webserver Setup
---------------

Define a new virtualhost for the pastey application.

There's a sample nginx site included that you can use.

Todo
----

* Setup Bower, AMD and require JS
* Allow user to upload a patch/diff file
* Add a diff parser upload and option
* Add a CTRL+Enter submit shortcut