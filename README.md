MVC Framework
===

## Known issues

* There is an issue with the .htaccess acting weired when you add a / to the end of a url.

## Tools used for this project

* docker-compose - Used for local development server. 
* Sass/scss - A css precompiler which offers additional functionality to css
* Browsersync - A tool used to auto-reload and auto-inject changes in to the browser
* Gulp - A tool which runs repetative tasks for you automatically.

## Notes

* Though there is a composer.json file in this project, composer is NOT used. It is simply their to satisfy PHPStrom complaining about missing extensions.
* The view engine dies when rendering larges pages. Temp solution; paginate :D
