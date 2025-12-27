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
use Runtime\Exceptions\RuntimeException;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Provider;


class QueryField extends \Runtime\BaseObject
{
	var $annotation;
	var $table_name;
	var $field_name;
	var $alias_name;
	
	
	/**
	 * Returns row column name
	 */
	function getRowColumnName()
	{
		if ($this->alias_name != "") return $this->alias_name;
		return $this->field_name;
	}
	
	
	/**
	 * Create from field name
	 */
	function __construct($table_name = "", $field_name = "", $alias_name = "")
	{
		$table_name = \Runtime\rs::trim($table_name);
		$field_name = \Runtime\rs::trim($field_name);
		$table_name = \Runtime\rs::trim($table_name);
		$this->alias_name = $alias_name;
		$this->field_name = $field_name;
		$this->table_name = $table_name;
	}
	
	
	/**
	 * Create from string
	 */
	static function fromString($s)
	{
		$res1 = \Runtime\rs::split(" as ", $s);
		$res2 = \Runtime\rs::split(".", $res1[0]);
		$alias_name = $res1->count() > 1 ? $res1->get(1) : "";
		$field_name = $res2->count() == 1 ? $res2->get(0) : $res2->get(1);
		$table_name = $res2->count() > 1 ? $res2->get(0) : "";
		return new \Runtime\ORM\QueryField($table_name, $field_name, $alias_name);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->annotation = null;
		$this->table_name = "";
		$this->field_name = "";
		$this->alias_name = "";
	}
	static function getClassName(){ return "Runtime.ORM.QueryField"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}