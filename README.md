# Snathe.net code

This repo contains everything needed for running snathe.net, using the FuelPHP framework:
- The most recent version of FuelPHP and related files can be found in the `/core`, `/packages` and `/vendor` directories.
- The website code itself can be found in `/app`.
- The `/assets` directory contains all site assets such as javascript files, css files and images.

## Usage
To ensure the site works as expected when deployed, make sure the following tasks are complete:
1. Copy the `/app/.env.example` file to `/app/.env` and fill in the missing values for the "home" information (`HOME_LOC` is the place name of your home location), phonecodes database, geocode API and weather API. You can change the default `HOME_TZ` value to any tz time zone identifier.
2. Move the `/assets` directory to a publicly accessible directory.

## Notes
If the hosting already has FuelPHP installed, then all that *should* be needed here are the `/app` and `/assets` directories.