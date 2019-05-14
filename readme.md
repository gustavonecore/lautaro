Business Leftaro, a micro Web Framework for sales platforms
=============================

Install
--------------

- **Install the dependencies**
`composer install`
- **Define your settings**
`cp -R config/base config/local`  replace with your settings
- **Configure Phinx migration tool**
`cp phinx.default.yml phinx.yml`  replace with your settings
- **Configure the Propel ORM inside**
`config/local/propel.php`
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
