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


class HashMap
{
	var $_map;
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->_map = new \SplObjectStorage();
	}
	
	
	/**
	 * Set value size_to position
	 * @param Key key - position
	 * @param Value value 
	 * @return self
	 */
	function set($key, $value)
	{
		$this->_map->attach($key, $value);
		return $this;
	}
	
	
	/**
	 * Returns value from position
	 * @param string key
	 * @return Value
	 */
	function get($key)
	{
		if (!$this->_map->contains($key)) return null;
		return $this->_map[$key];
		return $this;
	}
	
	
	/**
	 * Return true if key exists
	 * @param string key
	 * @return bool var
	 */
	function has($key)
	{
		if (!$this->_map->has($key)) return false;
		return true;
	}
	
	
	/**
	 * Remove value from position
	 * @param string key
	 * @return self
	 */
	function remove($key)
	{
		$this->_map->detach($key);
		return $this;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.HashMap"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}