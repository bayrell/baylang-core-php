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

use Runtime\BaseObject;
use Runtime\SerializeInterface;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\VectorType;
use Runtime\Test\Serialize\Data\ContactEmail;
use Runtime\Test\Serialize\Data\ContactPhone;


class ContactList extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $items;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("items", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"implements" => "Contact",
			"rules" => function ($rules, $data)
			{
				if (!$data) return;
				$kind = $data->get("kind");
				if ($kind == "email") \Runtime\Test\Serialize\Data\ContactEmail::serialize($rules);
				if ($kind == "phone") \Runtime\Test\Serialize\Data\ContactPhone::serialize($rules);
			},
		]))))
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rules){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->items = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Test.Serialize.Data.ContactList"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}