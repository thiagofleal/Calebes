<?php

require __DIR__ . '/../bootstrap.php';

use Tonight\Server\Request;
use Tonight\Server\Session;
use Tonight\MVC\Config;
use Tonight\MVC\Router;

Session::start();

Config::setBaseUrl( 'http://localhost/calebes' );
Config::setModelsNamespace( 'App\\Models' );
Config::setControllersNamespace( 'App\\Controllers' );
Config::setViewsPath( __DIR__ . '/../app/Views/pages' );
Config::setTemplatesPath( __DIR__ . '/../app/Views/templates' );

Config::setUrlGetter( function() {
	$request = new Request(Request::GET);
	return $request->get('url', '');
});

Config::setNotFoundRoute('ErrorController@notFound');

Config::addRoute('', 'HomeController@index');

Config::addRoute('erro/{type}', 'ErrorController@show');
Config::addRoute('erro/{type}/mensagem/{message}', 'ErrorController@show');

Config::addRoute('login', 'LoginController@index');
Config::addRoute('login/acao', 'LoginController@action');
Config::addRoute('logout/acao', 'LoginController@logout');

Config::addRoute('pontos', 'PointController@index');
Config::addRoute('ponto/cadastrar', 'PointController@register');
Config::addRoute('ponto/acao/cadastrar', 'PointController@registerAction');
Config::addRoute('ponto/{id}/visualizar', 'PointController@view');
Config::addRoute('ponto/{id}/editar', 'PointController@edit');
Config::addRoute('ponto/{id}/acao/editar', 'PointController@editAction');
Config::addRoute('ponto/{id}/excluir', 'PointController@delete');

Config::addRoute('calebes', 'UserController@index');
Config::addRoute('calebe/cadastrar', 'UserController@register');
Config::addRoute('calebe/acao/cadastrar', 'UserController@registerAction');
Config::addRoute('calebe/{id}/visualizar', 'UserController@view');
Config::addRoute('calebe/{id}/editar', 'UserController@edit');
Config::addRoute('calebe/{id}/acao/editar', 'UserController@editAction');
Config::addRoute('calebe/{id}/excluir', 'UserController@delete');
Config::addRoute('lider/{id}/adicionar', 'UserController@addLeader');
Config::addRoute('lider/{id}/remover', 'UserController@removeLeader');
Config::addRoute('ponto/{id}/alocar', 'UserController@addPoint');
Config::addRoute('ponto/{id}/desalocar', 'UserController@removePoint');
/*
Config::addRoute('pesquisa/cadastrar', 'SeachController@register');
Config::addRoute('pesquisa/{id}/visualizar', 'SearchController@view');
Config::addRoute('pesquisa/{id}/editar', 'SearchController@edit');
Config::addRoute('pesquisa/{id}/excluir', 'SearchController@delete');
*/
if (!Session::isset('user')) {
	Session::set('user', false);
}

try
{
	(new Router())->run();
}
catch(Exception $e)
{
	Router::redirect('erro', 'Exceção não tratada', 'mensagem', $e->getMessage());
}