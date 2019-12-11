<p align="center"><img src="https://musario.ml/pic/land2.png"></p>

# About Musario

In addition to the standard features of a music player (creating and editing playlists, editing the playback order in playlists, editing songs), it also includes such features as:

- **Everyday**. It is a playlist that is formed on the basis of the songs you listen to and is updated every day. If you like a song you can add it to your playlists or / and download it.
- **Search**. Provides the ability to search for songs on the Internet and, as well as Everyday download them and add to their playlists.
- **Upload**. Allows you to upload songs in mp3 format to your playlists.
- **UpTube**. Allows you to download sound from video from YouTube using url and add it to playlists.

You can also see an example [musario.ml](https://musario.ml "musario.ml").

# Documentation
Swagger located at /documentation. You can also look [here](https://musario.ml/documentation "musario.ml/documentation").

# Requirements
- php 7.2.2
- the file .env must have a constant `PYTHON_PATH=''`, if you want to use the standard path leave an empty string (like a '')

# Installation

First run the composer, it will load everything you need and generate an autoloader.
```bash
composer install
```

Perform npm update
```bash
npm update
```

Setup using artisan

```bash
php artisan migrate
php artisan passport:install
php artisan storage:link
```

Generate Vue components

```bash
npm run production
```

Install components for upTube

```bash
sudo apt-get install python-pip
sudo pip install youtube-dl
sudo apt install ffmpeg
```

And do not forget to increase the limits in php.ini !
