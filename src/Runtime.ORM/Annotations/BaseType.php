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

use Runtime\ORM\ConnectionInterface as Connection;
use Runtime\ORM\Annotations\BaseAnnotation;


class BaseType extends \Runtime\ORM\Annotations\BaseAnnotation
{
	var $default;
	var $name;
	var $comment;
	var $nullable;
	
	
	/**
	 * Create object
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Returns rule
	 */
	function getRule(){ return null; }
	
	
	/**
	 * Prepare data before save
	 */
	function prepare($item, $action){ return $item; }
	
	
	/**
	 * Process item from database
	 */
	function fromDatabase($conn, $item)
	{
		if ($item->has($this->name))
		{
			$item = $conn->fromDatabase($this, $item, $this->name);
		}
		return $item;
	}
	
	
	/**
	 * Process item to database
	 */
	function toDatabase($conn, $item)
	{
		if ($this->name != "" && !$item->has($this->name))
		{
			if ($this->default) $item->set($this->name, $this->default);
		}
		$item = $conn->toDatabase($this, $item, $this->name);
		return $item;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->default = null;
		$this->name = "";
		$this->comment = "";
		$this->nullable = false;
	}
	static function getClassName(){ return "Runtime.ORM.Annotations.BaseType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}