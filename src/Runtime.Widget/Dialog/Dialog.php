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
use Runtime\Widget\RowButtons;
use Runtime\Widget\WidgetResult;


class Dialog extends \Runtime\Component
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->model->title != "")
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__title", $componentHash))])));
			$__v0->push($this->model->title);
		}
		
		return $__v;
	}
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__content", $componentHash))])));
		$__v0->push($this->model->content);
		
		return $__v;
	}
	function renderButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderWidget($this->model->buttons));
		
		return $__v;
	}
	function renderResult()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderWidget($this->model->result));
		
		return $__v;
	}
	function renderDialog()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderTitle());
		$__v->push($this->renderContent());
		$__v->push($this->renderButtons());
		$__v->push($this->renderResult());
		
		return $__v;
	}
	function renderDialogContainer()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$props = $this->getProps();
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__container", $componentHash))]))->concat($props));
		$__v0->push($this->renderDialog());
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog", $this->model->is_open ? "widget_dialog--open" : "widget_dialog--hide", static::mergeStyles("widget_dialog", $this->model->styles), $componentHash))])));
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__shadow", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__box", $componentHash))])));
		
		/* Element table */
		$__v2 = $__v1->element("table", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__wrap", $componentHash))])));
		
		/* Element tbody */
		$__v3 = $__v2->element("tbody");
		
		/* Element tr */
		$__v4 = $__v3->element("tr");
		
		/* Element td */
		$__v5 = $__v4->element("td", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_dialog__td", $componentHash))])));
		$__v5->push($this->renderDialogContainer());
		
		return $__v;
	}
	function getProps()
	{
		$styles = new \Runtime\Vector();
		if ($this->model->width != "")
		{
			$styles->push("max-width: " . $this->model->width);
		}
		return new \Runtime\Map([
			"style" => $styles->join(";"),
		]);
	}
	/**
	 * On shadow click
	 */
	function onShadowClick()
	{
		if (!$this->model->modal)
		{
			$this->model->hide();
		}
	}
	/**
	 * On dialog click
	 */
	function onDialogClick($e)
	{
		if ($e->target->classList->contains("widget_dialog__td"))
		{
			$this->onShadowClick();
		}
	}
	/**
	 * Updated
	 */
	function onUpdated()
	{
		$elem = $document->documentElement;
		$is_scroll_lock = $elem->classList->contains("scroll-lock");
		if ($this->model->is_open)
		{
			if (!$is_scroll_lock)
			{
				$elem->classList->add("scroll-lock");
				$this->nextTick(function ()
				{
					$dialog_box = $this->getRef("dialog_box");
					$dialog_box->scrollTop = 0;
				});
			}
		}
		else
		{
			if ($is_scroll_lock)
			{
				$elem->classList->remove("scroll-lock");
			}
		}
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".widget_dialog.h-a5bb{text-align: left}.widget_dialog__box.h-a5bb, .widget_dialog__shadow.h-a5bb{position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 1001}.widget_dialog__box.h-a5bb{overflow: auto;overflow-y: scroll;display: none}.widget_dialog--open.h-a5bb .widget_dialog__box{display: block}.widget_dialog__shadow.h-a5bb{background-color: #000;opacity: 0.2;overflow: hidden;display: none}.widget_dialog--open.h-a5bb .widget_dialog__shadow{display: block}.widget_dialog__wrap.h-a5bb{width: 100%;min-height: 100%}.widget_dialog__wrap.h-a5bb > tr > td{padding: 20px}.widget_dialog__container.h-a5bb{position: relative;padding: calc(var(--widget-space) * 3);background-color: white;border-radius: 4px;max-width: 600px;margin: calc(var(--widget-space) * 5) auto;width: auto;z-index: 1002;box-shadow: 2px 4px 10px 0px rgba(0,0,0,0.5);font-size: var(--widget-font-size)}.widget_dialog__title.h-a5bb{font-weight: bold;font-size: var(--widget-font-size-h2);text-align: left;margin: var(--widget-margin-h2);margin-top: 0}%(RowButtons)widget_dialog__buttons.h-a5bb{margin: var(--widget-margin-h2);margin-bottom: 0}%(RowButtons)widget_dialog__buttons.h-a5bb %(Button)widget_button{min-width: 70px}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button", "Runtime.Widget.RowButtons", "Runtime.Widget.WidgetResult"); }
	static function getClassName(){ return "Runtime.Widget.Dialog.Dialog"; }
}