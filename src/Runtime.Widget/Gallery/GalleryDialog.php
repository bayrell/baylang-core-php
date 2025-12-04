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
namespace Runtime\Widget\Gallery;

use Runtime\Widget\Button;

class GalleryDialog extends \Runtime\Widget\Dialog\Dialog
{
	function renderDialogContainer()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__container--photo", $this->model->getImageContains() ? "widget_dialog__container--photo_contain" : "", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__image", $componentHash))])));
		
		/* Element Runtime.Widget.Button */
		$__v2 = $__v1->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__close", $componentHash))])));
		
		/* Content */
		$__v2->slot("default", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			$__v->push("x");
			return $__v;
		});
		
		/* Element img */
		$__v1->element("img", (new \Runtime\Map(["src" => $this->model->getCurrentImage(), "unselectable" => "on"])));
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__clear", $componentHash))])));
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__arrow widget_dialog__arrow--left", $componentHash))])));
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__arrow widget_dialog__arrow--right", $componentHash))])));
		
		return $__v;
	}
	/**
	 * On container click
	 */
	function onContainerClick($e)
	{
		if ($e->target->classList->contains("widget_dialog__arrow") || $e->target->classList->contains("widget_dialog__clear") || $e->target->classList->contains("widget_dialog__close") || $e->target->tagName == "IMG")
		{
			return;
		}
		$this->onShadowClick();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".widget_dialog__container--photo.h-ebbc{max-width: 1000px;background: transparent;border: 0px transparent solid;padding: 0px;margin: calc(var(--widget-space) * 5) auto;text-align: center}.widget_dialog__arrow.h-ebbc{position: fixed;top: calc(50% - 15px);cursor: pointer;opacity: 0.7}.widget_dialog__arrow.h-ebbc:before, .widget_dialog__arrow.h-ebbc:after{position: absolute;display: block;content: '';width: 0;height: 0;left: 0;top: 0}.widget_dialog__arrow.h-ebbc:hover{opacity: 0.8}.widget_dialog__arrow.h-ebbc:focus{outline: 0}.widget_dialog__arrow.h-ebbc:active{outline: 0;top: calc(50% - 14px)}.widget_dialog__arrow--left.h-ebbc{left: 10px}.widget_dialog__arrow--left.h-ebbc:before{border-top: 40px solid transparent;border-bottom: 40px solid transparent;border-right: 45px solid #3F3F3F}.widget_dialog__arrow--left.h-ebbc:after{left: 6px;top: 10px;border-top: 30px solid transparent;border-bottom: 30px solid transparent;border-right: 35px solid #FFF}.widget_dialog__arrow--right.h-ebbc{right: 70px}.widget_dialog__arrow--right.h-ebbc:before{border-top: 40px solid transparent;border-bottom: 40px solid transparent;border-left: 45px solid #3F3F3F}.widget_dialog__arrow--right.h-ebbc:after{left: 4px;top: 10px;border-top: 30px solid transparent;border-bottom: 30px solid transparent;border-left: 35px solid #FFF}.widget_dialog__image.h-ebbc{position: relative;display: inline-block}.widget_dialog__image.h-ebbc img{max-width: 100%;user-select: none}.widget_dialog__close.h-ebbc{position: absolute;display: flex;align-items: center;justify-content: center;margin: 10px;width: 42px;height: 34px;top: 0;right: 0;font-size: 20px;font-weight: bold;text-transform: uppercase;background-color: #fff;border: 1px #d9d9d9 solid;cursor: pointer;border-radius: 5px}.widget_dialog__shadow.h-ebbc{opacity: 0.5}.widget_dialog__clear.h-ebbc{clear: both}.widget_dialog__container--photo_contain.h-ebbc{text-align: center}.widget_dialog__container--photo_contain.h-ebbc .widget_dialog__image img{max-width: 100%;max-height: calc(100vh - 50px);user-select: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button"); }
	static function getClassName(){ return "Runtime.Widget.Gallery.GalleryDialog"; }
}