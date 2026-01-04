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
namespace Runtime\Widget\Api\Rules;

use Runtime\BaseObject;
use Runtime\Serializer;
use Runtime\ORM\Query;
use Runtime\Widget\Api\SaveApi;
use Runtime\Widget\Api\SearchApi;


class BaseRule extends \Runtime\BaseObject
{
	/**
	 * Create object
	 */
	function __construct($params)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Filter data
	 */
	function filter($data, $errors){ return $data; }
	
	
	/**
	 * On search before
	 */
	function onSearchBefore($api, $q){}
	
	
	/**
	 * On search after
	 */
	function onSearchAfter($api){}
	
	
	/**
	 * On save before
	 */
	function onSaveBefore($api){}
	
	
	/**
	 * On save after
	 */
	function onSaveAfter($api){}
	
	
	/**
	 * On delete before
	 */
	function onDeleteBefore($api){}
	
	
	/**
	 * On delete after
	 */
	function onDeleteAfter($api){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Widget.Api.Rules.BaseRule"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}