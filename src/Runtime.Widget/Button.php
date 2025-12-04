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


class Button extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		if ($this->href == null)
		{
			/* Element button */
			$__v0 = $__v->element("button", (new \Runtime\Map(["type" => $this->type, "class" => \Runtime\rs::className(new \Runtime\Vector("button", \Runtime\rs::mergeStyles("button", $this->styles), $this->class, $componentHash))])));
			$__v0->push($this->renderSlot("default"));
		}
		else
		{
			/* Element a */
			$__v1 = $__v->element("a", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("button", $this->class, \Runtime\rs::mergeStyles("button", $this->styles), $componentHash)), "href" => $this->href, "target" => $this->target])));
			$__v1->push($this->renderSlot("default"));
		}
		
		return $__v;
	}
	var $type;
	var $target;
	var $content;
	var $href;
	var $styles;
	/**
	 * Button click
	 */
	function onClick($e)
	{
		$e->stopPropagation();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->type = "button";
		$this->target = "_self";
		$this->content = "";
		$this->href = null;
		$this->styles = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".button{display: inline-flex;align-items: center;justify-content: center;color: var(--color-text);font-weight: 500;font-family: var(--font-family);font-size: var(--font-input-size);line-height: var(--line-height);text-decoration: none;background-color: var(--color-surface);border: var(--border-width) var(--color-border) solid;padding: calc(var(--space) * 0.75) calc(var(--space) * 1.5);outline: none;cursor: pointer;border-radius: var(--border-radius);transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.button:hover{box-shadow: 0 0 0 calc(var(--space) * 0.25) rgba(var(--color-primary-dark), 0.25)}.button:active{box-shadow: inset 0px 2px 5px 0px rgba(0,0,0,0.25)}.button--small{padding: calc(var(--space) * 0.5) calc(var(--space) * 1);line-height: 1.2em}.button--large{padding: calc(var(--space) * 1) calc(var(--space) * 2)}.button--primary{color: var(--color-primary-text);background-color: var(--color-primary);border-color: var(--color-primary-border)}.button--primary:hover{background-color: var(--color-primary-hover);border-color: var(--color-primary-hover)}.button--secondary{color: var(--color-secondary-text);background-color: var(--color-secondary);border-color: var(--color-secondary-border)}.button--secondary:hover{background-color: var(--color-secondary-hover);border-color: var(--color-secondary-hover)}.button--outline{background-color: transparent;color: var(--color-text);border-color: var(--color-border)}.button--outline:hover{background-color: var(--color-surface)}.button--danger{color: var(--color-danger-text);background-color: var(--color-danger);border-color: var(--color-danger-border)}.button--danger:hover{background-color: var(--color-danger-hover)}.button--success{color: var(--color-success-text);background-color: var(--color-success);border-color: var(--color-success-border)}.button--success:hover{background-color: var(--color-success-hover)}.button--info{color: var(--color-info-text);background-color: var(--color-info);border-color: var(--color-info-border)}.button--info:hover{background-color: var(--color-info-hover)}.button--warning{background-color: var(--color-warning);border-color: var(--color-warning-border)}.button--warning:hover{background-color: var(--color-warning-hover)}.button--stretch{width: 100%}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Button"; }
}