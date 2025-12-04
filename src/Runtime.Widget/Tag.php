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

class Tag extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tag", $componentHash))])));
		
		if ($this->value)
		{
			for ($i = 0; $i < $this->value->count(); $i++)
			{
				/* Element div */
				$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tag__item", $componentHash))])));
				
				/* Element div */
				$__v2 = $__v1->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tag__text", $componentHash)), "contenteditable" => "true"])));
				$__v2->push($this->value->get($i));
				
				/* Element div */
				$__v3 = $__v1->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tag__close", $componentHash))])));
				$__v3->push("x");
			}
		}
		
		/* Element span */
		$__v0->element("span", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tag__span", $componentHash)), "contenteditable" => "true"])));
		
		return $__v;
	}
	var $name;
	var $value;
	/**
	 * Text change
	 */
	function onTextChange($i, $value)
	{
		$old_value = $this->value->slice();
		$this->value->set($i, $value);
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $old_value,
			"data" => $this->data,
		])));
	}
	/**
	 * Text keydown
	 */
	function onTextKeyDown($i, $value, $e)
	{
		if ($e->keyCode != 13) return;
		/* Set value */
		$old_value = $this->value->slice();
		$this->value->set($i, $value);
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $old_value,
			"data" => $this->data,
		])));
		$e->preventDefault();
		return false;
	}
	/**
	 * Close click
	 */
	function onCloseClick($i)
	{
		$old_value = $this->value->slice();
		$this->value->remove($i);
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $old_value,
			"data" => $this->data,
		])));
	}
	/**
	 * Span blur
	 */
	function onSpanBlur($e)
	{
		$span = $this->getRef("span");
		if ($span->innerText == "") return;
		/* Add value */
		$old_value = $this->value->slice();
		$this->value->push($span->innerText);
		$span->innerText = "";
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $old_value,
			"data" => $this->data,
		])));
	}
	/**
	 * Span keydown
	 */
	function onSpanKeyDown($e)
	{
		if ($e->keyCode != 13) return;
		$span = $this->getRef("span");
		if ($span->innerText == "") return;
		/* Add value */
		$old_value = $this->value->slice();
		$this->value->push($span->innerText);
		$span->innerText = "";
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $this->value,
			"old_value" => $old_value,
			"data" => $this->data,
		])));
		$e->preventDefault();
		return false;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->value = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".tag.h-27ed{display: flex;flex-wrap: wrap;font-family: var(--font-family);font-size: var(--font-size);background-color: var(--color-default);border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: var(--border-radius);box-shadow: none;outline: transparent;line-height: normal;padding: var(--space);min-height: 40px;width: 100%}.tag__item.h-27ed{display: flex;align-items: stretch;justify-content: space-between;border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: 4px;padding: 3px 5px;margin: 5px}.tag__text.h-27ed{overflow-wrap: anywhere;outline: 0}.tag__close.h-27ed{display: inline-flex;align-items: center;justify-content: center;margin-left: 5px;cursor: pointer}.tag__span.h-27ed{overflow-wrap: anywhere;min-width: 100px;padding: 3px;margin: 5px;outline: 0}.tag__span.h-27ed:first-child{padding-left: 0}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Tag"; }
}