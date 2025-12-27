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
namespace Runtime\Widget\Form;

use Runtime\Widget\Form\FormRow;

class Form extends \Runtime\Component
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("title"))
		{
			$__v->push($this->renderSlot("title"));
		}
		
		return $__v;
	}
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("default") && $this->fields->count() == 0)
		{
			$__v->push($this->renderSlot("default"));
		}
		else
		{
			for ($i = 0; $i < $this->fields->count(); $i++)
			{
				$field = $this->fields->get($i);
				$props = $field->get("props");
				$component = $field->get("component");
				$name = $field->get("name");
				if (!$props)
				{
					$props = new \Runtime\Map();
				}
				
				/* Element Runtime.Widget.Form.FormRow */
				$__v0 = $__v->element("Runtime.Widget.Form.FormRow", (new \Runtime\Map(["name" => $name, "label" => $field->get("label"), "key" => $name, "result" => $this->model->getResult($name)])));
				
				/* Content */
				$__v0->slot("default", function ()
				{
					$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
					$__v = new \Runtime\VirtualDom($this);
					
					/* Element $component */
					$__v->element($component, (new \Runtime\Map(["name" => $name, "value" => $this->getValue($field)]))->concat($props));
					
					return $__v;
				});
			}
			$__v->push($this->renderWidget($this->model->result, new \Runtime\Map([
				"class" => "result--form",
			])));
		}
		
		return $__v;
	}
	function renderButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("buttons"))
		{
			$__v->push($this->renderSlot("buttons"));
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element form */
		$__v0 = $__v->element("form", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form", $componentHash))])));
		$__v0->push($this->renderTitle());
		$__v0->push($this->renderContent());
		$__v0->push($this->renderButtons());
		
		return $__v;
	}
	var $fields;
	/**
	 * Returns value
	 */
	function getValue($field)
	{
		if ($field->has("value"))
		{
			$value = $field->get("value");
			return $value($this->model->item);
		}
		$name = $field->get("name");
		return $this->model->item->get($name);
	}
	/**
	 * Set value
	 */
	function setValue($field, $value)
	{
		if ($field->has("setValue"))
		{
			$setValue = $field->get("setValue");
			$setValue($this->model->item, $value);
		}
		else
		{
			$name = $field->get("name");
			$this->model->setValue($name, $value);
		}
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->fields = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".form.h-b6a7 textarea{min-height: 300px}.form.h-b6a7 .row_buttons{justify-content: center}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Form.FormRow"); }
	static function getClassName(){ return "Runtime.Widget.Form.Form"; }
}