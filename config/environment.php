<?php

use Tonight\MVC\Config;

Config::setBaseUrl( 'http://localhost/calebes' );
Config::setModelsNamespace( 'App\\Models' );
Config::setControllersNamespace( 'App\\Controllers' );
Config::setViewsPath( __DIR__ . '/../app/Views/pages' );
Config::setTemplatesPath( __DIR__ . '/../app/Views/templates' );