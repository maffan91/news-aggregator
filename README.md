# News Aggregator API

## Steps to run the project

Before running the project, make sure you have docker installed and working correctly

1. Clone the repository.
2. Next, Inside the project you need to run the following docker command to download the dependencies for the first time:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
3. Create `.env` and `.env.testing` file at the root of the repository and copy the contents provided separately.

4. Now run `./vendor/bin/sail up --build` to build and start the services.

5. Run `./vendor/bin/sail composer install`

6. Run `./vendor/bin/sail artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"`

7. Next, you need to run all the migrations using the following commands: `./vendor/bin/sail artisan migrate`

8. Also, run the database seeder: `./vendor/bin/sail artisan db:seed`
9. This seeder will add the pre-configured Categories and Sources.

10. Go to `http://localhost` and see if the project is running. 


### Running Unit Tests
If the project is running correctly, then all tests should pass. Run the tests using the following:
`./vendor/bin/sail artisan test`

### API Documentation
Api documentation is available at: `http://localhost/api/documentation`


#### Caution:
If `localhost` or  any other port is already occupied, you need to change the port in the `docker-compose.yml`
