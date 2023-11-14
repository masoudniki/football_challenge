# Football Challenge


### Main Structure
```php
Services/
|-- Wallet/
|   |-- Database/
|   |   +-- ...
|   |-- Exception/
|   |   +-- ...
|   |-- Http/
|   |   |-- Controllers/
|   |   |   +-- ...
|   |   |-- Requests/
|   |   |   +-- ...
|   |   |-- Resources/
|   |   |   +-- ...
|   |   +-- ...
|   |-- Models/
|   |   +-- ...
|   |-- Providers/
|   |   +-- ...
|   +-- Tests/
|       +-- ...
|-- Charge/
|   |-- Database/
|   |   +-- ...
|   |-- Exception/
|   |   +-- ...
|   |-- Http/
|   |   |-- Controllers/
|   |   |   +-- ...
|   |   |-- Requests/
|   |   |   +-- ...
|   |   |-- Resources/
|   |   |   +-- ...
|   |   +-- ...
|   |-- Models/
|   |   +-- ...
|   |-- Providers/
|   |   +-- ...
|   +-- Tests/
|       +-- ...
+-- ...

```




## How To Run?

to running this project there are two approach:
1. docker

first run the application using:
```php
docker compose up -d
```
then for running migrations and seeders exec to the app and then:
```php
    docker exec -it app_container_id bash
    php artisan migrate
    php artisan db:seed
```
and you are ready to go :)


2. old way :)
first install project dependencies by:
```php
composer install
```
then generate a key by:
```php
php artisan key:generate
```
and after all setup a database in .env file

and running migrations and seeders by:
```php
php artisan migrate

php artisan db:seed
```



### running tests
```php
    php artisan test
```



