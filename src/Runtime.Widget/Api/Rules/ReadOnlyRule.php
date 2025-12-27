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
use Runtime\Widget\Api\Rules\BaseRule;


class ReadOnlyRule extends \Runtime\BaseObject implements \Runtime\Widget\Api\Rules\BaseRule
{
	var $field_name;
	
	
	/**
	 * Create object
	 */
	function __construct($field_name)
	{
		parent::__construct();
		$this->field_name = $field_name;
	}
	
	
	/**
	 * Filter data
	 */
	function filter($data, $errors)
	{
		if (!$data->has($this->field_name)) return;
		$data->remove($this->field_name);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->field_name = "";
	}
	static function getClassName(){ return "Runtime.Widget.Api.Rules.ReadOnlyRule"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}