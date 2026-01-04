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
namespace Runtime\Widget\Crud;

use Runtime\BaseObject;
use Runtime\Widget\Crud\CrudService;
use Runtime\Widget\Crud\Rules\BaseRule;
use Runtime\Widget\Crud\Rules\CrudRule;


class RulesManager extends \Runtime\BaseObject
{
	var $action;
	var $is_create;
	var $rules;
	var $fields;
	
	
	/**
	 * Constructor
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Returns true if create
	 */
	function isCreate(){ return $this->is_create; }
	
	
	/**
	 * Returns true if update
	 */
	function isUpdate(){ return !$this->is_create; }
	
	
	/**
	 * Set create
	 */
	function setCreate($value)
	{
		$this->is_create = $value;
	}
	
	
	/**
	 * Returns fields
	 */
	function getFields(){ return $this->fields; }
	
	
	/**
	 * Returns true if all rules pass
	 */
	function correct(){ return $this->fields->keys()->count() == 0; }
	
	
	/**
	 * Clear
	 */
	function clear()
	{
		$this->fields = new \Runtime\Map();
	}
	
	
	/**
	 * Add rules
	 */
	function addRules($rules)
	{
		$this->rules->appendItems($rules);
	}
	
	
	/**
	 * Add field error
	 */
	function addFieldError($field_name, $error_message)
	{
		if (!$this->fields->has($field_name))
		{
			$this->fields->set($field_name, new \Runtime\Vector());
		}
		$messages = $this->fields->get($field_name);
		$messages->push($error_message);
	}
	
	
	/**
	 * Validate data
	 */
	function validate($data)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules->get($i);
			if ($rule instanceof \Runtime\Widget\Crud\Rules\BaseRule)
			{
				$rule->validate($this, $data);
			}
		}
	}
	/**
	 * Before search
	 */
	function onSearchBefore($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onSearchBefore($service);
			}
		}
	}
	
	
	/**
	 * After search
	 */
	function onSearchAfter($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onSearchAfter($service);
			}
		}
	}
	
	
	/**
	 * Before save
	 */
	function onSaveBefore($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onSaveBefore($service);
			}
		}
	}
	
	
	/**
	 * After save
	 */
	function onSaveAfter($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onSaveAfter($service);
			}
		}
	}
	
	
	/**
	 * Before delete
	 */
	function onDeleteBefore($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onDeleteBefore($service);
			}
		}
	}
	
	
	/**
	 * After delete
	 */
	function onDeleteAfter($service)
	{
		for ($i = 0; $i < $this->rules->count(); $i++)
		{
			$rule = $this->rules[$i];
			if ($rule instanceof \Runtime\Widget\Crud\Rules\CrudRule)
			{
				$rule->onDeleteAfter($service);
			}
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
		$this->is_create = false;
		$this->rules = new \Runtime\Vector();
		$this->fields = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Widget.Crud.RulesManager"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}