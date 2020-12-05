# tado° Garmin Connect IQ Bridge

This [Laravel](https://laravel.com/) based API makes it possible to control your 
tado° smart thermostat from your Garmin device. 

## TODO

- Return a json 404 when an unknown route is called via the API.
- Return unauthorized error instead of redirecting to login page when the
  api_token is invalid. This is handled in the Authenticate middleware, using a 
  redirectTo function. This function should probably also change...
- Add a task to clean up the sessions table. Remove sessions that have not been 
  updated for an X period (depending on the period a refresh token is valid).
