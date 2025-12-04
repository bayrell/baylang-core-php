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
namespace Runtime\Widget;

use Runtime\Widget\Messages\ValueChangeMessage;

class Select extends \Runtime\Widget\Field
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$props = $this->getProps();
		
		/* Element select */
		$__v0 = $__v->element("select", (new \Runtime\Map(["name" => $this->name, "class" => \Runtime\rs::className(new \Runtime\Vector("select", $this->class, $componentHash))]))->concat($props));
		
		$value = $this->getValue();
		$options = $this->getOptions();
		$selected = new \Runtime\Map();
		if ($this->show_select_value === true || $this->show_select_value == "true")
		{
			if ($value === "" || $value === null)
			{
				$selected = new \Runtime\Map(["selected" => "selected"]);
			}
			
			/* Element option */
			$__v1 = $__v0->element("option", (new \Runtime\Map(["value" => ""]))->concat($selected));
			$__v1->push("Select value");
		}
		
		if ($options != null)
		{
			for ($i = 0; $i < $options->count(); $i++)
			{
				$item = $options->get($i);
				$selected = new \Runtime\Map();
				if ($item->get("key") == $value && $value !== "" && $value !== null)
				{
					$selected = new \Runtime\Map(["selected" => "selected"]);
				}
				
				/* Element option */
				$__v2 = $__v0->element("option", (new \Runtime\Map(["value" => "" . $item->get("key")]))->concat($selected));
				$__v2->push($item->get("value"));
			}
		}
		
		return $__v;
	}
	var $name;
	var $value;
	var $default;
	var $options;
	var $show_select_value;
	/**
	 * Returns value
	 */
	function getValue()
	{
		if ($this->value !== "" && $this->value !== null) return $this->value;
		return $this->default;
	}
	/**
	 * Returns options
	 */
	function getOptions()
	{
		if ($this->model == null) return $this->options;
		return $this->model->getOptions();
	}
	/**
	 * Change event
	 */
	function onChange($e)
	{
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $e->target->value,
			"old_value" => $this->value,
			"data" => $this->data,
		])));
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->value = "";
		$this->default = "";
		$this->options = null;
		$this->show_select_value = true;
	}
	static function getComponentStyle(){ return ".select.h-d72c, .select.h-d72c:focus{width: 100%;font-family: var(--font-family);font-size: var(--font-input-size);padding: calc(var(--space) * 0.75) calc(var(--space) * 1.5);background-color: var(--color-background);border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: var(--border-radius);box-shadow: none;outline: transparent;line-height: normal;color: inherit;max-width: 100%;min-height: 32px;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.widget_select.h-d72c:hover{color: inherit}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Select"; }
}