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

use Runtime\Serializer\IntegerType;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Connection;


class TinyIntType extends \Runtime\ORM\Annotations\BaseType
{
	/**
	 * Returns rule
	 */
	function getRule(){ return new \Runtime\Serializer\IntegerType(); }
	
	
	/**
	 * Process item from database
	 */
	function fromDatabase($conn, $item)
	{
		if ($item->has($this->name))
		{
			$value = $item->get($this->name);
			if ($value === null && $this->nullable)
			{
				$item->set($this->name, null);
			}
			else
			{
				$item->set($this->name, \Runtime\rtl::toInt($value));
			}
		}
		return $item;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.ORM.Annotations.TinyIntType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}