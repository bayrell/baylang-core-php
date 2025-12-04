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
namespace Runtime\Widget\Tab;

use Runtime\Component;
use Runtime\VirtualDom;
use Runtime\Web\BaseModel;
use Runtime\Widget\Tab\Tab;
use Runtime\Widget\Tab\TabMessage;


class Tabs extends \Runtime\Component
{
	function renderHeader()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs__header", $componentHash))])));
		
		$items = $this->items;
		for ($i = 0; $i < $items->count(); $i++)
		{
			$tab = $items->get($i);
			$tab_name = $tab->get("name");
			$tab_title = $tab->get("title");
			$tab_href = $tab->get("href");
			$is_active = $this->model->isActive($tab_name);
			if ($tab_href == null)
			{
				/* Element div */
				$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs__header_item", $is_active ? "tabs__header_item--active" : "", $componentHash)), "data-tab" => $tab_name])));
				$__v1->push($tab_title);
			}
			else
			{
				/* Element a */
				$__v2 = $__v0->element("a", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs__header_item", $is_active ? "tabs__header_item--active" : "", $componentHash)), "data-tab" => $tab_name, "href" => $tab_href])));
				$__v2->push($tab_title);
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
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs", $this->class, $componentHash))])));
		$__v0->push($this->renderHeader());
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("tabs__content", $componentHash))])));
		$__v1->push($this->renderSlot("default"));
		
		return $__v;
	}
	/**
	 * Returns items
	 */
	function items()
	{
		$result = new \Runtime\Vector();
		$items = new \Runtime\Vector();
		$vdom = $this->renderSlot("default");
		if ($vdom instanceof \Runtime\VirtualDom) $items = $vdom->items;
		else
		{
			$items = \Runtime\Vector::create($vdom);
		}
		for ($i = 0; $i < $items->count(); $i++)
		{
			$item = $items->get($i);
			if ($item instanceof \Runtime\VirtualDom)
			{
				$result->push(new \Runtime\Map([
					"name" => $item->attrs->get("name"),
					"title" => $item->attrs->get("title"),
					"href" => $item->attrs->get("href"),
				]));
			}
			else
			{
				$result->push(new \Runtime\Map([
					"name" => $item->props->name,
					"title" => $item->props->title,
					"href" => $item->props->href,
				]));
			}
		}
		return $result;
	}
	/**
	 * OnClick
	 */
	function onClick($tab_key)
	{
		$this->model->setActive($tab_key);
		$this->emit(new \Runtime\Widget\Tab\TabMessage(new \Runtime\Map([
			"key" => $tab_key,
		])));
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".tabs.h-209{position: relative}.tabs__header.h-209{display: flex;position: relative;border-bottom-width: var(--border-width);border-bottom-color: var(--color-border);border-bottom-style: solid;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.tabs__header_item.h-209{position: relative;padding: calc(1.5 * var(--space));border-color: transparent;border-width: var(--border-width);border-style: solid;border-bottom-width: 0px;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type);text-decoration: none;color: inherit;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-khtml-user-select: none;-ms-user-select: none;top: var(--border-width)}.tabs__header_item.h-209:hover, .tabs__header_item.h-209:visited, .tabs__header_item.h-209:visited:hover, .tabs__header_item.h-209:focus{text-decoration: none;color: inherit;box-shadow: none;outline: transparent}.tabs__header_item--active.h-209{background-color: var(--color-background);border-color: var(--color-border)}.tabs__content.h-209{margin-top: calc(2 * var(--space))}.tabs__item.h-209{position: relative;display: none}.tabs__item--active.h-209{display: block}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Tab.Tab"); }
	static function getClassName(){ return "Runtime.Widget.Tab.Tabs"; }
}