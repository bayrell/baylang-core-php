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
namespace Runtime\Exceptions;

use Runtime\Exceptions\DataError;
use Runtime\Exceptions\RuntimeException;


class FieldException extends \Runtime\Exceptions\RuntimeException implements \Runtime\Exceptions\DataError
{
	var $data;
	
	
	/**
	 * Create object
	 */
	function __construct($data, $prev = null)
	{
		parent::__construct("Field error", \Runtime\rtl::ERROR_UNKNOWN, $prev);
		$this->data = $data;
	}
	
	
	/**
	 * Returns data
	 */
	function getData(){ return $this->data; }
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->data = null;
	}
	static function getClassName(){ return "Runtime.Exceptions.FieldException"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}