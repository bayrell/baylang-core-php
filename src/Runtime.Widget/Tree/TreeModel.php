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

use Runtime\BaseObject;
use Runtime\BaseModel;
use Runtime\Reference;
use Runtime\Serializer;
use Runtime\Serializer\ObjectType;
use Runtime\Web\Events;
use Runtime\Widget\ContextMenu\ContextMenuModel;
use Runtime\Widget\Tree\TreeItem;
use Runtime\Widget\Tree\TreeMessage;
use Runtime\Widget\Tree\TreeWidget;


class TreeModel extends \Runtime\BaseModel
{
	var $component;
	var $autoselect;
	var $dnd;
	var $icons;
	var $is_open;
	var $context_menu_render;
	var $context_menu;
	var $selected_path;
	var $selected_item;
	var $root;
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("root", new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"autocreate" => true,
			"extends" => "Runtime.Widget.Tree.TreeItem",
		])));
	}
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
		if ($params->has("autoselect")) $this->autoselect = $params->get("autoselect");
		if ($params->has("dnd")) $this->dnd = $params->get("dnd");
		if ($params->has("icons")) $this->icons = $params->get("icons");
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Setup context menu */
		if ($params->has("context_menu"))
		{
			$this->setContextMenu($params->get("context_menu"));
			if ($params->has("context_menu_render"))
			{
				$this->context_menu_render = $params->get("context_menu_render");
			}
		}
	}
	
	
	/**
	 * Set context menu
	 */
	function setContextMenu($context_menu)
	{
		if ($context_menu instanceof \Runtime\Widget\ContextMenu\ContextMenuModel)
		{
			$this->context_menu_render = false;
			$this->context_menu = $context_menu;
		}
		else if ($context_menu instanceof \Runtime\Map)
		{
			$this->context_menu_render = true;
			$this->context_menu = $this->createWidget("Runtime.Widget.ContextMenu.ContextMenuModel", $context_menu);
		}
	}
	
	
	/**
	 * Select item
	 */
	function selectItem($path)
	{
		$item = $path ? $this->root->get($path) : null;
		if ($this->selected_item == $item) return;
		$this->selected_path = $path;
		$this->selected_item = $item;
		if ($this->selected_item)
		{
			$this->selected_item->onSelect();
		}
	}
	
	
	/**
	 * Can drag & drop
	 */
	function canDrag($src, $dest, $kind)
	{
		$message = new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"name" => "canDrag",
			"src" => $src,
			"dest" => $dest,
			"src_item" => $this->root->get($src),
			"dest_item" => $this->root->get($dest),
			"kind" => $kind,
			"result" => true,
		]));
		$this->emit($message);
		return $message->result;
	}
	
	
	/**
	 * Drag & Drop
	 */
	function dragElement($src, $dest, $kind)
	{
		if ($dest->count() == 0) $dest = new \Runtime\Vector($this->root->items->count() - 1);
		if (!$this->canDrag($src, $dest, $kind)) return;
		/* Move item */
		$src_item = $this->root->get($src);
		$dest_item = $this->root->get($dest);
		if (!$src_item) return;
		if (!$dest_item) return;
		/* Get parent items */
		$src_parent_path = $src->slice(0, -1);
		$dest_parent_path = $kind != "into" ? $dest->slice(0, -1) : $dest;
		$src_parent_item = $this->root->get($src_parent_path);
		$dest_parent_item = $this->root->get($dest_parent_path);
		/* Move item */
		$src_parent_item->items->removeItem($src_item);
		if ($kind == "into") $dest_parent_item->items->addItem($src_item, null, "before");
		else $dest_parent_item->items->addItem($src_item, $dest_item, $kind);
		/* Update dest path */
		$new_dest_parent_path = $dest_parent_path->slice();
		if ($src->count() <= $new_dest_parent_path->count())
		{
			$pos = $src->count() - 1;
			if ($src->get($pos) < $new_dest_parent_path->get($pos))
			{
				$new_dest_parent_path->set($pos, $new_dest_parent_path->get($pos) - 1);
			}
		}
		/* Create src new path */
		$pos = $dest_parent_item->find($src_item);
		$new_src_path = $new_dest_parent_path->pushIm($pos);
		/* Send drag & drop event */
		$this->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"name" => "dragElement",
			"dest_item" => $dest_item,
			"dest_parent_item" => $dest_parent_item,
			"kind" => $kind,
			"src_item" => $src_item,
			"src_parent_item" => $src_parent_item,
			"new_src_path" => $new_src_path,
		])));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Tree.TreeWidget";
		$this->autoselect = true;
		$this->dnd = false;
		$this->icons = true;
		$this->is_open = false;
		$this->context_menu_render = true;
		$this->context_menu = null;
		$this->selected_path = null;
		$this->selected_item = null;
		$this->root = null;
	}
	static function getClassName(){ return "Runtime.Widget.Tree.TreeModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}