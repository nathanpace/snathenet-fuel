# Snathe.net code

This repo contains everything needed for running snathe.net, using the FuelPHP framework:
- The most recent version of FuelPHP and related files can be found in the `/fuel/core`, `/fuel/packages` and `/fuel/vendor` directories.
- The website code itself can be found in `/fuel/app`.
- The `/assets` directory contains all site assets such as javascript files, css files and images.

## Usage
1. Copy the `/fuel/app/.env.example` file to `/fuel/app/.env` and fill in the missing values for the "home" information (`HOME_LOC` is the place name of your home location), phonecodes database, geocode API and weather API. You can change the default `HOME_TZ` value to any tz time zone identifier. (The filename has already been added to the .gitignore file so this will be automatically ignored if git commits/pushes are made to the repo)
2. Once the .env file is saved, copy everything to the directory which has been set up as the root for the hosting.

Assuming everything is correctly set up on the server, the site should be fully functional once refreshed.

## Notes
If the hosting already has FuelPHP installed, then all that *should* be needed to be copied over to the host are the `/fuel/app` and `/assets` directories, once the relevant '/fuel/app/.env' changes have been done.