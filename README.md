# Hacker News

Website inspired by Y Combinator's [Hacker News](https://news.ycombinator.com/news). It connects with the official [Hacker News API](https://github.com/HackerNews/API).



## Requirements

* Web server like Apache or Nginx

* PHP >= 7.1

* Composer

  


## Installation

1. Clone or download this repository to your web server.

2. Install the required packages with Composer.

3. Configure your [web server](https://silex.symfony.com/doc/2.0/web_servers.html) to redirect all requests to `index.php` in the webroot.




## Apache

If you are using Apache, make sure `mod_rewrite` is enabled and use the following `.htaccess` file:

```
<IfModule mod_rewrite.c>
    Options -MultiViews
    RewriteEngine On
    #RewriteBase /path/to/app
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```