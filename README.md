#### Server Requirements
1. PHP >= 7.3.0 (and meet [Laravel 8.x server requirements](https://laravel.com/docs/8.x/deployment#server-requirements)),
2. MySQL or MariaDB database,
3. SQlite (for automated testing).

#### Installation Steps

1. Clone the repo : `git clone https://github.com/nafiesl/free-pmo.git`
2. `$ cd free-pmo`
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. Create new MySQL database for this application
7. Set database credentials on `.env` file
8. `$ php artisan migrate`
9. `$ php artisan storage:link`
10. `$ php artisan serve`
11. Visit `http://localhost:8000/app-install` via web browser
12. Fill out the forms
13. Done, you are logged in as Administrator.