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

use Runtime\re;
use Runtime\BaseObject;
use Runtime\ORM\Connection;
use Runtime\ORM\Query;
use Runtime\ORM\QueryField;
use Runtime\ORM\QueryFilter;


class SQLBuilder extends \Runtime\BaseObject
{
	var $conn;
	var $data;
	var $q;
	var $sql;
	
	
	/**
	 * Constructor
	 */
	function __construct($conn, $q)
	{
		$this->conn = $conn;
		$this->q = $q;
	}
	
	
	/**
	 * Returns sql
	 */
	function getSQL(){ return $this->sql; }
	
	
	/**
	 * Returns data
	 */
	function getData(){ return $this->data; }
	
	
	/**
	 * Returns true if builder is correct
	 */
	function isValid(){ return $this->sql != null; }
	
	
	/**
	 * Format sql
	 */
	function formatSQL()
	{
		$sql = $this->sql;
		$data = $this->data;
		if ($data == null) return $sql;
		$data->each(function ($value, $key) use (&$sql)
		{
			if ($value === null)
			{
				$sql = \Runtime\rs::replace($this->formatKey($key), "null", $sql);
			}
			else
			{
				$value = $this->quote($value);
				$sql = \Runtime\rs::replace($this->formatKey($key), $value, $sql);
			}
		});
		return $sql;
	}
	
	
	/**
	 * Format key
	 */
	function formatKey($key){ return ":" . $key; }
	
	
	/**
	 * Prepare field
	 */
	function prepareField($item)
	{
		$res1 = \Runtime\rs::split(",", $item);
		$res1 = $res1->map(function ($s){ return \Runtime\rs::trim($s); });
		$res1 = $res1->map(function ($s)
		{
			$res2 = \Runtime\rs::split(".", $s);
			$res2 = $res2->map(function ($name){ return "`" . $name . "`"; });
			return \Runtime\rs::join(".", $res2);
		});
		return \Runtime\rs::join(",", $res1);
	}
	
	
	/**
	 * Prepare value
	 */
	function prepareValue($item, $op)
	{
		if ($op == "%like%")
		{
			$item = "%" . $item . "%";
			$op = "like";
		}
		else if ($op == "like%")
		{
			$item = $item . "%";
			$op = "like";
		}
		else if ($op == "%like")
		{
			$item = "%" . $item;
			$op = "like";
		}
		return new \Runtime\Vector($item, $op);
	}
	
	
	/**
	 * Quote
	 */
	function quote($value)
	{
		$value = $this->conn->pdo->quote($value);
		return $value;
	}
	
	
	/**
	 * Returns table name
	 */
	function getTableName()
	{
		if (\Runtime\rs::substr($this->q->_table_name, 0, 1) != "`")
		{
			return "`" . $this->conn->prefix . $this->q->_table_name . "`";
		}
		return $this->q->_table_name;
	}
	
	
	/**
	 * Returns query sql
	 */
	function build()
	{
		if ($this->q == null) return $this;
		$q = $this->q;
		/* Select query */
		if ($q->_kind == \Runtime\ORM\Query::QUERY_SELECT)
		{
			$data = $q->_data->copy();
			$sql = "SELECT ";
			$field_index = 0;
			/* Add distinct */
			if ($q->_distinct != "") $sql .= " DISTINCT ";
			/* Add fields */
			if ($q->_fields != null)
			{
				$fields = $q->_fields->map(function ($item) use (&$q, &$data, &$field_index)
				{
					$field_name = "";
					if ($item instanceof \Runtime\ORM\QueryField)
					{
						$table_name = $item->table_name;
						if ($table_name == "") $table_name = $q->_table_name;
						$field_name = "`" . $table_name . "`.`" . $item->field_name . "`";
						if ($item->alias_name != "")
						{
							$field_name .= " as `" . $item->alias_name . "`";
						}
					}
					else if ($item instanceof \Runtime\ORM\QueryFilter)
					{
						$res = $this->convertFilterItem($item, $data, $field_index);
						$field_name = $res[0];
						$field_index = $res[1];
						if ($item->alias != "")
						{
							$field_name .= " as `" . $item->alias . "`";
						}
					}
					else if (\Runtime\rtl::isFunction($item))
					{
						return $item($this);
					}
					else
					{
						$field_name = $item;
					}
					return $field_name;
				});
				$sql .= \Runtime\rs::join(", ", $fields);
			}
			else $sql .= " * ";
			/* New line */
			$sql .= "\n";
			/* Add table name */
			$sql .= " FROM " . $this->getTableName() . " AS `" . $q->_table_alias . "`";
			/* New line */
			$sql .= "\n";
			/* Add joins */
			if ($q->_join != null && $q->_join->count() > 0)
			{
				for ($i = 0; $i < $q->_join->count(); $i++)
				{
					$join = $q->_join[$i];
					$kind = $join->get("kind");
					$table_name = $join->get("table_name");
					$alias_name = $join->get("alias_name");
					$where = $join->get("filter");
					if ($where instanceof \Runtime\Vector)
					{
						$res = $this->convertFilter($where, $data, $field_index);
						$where = $res[0];
						$field_index = $res[1];
					}
					if ($kind == "left") $sql .= " LEFT JOIN ";
					else $sql .= " INNER JOIN ";
					$sql .= "`" . $this->conn->prefix . $table_name . "`";
					if ($alias_name != "") $sql .= " AS `" . $alias_name . "`";
					$sql .= " ON (" . $where . ")";
					/* New line */
					$sql .= "\n";
				}
			}
			/* Add where */
			if ($q->_filter != null && $q->_filter->count() > 0)
			{
				$res = $this->convertFilter($q->_filter, $data, $field_index);
				$where = $res[0];
				$field_index = $res[1];
				if ($where != "") $sql .= " WHERE " . $where;
				/* New line */
				$sql .= "\n";
			}
			/* Add order */
			if ($q->_order != null && $q->_order->count() > 0)
			{
				$order = $q->_order->map(function ($item){ return $this->prepareField($item[0]) . " " . $item[1]; });
				$sql .= " ORDER BY " . \Runtime\rs::join(",", $order);
				/* New line */
				$sql .= "\n";
			}
			/* Add order */
			if ($q->_limit >= 0) $sql .= " LIMIT " . $q->_limit;
			if ($q->_limit >= 0 && $q->_start >= 0) $sql .= " OFFSET " . $q->_start;
			$this->sql = $sql;
			$this->data = $data;
		}
		else if ($q->_kind == \Runtime\ORM\Query::QUERY_INSERT)
		{
			$keys = new \Runtime\Vector();
			$values = new \Runtime\Vector();
			if ($q->_data)
			{
				$q->_data->each(function ($value, $key) use (&$keys, &$values)
				{
					$keys->push("`" . $key . "`");
					$values->push($this->formatKey($key));
				});
			}
			/* Build sql */
			$this->sql = "INSERT INTO " . $this->getTableName() . " (" . \Runtime\rs::join(",", $keys) . ") VALUES (" . \Runtime\rs::join(",", $values) . ")";
			$this->data = $q->_data->copy();
		}
		else if ($q->_kind == \Runtime\ORM\Query::QUERY_UPDATE)
		{
			$update_arr = new \Runtime\Vector();
			$values = new \Runtime\Vector();
			$data = new \Runtime\Map();
			/* Build update */
			if ($q->_data)
			{
				$q->_data->each(function ($value, $key) use (&$update_arr, &$data)
				{
					$field_key = "update_" . $key;
					$field_name = $this->prepareField($key);
					$update_arr->push($field_name . " = " . $this->formatKey($field_key));
					$data->set($field_key, $value);
				});
			}
			/* Build where */
			$res = $this->convertFilter($q->_filter, $data);
			$where_str = $res[0];
			/* Build sql */
			$this->sql = "UPDATE " . $this->getTableName() . " SET " . \Runtime\rs::join(", ", $update_arr) . " WHERE " . $where_str;
			$this->data = $data;
		}
		else if ($q->_kind == \Runtime\ORM\Query::QUERY_DELETE)
		{
			/* Build where */
			$data = new \Runtime\Map();
			$res = $this->convertFilter($q->_filter, $data);
			$where_str = $res[0];
			/* Delete item */
			$this->sql = "DELETE FROM " . $this->getTableName() . " WHERE " . $where_str;
			$this->data = $data;
		}
		else if ($q->_kind == \Runtime\ORM\Query::QUERY_RAW)
		{
			$this->sql = $q->_sql;
			$this->data = $q->_data;
		}
		return $this;
	}
	
	
	/**
	 * Convert filter
	 */
	function convertFilterItem($item, $data, $field_index = 0)
	{
		if (\Runtime\rtl::isString($item))
		{
			return new \Runtime\Vector($item, $field_index);
		}
		$allow_operations = new \Runtime\Vector(
			"=",
			"!=",
			">=",
			"<=",
			"<",
			">",
			"like",
			"%like%",
			"like%",
			"%like",
			"match",
			"match_boolean",
		);
		$convert_key = function ($s)
		{
			$s = \Runtime\re::replace("[,\\.]", "_", $s);
			$s = \Runtime\re::replace("[^0-9A-Za-z_]", "", $s);
			return $s;
		};
		$field_name = "";
		$op = "";
		$value = "";
		/* If QueryFilter */
		if ($item instanceof \Runtime\ORM\QueryFilter)
		{
			$field_name = $item->key;
			$op = $item->op;
			$value = $item->value;
		}
		else
		{
			return new \Runtime\Vector("", $field_index);
		}
		/* OR */
		if ($field_name == "\$or")
		{
			$where_or = new \Runtime\Vector();
			for ($j = 0; $j < $value->count(); $j++)
			{
				$res_or = $this->convertFilterItem($value[$j], $data, $field_index);
				$where_or->push($res_or[0]);
				$field_index = $res_or[1];
			}
			$s = "(" . \Runtime\rs::join(" OR ", $where_or) . ")";
			return new \Runtime\Vector($s, $field_index);
		}
		/* Check operation */
		if ($allow_operations->indexOf($op) == -1)
		{
			$op = "=";
		}
		if ($value === null)
		{
			if ($op == "!=")
			{
				$s = $this->prepareField($field_name) . " is not null";
				return new \Runtime\Vector($s, $field_index);
			}
			else
			{
				$s = $this->prepareField($field_name) . " is null";
				return new \Runtime\Vector($s, $field_index);
			}
		}
		else if ($value instanceof \Runtime\Vector)
		{
			if ($op == "=")
			{
				if ($value->count() == 0)
				{
					return new \Runtime\Vector("1 = 0", $field_index);
				}
				else
				{
					$res = new \Runtime\Vector();
					for ($j = 0; $j < $value->count(); $j++)
					{
						$field_key = $convert_key($field_index . "_where_" . $field_name);
						$data->set($field_key, $value[$j]);
						$res->push($this->formatKey($field_key));
						$field_index++;
					}
					$s = $this->prepareField($field_name) . " in (" . \Runtime\rs::join(",", $res) . ")";
					return new \Runtime\Vector($s, $field_index);
				}
			}
		}
		else
		{
			$s = "";
			$field_key = $convert_key($field_index . "_where_" . $field_name);
			$field_name = $this->prepareField($field_name);
			$res = $this->prepareValue($value, $op);
			$value = $res[0];
			$op = $res[1];
			if ($op == "match")
			{
				$s = "MATCH(" . $field_name . ") AGAINST (" . $this->formatKey($field_key) . ")";
			}
			else if ($op == "match_boolean")
			{
				$s = "MATCH(" . $field_name . ") AGAINST (:" . $this->formatKey($field_key) . " IN BOOLEAN MODE)";
			}
			else
			{
				$s = $field_name . " " . $op . " " . $this->formatKey($field_key);
			}
			$data->set($field_key, $value);
			$field_index++;
			return new \Runtime\Vector($s, $field_index);
		}
		return new \Runtime\Vector("", $field_index);
	}
	
	
	/**
	 * Convert filter
	 */
	function convertFilter($filter, $data, $field_index = 0)
	{
		$where = new \Runtime\Vector();
		for ($i = 0; $i < $filter->count(); $i++)
		{
			$item = $filter->get($i);
			$res = $this->convertFilterItem($item, $data, $field_index);
			$s = $res->get(0);
			$field_index = $res->get(1);
			if ($s != "") $where->push($s);
		}
		$where_str = \Runtime\rs::join(" AND ", $where);
		return new \Runtime\Vector($where_str, $field_index);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->conn = null;
		$this->data = null;
		$this->q = null;
		$this->sql = null;
	}
	static function getClassName(){ return "Runtime.ORM.MySQL.SQLBuilder"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}