INTRODUCTION
============

Gen - Developed based Yii Framework 2.0


REQUIREMENT
-----------

  equal or greater than PHP 5.4 (5.6 or 7.x is recommended mostly)


INSTALLATION
------------
### Clone

git clone https://github.com/Maslow/Gen.git

### Initialize project

1、In the root path of your project , enter the commands:
  
```command
php yii init
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

COMMANDS INTRODUCTION
---------------------
### init [env]
The init command will generate `db.php` configuration file. The value of parameter [env] would be 'dev' by default.
```command
php yii init

```
Alternatively , you can also use it like this, to initialize your project for development environment or production environment.
```command
php yii init dev
#or
php yii init prod
```

### module/install (module_id)
Install the module from Module Transfer Station.
```command
php yii module/install your_module_id
```

### module/remove  (module_id)
Remove the module to Module Transfer Station.
```command
php yii module/remove your_module_id
```

### module/update  (module_id)
Update the permissions and migrations that might have been modified.
```command
php yii module/update your_module_id
```

### module/update-all
Update all the modules, just behave like running module/update for every module.
```command
php yii module/update-all
```

OTHERWISE
---------

Dashboard URL:  http://localhost/gen/web/dashboard

  
 