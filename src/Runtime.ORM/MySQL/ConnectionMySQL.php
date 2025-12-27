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
namespace Runtime\ORM\MySQL;

use Runtime\io;
use Runtime\re;
use Runtime\ORM\Factory\CursorFactory;
use Runtime\ORM\Connection;
use Runtime\ORM\Cursor;
use Runtime\ORM\Query;
use Runtime\ORM\QueryField;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\Exceptions\OrmException;
use Runtime\ORM\MySQL\CursorMySQL;
use Runtime\ORM\MySQL\SQLBuilder;


class ConnectionMySQL extends \Runtime\ORM\Connection
{
	var $host;
	var $port;
	var $login;
	var $password;
	var $database;
	var $prefix;
	var $connect_error;
	var $pdo;
	var $is_transaction;
	
	
	/**
	 * Constructor
	 */
	function __construct($name = "")
	{
		parent::__construct($name);
		$this->cursor = new \Runtime\ORM\Factory\CursorFactory("Runtime.ORM.MySQL.CursorMySQL");
	}
	
	
	/**
	 * Connect
	 */
	function connect()
	{
		$this->connect_error = "";
		try
		{
			$str = 'mysql:host='.$this->host;
			if ($this->port != null) $str .= ':'.$this->port;
			if ($this->database != null) $str .= ';dbname='.$this->database;
			$this->pdo = new \PDO(
				$str, $this->login, $this->password, 
				array(
					\PDO::ATTR_PERSISTENT => false
				)
			);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->pdo->exec("set names utf8");
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
	 * Connect
	 */
	function isConnected()
	{
		return $this->pdo != null;
	}
	
	
	/**
	 * Create SQLBuilder
	 */
	function createBuilder($q){ return new \Runtime\ORM\MySQL\SQLBuilder($this, $q); }
	
	
	/**
	 * Fork connection
	 */
	function fork()
	{
		$connection = parent::fork();
		$connection->host = $this->host;
		$connection->port = $this->port;
		$connection->login = $this->login;
		$connection->password = $this->password;
		$connection->database = $this->database;
		$connection->prefix = $this->prefix;
		$connection->connect_error = $this->connect_error;
		$connection->pdo = $this->pdo;
		return $connection;
	}
	
	
	/**
	 * Add to query log
	 */
	function logQuery($params = null)
	{
		if ($params == null) $params = new \Runtime\Map();
		$q = $params->get("query");
		$builder = $params->get("builder", false);
		/* Check is debug */
		$is_debug = $params->get("debug", false);
		if ($q != null && $q->_debug)
		{
			$is_debug = true;
		}
		/* Is debug */
		if (!$is_debug && !$this->log) return;
		/* Get SQL query */
		$sql_debug = "";
		if ($builder != null) $sql_debug = $builder->formatSQL();
		else if ($params->has($sql_debug)) $sql_debug = $params->get("sql_debug");
		/* Print debug SQL */
		if ($is_debug)
		{
			\Runtime\io::print($sql_debug);
		}
		/* Query log */
		if ($this->log)
		{
			$this->log->push(new \Runtime\Map([
				"query" => $params->get("query"),
				"builder" => $params->get("builder"),
				"sql" => $sql_debug,
			]));
		}
	}
	
	
	/**
	 * Execute Query
	 */
	function execute($q, $params = null)
	{
		if ($params == null) $params = new \Runtime\Map();
		/* Build query */
		$builder = $this->createBuilder($q)->build();
		/* Check builder correct */
		if (!$builder->isValid())
		{
			throw new \Runtime\ORM\Exceptions\OrmException("SQL builder is not correct");
		}
		/* Log query */
		$this->logQuery($params->concat(new \Runtime\Map([
			"builder" => $builder,
			"query" => $q->copy(),
		])));
		/* Create cursor */
		$cursor = $this->createCursor();
		$cursor->q = $q->copy();
		/* Execute sql */
		if ($cursor instanceof \Runtime\ORM\MySQL\CursorMySQL)
		{
			$cursor = $cursor->executeSQL($builder);
		}
		return $cursor;
	}
	
	
	/**
	 * Execute Query
	 */
	function executeSQL($sql, $data = null, $params = null)
	{
		if ($params == null) $params = new \Runtime\Map();
		/* Build query */
		$builder = $this->createBuilder(null);
		$builder->sql = $sql;
		$builder->data = $data;
		/* Log query */
		$this->logQuery($params->concat(new \Runtime\Map([
			"builder" => $builder,
		])));
		/* Create cursor */
		$cursor = $this->createCursor();
		/* Execute sql */
		if ($cursor instanceof \Runtime\ORM\MySQL\CursorMySQL)
		{
			$cursor = $cursor->executeSQL($builder);
		}
		return $cursor;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->host = "";
		$this->port = "";
		$this->login = "";
		$this->password = "";
		$this->database = "";
		$this->prefix = "";
		$this->connect_error = "";
		$this->pdo = null;
		$this->is_transaction = false;
	}
	static function getClassName(){ return "Runtime.ORM.MySQL.ConnectionMySQL"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}