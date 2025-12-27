<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2024 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime\ORM\SQLite;

use Runtime\ORM\Exceptions\OrmException;
use Runtime\ORM\MySQL\ConnectionMySQL;


class ConnectionSQLite extends \Runtime\ORM\MySQL\ConnectionMySQL
{
	var $path;
	
	
	/**
	 * Connect
	 */
	function connect()
	{
		$this->connect_error = "";
		try
		{
			$str = 'sqlite:'.$this->database;
			$this->pdo = new \PDO($str);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		catch (\PDOException $e)
		{
			$this->connect_error = $e->getMessage();
			throw new \Runtime\ORM\Exceptions\OrmException('Failed connected to database!');
		}
		catch (\Excepion $e)
		{
			$this->connect_error = $e->getMessage();
		}
		return $this;
	}
	
	
	/**
	 * Fork connection
	 */
	function fork()
	{
		$connection = parent::fork();
		$connection->path = $this->path;
		return $connection;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->path = "";
	}
	static function getClassName(){ return "Runtime.ORM.SQLite.ConnectionSQLite"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}