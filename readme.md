Lautaro Framework for restful API
=============================

Lautaro framework is a light toolset to create restful API in a easy way with only what you need.


Current status
--------------

- [x] Add PSR-15 middleware approach
- [x] Add PSR-7 Http library
- [x] Add PSR-11 Container
- [x] Add configuration loader class
- [x] Add built-in server for development mode
- [x] Add FastRoute for fixed routes by file
- [x] Add automatic route resolver by URI/Controller

Install
--------------

- **Install the dependencies**

	`composer install`
- **Create your environment file**

	`cp .env.example .env`
- **Place your local settings**

	`cp -R config/base config/local`
- **Configure Phinx migration tool**

	`cp phinx.default.yml phinx.yml` -> Note: replace with your database settings

- **Configure the Propel ORM inside**

	`config/local/propel.php`

- **Create the migrations and seed folders required by Phinx**

	`mkdir etc/migrations`

	`mkdir etc/seeds`

- **Generate the first schema with**

	`php bin/update-model.php`

- **Run the local server**

	 `php bin/server.php`

Database Migrations
--------------

- Generate models from existing database schema

	`php bin/update-model.php`
- Add a new migration

	`php vendor/bin/phinx create MyNewMigration`
- Run migration

	`php vendor/bin/phinx migrate`

Getting Started
--------------
- [Build a blog Restful API](https://github.com/gustavonecore/lautaro/blob/master/blog.md)
- [Leftaro console commands](https://github.com/gustavonecore/lautaro/blob/master/commands.md)
