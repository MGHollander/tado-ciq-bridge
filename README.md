# tado° Garmin Connect IQ Bridge

This [Laravel](https://laravel.com/) based API functions as a bridge between
your tado° smart thermostat and your Garmin wearable.

tado° has an API to control your smart thermostat, but their API is not publicly
documented by tado°. There are several user initiatives to document the API,
such as https://shkspr.mobi/blog/2019/02/tado-api-guide-updated-for-2019/ and
https://documenter.getpostman.com/view/154267/S11Bz2gw.

This API uses the tado° API to get information from tado° and narrows it down to
the bear minimum for the Garmin tado° widget. This helps the widget performance.

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
    "id": "auniquidbytado",
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

```
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
