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
namespace Runtime\Widget;

use Runtime\ApiResult;
use Runtime\BaseModel;
use Runtime\SerializeInterface;
use Runtime\Serializer\IntegerType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Exceptions\AbstractException;
use Runtime\Widget\Result;


class ResultModel extends \Runtime\BaseModel
{
	var $code;
	var $message;
	var $component;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("code", new \Runtime\Serializer\IntegerType());
		$rules->addType("message", new \Runtime\Serializer\StringType());
	}
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
	}
	
	
	/**
	 * Message
	 */
	function setMessage($message)
	{
		$this->message = $message;
	}
	
	
	/**
	 * Success
	 */
	function setSuccess($message, $code = 1)
	{
		$this->code = $code;
		$this->message = $message;
	}
	
	
	/**
	 * Error
	 */
	function setError($message, $code = -1)
	{
		$this->code = $code;
		$this->message = $message;
	}
	
	
	/**
	 * Set exception
	 */
	function setException($e)
	{
		$this->code = $e->getErrorCode();
		$this->message = $e->getErrorMessage();
	}
	
	
	/**
	 * Set api result
	 */
	function setApiResult($res)
	{
		$this->code = $res->code;
		$this->message = $res->message;
	}
	
	
	/**
	 * Set wait message
	 */
	function setWaitMessage($message = "")
	{
		$this->code = 0;
		$this->message = $message != "" ? $message : "Wait please ...";
	}
	
	
	/**
	 * Clear error
	 */
	function clear()
	{
		$this->code = 0;
		$this->message = "";
	}
	
	
	/**
	 * Returns true if error
	 */
	function isError(){ return $this->code < 0; }
	
	
	/**
	 * Returns true if success
	 */
	function isSuccess(){ return $this->code > 0; }
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->code = 0;
		$this->message = "";
		$this->component = "Runtime.Widget.Result";
	}
	static function getClassName(){ return "Runtime.Widget.ResultModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}