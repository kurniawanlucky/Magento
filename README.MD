#Install Magento 2 Locally on Mac


Step 1 – Installing Homebrew
----------------------------

```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

or you can go to their website and follow the instructions there: https://brew.sh

Once homebrew finishes installing you can now see what services you may already have, you should not have any, using this command

```
brew services list
```

Step 2 – Installing httpd (Apache)
---

```
brew install httpd
```

Once homebrew finishes the installation process you should be able to use our brew services list command to see its status. It should say httpd stopped To start your Apache server you will enter in the following command

```
brew services start httpd
```

Step 3 – httpd Configuration
--

__Changing Localhost URL__

We will be looking for the httpd.conf file located at
```
/usr/local/etc/httpd
```

Open httpd.conf with your preferred text editor and look for the following:

```
listen 8080
```
Change to:
```angular2html
listen 80
```
__Changing DocumentRoot Directory__
Next, you will edit will be the DocumentRoot and directory. By default, it should say:
```angular2html
DocumentRoot “/usr/local/var/www”
```
I’d recommend you change the path to be in your user directory so it’s easy to access. You can do this by replacing what is above with the location of a directory named www inside your current users home directory:

```angular2html
DocumentRoot "/Users/[your user]/www"
<Directory "/Users/[your user]/www">
```
__Allowing Overrides__
The next configuration setting you’ll need navigate to is:
```angular2html
AllowOverride none
```
Magento 2 Requires that this be set to the following
```
AllowOverride all
```

__Enabling the rewrite_module__
Now, we need to enable the rewrite_module.  Navigate to:
```angular2html
#LoadModule rewrite_module lib/httpd/modules/mod_rewrite.so
```
We need to uncomment this module so that Magento 2 may use this.  We do this as follows:
```angular2html
LoadModule rewrite_module lib/httpd/modules/mod_rewrite.so
```

__Setting our User and Group__
Finally, you’ll want to specify the local user and their group that you are using for your directory By default, the two variables will look like:
```angular2html
User _www
Group _www
```
Check your user and your group, the default group is "staff"

```angular2html
User [your user]
Group [your user group]
```

Step 4 – Installing and Setting up MySQL
--
you can download mysql at:
```angular2html
https://dev.mysql.com/downloads/mysql/
```

Or you can install with brew:
```angular2html
brew install mySQL@5.7
```

If you are on macOS 10.15 or installing the latest version of MySQL

```angular2html
brew install mySQL
```
Once MySQL installs you can now see that it is a brew service by using the command:
```angular2html
brew services list
```

You can start MySQL with the command:
```angular2html
brew services start mysql
```
__Preparing MySQL for Magento 2__
Once MySQL is running you can type the following in your terminal:
```angular2html
mySQL -u root
```
Now that we are in your MySQL database, let’s start by creating our database by entering the following:
```angular2html
create database magento2
```

The next step will be to create a user account. This isn’t always necessary but it will avoid some potential installation issues down the road:
```angular2html
CREATE USER 'admin'@'localhost' IDENTIFIED WITH mysql_native_password BY 'admin123';
```

You need to give permissions to this user so that Magento 2 can have access to your MySQL instance and create and update the magento2 database. Enter the following to grant these permissions:

```angular2html
GRANT ALL PRIVILEGES ON *.* to 'admin'@'localhost';
```

Once you have done this you can use the following to flush the privileges so that your database is up to date:
```angular2html
flush privileges;
```

That is everything we need to set up in MySQL so go ahead and log out:
```angular2html
exit;
```

Step 5 – Installing PHP 7.3
===
Install PHP 7.3 by entering the following command:
```angular2html
brew install php@7.3
```

Now once homebrew finishes the installation, you can check your brew services list – you should see php@7.3 stopped.   To start PHP on your localhost type:
```angular2html
brew services start php@7.3
```

__Configuring httpd for php@7.3 module__

If you try to run PHP on Apache as we are right now, PHP would not run. That is why we need to go back to our httpd configuration file found in:
```angular2html
/usr/local/etc/httpd
```

In httpd.conf we need to navigate back to the rewrite_module and add the correct PHP module below it by entering the following:
```angular2html
LoadModule php7_module /usr/local/opt/php@7.3/lib/httpd/modules/libphp7.so
```

There is also one more section that we need to modify: navigate to DirectoryIndex. Specify index.php in the directory index by adding to:
```angular2html
DirectoryIndex index.html
```
So it matches:
```angular2html
DirectoryIndex index.php index.html
```

Step 6 – Installing Composer and Magento 2
--

We want to install Composer our package builder which will allow us to download the Magento 2 environment onto your machine.

__Installing Composer__

We need to make sure we are in our local Apache directory:

```angular2html
/Users/[your user]/www
```
Once you are here, copy the following lines of commands and paste them into your terminal
```angular2html
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
You can also use the current installation method found at:
```angular2html
https://getcomposer.org/download/
```

