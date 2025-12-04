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


class Image extends \Runtime\Component
{
	function renderNoImage()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("image__no_image", $componentHash))])));
		$__v0->push("No image");
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		if ($this->src)
		{
			/* Element img */
			$__v->element("img", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("image", $this->class, $componentHash)), "src" => $this->src])));
		}
		else
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("image", $this->class, $componentHash))])));
			$__v0->push($this->renderNoImage());
		}
		
		return $__v;
	}
	var $src;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->src = "";
	}
	static function getComponentStyle(){ return ".image.h-3404{display: inline-block}.image__no_image.h-3404{display: flex;align-items: center;justify-content: center;font-size: 20px;text-transform: uppercase;width: 270px;height: 70px}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Image"; }
}