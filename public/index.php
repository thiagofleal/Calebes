<?php

require __DIR__ . '/../bootstrap.php';

use Tonight\Tools\Request;
use Tonight\Tools\Session;
use Tonight\MVC\Config;
use Tonight\MVC\Router;
use App\Models\Member;

Session::start();

Config::setBaseUrl( 'http://localhost/calebes' );
Config::setModelsNamespace( 'App\\Models' );
Config::setControllersNamespace( 'App\\Controllers' );
Config::setViewsPath( __DIR__ . '/../app/Views/pages' );
Config::setTemplatesPath( __DIR__ . '/../app/Views/templates' );

Config::setUrlGetter( function() {
	$request = Request::getMode();
	return $request->get('url', '');
});

Config::setNotFoundRoute('ErrorController@notFound');

Config::addRoute(
	'',
	'HomeController@index'
);

Config::addRoute(
	'erro/{type}',
	'ErrorController@show'
);
Config::addRoute(
	'erro/{type}/{message}',
	'ErrorController@show'
);

Config::addRoute(
	'login',
	'LoginController@index'
);
Config::addRoute(
	'login/acao',
	'LoginController@action'
);
Config::addRoute(
	'logout/acao',
	'LoginController@logout'
);

Config::addRoute(
	'pontos',
	'PointController@index'
);
Config::addRoute(
	'ponto/cadastrar',
	'PointController@register'
);
Config::addRoute(
	'ponto/acao/cadastrar',
	'PointController@registerAction'
);
Config::addRoute(
	'ponto/{id}/visualizar',
	'PointController@view'
);
Config::addRoute(
	'ponto/{id}/editar',
	'PointController@edit'
);
Config::addRoute(
	'ponto/{id}/acao/editar',
	'PointController@editAction'
);
Config::addRoute(
	'ponto/{id}/excluir',
	'PointController@delete'
);
Config::addRoute(
	'calebe/ponto/{id}/alocar',
	'PointController@addUser'
);

Config::addRoute(
	'calebes',
	'UserController@index'
);
Config::addRoute(
	'calebe/cadastrar',
	'UserController@register'
);
Config::addRoute(
	'calebe/acao/cadastrar',
	'UserController@registerAction'
);
Config::addRoute(
	'calebe/{id}/visualizar',
	'UserController@view'
);
Config::addRoute(
	'calebe/{id}/editar',
	'UserController@edit'
);
Config::addRoute(
	'calebe/{id}/acao/editar',
	'UserController@editAction'
);
Config::addRoute(
	'calebe/{id}/excluir',
	'UserController@delete'
);
Config::addRoute(
	'lider/calebe/{id}/adicionar',
	'UserController@addLeader'
);
Config::addRoute(
	'lider/calebe/{id}/remover',
	'UserController@removeLeader'
);
Config::addRoute(
	'ponto/calebe/{id}/alocar',
	'UserController@addPoint'
);
Config::addRoute(
	'ponto/calebe/{id}/desalocar',
	'UserController@removePoint'
);

Config::addRoute(
	'pesquisas',
	'SearchController@index'
);
Config::addRoute(
	'pesquisa/cadastrar',
	'SearchController@register'
);
Config::addRoute(
	'pesquisa/acao/cadastrar',
	'SearchController@registerAction'
);
Config::addRoute(
	'pesquisa/{id}/editar',
	'SearchController@edit'
);
Config::addRoute(
	'pesquisa/{id}/abrir',
	'SearchController@open'
);
Config::addRoute(
	'pesquisa/{id}/acao/editar',
	'SearchController@editAction'
);
Config::addRoute(
	'pesquisa/{id}/excluir',
	'SearchController@delete'
);

Config::addRoute(
	'pesquisa/{search}/pergunta/adicionar',
	'QuestionController@create'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/acao/adicionar',
	'QuestionController@createAction'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/editar',
	'QuestionController@edit'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/acao/editar',
	'QuestionController@editAction'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/acao/subir',
	'QuestionController@moveUp'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/acao/descer',
	'QuestionController@moveDown'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/excluir',
	'QuestionController@delete'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/acao/adicionar',
	'OptionController@add'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/{option}/editar',
	'OptionController@edit'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/{option}/acao/subir',
	'OptionController@moveUp'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/{option}/acao/descer',
	'OptionController@moveDown'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/{option}/acao/editar',
	'OptionController@editAction'
);
Config::addRoute(
	'pesquisa/{search}/pergunta/{question}/resposta/{option}/acao/remover',
	'OptionController@remove'
);

if (Session::isset('user') && Session::get('user') !== false) {
	$user = Session::get('user');
	Session::set('user', Member::get($user->getId()));
} else {
	Session::set('user', false);
}

try
{
	(new Router())->run();
}
catch(Exception $e)
{
	Router::redirect('erro', 'Exceção não tratada', $e->getMessage());
}