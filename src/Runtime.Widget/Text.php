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

use Runtime\VirtualDom;

class Text extends \Runtime\Component
{
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$content = \Runtime\rs::split("\n", $this->content);
		if ($content->count() == 1)
		{
			$__v->push($this->renderText($content->get(0)));
		}
		else
		{
			for ($i = 0; $i < $content->count(); $i++)
			{
				$item = $content->get($i);
				
				/* Element div */
				$__v0 = $__v->element("div");
				$__v0->push($this->renderText($item));
			}
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		if ($this->tag == "p")
		{
			/* Element p */
			$__v0 = $__v->element("p", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v0->push($this->renderContent());
		}
		else if ($this->tag == "h1")
		{
			/* Element h1 */
			$__v1 = $__v->element("h1", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v1->push($this->renderContent());
		}
		else if ($this->tag == "h2")
		{
			/* Element h2 */
			$__v2 = $__v->element("h2", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v2->push($this->renderContent());
		}
		else if ($this->tag == "h3")
		{
			/* Element h3 */
			$__v3 = $__v->element("h3", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v3->push($this->renderContent());
		}
		else if ($this->tag == "h4")
		{
			/* Element h4 */
			$__v4 = $__v->element("h4", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v4->push($this->renderContent());
		}
		else if ($this->tag == "html")
		{
			/* Element div */
			$__v5 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v5->push($this->renderContent());
		}
		else
		{
			/* Element div */
			$__v6 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text", $this->class, $componentHash))])));
			$__v6->push($this->renderContent());
		}
		
		return $__v;
	}
	var $tag;
	var $raw;
	var $content;
	/**
	 * Returns key
	 */
	function getKey()
	{
		$key = $this->tag;
		if ($this->raw == "true") $key = $key . "-raw";
		return $key;
	}
	/**
	 * Render content
	 */
	function renderText($content)
	{
		$vdom = new \Runtime\VirtualDom();
		$vdom->push($content);
		$vdom->is_raw = $this->raw == "true";
		return $vdom;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->tag = "text";
		$this->raw = "false";
		$this->content = "Text";
	}
	static function getComponentStyle(){ return ".text.h-8fb4{padding: 0;margin: 0}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Text"; }
}