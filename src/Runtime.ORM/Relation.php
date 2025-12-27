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
use Runtime\BaseStruct;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Exceptions\RuntimeException;
use Runtime\Serializer\MapType;
use Runtime\ORM\Connection;
use Runtime\ORM\Cursor;
use Runtime\ORM\DatabaseSchema;
use Runtime\ORM\Provider;
use Runtime\ORM\Query;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\QueryResult;
use Runtime\ORM\Record;
use Runtime\ORM\Annotations\AutoIncrement;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Annotations\ForeignKey;
use Runtime\ORM\Annotations\Primary;


class Relation extends \Runtime\BaseObject
{
	var $connection;
	
	
	/**
	 * Constructor
	 */
	function __construct($params = null)
	{
		parent::__construct();
		if ($params && $params->has("connection")) $this->connection = $params->get("connection");
		else $this->connection = \Runtime\ORM\Connection::get($params ? $params->get("connection_name", "default") : "default");
	}
	
	
	/**
	 * Returns table name
	 */
	static function getTableName(){ return ""; }
	
	
	/**
	 * Returns connection;
	 */
	function getConnection(){ return $this->connection; }
	
	
	/**
	 * Set connection
	 */
	function setConnection($connection)
	{
		$this->connection = $connection;
	}
	
	
	/**
	 * Create Instance of class
	 */
	function createRecord($data = null)
	{
		$instance = new \Runtime\ORM\Record($data, $this);
		return $instance;
	}
	
	
	/**
	 * Returns table annotations
	 */
	function getAnotations()
	{
		$provider = \Runtime\rtl::getContext()->provider("Runtime.ORM.Provider");
		return $provider->getAnotations(static::getTableName());
	}
	
	
	/**
	 * To database
	 */
	function toDatabase($data)
	{
		if ($data == null) return null;
		$annotations = $this->getAnotations();
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations->get($i);
			if ($annotation instanceof \Runtime\ORM\Annotations\BaseType && $data->has($annotation->name))
			{
				$data = $annotation->toDatabase($this->connection, $data);
			}
		}
		return $data;
	}
	
	
	/**
	 * From database
	 */
	function fromDatabase($data)
	{
		if ($data == null) return null;
		$annotations = $this->getAnotations();
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations->get($i);
			if ($annotation instanceof \Runtime\ORM\Annotations\BaseType && $data->has($annotation->name))
			{
				$data = $annotation->fromDatabase($this->connection, $data);
			}
		}
		return $data;
	}
	
	
	/**
	 * Returns true if primary key is auto increment
	 */
	function getAutoIncrement()
	{
		$annotations = $this->getAnotations();
		$annotation = $annotations->find(function ($item){ return $item instanceof \Runtime\ORM\Annotations\AutoIncrement; });
		return $annotation;
	}
	
	
	/**
	 * Returns primary rules
	 */
	function getPrimaryRules()
	{
		$rules = new \Runtime\Serializer\MapType();
		$annotations = $this->getAnotations();
		$primary = $annotations->find(function ($item){ return $item instanceof \Runtime\ORM\Annotations\Primary; });
		if ($primary == null) return $rules;
		$annotations = $annotations->filter(function ($item) use (&$primary)
		{
			if (!($item instanceof \Runtime\ORM\Annotations\BaseType)) return false;
			if ($primary->keys->indexOf($item->name) == -1) return false;
			return true;
		});
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations->get($i);
			$rule = $annotation->getRule();
			if (!$rule)
			{
				throw new \Runtime\Exceptions\RuntimeException("Rule is not defined");
			}
			$rules->addType($annotation->name, $rule);
		}
		return $rules;
	}
	
	
	/**
	 * Returns primary keys of table
	 */
	function getPrimaryKeys()
	{
		/* Get primary annotation */
		$annotations = $this->getAnotations();
		$primary = $annotations->find(function ($item){ return $item instanceof \Runtime\ORM\Annotations\Primary; });
		if ($primary == null) return null;
		return $primary->keys;
	}
	
	
	/**
	 * Returns primary data
	 */
	function getPrimaryKey($data)
	{
		/* Get primary annotation */
		$primary_keys = $this->getPrimaryKeys();
		/* Check if primary keys if exists */
		if ($primary_keys == null)
		{
			$table_name = static::getTableName();
			throw new \Runtime\Exceptions\RuntimeException("Primary key does not exists in " . $table_name);
		}
		/* Intersect values */
		$pk = new \Runtime\Map();
		for ($i = 0; $i < $primary_keys->count(); $i++)
		{
			$field_name = $primary_keys->get($i);
			$pk->set($field_name, $data ? $data->get($field_name) : null);
		}
		return $pk;
	}
	
	
	/**
	 * Returns primary filter by data
	 */
	function getPrimaryFilter($data, $use_full_key = true)
	{
		$table_name = static::getTableName();
		$pk = $this->getPrimaryKey($data);
		$filter = $pk->transition(function ($value, $key) use (&$table_name, &$use_full_key)
		{
			$item = new \Runtime\ORM\QueryFilter();
			$item->key = $key;
			$item->op = "=";
			$item->value = $value;
			if ($use_full_key)
			{
				$item->key = $table_name . "." . $key;
			}
			return $item;
		});
		if ($filter->count() == 0)
		{
			throw new \Runtime\Exceptions\RuntimeException("Primary key does not exists in " . $table_name);
		}
		return $filter;
	}
	
	
	/**
	 * Save model
	 */
	function save($item, $params = null)
	{
		$table_name = static::getTableName();
		/* Call before save */
		\Runtime\rtl::getContext()->hook(\Runtime\ORM\DatabaseSchema::SAVE_BEFORE, new \Runtime\Map([
			"table_name" => $table_name,
			"relation" => $this,
			"item" => $item,
		]));
		$is_update = $item->isUpdate();
		$updated_data = $item->getUpdatedData();
		/* Prepare */
		$annotations = $this->getAnotations();
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations->get($i);
			if ($annotation instanceof \Runtime\ORM\Annotations\BaseType)
			{
				$updated_data = $annotation->prepare($updated_data, $is_update);
			}
		}
		if ($is_update)
		{
			$updated_data_keys = \Runtime\rtl::list($updated_data->keys());
			if ($updated_data_keys->count() > 0)
			{
				$filter = $this->getPrimaryFilter($item->old(), false);
				if ($filter->count() > 0)
				{
					$db_updated_data = $this->toDatabase($updated_data);
					$this->connection->update($table_name, $filter, $db_updated_data, $params);
				}
				else
				{
					throw new \Runtime\Exceptions\RuntimeException("Primary key does not exists in " . $table_name);
				}
				for ($i = 0; $i < $updated_data_keys->count(); $i++)
				{
					$field_name = $updated_data_keys[$i];
					$item->set($field_name, $updated_data->get($field_name));
				}
			}
		}
		else
		{
			$db_updated_data = $this->toDatabase($updated_data);
			$last_id = $this->connection->insert($table_name, $db_updated_data, true, $params);
			$auto_increment = $this->getAutoIncrement($table_name);
			if ($auto_increment && $auto_increment->name)
			{
				$item->set($auto_increment->name, $last_id);
			}
		}
		$item->_initData($item->all());
		/* Call before after */
		\Runtime\rtl::getContext()->hook(\Runtime\ORM\DatabaseSchema::SAVE_AFTER, new \Runtime\Map([
			"table_name" => $table_name,
			"relation" => $this,
			"item" => $item,
		]));
	}
	
	
	/**
	 * Delete model
	 */
	function delete($item, $params = null)
	{
		/* Get primary filter */
		$table_name = static::getTableName();
		$filter = $this->getPrimaryFilter($item->old(), false);
		/* Delete record */
		if ($filter->count() > 0)
		{
			$this->connection->delete($table_name, $filter, $params);
		}
		else
		{
			throw new \Runtime\Exceptions\RuntimeException("Primary key does not exists in " . $table_name);
		}
	}
	
	
	/**
	 * Refresh model from database
	 */
	function refresh($item, $params = null)
	{
		$new_item = $this->findByPk($item, $params);
		if ($new_item)
		{
			$item->_initData($new_item->all());
		}
	}
	
	
	/**
	 * Returns query
	 */
	function query(){ return (new \Runtime\ORM\Query())->from(static::getTableName()); }
	
	
	/**
	 * Returns select query
	 */
	function select($fields = null)
	{
		$table_name = static::getTableName();
		if ($fields == null) $fields = new \Runtime\Vector($table_name . ".*");
		$q = (new \Runtime\ORM\Query())->select($fields)->from($table_name);
		return $q;
	}
	
	
	/**
	 * Fetch all
	 */
	function fetchAll($q, $params = null)
	{
		$c = $this->connection->execute($q, $params);
		$items = $c->fetchAll();
		$c->close();
		return $items;
	}
	
	
	/**
	 * Fetch all records
	 */
	function fetchAllRecords($q, $params = null)
	{
		$result = $this->fetchAll($q);
		return $result->map(function ($row){ return $this->createRecord($row); });
	}
	
	
	/**
	 * Fetch
	 */
	function fetch($q, $params = null)
	{
		$q->limit(1);
		$c = $this->connection->execute($q, $params);
		$item = $c->fetch();
		$c->close();
		return $item;
	}
	function fetchOne($q, $params = null){ return $this->fetch($q, $params); }
	
	
	
	/**
	 * Fetch record
	 */
	function fetchRecord($q, $params = null)
	{
		$row = $this->fetch($q, $params);
		return $row ? $this->createRecord($row) : null;
	}
	
	
	/**
	 * Find rows by filter
	 */
	function find($filter, $params = null)
	{
		$q = $this->select()->setFilter($filter);
		return $this->connection->fetchAll($q, $params);
	}
	
	
	/**
	 * Find relation by filter
	 */
	function findRecord($filter, $params = null)
	{
		$q = $this->select()->setFilter($filter)->limit(1);
		return $this->connection->fetchRecord($q, $params);
	}
	
	
	/**
	 * Find relation by primary key
	 */
	function findByPk($pk, $params = null)
	{
		/* Returns primary filter */
		$filter = $this->getPrimaryFilter($pk, false);
		/* Find record */
		return $this->findRecord($filter, $params);
	}
	
	
	/**
	 * Find relation by object
	 */
	function findById($id, $params = null)
	{
		/* Get primary keys */
		$pk = $this->getPrimaryKeyNames();
		$pk_name = $pk->get(0);
		/* Build filter */
		$filter = new \Runtime\Vector(
			new \Runtime\ORM\QueryFilter($pk_name, "=", $id),
		);
		/* Find record */
		return $this->findRecord($filter, $params);
	}
	
	
	/**
	 * Find or create
	 */
	function findOrCreate($filter, $params = null)
	{
		$class_name = static::getClassName();
		/* Build filter */
		$search = $filter->transition(function ($value, $key)
		{
			return new \Runtime\ORM\QueryFilter($key, "=", $value);
		});
		/* Find item */
		$record = $this->findRecord($search, $params);
		if ($record) return $record;
		/* Create if not found */
		$record = $this->createRecord();
		/* Set new data */
		$record->setData($filter);
		return $record;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->connection = null;
	}
	static function getClassName(){ return "Runtime.ORM.Relation"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}