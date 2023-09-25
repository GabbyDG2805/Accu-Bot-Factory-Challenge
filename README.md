# The Accu Bot Factory Challenge

This coding challenge is part of the interview process for the PHP Developer position at Accu. The purpose of the challenge is to assess your skills and expertise in PHP and Laravel, and to evaluate your ability to build a web application that uses external data sources. The challenge requires you to build a web application that loads order data from a CSV file and component data from an engineering component API, and uses this information to generate amusing robot names based on the most prevalent category of components in each order. You will also need to display the results in a table on a web page. 

## Installation & Setup (Windows)
### 1. XAMPP
Download [XAMPP](https://www.apachefriends.org/download.html) which is free and open-source web server solution stack that includes PHP, MySQL, and Apache. 

Run the installer and follow the on-screen instructions. During the installation process, make sure to select PHP, MySQL, and PHPMyAdmin when promoted to choose which components to install.

Once installed, launch the XAMPP Control Panel and start the Apache and MySQL services.

### 2. Composer
Composer is a dependency management tool for PHP that is required to install Laravel.

Visit the [Composer download page](https://getcomposer.org/download/) and download the Composer-Setup.exe file.

Run the Composer-Setup.exe file and follow the on-screen instructions. You’ll be prompted to select the install mode - be sure to pick Install for all users (recommended).

Make sure to select the correct PHP executable during the installation process (usually located in the XAMPP installation folder under xampp/php/php.exe) and add this PHP to your Path.

Click Next to move through the on-screen instructions then click Install.

Once installation is completed, click Finish.

To verify that Composer was installed correctly, open the Command Prompt and run `composer --version`

If the installation was successful, you should see the Composer version displayed.

### 3. Database

In the XAMPP Control Panel, click the Admin button by MySQL. This will take you to PHPMyAdmin in your browser.

Click the Databases tab at the top, then create a new database with the exact name "accu_bot_factory_challenge".

### 4. Project

Clone the project from [this repository](https://github.com/GabbyDG2805/Accu-Bot-Factory-Challenge) by clicking the green Code dropdown button and then clicking Download ZIP.

Once downloaded, extract the zip somewhere on your computer.

In the Command Prompt, CD to the project directory.

Run `move .env.example .env`.

Then, run `composer install`.

Once Composer is successfully installed, run `php artisan key:generate`.

Once the application key is set successfully, run `php artisan migrate:fresh`.

## Usage

If not already, CD to the project directory in the Command Prompt.

To import the orders, run `php artisan import:orders orders.csv` (the csv is included within the project files). This command imports the data from the csv and also from the API, and makes the necessary calculations in order to generate robot names.

Once the order data has imported successfully, run `php artisan serve` which should inform you that the server is running on [http://127.0.0.1:8000](http://127.0.0.1:8000) which you can now access in your browser.

On the webpage, you should be able to see the orders, search amongst them using either an ID number, a customer name or robot name (which can be partial e.g. surname only), navigate to view details for each one and change each robot name.

### Tests:

PHPUnit Tests were created for this project. If the server is running, stop it by pressing Ctrl + C.

To run the tests, execute the command `php artisan test`.

In just a couple of seconds, you should be able to see that 9 tests have passed with 40 assertions.
