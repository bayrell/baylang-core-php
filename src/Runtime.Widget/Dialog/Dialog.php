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
namespace Runtime\Widget\Dialog;

use Runtime\Widget\Button;
use Runtime\Widget\Dialog\DialogModel;


class Dialog extends \Runtime\Component
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderSlot("title"));
		
		return $__v;
	}
	function renderHeader()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__header", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__header_title", $componentHash))])));
		$__v1->push($this->renderTitle());
		
		/* Element Runtime.Widget.Button */
		$__v2 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__header_close button--small", $componentHash))])));
		
		/* Content */
		$__v2->slot("default", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			
			/* Element svg */
			$__v0 = $__v->element("svg", (new \Runtime\Map(["width" => "16", "height" => "16", "viewBox" => "0 0 16 16", "fill" => "none", "xmlns" => "http://www.w3.org/2000/svg"])));
			
			/* Element path */
			$__v0->element("path", (new \Runtime\Map(["d" => "M12 4L4 12", "stroke" => "currentColor", "stroke-width" => "2", "stroke-linecap" => "round", "stroke-linejoin" => "round"])));
			
			/* Element path */
			$__v0->element("path", (new \Runtime\Map(["d" => "M4 4L12 12", "stroke" => "currentColor", "stroke-width" => "2", "stroke-linecap" => "round", "stroke-linejoin" => "round"])));
			
			return $__v;
		});
		
		return $__v;
	}
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__content", $componentHash))])));
		$__v0->push($this->renderSlot("content"));
		
		return $__v;
	}
	function renderFooter()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("footer"))
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__footer", $componentHash))])));
			$__v0->push($this->renderSlot("footer"));
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog", $this->model->is_open ? "dialog--open" : "", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__container", $componentHash))])));
		$__v1->push($this->renderHeader());
		$__v1->push($this->renderContent());
		$__v1->push($this->renderFooter());
		
		return $__v;
	}
	var $model;
	var $modal;
	/**
	 * Overlay click
	 */
	function onOverlayClick()
	{
		if (!$this->modal)
		{
			$this->model->hide();
		}
	}
	/**
	 * Close button click
	 */
	function onCloseClick()
	{
		$this->model->hide();
	}
	/**
	 * Update component
	 */
	function updated()
	{
		$body = $document->getElementsByTagName("body")[0];
		if ($this->model->is_open)
		{
			if (!$body->classList->contains("scroll-lock"))
			{
				$body->classList->add("scroll-lock");
			}
		}
		else
		{
			if ($body->classList->contains("scroll-lock"))
			{
				$body->classList->remove("scroll-lock");
			}
		}
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->model = null;
		$this->modal = true;
	}
	static function getComponentStyle(){ return ".dialog.h-a5bb{display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0, 0, 0, 0.2);z-index: 99999;align-items: center;justify-content: center;overflow-y: auto}.dialog--open.h-a5bb{display: grid}.dialog__container.h-a5bb{background-color: var(--color-background);border-radius: var(--border-radius);box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);width: 600px;margin: calc(var(--space) * 2) auto}.dialog__content.h-a5bb{padding: calc(var(--space) * 2)}.dialog__header.h-a5bb{padding: var(--space) calc(var(--space) * 2);border-bottom: 1px solid var(--color-border);display: flex;justify-content: space-between;align-items: center;font-weight: bold;font-size: 1.2em}.dialog__header.h-a5bb .button{font-size: 1.5em;line-height: 0;border: none}.dialog__footer.h-a5bb{padding: calc(var(--space) * 2);padding-top: var(--space);display: flex;justify-content: flex-end;gap: var(--space)}@media (max-width: 768px){.dialog__content_wrapper.h-a5bb{width: 95%}.dialog__header.h-a5bb{font-size: 1.1em;padding: 10px 15px}.dialog__content.h-a5bb{padding: 15px}.dialog__footer.h-a5bb{padding: 10px 15px}}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Dialog.Dialog"; }
}