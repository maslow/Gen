INTRODUCTION
============

Gen - Developed based Yii Framework 2.0


REQUIREMENT
-----------

>=PHP 5.4.0


INSTALLATION
------------

git clone https://github.com/Maslow/Gen.git


CONFIGURATION
-------------

### Initialize project

1、In the root path of your project , enter the commands:
  
```command
php yii init
```

Alternatively : you can also use it like this, to initialize your project for development environment or production environment.
```command
php yii init dev
#or
php yii init prod
```

### Database

1､ Run this SQL statement to create a database in MySQL:

```sql
create database gen CHARACTER SET utf8 COLLATE utf8_unicode_ci;
```

2、Modify `config/db.php` :

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=gen',
    'username' => 'root',
    'password' => 'Enter Your MySQL password here',
    'charset' => 'utf8',
];
```


### Install and Update the modules

```command
php yii module/update-all
```

OTHERWISE
---------

Dashboard URL:  http://localhost/gen/web/index.php/dashboard

  
 