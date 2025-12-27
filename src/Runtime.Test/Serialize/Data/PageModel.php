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
namespace Runtime\Test\Serialize\Data;

use Runtime\BaseModel;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Test\Serialize\Data\User;
use Runtime\Widget\Table\TableModel;


class PageModel extends \Runtime\BaseModel
{
	var $user;
	var $table;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("user", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"class_name" => "Runtime.Test.Serialize.Data.User",
		])));
		$rules->addType("table", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"class_name" => "Runtime.Widget.Table.TableModel",
			"item_type" => new \Runtime\Serializer\MapType(new \Runtime\Map([
				"name" => new \Runtime\Serializer\StringType(),
			])),
		])));
	}
	
	
	/**
	 * Init widget
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		$this->table = $this->createWidget("Runtime.Widget.Table.TableModel", new \Runtime\Map([
			"component" => "App.Table",
		]));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->user = null;
		$this->table = null;
	}
	static function getClassName(){ return "Runtime.Test.Serialize.Data.PageModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}