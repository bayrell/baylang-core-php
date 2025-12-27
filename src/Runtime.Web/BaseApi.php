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
namespace Runtime\Web;

use Runtime\BaseObject;
use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\FieldException;
use Runtime\Exceptions\RuntimeException;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\MapType;
use Runtime\Web\ApiRequest;
use Runtime\Web\ApiResult;


class BaseApi extends \Runtime\BaseObject
{
	var $action;
	var $request;
	var $result;
	var $data;
	var $storage;
	
	
	/**
	 * Returns api name
	 */
	static function getApiName(){ return ""; }
	
	
	/**
	 * Returns data rules
	 */
	function getDataRules($rules){}
	
	
	/**
	 * Set action
	 */
	function setAction($action)
	{
		$this->action = $action;
	}
	
	
	/**
	 * Set request
	 */
	function setRequest($request)
	{
		$this->request = $request;
		$this->storage = $request->storage;
	}
	
	
	/**
	 * Filter rules
	 */
	function filter($data, $rules, $error = new \Runtime\Vector())
	{
		$data = $rules->filter($data, $error);
		if ($error->count() > 0)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\FieldException(new \Runtime\Map([
				"error" => TypeError::getMessages($error),
			])));
		}
		return $data;
	}
	
	
	/**
	 * Filter data
	 */
	function filterData()
	{
		$errors = new \Runtime\Vector();
		$rules = new \Runtime\Serializer\MapType();
		$this->getDataRules($rules);
		$this->data = $this->filter($this->request->data, $rules, $errors);
	}
	
	
	/**
	 * Set success
	 */
	function success($data = null)
	{
		return $this->result->success($data);
	}
	
	
	/**
	 * Setup exception
	 */
	function exception($e)
	{
		return $this->result->exception($e);
	}
	
	
	/**
	 * Setup fail
	 */
	function fail($data = null)
	{
		return $this->result->fail($data);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
		$this->request = null;
		$this->result = new \Runtime\Web\ApiResult();
		$this->data = null;
		$this->storage = null;
	}
	static function getClassName(){ return "Runtime.Web.BaseApi"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}