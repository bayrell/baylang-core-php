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

use Runtime\ApiResult;
use Runtime\RuntimeConstant;
use Runtime\Exceptions\RuntimeException;


class ApiError extends \Runtime\Exceptions\RuntimeException
{
	var $result;
	
	
	function __construct($prev = null)
	{
		parent::__construct($prev->getErrorMessage(), \Runtime\rtl::ERROR_API, $prev instanceof \Runtime\Exceptions\RuntimeException ? $prev : null);
		if ($prev instanceof \Runtime\ApiResult) $this->result = $prev;
	}
	
	
	/**
	 * Returns error message
	 */
	function getErrorMessage()
	{
		return $this->prev->getErrorMessage();
	}
	
	
	/**
	 * Returns error code
	 */
	function getErrorCode()
	{
		return $this->prev->getErrorCode();
	}
	
	
	/**
	 * Returns error file name
	 */
	function getFileName()
	{
		return $this->prev->getFileName();
	}
	
	
	/**
	 * Returns error line
	 */
	function getErrorLine()
	{
		return $this->prev->getErrorLine();
	}
	
	
	/**
	 * Returns error position
	 */
	function getErrorPos()
	{
		return $this->prev->getErrorPos();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->result = null;
	}
	static function getClassName(){ return "Runtime.Exceptions.ApiError"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}