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

use Runtime\Exceptions\IndexOutOfRange;
use Runtime\Exceptions\RuntimeException;


interface Collection
{
	/**
	 * Returns value from position
	 * @param int pos - position
	 */
	function get($pos, $default_value = null);
	
	
	/**
	 * Set value to position
	 * @param int pos - position
	 * @param T value
	 */
	function set($pos, $value);
	
	
	/**
	 * Insert first value to array
	 * @return T value
	 */
	function insert($pos, $value);
	
	
	/**
	 * Append value to the end of array
	 * @param T value
	 */
	function push($value);
	
	
	/**
	 * Flatten Collection
	 */
	function flatten();
	
	
	/**
	 * Find value in array. Returns -1 if value not found.
	 * @param T value
	 * @return  int
	 */
	function indexOf($value);
	
	
	/**
	 * Find item
	 * @param fn f - Find function
	 * @return int - position
	 */
	function find($f);
	
	
	/**
	 * Find item pos
	 * @param fn f - Find function
	 * @return int - position
	 */
	function findIndex($f);
	
	
	/**
	 * Get first item
	 */
	function first($default_value = null);
	
	
	/**
	 * Get last item
	 */
	function last($default_value = null, $pos = -1);
	
	
	/**
	 * Map
	 * @param fn f
	 * @return Collection
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
	 * Transition Collection to Dict
	 * @param fn f
	 * @return Dict
	 */
	function transition($f);
	
	
	/**
	 * Append vector to the end of the vector
	 * @param Collection<T> arr
	 */
	function concat($arr);
	
	
	/**
	 * Returns count items in vector
	 */
	function count();
	
	
	/**
	 * Returns new Collection
	 * @param int offset
	 * @param int lenght
	 * @return Collection<T>
	 */
	function slice($offset = 0, $length = null);
	
	
	/**
	 * Sort Collection
	 * @param fn f - Sort user function
	 */
	function sort($f = null);
	
	
	/**
	 * Reverse Collection
	 */
	function reverse();
}