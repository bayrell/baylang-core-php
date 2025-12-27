<?php
/*
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

use Runtime\Math;
use Runtime\Widget\Tree\Dnd;
use Runtime\Widget\Tree\TreeItem;
use Runtime\Widget\Tree\TreeMessage;


class TreeWidget extends \Runtime\Component
{
	function renderBox()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->dnd && $this->drag_dest_box != null && $this->is_drag)
		{
			/* Element div */
			$__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget__box", "tree_widget__box--" . $this->drag_dest_kind, $componentHash)), "style" => $this->drag_dest_box])));
		}
		
		return $__v;
	}
	function renderItemLabel($item, $path)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element span */
		$__v0 = $__v->element("span", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget__item_label", $componentHash))])));
		$__v0->push($item->label);
		
		return $__v;
	}
	function renderItemContent($item, $path)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderItemLabel($item, $path));
		
		return $__v;
	}
	function renderItem($item, $path)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget__item", $item == $this->model->selected_item ? "selected" : "", $componentHash)), "data-path" => \Runtime\rs::join(".", $path)])));
		$__v0->push($this->renderItemContent($item, $path));
		$__v->push($this->renderItems($item, $path));
		
		return $__v;
	}
	function renderItems($item, $path)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($item != null && $item->items != null && $item->items->count() > 0)
		{
			$key = $path->count() > 0 ? "item." . \Runtime\rs::join(".", $path) . ".items" : "item";
			
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget__items", !$item->open ? "hide" : "", $componentHash))])));
			
			for ($i = 0; $i < $item->items->count(); $i++)
			{
				$__v0->push($this->renderItem($item->items->get($i), $path->concat(new \Runtime\Vector($i))));
			}
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget", $componentHash))])));
		$__v0->push($this->renderBox());
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tree_widget__content", $componentHash))])));
		$__v1->push($this->renderItems($this->model->root, new \Runtime\Vector()));
		
		if ($this->model->context_menu && $this->model->context_menu_render)
		{
			$__v->push($this->renderWidget($this->model->context_menu));
		}
		
		return $__v;
	}
	var $dnd;
	/**
	 * Show context menu
	 */
	function showContextMenu($e)
	{
		if ($this->model->context_menu_render)
		{
			$x = $e->layerX;
			$y = $e->layerY;
		}
		else
		{
			$x = $e->clientX;
			$y = $e->clientY;
		}
		$this->model->context_menu->show($x, $y);
	}
	/**
	 * Mouse context menu item click
	 */
	function onContextMenuItem($e, $path)
	{
		/* Send message context menu */
		$this->model->listener->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"name" => "contextMenu",
			"path" => $path,
			"item" => $this->model->root->get($path),
			"event" => $e,
		])));
		/* Select item */
		if ($this->model->autoselect)
		{
			$this->model->selectItem($path);
			/* Send event */
			$this->model->listener->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
				"kind" => "context_menu",
				"name" => "selectItem",
				"path" => $path,
				"item" => $this->model->selected_item,
			])));
		}
		/* Show context menu */
		if ($this->model->context_menu)
		{
			$this->showContextMenu($e);
		}
		$e->preventDefault();
		$e->stopPropagation();
		return false;
	}
	/**
	 * Mouse down
	 */
	function onMouseDownItem($e, $path)
	{
		if ($e->button != 0) return;
		/* Hide context menu */
		if ($this->model->context_menu)
		{
			$this->model->context_menu->hide();
		}
		/* Select item */
		if ($this->model->autoselect)
		{
			$this->model->selectItem($path);
		}
		/* Send event */
		$this->model->listener->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"kind" => "click",
			"name" => "selectItem",
			"path" => $path,
			"item" => $this->model->root->get($path),
			"event" => $e,
		])));
		/* Set start drag item */
		if ($this->dnd) $this->dnd->setStartDragItem($e);
		$e->preventDefault();
		$e->stopPropagation();
		return false;
	}
	/**
	 * Mouse down
	 */
	function onMouseDown($e)
	{
		if ($this->model->context_menu)
		{
			$this->model->context_menu->hide();
		}
	}
	/**
	 * Mouse tree up
	 */
	function onMouseUp($e)
	{
		if ($this->dnd) $this->dnd->stopDrag();
	}
	/**
	 * Mouse move item
	 */
	function onMouseMoveItem($e, $item)
	{
		if ($this->dnd) $this->dnd->onMouseMoveItem($e, $item);
	}
	/**
	 * Mouse tree move
	 */
	function onMouseMove($e)
	{
		if ($this->dnd) $this->dnd->onMouseMove($e);
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->dnd = null;
	}
	static function getComponentStyle(){ return ".tree_widget.h-fd25{position: relative;height: 100%}.tree_widget__items.h-fd25 > .tree_widget__items{padding-left: 10px}.tree_widget__items.hide.h-fd25{display: none}.tree_widget__item_label.h-fd25{display: inline-block;padding: 5px;cursor: pointer;user-select: none}.tree_widget__item.selected.h-fd25 > .tree_widget__item_label{background-color: var(--color-primary);color: var(--color-primary-text)}.tree_widget__box.h-fd25{position: absolute;border-style: solid;border-width: 0;border-color: transparent}.tree_widget__box--into.h-fd25{background-color: rgba(255, 0, 0, 0.5);pointer-events: none}.tree_widget__box--before.h-fd25, .tree_widget__box--after.h-fd25{border-top-width: 2px;border-top-color: red}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Tree.TreeWidget"; }
}