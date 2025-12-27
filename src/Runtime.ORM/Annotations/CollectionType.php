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

use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Connection;


class CollectionType extends \Runtime\ORM\Annotations\BaseType
{
	/**
	 * Process item from database
	 */
	function fromDatabase($conn, $item)
	{
		if ($item->has($this->name))
		{
			$value = $item->get($this->name);
			$obj = \Runtime\rtl::jsonDecode($value);
			$item->set($this->name, $obj);
			$item = $conn->fromDatabase($this, $item, $this->name);
		}
		return $item;
	}
	
	
	/**
	 * Process item to database
	 */
	function toDatabase($conn, $item)
	{
		$value = $item->get($this->name);
		if ($value)
		{
			$item->set($this->name, \Runtime\rtl::jsonEncode($value, false));
		}
		$item = $conn->toDatabase($this, $item, $this->name);
		return $item;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.ORM.Annotations.CollectionType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}