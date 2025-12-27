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
namespace Runtime\ORM\Factory;

use Runtime\BaseObject;
use Runtime\BaseProvider;
use Runtime\Entity\Entity;
use Runtime\ORM\Factory\ConnectionFactory;
use Runtime\ORM\SQLite\ConnectionSQLite;


class SQLiteFactory extends \Runtime\ORM\Factory\ConnectionFactory
{
	var $path;
	
	
	/**
	 * Create connection
	 */
	function createConnection()
	{
		$conn = new \Runtime\ORM\SQLite\ConnectionSQLite($this->name);
		$conn->database = $this->path;
		$conn->connect();
		$conn->executeSQL("PRAGMA journal_mode = WAL;");
		return $conn;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->path = "";
	}
	static function getClassName(){ return "Runtime.ORM.Factory.SQLiteFactory"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}