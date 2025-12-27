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

use Runtime\Widget\Input;
use Runtime\Widget\Select;


class FieldList extends \Runtime\Widget\Sortable\ItemList
{
	function renderValueItem($field, $pos, $item)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_value_row", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_value_label", $componentHash))])));
		$__v1->push($field->get("label"));
		
		/* Element div */
		$__v2 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_value_item", $componentHash))])));
		
		if ($item)
		{
			$field_name = $field->get("name");
			$field_component = $field->get("component");
			$field_props = $field->get("props");
			if (!$field_props)
			{
				$field_props = new \Runtime\Map();
			}
			
			/* Element $field_component */
			$__v2->element($field_component, (new \Runtime\Map(["name" => $field_name, "value" => $this->getValue($field, $item)]))->concat($field_props));
		}
		
		return $__v;
	}
	function renderValue($pos, $item)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		for ($i = 0; $i < $this->fields->count(); $i++)
		{
			$field = $this->fields->get($i);
			$__v->push($this->renderValueItem($field, $pos, $item));
		}
		
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
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_element sortable_list__item_element--drag", $componentHash))])));
		
		/* Element div */
		$__v2 = $__v1->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_drag", $componentHash))])));
		$__v2->push("☰");
		
		/* Element div */
		$__v3 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_value", $componentHash))])));
		$__v3->push($this->renderValue($pos, $item));
		
		/* Element div */
		$__v4 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_element sortable_list__item_element--remove", $componentHash))])));
		
		/* Element div */
		$__v5 = $__v4->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("sortable_list__item_remove", $componentHash))])));
		$__v5->push("✕");
		
		return $__v;
	}
	var $fields;
	/**
	 * Create new item
	 */
	function createItem(){ return new \Runtime\Map(); }
	/**
	 * Returns value
	 */
	function getValue($field, $item)
	{
		if ($field->has("value"))
		{
			$value = $field->get("value");
			return $value($item);
		}
		$field_name = $field->get("name");
		return $item->get($field_name);
	}
	/**
	 * Set value
	 */
	function setValue($field, $item, $message)
	{
		if ($field->has("setValue"))
		{
			$setValue = $field->get("setValue");
			$setValue($item, $message->value);
		}
		else
		{
			$field_name = $field->get("name");
			$item->set($field_name, $message->value);
		}
		$this->onValueChange();
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
		if ($elem->parentElement->parentElement->classList->contains("sortable_list__item"))
		{
			return $elem->parentElement->parentElement;
		}
		return null;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->fields = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".sortable_list__item.h-a648{align-items: stretch;margin: 10px 0px}.sortable_list__item_element.h-a648{display: flex}.sortable_list__item_element--drag.h-a648{align-items: center}.sortable_list__item_element--remove.h-a648{align-items: start}.sortable_list__item_value.h-a648{padding: 5px}.sortable_list__item_value_row.h-a648{display: flex;align-items: center;margin-bottom: 1px}.sortable_list__item_value_label.h-a648{width: 80px}.sortable_list__item_value_item.h-a648{flex: 1}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Input", "Runtime.Widget.Select"); }
	static function getClassName(){ return "Runtime.Widget.Sortable.FieldList"; }
}