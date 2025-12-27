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
namespace Runtime\ORM;

use Runtime\BaseObject;
use Runtime\Method;
use Runtime\ORM\Connection;
use Runtime\ORM\Query;


class BaseMigration extends \Runtime\BaseObject
{
	var $name;
	var $connection;
	var $required;
	var $migrations;
	var $up;
	var $down;
	
	
	/**
	 * Create migration
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Set connection
	 */
	function setConnection($connection)
	{
		$this->connection = $connection;
	}
	
	
	/**
	 * Returns name
	 */
	function getName(){ return $this->name; }
	
	
	/**
	 * Returns required migrations
	 */
	function getRequired(){ return $this->required->slice(); }
	
	
	/**
	 * Returns migrations
	 */
	function getMigrations(){ return $this->migrations->slice(); }
	
	
	/**
	 * Returns migrations
	 */
	function buildMigrations()
	{
		$current_name = $this->name;
		$result = new \Runtime\Vector();
		$prev_required = $this->getRequired();
		/* Add child migrations */
		$items = $this->getMigrations();
		for ($i = 0; $i < $items->count(); $i++)
		{
			$migration_name = $items->get($i);
			$f = new \Runtime\Method($this, $items->get($i));
			if (!$f->exists()) continue;
			/* Get migration */
			$migration = $f->apply();
			$migration->name = $this->name . "." . $migration_name;
			$migration->required = $migration->required->concat($prev_required);
			$prev_required = new \Runtime\Vector($migration->name);
			/* Build migrations */
			$result->appendItems($migration->buildMigrations());
			/* Add new migration */
			$result->push($migration);
		}
		/* Change require */
		$this->required = $prev_required;
		return $result;
	}
	
	
	/**
	 * Comment
	 */
	function comment($text)
	{
		$this->executeSQL("-- " . $text);
	}
	
	
	/**
	 * Execute raw SQL
	 */
	function executeSQL($sql, $data = null)
	{
		if ($sql instanceof \Runtime\Vector)
		{
			$sql = \Runtime\rs::join("\n", $sql);
		}
		$q = new \Runtime\ORM\Query();
		$q->raw($sql, $data);
		$this->connection->execute($q);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->connection = null;
		$this->required = new \Runtime\Vector();
		$this->migrations = new \Runtime\Vector();
		$this->up = null;
		$this->down = null;
	}
	static function getClassName(){ return "Runtime.ORM.BaseMigration"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}