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
namespace Runtime\Widget\Sortable;

use Runtime\Serializer;
use Runtime\Widget\Messages\ValueChangeMessage;
use Runtime\Widget\Button;
use Runtime\Widget\Input;
use Runtime\Widget\Select;


class ItemList extends \Runtime\Component
{
	function renderValue($pos, $item)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element Runtime.Widget.Input */
		$__v->element("Runtime.Widget.Input", (new \Runtime\Map(["value" => $item])));
		
		return $__v;
	}
	function renderItem($pos)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$item = $this->getItems()->get($pos);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item", $componentHash)), "data-pos" => $pos])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_drag", $componentHash))])));
		$__v1->push("@raw");
		$__v1->push("&#9776;");
		
		/* Element div */
		$__v2 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_value", $componentHash))])));
		$__v2->push($this->renderValue($pos, $item));
		
		/* Element div */
		$__v3 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_remove", $componentHash))])));
		$__v3->push("@raw");
		$__v3->push("&#10005;");
		
		return $__v;
	}
	function renderItems()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__items", $componentHash))])));
		
		$items = $this->getItems();
		if ($items)
		{
			/* Element TransitionGroup */
			$__v1 = $__v0->element("TransitionGroup", (new \Runtime\Map(["name" => "sortable_list"])));
			
			/* Content */
			$__v1->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				
				for ($i = 0; $i < $items->count(); $i++)
				{
					$__v->push($this->renderItem($i));
				}
				
				return $__v;
			});
		}
		
		return $__v;
	}
	function renderButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->show_buttons == "true")
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__buttons", $componentHash))])));
			
			/* Element Runtime.Widget.Button */
			$__v1 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["styles" => new \Runtime\Vector("small")])));
			
			/* Content */
			$__v1->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Add");
				return $__v;
			});
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list", $componentHash))])));
		$__v0->push($this->renderItems());
		$__v0->push($this->renderButtons());
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__shadow", $componentHash))])));
		
		return $__v;
	}
	var $show_buttons;
	var $name;
	var $value;
	var $old_value;
	/* Drag & Drop */
	var $is_drag;
	var $is_transition;
	var $drag_elem;
	var $drag_item;
	var $drag_item_pos;
	var $shadow_elem;
	var $drag_elem_css;
	var $drag_start_point;
	var $duration;
	/**
	 * Returns items
	 */
	function getItems(){ return $this->value; }
	/**
	 * Create new item
	 */
	function createItem(){ return ""; }
	/**
	 * Copy item
	 */
	function copyItem($item){ return $item; }
	/**
	 * Create value
	 */
	function createValue(){ return new \Runtime\Vector(); }
	/**
	 * Swap items
	 */
	function swapItems($a, $b)
	{
		if ($a > $b)
		{
			$c = $a;
			$a = $b;
			$b = $c;
		}
		$items = $this->getItems();
		$obj_a = $items->get($a);
		$obj_b = $items->get($b);
		$items->remove($b);
		$items->insert($b, $obj_a);
		$items->remove($a);
		$items->insert($a, $obj_b);
	}
	/**
	 * Remove item
	 */
	function removeItem($pos)
	{
		$items = $this->getItems();
		$this->old_value = $this->value->slice();
		$items->remove($pos);
		$this->onValueChange();
	}
	/**
	 * Returns drag & drop element from point
	 */
	function getDragElementFromPoint($x, $y)
	{
		$items = $document->elementsFromPoint($x, $y);
		for ($i = 0; $i < $items->length; $i++)
		{
			$elem = $items[$i];
			if ($elem->parentElement == $this->shadow_elem) continue;
			if ($elem->classList->contains("sortable_list__item")) return $elem;
		}
		return null;
	}
	/**
	 * Returns drag & drop element
	 */
	function getDragElement($elem)
	{
		if ($elem->classList->contains("sortable_list__item")) return $elem;
		if ($elem->parentElement->classList->contains("sortable_list__item"))
		{
			return $elem->parentElement;
		}
		return null;
	}
	/**
	 * Create shadow elem
	 */
	function createShadow()
	{
		if (!$this->drag_elem) return;
		$this->shadow_elem = $document->createElement("div");
		$this->shadow_elem->innerHTML = $this->drag_elem->outerHTML;
		$this->shadow_elem->classList->add("sortable_list__shadow_elem");
		$items = \Runtime\rs::split(" ", \Runtime\rs::getComponentHash($this->getClassName()));
		for ($i = 0; $i < $items->count(); $i++) $this->shadow_elem->classList->add($items->get($i));
		$arr = \Runtime\rs::split(" ", \Runtime\rs::getCssHash($this->getClassName()));
		$arr = $arr->filter(function ($item){ return $item != ""; });
		for ($i = 0; $i < $arr->count(); $i++)
		{
			$this->shadow_elem->classList->add($arr->get($i));
		}
		$this->shadow_elem->style->height = $this->drag_elem->offsetHeight . "px";
		$this->shadow_elem->style->width = $this->drag_elem->offsetWidth . "px";
		$this->getRef("shadow")->appendChild($this->shadow_elem);
	}
	/**
	 * Move shadow element
	 */
	function moveShadow($x, $y)
	{
		if (!$this->shadow_elem) return;
		$left = $x - $this->drag_start_point->get("shift_x");
		$top = $y - $this->drag_start_point->get("shift_y");
		$this->shadow_elem->style->left = $left . "px";
		$this->shadow_elem->style->top = $top . "px";
	}
	/**
	 * Start Drag & Drop
	 */
	function startDrag($e)
	{
		if ($this->is_drag != false) return false;
		if ($this->drag_start_point == null) return false;
		/* Check move */
		$move_x = \Runtime\rtl::abs($e->pageX - $this->drag_start_point->get("x"));
		$move_y = \Runtime\rtl::abs($e->pageY - $this->drag_start_point->get("y"));
		if ($move_x < 10 && $move_y < 10) return false;
		/* Start drag */
		$this->is_drag = true;
		$this->createShadow();
		return true;
	}
	/**
	 * Stop drag & drop
	 */
	function stopDrag()
	{
		if (!$this->is_drag) return;
		$this->is_drag = false;
		$this->is_transition = false;
		$this->drag_elem = null;
		$this->drag_start_point = null;
		$this->shadow_elem->remove();
		$this->shadow_elem = null;
	}
	/**
	 * Move drag & drop
	 */
	function moveDrag($e)
	{
		if (!$this->is_drag) return;
		$this->moveShadow($e->pageX, $e->pageY);
		if ($this->is_transition) return;
		$elem = $this->getDragElementFromPoint($e->pageX, $e->pageY);
		if (!$elem) return;
		$pos = $elem->getAttribute("data-pos");
		if ($pos == $this->drag_item_pos) return;
		/* Swap items with animation */
		$this->is_transition = true;
		$this->old_value = $this->value->slice();
		$this->swapItems($this->drag_item_pos, $pos);
		$this->drag_item_pos = $pos;
		/* Stop animation */
		$window->setTimeout(function ()
		{
			$this->is_transition = false;
		}, $this->duration);
		/* Send value change */
		$this->onValueChange();
	}
	/**
	 * On value change
	 */
	function onValueChange()
	{
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $this->old_value,
			"data" => $this->data,
		])));
	}
	/**
	 * Add item click
	 */
	function onAddItemClick()
	{
		$items = $this->getItems();
		if ($items == null)
		{
			$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
				"value" => $this->createValue(),
				"old_value" => $this->old_value,
				"data" => $this->data,
			])));
		}
		$this->nextTick(function ()
		{
			$this->old_value = $this->value->slice();
			$items = $this->getItems();
			$items->push($this->createItem());
			$this->onValueChange();
		});
	}
	/**
	 * Mouse down
	 */
	function onMouseDown($e, $item)
	{
		if ($e->button != 0) return;
		if ($this->is_drag) return;
		/* Set start drag item */
		$this->drag_elem = $this->getDragElement($e->target);
		$this->drag_item = $item;
		$this->drag_item_pos = $this->drag_elem->getAttribute("data-pos");
		$this->drag_start_point = new \Runtime\Map([
			"x" => $e->pageX,
			"y" => $e->pageY,
			"shift_x" => $e->pageX - $e->target->offsetLeft,
			"shift_y" => $e->pageY - $e->target->offsetTop,
		]);
		/* Add event listener */
		$document->addEventListener("mouseup", $this->onMouseUp);
		$document->addEventListener("mousemove", $this->onMouseMove);
		$e->preventDefault();
		return false;
	}
	/**
	 * Mouse tree up
	 */
	function onMouseUp($e)
	{
		/* Remove event listener */
		$document->removeEventListener("mouseup", $this->onMouseUp);
		$document->removeEventListener("mousemove", $this->onMouseMove);
		/* Stop drag & drop */
		$this->stopDrag();
	}
	/**
	 * Mouse move
	 */
	function onMouseMove($e)
	{
		if ($this->drag_elem == null) return;
		/* Try to start drag & drop */
		if (!$this->is_drag) $this->startDrag($e);
		if (!$this->is_drag) return;
		/* Move Drag & Drop */
		$this->moveDrag($e);
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->show_buttons = "true";
		$this->name = "";
		$this->value = new \Runtime\Vector();
		$this->old_value = new \Runtime\Vector();
		$this->is_drag = false;
		$this->is_transition = false;
		$this->drag_elem = null;
		$this->drag_item = null;
		$this->drag_item_pos = -1;
		$this->shadow_elem = null;
		$this->drag_elem_css = null;
		$this->drag_start_point = null;
		$this->duration = 300;
	}
	static function getComponentStyle(){ return ".sortable_list.h-3bb3{position: relative}.sortable_list__item.h-3bb3{display: flex;align-items: center;justify-content: flex-start;border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: 4px;margin: 5px}.sortable_list__item_drag.h-3bb3, .sortable_list__item_remove.h-3bb3{cursor: pointer;padding: 0px 5px}.sortable_list__item_value.h-3bb3{flex: 1}.sortable_list__item_value.h-3bb3 .input, .sortable_list__item_value.h-3bb3 .select{padding: 0px 10px;border-color: transparent;border-radius: 0;border-width: 0}.sortable_list__buttons.h-3bb3{text-align: center;margin-top: var(--space)}.sortable_list__shadow_elem.h-3bb3{position: absolute;opacity: 0.5;user-select: none;z-index: 9999999}.sortable_list__shadow_elem.h-3bb3 .sortable_list__item_drag{cursor: grabbing}.sortable_list__shadow_elem.h-3bb3 .sortable_list__item{margin: 0}.sortable_list-move.h-3bb3, .sortable_list-enter-active.h-3bb3, .sortable_list-leave-active.h-3bb3{transition: all 0.3s ease}.sortable_list-enter-from.h-3bb3, .sortable_list-leave-to.h-3bb3{opacity: 0}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button", "Runtime.Widget.Input", "Runtime.Widget.Select"); }
	static function getClassName(){ return "Runtime.Widget.Sortable.ItemList"; }
}