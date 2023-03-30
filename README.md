Notifications preferences micro-service

## Installation
- Install the [WSL with UbuntuV22.04](https://github.com/dynamics-unlimited/wsl-install)
- If Postgresql and its php drivers are not installed in the WSL, install them
- Create a new Postgresql database using the `init-db.sh` script (adjust the user and database name inside)
- From your Ubuntu terminal, navigate to the root of the project, and execute
```
composer install
```
- Then execute the database's migrations
```
php artisan migrate
```
- To add some test data to your database, execute
```
php artisan db:seed
```

## Creating a project from this template
Use composer to create a new project based on this template using:
```
composer create-project dynamics-unlimited/laravel-api-template <local-directory>
```

## Environment variables
Copy/paste .env.example then rename the new file into '.env'. Update the content of the file according to
your environment.

## JWT key

Copy the **public** key for the appropriate environment to `storage/app/keys/public` (public being a file).

## Api documentation
To generate the api documentation use:
```
php artisan l5-swagger:generate
```
The swagger documentation is accessible using this route {{url}}/api/documentation

The redoc documentation is accessible using this route {{url}}/api/redoc

## Notes for deploy
Execute the following command:
```
php artisan l5-swagger:generate
```
