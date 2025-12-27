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
use Runtime\Serializer\Serializer;
use Runtime\Serializer\StringType;
use Runtime\Serializer\VectorType;
use Runtime\Test\Serialize\Data\Contact;
use Runtime\Test\Serialize\Data\ContactEmail;
use Runtime\Test\Serialize\Data\ContactPhone;


class User extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $name;
	var $roles;
	var $contact;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rule)
	{
		parent::serialize($rule);
		$rule->addType("name", new \Runtime\Serializer\StringType());
		$rule->addType("roles", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType()));
		$rule->addType("contact", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"implements" => "Runtime.Test.Serialize.Data.Contact",
			"rules" => function ($rules, $data)
			{
				if (!$data) return;
				$kind = $data->get("kind");
				if ($kind == "email") \Runtime\Test\Serialize\Data\ContactEmail::serialize($rules);
				if ($kind == "phone") \Runtime\Test\Serialize\Data\ContactPhone::serialize($rules);
			},
		])))
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rule){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->roles = new \Runtime\Vector();
		$this->contact = null;
	}
	static function getClassName(){ return "Runtime.Test.Serialize.Data.User"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}