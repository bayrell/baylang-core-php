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

use Runtime\Exceptions\KeyNotFound;

class Map implements \JsonSerializable
{
	var $_map = [];
	
	
	/**
	 * Constructor
	 */
	function __construct($map = null)
	{
		if ($map != null)
		{
			if ($map instanceof \stdClass) $map = (array)$map;
			if (is_array($map)) $this->_map = $map;
			else $this->_map = $map->_map;
		}
	}
	
	
	/**
	 * Create map from Object
	 */
	static function create($obj)
	{
		return new Map($obj);
	}
	
	
	/**
	 * Returns value from position
	 * @param string key
	 * @param T default_value
	 * @return T
	 */
	function get($key, $default_value = null)
	{
		return isset($this->_map[$key]) ? $this->_map[$key] : $default_value;
	}
	
	
	/**
	 * Set value size_to position
	 * @param string key - position
	 * @param T value 
	 * @return self
	 */
	function set($key, $value)
	{
		$this->_map[$key] = $value;
	}
	
	
	/**
	 * Return true if key exists
	 * @param string key
	 * @return bool var
	 */
	function has($key)
	{
		return isset($this->_map[$key]);
	}
	
	
	/**
	 * Remove value from position
	 * @param string key
	 * @return self
	 */
	function remove($key)
	{
		if (isset($this->_map[$key]))
		{
			unset($this->_map[$key]);
		}
	}
	
	
	/**
	 * Clear all values from vector
	 * @return self
	 */
	function clear()
	{
		$this->_map = [];
	}
	
	
	/**
	 * Copy map
	 */
	function copy()
	{
		return new static($this);
	}
	
	
	/**
	 * Add values from other map
	 * @param Dict<T> map
	 * @return self
	 */
	function concat($map = null)
	{
		if ($map == null) return;
		
		$instance = new static($this);
		$instance->_map = array_merge($this->_map, $map->_map);
		return $instance;
	}
	
	
	/**
	 * Returns vector of the keys
	 * @return Collection<string>
	 */
	function keys()
	{
		foreach ($this->_map as $key => $value)
		{
			yield $key;
		}
	}
	
	
	/**
	 * Returns vector of the values
	 * @return Collection<T>
	 */
	function values()
	{
		foreach ($this->_map as $value)
		{
			yield $value;
		}
	}
	
	
	/**
	 * Call function map
	 * @param fn f
	 * @return Dict
	 */
	function map($f)
	{
		$map = new static();
		foreach ($this->_map as $key => $value)
		{
			$map->set($key, $f($value, $key, $this));
		}
		return $map;
	}
	
	
	/**
	 * Call function map
	 * @param fn f
	 * @return Dict
	 */
	function mapWithKeys($f)
	{
		$map = new static();
		foreach ($this->_map as $key => $value)
		{
			$item = $f($value, $key, $this);
			$map->set($item[1], $item[0]);
		}
		return $map;
	}
	
	
	/**
	 * Reduce
	 * @param fn f
	 * @param var init_value
	 * @return init_value
	 */
	function reduce($f, $result)
	{
		foreach ($this->_map as $key => $value)
		{
			$result = $f($result, $value, $key, $this);
		}
		return $result;
	}
	
	
	/**
	 * Filter items
	 * @param fn f
	 * @return Collection
	 */
	function filter($f)
	{
		$map = new static();
		foreach ($this->_map as $key => $value)
		{
			$flag = $f($value, $key, $this);
			if ($flag) $map->set($key, $value);
		}
		return $map;
	}
	
	
	/**
	 * Call function for each item
	 * @param fn f
	 */
	function each($f)
	{
		foreach ($this->_map as $key => $value)
		{
			$f($value, $key, $this);
		}
	}
	
	
	/**
	 * Transition Dict to Vector
	 * @param fn f
	 * @return Vector
	 */
	function transition($f)
	{
		$arr = new \Runtime\Vector();
		foreach ($this->_map as $key => $value)
		{
			$arr->push($f($value, $key, $this));
		}
		return $arr;
	}
	
	
	/**
	 * Json Serialize
	 */
	function jsonSerialize() : mixed
	{
		return $this->_map;
	}
}