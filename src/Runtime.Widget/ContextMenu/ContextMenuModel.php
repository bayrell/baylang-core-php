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
namespace Runtime\Widget\ContextMenu;

use Runtime\BaseModel;
use Runtime\Widget\ContextMenu\ContextMenu;
use Runtime\Widget\ContextMenu\ContextMenuMessage;


class ContextMenuModel extends \Runtime\BaseModel
{
	var $component;
	var $widget_name;
	var $is_open;
	var $width;
	var $x;
	var $y;
	var $items;
	var $data;
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("items")) $this->items = $params->get("items");
	}
	
	
	/**
	 * Set width
	 */
	function setWidth($value)
	{
		$this->width = $value;
	}
	
	
	/**
	 * Show dialog
	 */
	function show($x, $y)
	{
		$this->is_open = true;
		$this->x = $x;
		$this->y = $y;
	}
	
	
	/**
	 * Hide dialog
	 */
	function hide()
	{
		$this->is_open = false;
	}
	
	
	/**
	 * Add item
	 */
	function addItem($item)
	{
		$this->items->push($item);
	}
	
	
	/**
	 * Find index
	 */
	function find($key)
	{
		return $this->items->find(function ($item) use (&$key){ return $item->get("key") == $key; });
	}
	
	
	
	/**
	 * Find item
	 */
	function findItem($key){ return $this->items->get($this->find($key)); }
	
	
	/**
	 * On click
	 */
	function onClickItem($item)
	{
		$this->emit(new \Runtime\Widget\ContextMenu\ContextMenuMessage(new \Runtime\Map([
			"name" => "clickItem",
			"item" => $item,
		])));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.ContextMenu.ContextMenu";
		$this->widget_name = "context_menu";
		$this->is_open = false;
		$this->width = "";
		$this->x = 0;
		$this->y = 0;
		$this->items = new \Runtime\Vector();
		$this->data = null;
	}
	static function getClassName(){ return "Runtime.Widget.ContextMenu.ContextMenuModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}