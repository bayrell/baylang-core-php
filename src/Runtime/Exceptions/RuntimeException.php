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

class ClassException extends \Exception
{
	function __construct($message="", $code=-1, $prev=null)
	{
		parent::__construct($message, (int)$code, $prev);
	}
	function _init(){}
}


class RuntimeException extends \Runtime\Exceptions\ClassException
{
	var $prev;
	var $error_message;
	var $error_code;
	var $error_file;
	var $error_line;
	var $error_pos;
	
	
	/**
	 * Constructor
	 */
	function __construct($message = "", $code = -1, $prev = null)
	{
		parent::__construct($message, $code, $prev);
		$this->_init();
		$this->error_message = $message;
		$this->error_code = $code;
		$this->prev = $prev;
	}
	
	
	/**
	 * Returns previous exception
	 */
	function getPreviousException()
	{
		return $this->prev;
	}
	
	
	/**
	 * Build error message
	 */
	function buildErrorMessage()
	{
		return $this->error_message;
	}
	
	
	/**
	 * Returns error message
	 */
	function getErrorMessage()
	{
		return $this->error_message;
	}
	
	
	/**
	 * Returns error code
	 */
	function getErrorCode()
	{
		return $this->error_code;
	}
	
	
	/**
	 * Returns error file name
	 */
	function getFileName()
	{
		if ($this->error_file == "")
		{
			return $this->getFile();
		}
		return $this->error_file;
	}
	
	
	/**
	 * Returns error line
	 */
	function getErrorLine()
	{
		if ($this->error_line == "")
		{
			return $this->getLine();
		}
		return $this->error_line;
	}
	
	
	/**
	 * Returns error position
	 */
	function getErrorPos()
	{
		return $this->error_pos;
	}
	
	
	/**
	 * Convert exception to string
	 */
	function toString()
	{
		return $this->buildErrorMessage();
	}
	
	
	/**
	 * Returns trace
	 */
	function getTraceStr()
	{
		return $this->getTraceAsString();
	}
	
	
	/**
	 * Returns trace
	 */
	function getTraceCollection()
	{
		$error_trace = $this->getTrace();
		$error_trace = array_map(
			function ($item){
				$prefix = "internal";
				if (isset($item["file"]))
					$prefix = $item["file"] . "(" . $item["line"] . ")";
				else if (isset($item["class"]))
					$prefix = $item["class"];
				return $prefix . ": " . $item["function"];
			},
			$error_trace
		);
		return \Runtime\Collection::from($error_trace);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->prev = null;
		$this->error_message = "";
		$this->error_code = 0;
		$this->error_file = "";
		$this->error_line = "";
		$this->error_pos = "";
	}
	static function getClassName(){ return "Runtime.Exceptions.RuntimeException"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}