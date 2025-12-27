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
namespace Runtime\Widget\Table;

use Runtime\BaseModel;
use Runtime\Message;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\VectorType;
use Runtime\Widget\Table\Table;


class TableModel extends \Runtime\BaseModel
{
	var $component;
	var $items;
	var $item_type;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("items", new \Runtime\Serializer\VectorType($rules->params ? $rules->params->get("item_type") : null));
		$rules->setup->add(function ($model, $rules)
		{
			$model->item_type = $rules->params ? $rules->params->get("item_type") : null;
		});
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rules)
	{
		parent::assignRules($rules);
		$this->item_type = $rules->params->get("item_type");
	}
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
	}
	
	
	/**
	 * Set items
	 */
	function setItems($items)
	{
		$vector = new \Runtime\Serializer\VectorType($this->item_type);
		$this->items = $vector->filter($items, new \Runtime\Vector());
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Table.Table";
		$this->items = new \Runtime\Vector();
		$this->item_type = null;
	}
	static function getClassName(){ return "Runtime.Widget.Table.TableModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}