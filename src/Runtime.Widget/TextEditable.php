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

use Runtime\Reference;
use Runtime\Widget\Messages\ValueChangeMessage;


class TextEditable extends \Runtime\Widget\Field
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$props = $this->getProps();
		
		/* Element div */
		$__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_editable", $this->class, $componentHash)), "name" => $this->name, "contenteditable" => "plaintext-only"]))->concat($props));
		
		return $__v;
	}
	var $reference;
	var $readonly;
	var $timeout;
	var $name;
	var $value;
	var $change_timer;
	var $old_value;
	/**
	 * Returns value
	 */
	function getValue(){ return $this->getRef("text")->innerText; }
	/**
	 * Set value
	 */
	function setValue($content)
	{
		$text = $this->getRef("text");
		$text->innerText = $content;
		$this->old_value = $content;
	}
	/**
	 * Returns textarea props
	 */
	function getProps()
	{
		if ($this->readonly) return new \Runtime\Map([
			"readonly" => true,
		]);
		return new \Runtime\Map();
	}
	/**
	 * Mounted event
	 */
	function mounted()
	{
		if ($this->reference) $this->reference->setValue($this);
		$this->setValue($this->value);
	}
	/**
	 * Updated event
	 */
	function updated()
	{
		if ($this->old_value == $this->value) return;
		if ($this->change_timer) return;
		$this->setValue($this->value);
	}
	/**
	 * Key down event
	 */
	function onKeyDown($e)
	{
		if ($e->key == "Tab")
		{
			$e->preventDefault();
			$e->stopPropagation();
			$selection = $this->getRef("text")->ownerDocument->defaultView->getSelection();
			$range = $selection->getRangeAt(0);
			$node = $document->createTextNode("\t");
			$range->insertNode($node);
			$range->setStartAfter($node);
			$range->setEndAfter($node);
		}
	}
	/**
	 * Input event
	 */
	function onInput($e)
	{
		if ($this->change_timer != null)
		{
			$window->clearTimeout($this->change_timer);
			$this->change_timer = null;
		}
		$this->change_timer = $window->setTimeout(function ()
		{
			$this->onChange();
		}, $this->timeout);
	}
	/**
	 * Change event
	 */
	function onChange($e)
	{
		$value = $this->getValue();
		/* Send value change */
		$this->emit(new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $value,
			"old_value" => $this->old_value,
			"data" => $this->data,
		])));
		/* Set old value */
		$this->old_value = $value;
		$this->change_timer = null;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->reference = null;
		$this->readonly = false;
		$this->timeout = 500;
		$this->name = "";
		$this->value = "";
		$this->change_timer = null;
		$this->old_value = null;
	}
	static function getComponentStyle(){ return ".text_editable.h-86cc{width: 100%;max-width: 100%;font-family: var(--font-family);font-size: var(--font-input-size);padding: calc(var(--space) * 0.75) calc(var(--space) * 1.5);margin: 0;background-color: var(--color-default);border-width: var(--border-width);border-color: var(--color-border);border-style: solid;border-radius: var(--space);transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type);box-shadow: none;overflow: auto;overflow-wrap: break-word;outline: transparent;line-height: normal;tab-size: 4;text-wrap: wrap;white-space: pre}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.TextEditable"; }
}