Once that completes you should see a file name composer.phar We can now install composer by typing
```angular2html
php composer.phar install
```

__Installing Magento 2 Using Composer__

Now that composer is installed we can go ahead and install Magento 2 to our Apache server. I will give you the command for both Magento Open Source and Magento Commerce, although I realize you have to have the proper authentication keys from Magento.

The Magento Open Source command is
```angular2html
composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition magento2
```

Step 7 – Installing Elasticsearch
--
Install elasticsearch by entering the following command:
```angular2html
brew install elasticsearch
```

Now once homebrew finishes the installation, you can check your brew services list – you should see elasticsearch stopped.   To start elasticsearch on your localhost type:
```angular2html
brew services start elasticsearch
```

Step 8 – Permissions and MySQL 8.0 Compatibility Fix
--
__Permissions__
To start we’ll ensure that we allow Magento commands to execute. To do this you need to navigate to your magento2 folder inside of your local Apache directory:
```angular2html
cd ~/www/magento2
```
Once there, we need to enter a simple command that will allow Magento 2’s commands to run:
```angular2html
chmod a+x bin/magento
```
__Increasing PHP memory_limit__
First, we’ll give Magento the correct amount of ram so that there are no memory errors on installation. To do this we will need to access the php.ini file. To find that, type:
```angular2html
php --ini
```
Mine is located at /usr/local/etc/php/7.3/php.ini.  I used a text editor to find a line containing:
```angular2html
memory_limit
```
The default value will not be enough for Magento 2 to set up so we will need to change this to either 4G –  or if you end up still having memory issues -1 which will give Magento 2 as much memory as it needs.
```angular2html
memory_limit = 4G
```
__Fixing MySQL 8.0 Compatibility Errors__

First, we need to navigate to the file Mysql.php found in:
```angular2html
~/magento2/vendor/magento/framework/DB/Adapter/Pdo
```
Open up Mysql.php with your preferred editor and find:
```angular2html
case 'smallint':
```
Directly below this line, you need to add:
```angular2html
case 'smallint unsigned':
```
You also will need to do a similar thing after:
```angular2html
case 'int':
```
Directly below adding:
```angular2html
case 'int unsigned':
```php
protected function _getColumnTypeByDdl($column)
    {
        // phpstan:ignore
        switch ($column['DATA_TYPE']) {
            case 'bool':
                return Table::TYPE_BOOLEAN;
            case 'tinytext':
            case 'char':
            case 'varchar':
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return Table::TYPE_TEXT;
            case 'blob':
            case 'mediumblob':
            case 'longblob':
                return Table::TYPE_BLOB;
            case 'tinyint':
            case 'smallint':
            case 'smallint unsigned':
                return Table::TYPE_SMALLINT;
            case 'mediumint':
            case 'int':
            case 'int unsigned':
                return Table::TYPE_INTEGER;
            case 'bigint':
                return Table::TYPE_BIGINT;
            case 'datetime':
                return Table::TYPE_DATETIME;
            case 'timestamp':
                return Table::TYPE_TIMESTAMP;
            case 'date':
                return Table::TYPE_DATE;
            case 'float':
                return Table::TYPE_FLOAT;
            case 'decimal':
            case 'numeric':
                return Table::TYPE_DECIMAL;
        }
        return null;
    }
```

Step 8 – Launching the Site
---
Let’s begin by restarting all of our brew services so that we can be sure our changes have all been applied:
```
brew services restart httpd
brew services restart mysql
brew services restart php@7.3
```

__The Command-line Way__

Again make sure you are inside of your magento2 directory and then you will enter in the following:

```php
bin/magento setup:install --base-url=http://localhost/magento2/ --db-host=localhost --db-name=magento2 --db-user=admin --db-password=admin123 --admin-firstname=Magento --admin-lastname=Admin --admin-email=user@example.com --admin-user=admin --admin-password=admin123 --language=en_US --currency=USD --timezone=America/Chicago --use-rewrites=1
```

Once this completes it will display your admin URL which will allow you access the backend. You are all set now! To see your website enter the following into your web browser
```php
localhost/magento2
```

After installation finish you can see your admin unique admin url, for example, my admin URL is
```php
localhost/magento2/admin_1d4sxp
```

if you forget the admin url you can find at:
```php
app/etc.env.php
```
inside front name:
```php
'backend' => [
    'frontName' => 'admin_1d4sxp'
],
```
