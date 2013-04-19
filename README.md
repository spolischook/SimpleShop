Simple Shop
========================

About project
----------------------------------

The challenge is to do REST API for simple shop with products and category
Content must to import from xml
Admin can to add and edit content
Anonymous can access to public API
The master branch have a working project, however you can see how project are grow with step** branch.
So...

step0  - Jast istalled Symfony2.2
step1  -
step2
step3  - Import simple price in controller, and view it in template

Installing project
----------------------------------

### Use Composer (*recommended*)

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

Then, use the clone repository SimpleShop:

    git clone https://github.com/spolischook/SimpleShop /var/www/your_project

Then run:

    composer update

Composer will install all dependencies under the
`/var/www/your_project/vendor` directory.

Copy parameters.yml.dist and rename to parameters.yml (app/config/)
Set database connection parameters (database_name, database_user, database_password)
Create table:

``` bash
    app/console doctrine:database:create
```

Run reload to do all necessary operation to start project:

``` bash
    php bin/reload.php
```

Import price:

``` bash
    app/console import:price
```

You have to access your project with:

    http://localhost/your_project/web/app_dev.php

Tests
-------------------------------------

Coming soon...

Bug tracking
------------

Geekhub test uses [GitHub issues](https://github.com/spolischook/SimpleShop/issues?state=open).
If you have found bug, please create an issue.

MIT License
-----------

License can be found [here](https://github.com/Sylius/Sylius/blob/master/LICENSE).

Authors
-------

Simple Shop was originally created by [Sergey Polischook](http://kotoblog.pp.ua) for the final examination of [Geekhub Project](http://geekhub.ck.ua).