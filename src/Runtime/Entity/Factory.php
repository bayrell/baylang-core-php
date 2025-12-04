<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2025 "Ildar Bikmamatov" <support@bayrell.org>
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
namespace Runtime\Entity;

use Runtime\BaseObject;
use Runtime\FactoryInterface;
use Runtime\Entity\Entity;


class Factory extends \Runtime\Entity\Entity implements \Runtime\FactoryInterface
{
	/* Provider class name */
	var $value;
	
	/* Factory params */
	var $params;
	
	
	/**
	 * Create factory
	 */
	function __construct($name, $value = null, $params = null)
	{
		parent::__construct(new \Runtime\Map([
			"name" => $name,
			"value" => $value,
			"params" => $params,
		]));
	}
	
	
	/**
	 * Create new object
	 */
	function createInstance($params = null)
	{
		$instance = null;
		$class_name = $this->value;
		$factory_params = $this->params;
		if ($params)
		{
			if ($factory_params) $factory_params = $factory_params->concat($params);
			else $factory_params = $params;
		}
		if ($class_name == null) $class_name = $this->name;
		if ($class_name instanceof \Runtime\BaseObject)
		{
			$instance = $class_name;
		}
		else if (\Runtime\rtl::isString($class_name))
		{
			$instance = \Runtime\rtl::newInstance($class_name, new \Runtime\Vector($factory_params));
		}
		return $instance;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->value = null;
		$this->params = null;
	}
	static function getClassName(){ return "Runtime.Entity.Factory"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}