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


class ReadOnlyRule extends \Runtime\Widget\Crud\Rules\BaseRule
{
	var $name;
	var $can_create;
	var $can_update;
	
	
	/**
	 * Validate item
	 */
	function validate($rules, $data)
	{
		if ($rules->isCreate() && $this->can_create) return true;
		if ($rules->isUpdate() && $this->can_update) return true;
		$data->remove($this->name);
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->can_create = false;
		$this->can_update = false;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Rules.ReadOnlyRule"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}