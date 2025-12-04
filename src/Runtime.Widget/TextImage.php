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

use Runtime\Widget\Image;

class TextImage extends \Runtime\Component
{
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$content = \Runtime\rs::split("\n", $this->content);
		if ($content->count() == 1)
		{
			$__v->push($content->get(0));
		}
		else
		{
			for ($i = 0; $i < $content->count(); $i++)
			{
				$item = $content->get($i);
				
				/* Element div */
				$__v0 = $__v->element("div");
				$__v0->push(!\Runtime\rtl::isEmpty($item) ? $item : "");
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
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_image", $this->getClass(), $this->class, $componentHash))])));
		
		if ($this->kind == "text_bottom" || $this->kind == "text_right")
		{
			/* Element div */
			$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_image__image", $componentHash))])));
			
			/* Element Runtime.Widget.Image */
			$__v1->element("Runtime.Widget.Image", (new \Runtime\Map(["src" => $this->image])));
			
			/* Element div */
			$__v2 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_image__text", $componentHash))])));
			$__v2->push($this->renderContent($this->content));
		}
		else if ($this->kind == "text_top" || $this->kind == "text_left")
		{
			/* Element div */
			$__v3 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_image__text", $componentHash))])));
			$__v3->push($this->renderContent($this->content));
			
			/* Element div */
			$__v4 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("text_image__image", $componentHash))])));
			
			/* Element Runtime.Widget.Image */
			$__v4->element("Runtime.Widget.Image", (new \Runtime\Map(["src" => $this->image])));
		}
		
		return $__v;
	}
	var $kind;
	var $image;
	var $content;
	/**
	 * Returns class
	 */
	function getClass()
	{
		$styles = new \Runtime\Vector();
		$styles->push("widget_text_image--" . $this->kind);
		return \Runtime\rs::join(" ", $styles);
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->kind = "text_right";
		$this->image = "";
		$this->content = "";
	}
	static function getComponentStyle(){ return ".text_image--text_left.h-e2c6, .text_image--text_right.h-e2c6{display: flex;align-items: center;justify-content: space-between}.text_image--text_top.h-e2c6, .text_image--text_bottom.h-e2c6{text-align: center}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Image"); }
	static function getClassName(){ return "Runtime.Widget.TextImage"; }
}