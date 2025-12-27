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
namespace Runtime\ORM\Annotations;

use Runtime\lib;
use Runtime\BaseObject;
use Runtime\ORM\Connection;
use Runtime\ORM\Query;
use Runtime\ORM\Relation;
use Runtime\ORM\RelationArray;


class ForeignKey extends \Runtime\BaseObject
{
	var $name;
	var $table_name;
	var $table_name_source;
	var $primary_key;
	var $foreign_key;
	
	
	/**
	 * Build search query
	 */
	function buildSearchQuery($kind, $conn, $q){ return $q; }
	
	
	/**
	 * Build query for resolve foreign key
	 */
	function resolveQuery($conn, $items)
	{
		$ids = $items->map(function ($item){ return $item->get($this->foreign_key); })->removeDuplicates();
		$q = (new \Query\select($this->table_name))->where($this->table_name . "." . $this->primary_key, "=", $ids);
		/* Filter */
		$q = $this->buildSearchQuery("resolve", $conn, $q);
		return $q;
	}
	
	
	/**
	 * Resolve foreign key
	 */
	function resolve($conn, $items)
	{
		$q = static::resolveQuery($conn, $items);
		$result = $conn->fetchAll($q);
		return $result;
	}
	
	
	/**
	 * Build query for reverse resolve foreign key
	 */
	function resolveReverseQuery($conn, $items)
	{
		$ids = $items->map(function ($item){ return $item->get($this->primary_key); })->removeDuplicates();
		$q = (new \Query\select($this->table_name_source))->where($this->table_name_source . "." . $this->foreign_key, "=", $ids);
		/* Filter */
		$q = $this->buildSearchQuery("resolveReverse", $conn, $q);
		return $q;
	}
	
	
	/**
	 * Reverse resolve foreign key
	 */
	function resolveReverse($conn, $items)
	{
		$q = static::resolveReverseQuery($conn, $items);
		$result = $conn->fetchAll($q);
		return $result;
	}
	
	
	/**
	 * Resolve all
	 */
	function resolveAll($conn)
	{
		$q = new \Query\select($this->table_name);
		/* Filter */
		$q = $this->buildSearchQuery("resolveAll", $conn, $q);
		$result = $conn->fetchAll($q);
		return $result;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->table_name = "";
		$this->table_name_source = "";
		$this->primary_key = null;
		$this->foreign_key = null;
	}
	static function getClassName(){ return "Runtime.ORM.Annotations.ForeignKey"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}