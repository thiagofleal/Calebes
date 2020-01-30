<?php

namespace App\Models;

use PDO;
use Exception;
use PDOException;
use Tonight\Data\MySQL;
use Tonight\MVC\Router;

class DataBase extends MySQL
{
	public function __construct($fields)
	{
		global $dbconfig;

		try
		{
			$con = array(
				'dbname' => $dbconfig->name,
				'host' => $dbconfig->host
			);
			$user = $dbconfig->user;
			$pass = $dbconfig->pass;
			parent::__construct($con, $user, $pass);
			$this->start($fields);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			Router::redirect('erros', 'Conexão com banco de dados', $e->getMessage());
		}
		catch(Exception $e)
		{
			Router::redirect('erros', 'Erro interno', $e->getMessage());
		}
	}
}