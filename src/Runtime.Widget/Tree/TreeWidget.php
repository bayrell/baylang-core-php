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
use Runtime\Widget\Tree\TreeItem;
use Runtime\Widget\Tree\TreeMessage;


class TreeWidget extends \Runtime\Component
{
	function renderBox()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->drag_dest_box != null && $this->is_drag)
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
				$__v0->push($this->renderItem($item->items->get($i), $path->pushIm($i)));
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
		
		if ($this->model->context_menu && $this->model->render_context_menu)
		{
			$__v->push($this->renderWidget($this->model->context_menu));
		}
		
		return $__v;
	}
	var $is_drag;
	var $drag_elem;
	var $drag_start_point;
	var $drag_dest_box;
	var $drag_dest_elem;
	var $drag_dest_item;
	var $drag_dest_kind;
	/**
	 * Returns src elem
	 */
	function getSrc()
	{
		if (!$this->drag_elem) return null;
		$src_elem_path = $this->drag_elem->getAttribute("data-path");
		$src_elem = $src_elem_path ? \Runtime\rs::split(".", $src_elem_path) : new \Runtime\Vector();
		return $src_elem->map(function ($s){ return \Runtime\rtl::toInt($s); });
	}
	/**
	 * Returns dest elem
	 */
	function getDest()
	{
		if (!$this->drag_dest_elem) return null;
		$dest_elem_path = $this->drag_dest_elem->getAttribute("data-path");
		$dest_elem = $dest_elem_path ? \Runtime\rs::split(".", $dest_elem_path) : new \Runtime\Vector();
		return $dest_elem->map(function ($s){ return \Runtime\rtl::toInt($s); });
	}
	/**
	 * Find drag elem
	 */
	function findDragElem($elem)
	{
		if ($elem->classList->contains("tree_widget__item_label")) return $elem->parentElement;
		return $elem;
	}
	/**
	 * Find elem by path
	 */
	function findElemByPath($path)
	{
		$path = ".tree_widget__item[data-path='" . $path . "']";
		return $document->querySelector($path);
	}
	/**
	 * Returns true if elem inside drag_elem
	 */
	function checkInside($elem)
	{
		if (!$this->drag_elem) return false;
		if ($elem == $this->drag_elem) return false;
		$drag_elem_path = $this->drag_elem->getAttribute("data-path");
		$elem_path = $elem->getAttribute("data-path");
		if ($drag_elem_path == $elem_path) return true;
		if (\Runtime\rs::substr($elem_path, 0, \Runtime\rs::strlen($drag_elem_path) + 1) == $drag_elem_path . ".") return true;
		return false;
	}
	/**
	 * Start Drag & Drop
	 */
	function startDrag($e)
	{
		if (!$this->model->dnd) return false;
		if ($this->is_drag != false) return false;
		if ($this->drag_start_point == null) return false;
		if (\Runtime\Math::abs($e->layerY - $this->drag_start_point->get("y")) > 5) return false;
		$this->is_drag = true;
		return true;
	}
	/**
	 * Stop drag & drop
	 */
	function stopDrag()
	{
		/* Do drag & drop */
		if ($this->drag_dest_box && $this->drag_elem && $this->drag_dest_elem)
		{
			$this->model->dragElement($this->getSrc(), $this->getDest(), $this->drag_dest_kind);
		}
		$this->is_drag = false;
		$this->drag_dest_box = null;
		$this->drag_dest_elem = null;
		$this->drag_dest_item = null;
		$this->drag_dest_kind = null;
		$this->drag_elem = null;
		$this->drag_start_point = null;
	}
	/**
	 * Set drag & drop dest element
	 */
	function setDragDestElement($elem, $item, $kind)
	{
		if (!$this->is_drag) return;
		if ($this->checkInside($elem)) return;
		if ($kind == "into" && $this->drag_elem == $elem) $kind = "before";
		if ($kind == "into" && $item != null && !$item->canDragInside()) $kind = "after";
		if ($this->drag_dest_elem == $elem && $this->drag_dest_kind == $kind) return;
		/* Setup box */
		if ($this->drag_elem == $elem)
		{
			$this->drag_dest_box = null;
			return;
		}
		/* Setup dest element */
		$this->drag_dest_elem = $elem;
		$this->drag_dest_item = $item;
		/* Get elem path */
		$src_path = $this->getSrc();
		$dest_path = $this->getDest();
		if ($dest_path == null)
		{
			$this->drag_dest_box = null;
			return;
		}
		/* Can drag */
		$can_drag = $this->model->canDrag($src_path, $dest_path, $kind);
		if (!$can_drag)
		{
			if ($kind == "into")
			{
				$kind = "after";
				$can_drag = $this->model->canDrag($src_path, $dest_path, $kind);
				if (!$can_drag)
				{
					$this->drag_dest_box = null;
					return;
				}
			}
		}
		/* Setup dest values */
		$this->drag_dest_kind = $kind;
		$this->drag_dest_box = $this->getBoxStyles($elem, $kind);
	}
	/**
	 * Returns box styles by element
	 */
	function getBoxStyles($elem, $kind = "")
	{
		$left = $elem->offsetLeft;
		$top = $elem->offsetTop;
		$width = $elem->clientWidth - 1;
		$height = $elem->clientHeight - 1;
		if ($kind == "before") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top . "px",
			"width: " . $width . "px",
			"height: 1px",
		));
		if ($kind == "after") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top + $height . "px",
			"width: " . $width . "px",
			"height: 1px",
		));
		if ($kind == "into") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top . "px",
			"width: " . $width . "px",
			"height: " . $height . "px",
		));
		return null;
	}
	/**
	 * Show context menu
	 */
	function showContextMenu($e)
	{
		if ($this->model->render_context_menu)
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
	function onContextMenuItem($e, $item, $path)
	{
		/* Send message context menu */
		$this->model->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"name" => "contextMenu",
			"path" => $path,
			"item" => $item,
			"event" => $e,
		])));
		if ($item)
		{
			$item->onContextMenu($this->model);
		}
		/* Select item */
		if ($this->model->autoselect)
		{
			$this->model->selectItem($path);
			/* Send event */
			$this->model->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
				"kind" => "context_menu",
				"name" => "selectItem",
				"path" => $this->model->selected_path,
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
	function onMouseDownItem($e, $item, $path)
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
		$this->model->emit(new \Runtime\Widget\Tree\TreeMessage(new \Runtime\Map([
			"kind" => "click",
			"name" => "selectItem",
			"path" => $this->model->selected_path,
			"item" => $this->model->selected_item,
			"event" => $e,
		])));
		if ($item)
		{
			$item->onClick($this->model);
		}
		/* Set start drag item */
		if ($this->model->dnd)
		{
			$this->drag_elem = $this->findDragElem($e->currentTarget);
			$this->drag_start_point = new \Runtime\Map([
				"x" => $e->layerX,
				"y" => $e->layerY,
			]);
		}
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
		$this->stopDrag();
	}
	/**
	 * Mouse move item
	 */
	function onMouseMoveItem($e, $item)
	{
		if ($this->drag_elem == null) return;
		/* Try to start drag & drop */
		if (!$this->is_drag) $this->startDrag($e);
		if (!$this->is_drag) return;
		/* Drag & Drop started */
		$target = $e->currentTarget;
		$top = $target->offsetTop;
		$bottom = $target->offsetTop + $target->clientHeight;
		$center = ($top + $bottom) / 2;
		$kind = "before";
		if ($e->layerY >= $center)
		{
			$kind = "into";
		}
		$this->setDragDestElement($target, $item, $kind);
		$e->preventDefault();
	}
	/**
	 * Mouse tree move
	 */
	function onMouseMove($e)
	{
		if ($this->drag_elem == null) return;
		/* Try to start drag & drop */
		if (!$this->is_drag) $this->startDrag($e);
		if (!$this->is_drag) return;
		/* Outside of tree contents */
		$tree_content = $this->getRef("content");
		if ($e->layerY > $tree_content->clientHeight)
		{
			$this->setDragDestElement($tree_content, null, "after");
			$e->preventDefault();
			return false;
		}
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->is_drag = false;
		$this->drag_elem = null;
		$this->drag_start_point = null;
		$this->drag_dest_box = null;
		$this->drag_dest_elem = null;
		$this->drag_dest_item = null;
		$this->drag_dest_kind = null;
	}
	static function getComponentStyle(){ return ".tree_widget.h-fd25{position: relative;height: 100%}.tree_widget__items.h-fd25 > .tree_widget__items{padding-left: 10px}.tree_widget__items.hide.h-fd25{display: none}.tree_widget__item_label.h-fd25{display: inline-block;padding: 5px;cursor: pointer;user-select: none}.tree_widget__item.selected.h-fd25 > .tree_widget__item_label{background-color: var(--widget-color-primary);color: var(--widget-color-primary-text)}.tree_widget__box.h-fd25{position: absolute;border-style: solid;border-width: 0;border-color: transparent}.tree_widget__box--into.h-fd25{background-color: rgba(255, 0, 0, 0.5);pointer-events: none}.tree_widget__box--before.h-fd25, .tree_widget__box--after.h-fd25{border-top-width: 2px;border-top-color: red}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Tree.TreeWidget"; }
}