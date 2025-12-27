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
namespace Runtime\Widget\Form;

use Runtime\ApiResult;
use Runtime\BaseModel;
use Runtime\Method;
use Runtime\Message;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\ObjectType;
use Runtime\Widget\ResultModel;
use Runtime\Widget\Form\Form;


class FormModel extends \Runtime\BaseModel
{
	var $component;
	var $pk;
	var $item;
	var $field_errors;
	var $result;
	var $pk_type;
	var $item_type;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("pk", $rules->params ? $rules->params->get("pk_type") : null);
		$rules->addType("item", $rules->params ? $rules->params->get("item_type") : null);
		$rules->addType("result", new \Runtime\Serializer\ObjectType(new \Runtime\Map(["class_name" => "Runtime.Widget.ResultModel"])));
		$rules->setup->add(function ($model, $rules)
		{
			$model->pk_type = $rules->params ? $rules->params->get("pk_type") : null;
			$model->item_type = $rules->params ? $rules->params->get("item_type") : null;
		});
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
	}
	
	
	/**
	 * Init widget
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		$this->result = $this->createWidget("Runtime.Widget.ResultModel");
	}
	
	
	/**
	 * Set wait message
	 */
	function setWaitMessage()
	{
		$this->result->setWaitMessage();
	}
	
	
	/**
	 * Set api result
	 */
	function setApiResult($result)
	{
		$this->result->setApiResult($result);
		$this->setFieldErrors($result->data->get("fields"));
	}
	
	
	/**
	 * Set field errors
	 */
	function setFieldErrors($field_errors)
	{
		if (!$field_errors) return;
		$this->field_errors = new \Runtime\Map();
		$keys = \Runtime\rtl::list($field_errors->keys());
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$field_name = $keys->get($i);
			$messages = $field_errors->get($field_name);
			$message = \Runtime\rs::join(", ", $messages);
			$result = new \Runtime\Widget\ResultModel();
			$result->setError($message);
			$this->field_errors->set($field_name, $result);
		}
	}
	
	
	/**
	 * Returns result
	 */
	function getResult($name)
	{
		return $this->field_errors->get($name);
	}
	
	
	/**
	 * Set item value
	 */
	function setValue($name, $value)
	{
		$this->item->set($name, $value);
	}
	
	
	/**
	 * Set primary key
	 */
	function setPrimaryKey($item)
	{
		if ($this->pk_type)
		{
			$primary_key = $this->pk_type->keys();
			$this->pk = $this->pk_type->filter($item->intersect($primary_key), new \Runtime\Vector());
		}
		else
		{
			$this->pk = null;
		}
	}
	
	
	/**
	 * Set item
	 */
	function setItem($item)
	{
		$this->item = $this->item_type ? $this->item_type->filter($item, new \Runtime\Vector()) : $item;
	}
	
	
	/**
	 * Clear form
	 */
	function clear()
	{
		$this->pk = null;
		$this->item = $this->data_object ? \Runtime\rtl::newInstance($this->data_object) : new \Runtime\Map();
		$this->field_errors = new \Runtime\Map();
		$this->result->clear();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Form.Form";
		$this->pk = null;
		$this->item = new \Runtime\Map();
		$this->field_errors = new \Runtime\Map();
		$this->result = null;
		$this->pk_type = null;
		$this->item_type = null;
	}
	static function getClassName(){ return "Runtime.Widget.Form.FormModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}