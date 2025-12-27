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

class QueryFilter extends \Runtime\BaseObject
{
	var $key;
	var $op;
	var $value;
	var $alias;
	
	
	/**
	 * Constructor
	 */
	function __construct($key = "", $op = "", $value = "", $alias = "")
	{
		parent::__construct();
		$this->key = $key;
		$this->op = $op;
		$this->value = $value;
		$this->alias = $alias;
	}
	
	
	/**
	 * Validate operation
	 */
	static function validateOp($op)
	{
		if ($op == "=") return true;
		if ($op == ">") return true;
		if ($op == ">=") return true;
		if ($op == "<") return true;
		if ($op == "<=") return true;
		if ($op == "!=") return true;
		return false;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->key = "";
		$this->op = "";
		$this->value = "";
		$this->alias = "";
	}
	static function getClassName(){ return "Runtime.ORM.QueryFilter"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}