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

use Runtime\lib;
use Runtime\BaseObject;
use Runtime\BaseProvider;
use Runtime\Method;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Exceptions\RuntimeException;
use Runtime\ORM\Connection;
use Runtime\ORM\DatabaseSchema;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\Annotations\AutoIncrement;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Annotations\Database;
use Runtime\ORM\Annotations\Primary;
use Runtime\ORM\Annotations\Rule;
use Runtime\ORM\Annotations\Table;
use Runtime\ORM\Factory\ConnectionFactory;


class Provider extends \Runtime\BaseProvider
{
	var $foreign_keys;
	var $connection_list;
	var $annotations;
	
	
	/**
	 * Returns connection
	 */
	function getConnection($name)
	{
		if (!$this->connection_list->has($name))
		{
			throw new \Runtime\Exceptions\ItemNotFound($name, "Connection");
		}
		return $this->connection_list->get($name);
	}
	
	
	/**
	 * Add new connection
	 */
	function addConnection($conn)
	{
		$this->connection_list->set($conn->getName(), $conn);
	}
	
	
	/**
	 * Returns table annotations
	 */
	function getAnotations($table_name)
	{
		if (!$this->annotations->has($table_name)) return new \Runtime\Vector();
		return $this->annotations->get($table_name);
	}
	
	
	/**
	 * Returns fields from table
	 */
	function getFieldType($table_name, $field_name)
	{
		$annotations = static::getAnotations($table_name);
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations[$i];
			if ($annotation instanceof \Runtime\ORM\Annotations\BaseType)
			{
				if ($annotation->name == $field_name)
				{
					return $annotation;
				}
			}
		}
		return null;
	}
	
	
	/**
	 * Add table
	 */
	function addTable($table_name, $annotations)
	{
		$this->annotations->set($table_name, $annotations);
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$rule = $annotations->get($i);
			$rule->table_name = $table_name;
		}
	}
	
	
	/**
	 * Start provider
	 */
	function start()
	{
		parent::start();
		$this->registerTables();
		$this->registerConnections();
	}
	
	
	/**
	 * Register tables
	 */
	function registerTables()
	{
		$items = \Runtime\rtl::getContext()->getEntities("Runtime.ORM.Annotations.Table");
		for ($i = 0; $i < $items->count(); $i++)
		{
			$table = $items[$i];
			$relation_name = $table->name;
			$table_name = (new \Runtime\Method($relation_name, "getTableName"))->apply();
			/* Add schema */
			$rules = (new \Runtime\Method($relation_name, "schema"))->apply();
			$this->addTable($table_name, $rules);
		}
		/* Call register event */
		\Runtime\rtl::getContext()->hook(\Runtime\ORM\DatabaseSchema::REGISTER, new \Runtime\Map([
			"item" => $this,
		]));
	}
	
	
	/**
	 * Register connections
	 */
	function registerConnections()
	{
		$items = \Runtime\rtl::getContext()->getEntities("Runtime.ORM.Factory.ConnectionFactory");
		for ($i = 0; $i < $items->count(); $i++)
		{
			$factory = $items->get($i);
			$factory->registerConnections($this);
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->foreign_keys = new \Runtime\Map();
		$this->connection_list = new \Runtime\Map();
		$this->annotations = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.ORM.Provider"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}