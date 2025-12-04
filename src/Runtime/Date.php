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
use Runtime\StringInterface;


class Date extends \Runtime\BaseObject implements \Runtime\StringInterface
{
	var $y;
	var $m;
	var $d;
	
	
	/**
	 * Constructor
	 */
	function __construct($data = null)
	{
		parent::__construct();
		if ($data != null)
		{
			if ($data->has("y")) $this->y = $data->get("y");
			if ($data->has("m")) $this->m = $data->get("m");
			if ($data->has("d")) $this->d = $data->get("d");
		}
	}
	
	
	/**
	 * toMap
	 */
	function toMap()
	{
		return new \Runtime\Map([
			"y" => $this->y,
			"m" => $this->m,
			"d" => $this->d,
		]);
	}
	
	
	/**
	 * Return date
	 * @return string
	 */
	function getDate()
	{
		return $this->y . "-" . $this->m . "-" . $this->d;
	}
	
	
	/**
	 * Normalize date time
	 */
	function normalize(){ return $this; }
	
	
	/**
	 * Return db datetime
	 * @return string
	 */
	function toString()
	{
		return $this->y . "-" . $this->m . "-" . $this->d;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->y = 0;
		$this->m = 0;
		$this->d = 0;
	}
	static function getClassName(){ return "Runtime.Date"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}
