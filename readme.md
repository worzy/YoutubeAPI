# YouTube API

## Local Development

**Application base URL:** `youtubeapi.localhost`

The local environment requires Docker installed on the host machine, then there is a Makefile in the root of the project to easier setup and run the project, this includes running migrations and seeding the database with the required data to run the application.  The only manual step is to add the YouTube API key to the .env file, as I didn't think it was wise to add it to a public repo.

### Setup

* `make install` - Only needs to run once to install everything
* `make start` - Run it after the installation to start the environment  

### Commands to interact with the environment

* `make start` - Starts your environment
* `make stop` - Stops the environment
* `make down` - Remove all containers
* `make destroy` - Destroy entire enviornment

### Testing

This is automatically setup with: `make install`

* Run all tests `docker-compose exec php-fpm t` <-- alias to run `phpunit` from within the `/vendor` folder.

## Endpoints

All endpoints require that the `Accept` header is set to `application/json`.

`/videos/store` - Fetches all videos based on filter criteria

`/videos` - Fetches all videos

`/videos/{id}`- Fetches video from database by ID

`/video/{id}` - Deletes video from database by ID

`/video/search?q={query}` - Searches database by keyword