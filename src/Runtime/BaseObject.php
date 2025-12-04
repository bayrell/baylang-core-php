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
namespace Runtime;


class BaseObject
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		/* Init object */
		$this->_init();
	}
	
	
	/**
	 * Returns new isntance
	 */
	static function newInstance($args){ return \Runtime\rtl::newInstance(static::getClassName(), $args); }
	
	
	
	/**
	 * Init function
	 */
	function _init(){}
	
	
	/**
	 * Assign new values
	 */
	function _assign_values($changes = null)
	{
		if ($changes instanceof \Runtime\Map)
		{
			foreach ($changes->_map as $key => $value)
			{
				$k = $this->__getKey($key);
				if (property_exists($this, $k))
				{
					$this->$k = $value;
				}
			}
		}
	}
	function __getKey($k){return $k;}
	
	
	/* ========= Class init functions ========= */
	static function getClassName(){ return "Runtime.BaseObject"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}