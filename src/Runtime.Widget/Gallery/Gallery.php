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

use Runtime\Web\Annotations\Param;

class Gallery extends \Runtime\Component
{
	function renderItem($pos)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderSlot("default", new \Runtime\Map([
			"pos" => $pos,
			"item" => $this->model->items->get($pos),
			"onClick" => function () use (&$pos)
			{
				$this->onClick($pos);
			},
		])));
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_gallery", $this->class, $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_gallery__items", $componentHash))])));
		
		for ($i = 0; $i < $this->model->items->count(); $i++)
		{
			$__v1->push($this->renderItem($i));
		}
		$__v0->push($this->renderWidget($this->model->dialog));
		
		return $__v;
	}
	/**
	 * On click
	 */
	function onClick($pos)
	{
		$this->model->dialog->select($pos);
		$this->model->dialog->show();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".widget_gallery__items.h-9a67{display: flex;align-items: center;justify-content: space-around;flex-wrap: wrap}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Gallery.Gallery"; }
}