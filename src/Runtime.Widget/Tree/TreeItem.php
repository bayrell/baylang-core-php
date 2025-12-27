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
namespace Runtime\Widget\Tree;

use Runtime\lib;
use Runtime\BaseObject;
use Runtime\SerializeInterface;
use Runtime\Serializer;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Serializer\VectorType;
use Runtime\Widget\Tree\TreeModel;


class TreeItem extends \Runtime\BaseObject implements \Runtime\SerializeInterface
{
	var $key;
	var $label;
	var $open;
	var $items;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("key", new \Runtime\Serializer\StringType());
		$rules->addType("label", new \Runtime\Serializer\StringType());
		$rules->addType("items", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"autocreate" => true,
			"extends" => "Runtime.Widget.Tree.TreeItem",
		]))));
	}
	
	
	/**
	 * Assign rules
	 */
	function assignRules($rules){}
	
	
	/**
	 * Constructor
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Returns true if can insert inside
	 */
	function canDragInside(){ return true; }
	
	
	/**
	 * Get item
	 */
	function get($path)
	{
		if ($path == null) return null;
		if ($path->count() == 0) return $this;
		$pos = $path->first();
		$new_item = $this->items->get($pos);
		if ($new_item == null) return null;
		return $new_item->get($path->slice(1));
	}
	
	
	/**
	 * Find item position
	 */
	function find($item){ return $item ? $this->items->find(\Runtime\lib::equal($item)) : -1; }
	
	
	
	/**
	 * Context menu click
	 */
	function onContextMenu($model){}
	
	
	/**
	 * Click
	 */
	function onClick($model){}
	
	
	/**
	 * Select item
	 */
	function onSelect($model){}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->key = "";
		$this->label = "";
		$this->open = false;
		$this->items = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Widget.Tree.TreeItem"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}