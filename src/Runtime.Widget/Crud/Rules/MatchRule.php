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


class MatchRule extends \Runtime\Widget\Crud\Rules\BaseRule
{
	const ALPHA_NUMERIC = "^[0-9a-zA-Z]*\$";
	const ALPHA_NUMERIC_DASH = "^[0-9a-zA-Z\\_\\-]*\$";
	var $name;
	var $regular;
	var $pattern;
	
	
	/**
	 * Validate item
	 */
	function validate($rules, $data)
	{
		$value = $data[$this->name];
		if ($value == "") return true;
		/* Match string */
		$check = \Runtime\re::match($this->regular, $value, $this->pattern);
		if (!$check)
		{
			$rules->addFieldError($this->name, "The field contains invalid characters");
			return false;
		}
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->regular = "^\$";
		$this->pattern = "";
	}
	static function getClassName(){ return "Runtime.Widget.Crud.Rules.MatchRule"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}