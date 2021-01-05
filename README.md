# tado° Garmin Connect IQ Bridge

This [Laravel](https://laravel.com/) based API functions as a bridge between
your tado° smart thermostat and your Garmin wearable.

tado° has an API to control your smart thermostat, but their API is not publicly
documented by tado°. There are several user initiatives to document the API,
such as <https://shkspr.mobi/blog/2019/02/tado-api-guide-updated-for-2019/> and
<https://documenter.getpostman.com/view/154267/S11Bz2gw>.

This API uses the tado° API to get information from tado° and narrows it down to
the bear minimum for the Garmin tado° widget. This helps the widget performance.

## Table of content <!-- omit in toc -->

- [Scheduler](#scheduler)
- [API documentation](#api-documentation)
  - [Get user information](#get-user-information)
  - [Get data from all zones in a home](#get-data-from-all-zones-in-a-home)
- [Contribution](#contribution)
- [Deployment](#deployment)

## Scheduler

The API uses the scheduler. Add the following cron entry to your server to
enable it.

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## API documentation

You need an API token to use this API. A token will be created when you are
asked to login on your Garmin wearable. Every call needs the token as a GET
parameter, like `/api/me?api_token=YOUR_API_TOKEN`.

### Get user information

```txt
/api/me
```

returns

```json
{
    "id": "4un1qu31d6y74d0",
    "username": "email@example.com",
    "name": "Your Name",
    "email": "email@example.com",
    "homes": [
        {
            "id": 12345,
            "name": "My home"
        }
    ]
}
```

### Get data from all zones in a home

This call combines multiple tado° API calls and returns data from all zones in a
home. Normally this takes multiple calls. One to get the zones in a home and one
for each zone to get the zone information.

```txt
/zones/{homeId}
```

returns

```json
[
    {
        "name": "Living room",
        "temperature": {
            "celsius": 13.93,
            "fahrenheit": 57.07,
            "setting": {
                "celsius": 14,
                "fahrenheit": 57.2
            }
        },
        "humidity": 65.3
    },
    {
        "name": "Bedroom",
        "temperature": {
            "celsius": 14.54,
            "fahrenheit": 58.17,
            "setting": {
                "celsius": 14,
                "fahrenheit": 57.2
            }
        },
        "humidity": 66.6
    }
]
```

## Contribution

This project use Laravel Envoy for build and deployment tasks

Run the following commands from the project root in the terminal to build and
deploy for local used and development.

```bash
bash scripts/build.sh
bash scripts/deploy.sh
```

Add `@prod` as the first argument for both scripts to prepare it for use on a
production environment. Usage of both scripts is available via the `-h` option.

Run the following command to watch for changes in front-end files and
automatically build the compiled files when changes are made.

```bash
npm run watch
```

## Deployment

Automated deployments are configure using [CodeShip](https://app.codeship.com/).
The following steps need to be taken to trigger a deployment.

1. Checkout the `develop` branch and make sure you have al the latest
changes.

    ```bash
    git checkout develop
    git pull origin develop
    ```

2. Create a release branch with the new version. Replace x.x.x with the new
version. Run `git describe --tags $(git rev-list --tags --max-count=1)` to get
the last release tag, if you don't know the last version and bump the version.

    ```bash
    git checkout -b release-x.x.x develop
    ```

3. Create a tag for the new release. Replace both instances of x.x.x with the
new version.

    ```bash
    git tag x.x.x -am "Version x.x.x"
    ```

4. Push the branch and tag to GitHub. The deployment will be trigger
automatically.

    ```bash
    git push origin release-x.x.x
    ```
