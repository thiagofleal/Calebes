<?php

namespace App\Models;

use PDO;
use Exception;
use PDOException;
use Tonight\Data\DataBase as DB;
use Tonight\MVC\Router;

class DataBase extends DB
{
	public function __construct(...$fields)
	{
		global $dbconfig;

		try
		{
			parent::__construct(...$dbconfig);
			$this->load(...$fields);
		}
		catch(PDOException $e)
		{
			print_log($e->getMessage());
			Router::redirect('erros', 'Conexão com banco de dados', 'Houve um problema na operação do banco de dados');
		}
		catch(Exception $e)
		{
			print_log($e->getMessage());
			Router::redirect('erros', 'Erro interno', 'Houve um erro inesperado');
		}
	}
}