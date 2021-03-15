# Popular GitHub (vcs) Api Service
This API Service is created using Laravel 8. It has Search module to list public repositories for github at first.

#### Before you start, you need to have:
php >= 7.3
composer

#### Following are the Modules
* Search

#### Usage
Clone the project via git clone or download the zip file.

##### .env
Copy contents of .env.example file to .env file. you set some settings for github open api as GITHUB_API_URL & GITHUB_RESULTS_PAGINATE as default value in .env file.
##### Composer Install
cd into the project directory via terminal and run the following  command to install composer packages.
###### `composer install`
##### Generate Key
then run the following command to generate fresh key.
###### `php artisan key:generate`

There is no need to run migrations or seeds as they aren't used in system till now.
### API EndPoints
##### Search Repositories
* User GET `http://localhost:8000/api/v1/search/repositories`

This is the only endpoint for now, we can in future add more than of them

##### Tests
to run the unit tests for search repositories endpoint you can use the following command
using in this phpunit framework
###### `vendor/bin/phpunit --testdox --filter SearchRepositoriesTest`
