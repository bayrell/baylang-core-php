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
use Runtime\Exceptions\RuntimeException;
use Runtime\BaseObject;
use Runtime\BaseStruct;
use Runtime\Callback;
use Runtime\Math;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Annotations\Primary;
use Runtime\ORM\Connection;
use Runtime\ORM\Cursor;
use Runtime\ORM\Driver;
use Runtime\ORM\ForeignKey;
use Runtime\ORM\Helper;
use Runtime\ORM\Provider;
use Runtime\ORM\QueryField;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\Record;


class Query extends \Runtime\BaseObject
{
	const QUERY_RAW = "raw";
	const QUERY_SELECT = "select";
	const QUERY_INSERT = "insert";
	const QUERY_UPDATE = "update";
	const QUERY_DELETE = "delete";
	const QUERY_INSERT_OR_UPDATE = "insert_or_update";
	
	var $_kind;
	var $_table_name;
	var $_table_alias;
	var $_fields;
	var $_join;
	var $_order;
	var $_filter;
	var $_sql;
	var $_data;
	var $_start;
	var $_limit;
	var $_debug;
	var $_distinct;
	var $_calc_found_rows;
	var $_db_params;
	var $_provider;
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->_provider = \Runtime\rtl::getContext()->provider("Runtime.ORM.Provider");
	}
	
	
	/**
	 * Copy query
	 */
	function copy()
	{
		$q = new \Runtime\ORM\Query();
		$q->_kind = $this->_kind;
		$q->_table_name = $this->_table_name;
		$q->_table_alias = $this->_table_alias;
		$q->_fields = $this->_fields->slice();
		$q->_join = $this->_join->slice();
		$q->_order = $this->_order->slice();
		$q->_filter = $this->_filter->slice();
		$q->_sql = $this->_sql;
		$q->_data = $this->_data ? $this->_data->copy() : null;
		$q->_start = $this->_start;
		$q->_limit = $this->_limit;
		$q->_debug = $this->_debug;
		$q->_distinct = $this->_distinct;
		$q->_calc_found_rows = $this->_calc_found_rows;
		$q->_db_params = $this->_db_params;
		$q->_provider = $this->_provider;
		return $q;
	}
	
	
	/**
	 * Setup db params
	 */
	function setDatabaseParams($db_params = null)
	{
		$this->_db_params = $db_params;
		return $this;
	}
	
	
	/**
	 * Calc found rows
	 */
	function raw($sql, $data)
	{
		$this->_kind = static::QUERY_RAW;
		$this->_sql = $sql;
		$this->_data = $data ? $data->toMap() : null;
		return $this;
	}
	
	
	/**
	 * Calc found rows
	 */
	function calcFoundRows($value = true)
	{
		$this->_calc_found_rows = $value;
		return $this;
	}
	
	
	/**
	 * Set distinct
	 */
	function distinct($value = true)
	{
		$this->_distinct = $value;
		return $this;
	}
	
	
	/**
	 * Set debug log
	 */
	function debug($value = true)
	{
		$this->_debug = $value;
		return $this;
	}
	
	
	/**
	 * Select query
	 */
	function select($fields = null)
	{
		$this->_kind = static::QUERY_SELECT;
		if ($fields) $this->fields($fields);
		return $this;
	}
	
	
	/**
	 * Set table
	 */
	function table($table_name = "", $alias_name = "")
	{
		$this->_table_name = $table_name != "" ? $table_name : $this->_table_name;
		$this->_table_alias = $alias_name == "" ? $this->_table_name : $alias_name;
		return $this;
	}
	
	
	/**
	 * Set table
	 */
	function from($table_name = "", $alias_name = "")
	{
		$this->_table_name = $table_name != "" ? $table_name : $this->_table_name;
		$this->_table_alias = $alias_name == "" ? $this->_table_name : $alias_name;
		return $this;
	}
	
	
	/**
	 * Insert query
	 */
	function insert($table_name = "")
	{
		$this->_kind = static::QUERY_INSERT;
		$this->_table_name = $table_name != "" ? $table_name : $this->_table_name;
		return $this;
	}
	
	
	/**
	 * Select query
	 */
	function update($table_name = "")
	{
		$this->_kind = static::QUERY_UPDATE;
		$this->_table_name = $table_name != "" ? $table_name : $this->_table_name;
		return $this;
	}
	
	
	/**
	 * Delete query
	 */
	function delete($table_name = "")
	{
		$this->_kind = static::QUERY_DELETE;
		$this->_table_name = $table_name != "" ? $table_name : $this->_table_name;
		return $this;
	}
	
	
	/**
	 * Set kind
	 */
	function kind($kind)
	{
		$this->_kind = $kind;
		return $this;
	}
	
	
	/**
	 * Clear fields
	 */
	function clearFields()
	{
		$this->_fields = new \Runtime\Vector();
		return $this;
	}
	
	
	/**
	 * Set fields
	 */
	function fields($fields)
	{
		$this->_fields = new \Runtime\Vector();
		for ($i = 0; $i < $fields->count(); $i++)
		{
			$field_name = $fields[$i];
			$this->addField($field_name);
		}
		return $this;
	}
	
	
	/**
	 * Add table fields
	 */
	function addTableFields($table_name)
	{
		$annotations = $this->_provider->getAnotations($table_name);
		if (!$annotations) return $this;
		for ($i = 0; $i < $annotations->count(); $i++)
		{
			$annotation = $annotations[$i];
			if ($annotation instanceof \Runtime\ORM\Annotations\BaseType)
			{
				$field = new \Runtime\ORM\QueryField();
				$field->annotation = $annotation;
				$field->field_name = $annotation->name;
				$field->table_name = $table_name;
				$this->_fields->append($field);
			}
		}
		return $this;
	}
	
	
	/**
	 * Add field
	 */
	function addField($field)
	{
		if (\Runtime\rtl::isString($field))
		{
			/* Parse field */
			$field = \Runtime\rs::trim($field);
			$field = \Runtime\ORM\QueryField::fromString($field);
		}
		/* If field name is asterisk */
		if ($field instanceof \Runtime\ORM\QueryField && $field->field_name == "*")
		{
			$table_name = $field->table_name;
			if ($table_name == "") return $this;
			$annotations = $this->_provider->getAnotations($table_name);
			if (!$annotations) return $this;
			/* Add fields from annotation */
			for ($i = 0; $i < $annotations->count(); $i++)
			{
				$annotation = $annotations[$i];
				if ($annotation instanceof \Runtime\ORM\Annotations\BaseType)
				{
					$field = new \Runtime\ORM\QueryField();
					$field->annotation = $annotation;
					$field->field_name = $annotation->name;
					$field->table_name = $table_name;
					$this->_fields->push($field);
				}
			}
		}
		else
		{
			$field->annotation = $this->_provider->getFieldType($field->table_name, $field->field_name);
			$this->_fields->push($field);
		}
		return $this;
	}
	
	
	/**
	 * Add raw field
	 */
	function addRawField($field_name)
	{
		$this->_fields->push($field_name);
		return $this;
	}
	
	
	/**
	 * Add fields
	 */
	function addFields($fields)
	{
		for ($i = 0; $i < $fields->count(); $i++)
		{
			$field_name = $fields[$i];
			$this->addField($field_name);
		}
		return $this;
	}
	
	
	/**
	 * Set value
	 */
	function value($key, $value)
	{
		$this->_data->set($key, $value);
		return $this;
	}
	
	
	/**
	 * Set data
	 */
	function values($data)
	{
		$this->_data = $data;
		return $this;
	}
	
	
	/**
	 * Set data
	 */
	function data($data)
	{
		$this->_data = $data;
		return $this;
	}
	
	
	/**
	 * Add page
	 */
	function page($page, $limit = null)
	{
		$limit = $limit > 0 ? $limit : $this->_limit;
		$start = $page * $limit;
		if ($start < 0) $start = 0;
		$this->_start = $start;
		$this->_limit = $limit > 0 ? $limit : $this->_limit;
		return $this;
	}
	
	
	/**
	 * Set offset
	 */
	function offset($start)
	{
		$this->_start = $start;
		return $this;
	}
	
	
	/**
	 * Set start
	 */
	function start($start)
	{
		$this->_start = $start;
		return $this;
	}
	
	
	/**
	 * Set limit
	 */
	function limit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}
	
	
	/**
	 * Returns page
	 */
	function getPage()
	{
		$limit = $this->_limit;
		$offset = $this->_start;
		if ($limit <= 0) return 0;
		if ($offset <= 0) return 0;
		return \Runtime\rtl::floor($offset / $limit);
	}
	
	
	/**
	 * Returns pages
	 */
	function getPages($rows)
	{
		$limit = $this->_limit;
		if ($limit <= 0) return 0;
		return $rows > 0 ? \Runtime\rtl::floor(($rows - 1) / $limit) + 1 : 0;
	}
	
	
	/**
	 * Clear order
	 */
	function clearOrder()
	{
		$this->_order = new \Runtime\Vector();
		return $this;
	}
	
	
	/**
	 * Set order
	 */
	function orderBy($name, $sort)
	{
		$this->_order->push(new \Runtime\Vector($name, $sort));
		return $this;
	}
	
	
	/**
	 * Add where
	 */
	function where($key, $op, $value)
	{
		$filter = new \Runtime\ORM\QueryFilter();
		$filter->key = $key;
		$filter->op = $op;
		$filter->value = $value;
		$this->_filter->push($filter);
		return $this;
	}
	
	
	/**
	 * Add filter
	 */
	function setFilter($filter)
	{
		$this->_filter = $filter;
		return $this;
	}
	
	
	/**
	 * Add filter
	 */
	function addFilter($filter)
	{
		if ($filter instanceof \Runtime\Vector) $this->_filter->appendItems($filter);
		else $this->_filter->push($filter);
		return $this;
	}
	
	
	/**
	 * Add or filter
	 */
	function addOrFilter($filter)
	{
		$this->_filter->push(new \Runtime\ORM\QueryFilter("\$or", null, $filter));
		return $this;
	}
	
	
	/**
	 * Clear filter
	 */
	function clearFilter()
	{
		$this->_filter = new \Runtime\Vector();
		return $this;
	}
	
	
	/**
	 * Inner join
	 */
	function innerJoin($table_name, $filter, $alias_name = "")
	{
		if ($alias_name == "") $alias_name = $table_name;
		$this->_join->push(new \Runtime\Map([
			"kind" => "join",
			"alias_name" => $alias_name,
			"table_name" => $table_name,
			"filter" => $filter,
		]));
		return $this;
	}
	
	
	/**
	 * Left join
	 */
	function leftJoin($table_name, $filter, $alias_name = "")
	{
		if ($alias_name == "") $alias_name = $table_name;
		$this->_join->push(new \Runtime\Map([
			"kind" => "left",
			"alias_name" => $alias_name,
			"table_name" => $table_name,
			"filter" => $filter,
		]));
		return $this;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->_kind = "";
		$this->_table_name = "";
		$this->_table_alias = "";
		$this->_fields = new \Runtime\Vector();
		$this->_join = new \Runtime\Vector();
		$this->_order = new \Runtime\Vector();
		$this->_filter = new \Runtime\Vector();
		$this->_sql = "";
		$this->_data = new \Runtime\Map();
		$this->_start = 0;
		$this->_limit = -1;
		$this->_debug = false;
		$this->_distinct = false;
		$this->_calc_found_rows = false;
		$this->_db_params = null;
		$this->_provider = null;
	}
	static function getClassName(){ return "Runtime.ORM.Query"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}