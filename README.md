# Installation

## Setup

#### Build docker images
````sh
docker compose build
````

#### Start docker containers
````sh
docker compose up -d
````

#### Start docker containers
````sh
docker exec --user www-data laravel-app composer install
````

#### Create .env file base on .env.example
````sh
cp scripts/src/.env.example scripts/src/.env
````

#### Generate application key
````sh
docker exec --user www-data laravel-app php artisan key:generate
````

#### Run migration and seeders
````sh
docker exec --user www-data laravel-app php artisan migrate --seed
````

### Optional

#### Start laravel scheduler
````sh
docker exec --user www-data laravel-app php artisan schedule:work
````
