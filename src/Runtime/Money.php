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

use Runtime\BaseObject;
use Runtime\Exceptions\RuntimeException;


class Money extends \Runtime\BaseObject
{
	var $value;
	var $currency;
	
	
	/**
	 * Create new instance
	 */
	function __construct($value, $currency)
	{
		parent::__construct();
		$this->value = $value;
		$this->currency = $currency;
	}
	
	
	/**
	 * Returns value
	 */
	function getValue(){ return $this->value; }
	
	
	/**
	 * Returns currency
	 */
	function getCurrency(){ return $this->currency; }
	
	
	/**
	 * Add money
	 */
	function add($money)
	{
		if ($this->currency != $money->currency)
		{
			throw new \Runtime\Exceptions\RuntimeException("Money currency mismatch");
		}
		$this->value += $money->currency;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->value = 0;
		$this->currency = "";
	}
	static function getClassName(){ return "Runtime.Money"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}