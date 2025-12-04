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

interface Dict
{
	/**
	 * Returns value from position
	 * @param string key
	 * @param T default_value
	 * @return T
	 */
	function get($key, $default_value = null);
	
	
	/**
	 * Set value size_to position
	 * @param string key - position
	 * @param T value
	 * @return self
	 */
	function set($key, $value);
	
	
	/**
	 * Return true if key exists
	 * @param string key
	 * @return bool var
	 */
	function has($key);
	
	
	/**
	 * Remove value from position
	 * @param string key
	 * @return self
	 */
	function remove($key);
	
	
	/**
	 * Clear all values from vector
	 * @return self
	 */
	function clear();
	
	
	/**
	 * Copy map
	 */
	function copy();
	
	
	/**
	 * Add values from other map
	 * @param Dict<T> map
	 * @return self
	 */
	function concat($map = null);
	
	
	/**
	 * Returns vector of the keys
	 * @return Collection<string>
	 */
	function keys();
	
	
	/**
	 * Returns vector of the values
	 * @return Collection<T>
	 */
	function values();
	
	
	/**
	 * Call function map
	 * @param fn f
	 * @return Dict
	 */
	function map($f);
	
	
	/**
	 * Reduce
	 * @param fn f
	 * @param var init_value
	 * @return init_value
	 */
	function reduce($f, $init_value);
	
	
	/**
	 * Filter items
	 * @param fn f
	 * @return Collection
	 */
	function filter($f);
	
	
	/**
	 * Call function for each item
	 * @param fn f
	 */
	function each($f);
	
	
	/**
	 * Transition Dict to Vector
	 * @param fn f
	 * @return Vector
	 */
	function transition($f);
}