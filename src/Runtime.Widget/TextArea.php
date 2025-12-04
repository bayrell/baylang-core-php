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

class TextArea extends \Runtime\Widget\Field
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$props = $this->getProps();
		
		/* Element textarea */
		$__v->element("textarea", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("textarea", $this->class, $componentHash)), "name" => $this->name, "placeholder" => $this->placeholder, "style" => $this->getStyle()]))->concat($props));
		
		return $__v;
	}
	var $direct_update;
	var $readonly;
	var $height;
	var $name;
	var $value;
	var $placeholder;
	var $change_timer;
	/**
	 * Returns style
	 */
	function getStyle()
	{
		$content = new \Runtime\Vector();
		if ($this->height) $content->push("min-height: " . $this->height);
		return \Runtime\rs::join(";", $content);
	}
	/**
	 * Updated event
	 */
	function updated()
	{
		$textarea = $this->getRef("textarea");
		$textarea->value = $this->value;
	}
	/**
	 * KeyDown event
	 */
	function onKeyDown($e)
	{
		if (!$this->direct_update) return;
		if ($this->change_timer != null)
		{
			$window->clearTimeout($this->change_timer);
			$this->change_timer = null;
		}
		$this->change_timer = $window->setTimeout(function ()
		{
			$this->onChange();
		}, 300);
	}
	/**
	 * Change event
	 */
	function onChange()
	{
		$textarea = $this->getRef("textarea");
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $textarea->value,
			"old_value" => $this->value,
			"data" => $this->data,
		])));
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->direct_update = false;
		$this->readonly = false;
		$this->height = "";
		$this->name = "";
		$this->value = "";
		$this->placeholder = "";
		$this->change_timer = null;
	}
	static function getComponentStyle(){ return ".textarea.h-ee81{width: 100%;max-width: 100%;font-family: var(--font-family);font-size: var(--font-input-size);padding: calc(var(--space) * 0.75) calc(var(--space) * 1.5);background-color: var(--color-default);border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: var(--space);box-shadow: none;outline: transparent;line-height: normal;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.textarea.h-ee81:focus{outline: transparent}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.TextArea"; }
}