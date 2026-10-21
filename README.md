# YAB-CMS-FF

[![Packagist Version](https://img.shields.io/packagist/v/marks-software-gmbh/yab-cms-ff.svg?style=flat-square)](https://packagist.org/packages/marks-software-gmbh/yab-cms-ff)
[![Packagist Downloads](https://img.shields.io/packagist/dt/marks-software-gmbh/yab-cms-ff.svg?style=flat-square)](https://packagist.org/packages/marks-software-gmbh/yab-cms-ff)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg?style=flat-square)](https://www.php.net/)
[![CakePHP Version](https://img.shields.io/badge/CakePHP-5.4-D33C43.svg?style=flat-square)](https://cakephp.org/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

**"Yet Another Boring" CMS – Fast Forward (YAB CMS)** is a free, open source, bootstrap content management system for PHP 8 based on CakePHP 5, CakePHP vendor plugins, the AdminLTE bootstrap admin dashboard template and released under the [MIT License](https://github.com/MarksSoftwareGmbH/YAB-CMS-FF/blob/main/LICENSE).

It is powered by the [CakePHP](http://cakephp.org) 5 PHP framework.

## Requirements
  * Apache with `mod_rewrite`, intl, imagick OR nginx server
  * PHP 8.2 or higher
  * Latest MySQL or MariaDB, PostgreSQL, SQLite or SQL-Server database engines

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist marks-software-gmbh/yab-cms-ff [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist marks-software-gmbh/yab-cms-ff
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist marks-software-gmbh/yab-cms-ff my-yab-cms
```

Then configure your database in `config/app_local.php` or use the default Sqlite Setup and visit the site.

#### Default login: Admin / superadmin (change immediately).

**Important:** Change the default credentials immediately after the first login.

#### Installation using GitHub [Clone](https://docs.github.com/en/get-started/getting-started-with-git/about-remote-repositories)

Alternative: Installation using GitHub Clone

    https://github.com/MarksSoftwareGmbH/YAB-CMS-FF.git
    
or by using GitHub CLI

    gh repo clone MarksSoftwareGmbH/YAB-CMS-FF

When the cloning is finished please enter the cloned project and run

    composer update

When the composer update is finished

  * Upload the project content into a server htdocs TLD folder.
  * Create a new MySQL or MariaDB database (with the `utf8mb4_unicode_ci` collation).
  * Create a new user, set the password and grant the rights for the created database.
  * Update the file /config/app_local.php Datasources array with the database, username and password for the `default`, `test_mysql`, `development` and `production` configurations.
  * Update the file /config/app_local.php EmailTransport array with a default email server configuration (SMTP or Mail transport).
  * Visit http://your-site.com/ from your browser.
  * Visit http://your-site.com/login to login as Admin, the default username: Admin and password: superadmin (Please change this credentials to your needs).
  * Visit http://your-site.com/admin/dashboard to manage the backend as Admin.

#### Installation using the ZIP [Archive](https://github.com/MarksSoftwareGmbH/YAB-CMS-FF/archive/refs/heads/main.zip)

  * Download and extract the ZIP-file.
  * Run `composer update` 
  * Upload the unzipped archive content into a server htdocs TLD folder.
  * Create a new MySQL or MariaDB database (with the `utf8mb4_unicode_ci` collation).
  * Create a new user, set the password and grant the rights for the created database.
  * Update the file /config/app_local.php Datasources array with the database, username and password for the `default`, `test_mysql`, `development` and `production` configurations.
  * Update the file /config/app_local.php EmailTransport array with a default email server configuration (SMTP or Mail transport).
  * Visit http://your-site.com/ from your browser.
  * Visit http://your-site.com/login to login as Admin, the default username: Admin and password: superadmin (Please change this credentials to your needs).
  * Visit http://your-site.com/admin/dashboard to manage the backend as Admin.

## Vendor plugins

  * **CakePHP - Application Skeleton**: [cakephp/app](https://github.com/cakephp/app)
  * **CakePHP - Authentication**: [cakephp/authentication](https://github.com/cakephp/authentication)
  * **Mark Scherer - TinyAuth Plugin**: [dereuromark/cakephp-tinyauth](https://github.com/dereuromark/cakephp-tinyauth)
  * **PHP - JWT**: [firebase/php-jwt](https://github.com/firebase/php-jwt)
  * **Friends Of Cake - Bootstrap UI**: [friendsofcake/bootstrap-ui](https://github.com/friendsofcake/bootstrap-ui)
  * **Friends Of Cake - CsvView Plugin**: [friendsofcake/cakephp-csvview](https://github.com/friendsofcake/cakephp-csvview)
  * **Friends Of Cake - CRUD Plugin**: [friendsofcake/crud](https://github.com/friendsofcake/crud)
  * **Friends Of Cake - Search Plugin**: [friendsofcake/search](https://github.com/friendsofcake/search)
  * **Intervention - PHP Image Processing**: [intervention/image](https://github.com/intervention/image)
  * **PHPOffice - PhpSpreadsheet**: [phpoffice/phpspreadsheet](https://github.com/phpoffice/phpspreadsheet)
  * **PHPOffice - PhpWord**: [phpoffice/phpword](https://github.com/phpoffice/phpword)
  * **Colorlib (Aigars Silkalns) - AdminLTE**: [almasaeed2010/adminlte](https://github.com/ColorlibHQ/AdminLTE)

## Suggested vendor plugins you should install via composer

  * **Mark Story - Asset Compress**: [markstory/asset_compress](https://github.com/markstory/asset_compress)
  * **Mark Scherer - CakePHP IdeHelper Plugin**: [dereuromark/cakephp-ide-helper](https://github.com/dereuromark/cakephp-ide-helper)
  * **PHPStan - PHP Static Analysis Tool**: [phpstan/phpstan](https://github.com/phpstan/phpstan)

## Front- and Backend template

  * **Colorlib (Aigars Silkalns) - AdminLTE Bootstrap Admin Dashboard Template** [https://github.com/ColorlibHQ/AdminLTE](https://github.com/ColorlibHQ/AdminLTE)

## Links

  * **Marks Software GmbH website**: [https://www.marks-software.de](https://www.marks-software.de)
  * **Marks Software GmbH on X**: [https://x.com/SoftwareMarks](https://x.com/SoftwareMarks)