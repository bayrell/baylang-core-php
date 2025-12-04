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
namespace Runtime\Widget\Crud\Rules;

use Runtime\re;
use Runtime\Widget\Crud\RulesManager;
use Runtime\Widget\Crud\Rules\BaseRule;


class Purify extends \Runtime\Widget\Crud\Rules\BaseRule
{
	var $name;
	
	
	/**
	 * Purify item
	 */
	function purify($value)
	{
		$value = \Runtime\re::replace("[^a-z0-9_\\-\\.\\@]+\$", "", $value);
		$value = \Runtime\rs::trim($value);
		return $value;
	}
	
	
	/**
	 * Validate item
	 */
	function validate($rules, $data)
	{
		$value = $data->get($this->name);
		$value = $this->purify($value);
		$data->set($this->name, $value);
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Rules.Purify"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}