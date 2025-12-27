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


class Pagination extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$page_start = \Runtime\rtl::max(2, $this->page - $this->delta + 1);
		$page_end = \Runtime\rtl::min($this->page + $this->delta, $this->pages - 1);
		$props = new \Runtime\Map();
		
		/* Element nav */
		$__v0 = $__v->element("nav", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("pagination", $componentHash))])));
		
		if ($this->pages > 1)
		{
			/* Element ul */
			$__v1 = $__v0->element("ul");
			
			if ($this->name)
			{
				$_ = $props->set("href", $this->getPageUrl(1));
			}
			
			/* Element li */
			$__v2 = $__v1->element("li", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector($this->page == 1 ? "active" : "", $componentHash))])));
			
			/* Element a */
			$__v3 = $__v2->element("a", (new \Runtime\Map([]))->concat($props));
			$__v3->push("1");
			
			if ($page_start > 2)
			{
				/* Element li */
				$__v4 = $__v1->element("li");
				$__v4->push("...");
			}
			
			if ($page_start <= $page_end)
			{
				for ($p = $page_start; $p <= $page_end; $p++)
				{
					if ($this->name)
					{
						$_ = $props->set("href", $this->getPageUrl($p));
					}
					
					/* Element li */
					$__v5 = $__v1->element("li", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector($this->page == $p ? "active" : "", $componentHash))])));
					
					/* Element a */
					$__v6 = $__v5->element("a", (new \Runtime\Map([]))->concat($props));
					$__v6->push($p);
				}
			}
			
			if ($page_end < $this->pages - 1)
			{
				/* Element li */
				$__v7 = $__v1->element("li");
				$__v7->push("...");
			}
			
			if ($this->pages > 1)
			{
				if ($this->name)
				{
					$_ = $props->set("href", $this->getPageUrl($this->pages));
				}
				
				/* Element li */
				$__v8 = $__v1->element("li", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector($this->page == $this->pages ? "active" : "", $componentHash))])));
				
				/* Element a */
				$__v9 = $__v8->element("a", (new \Runtime\Map([]))->concat($props));
				$__v9->push($this->pages);
			}
		}
		
		return $__v;
	}
	var $page;
	var $pages;
	var $delta;
	var $name;
	/**
	 * Returns page url
	 */
	function getPageUrl($page)
	{
		$uri = $this->layout->request_full_uri;
		return \Runtime\rs::url_get_add($uri, $this->name, $page);
	}
	/**
	 * Click event
	 */
	function onClick($page)
	{
		if ($this->name) return;
		$this->emit("page", $page);
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->page = 1;
		$this->pages = 1;
		$this->delta = 5;
		$this->name = "";
	}
	static function getComponentStyle(){ return ".pagination.h-e7d5{text-align: center;padding: var(--space) 0;width: 100%}.pagination.h-e7d5 ul, .pagination.h-e7d5 li{padding: 0;margin: 0}.pagination.h-e7d5 li{display: inline-block;vertical-align: top;list-style: none;margin-left: calc(var(--space) * 0.5);margin-right: calc(var(--space) * 0.5)}.pagination.h-e7d5 a, .pagination.h-e7d5 a:hover, .pagination.h-e7d5 span{display: flex;align-items: center;justify-content: center;background-color: var(--color-surface);border-color: var(--color-border);border-width: var(--border-width);border-style: solid;border-radius: var(--border-radius);color: var(--color-surface-text);text-align: center;width: 30px;height: 30px;line-height: 0;font-size: var(--font-size);text-decoration: none}.pagination.h-e7d5 li:first-child{margin-left: 0px}.pagination.h-e7d5 li.active a, .pagination.h-e7d5 li.active a:hover{background-color: var(--color-selected);border-color: var(--color-selected);color: var(--color-selected-text)}.pagination.h-e7d5 li a:focus{outline: 0;box-shadow: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Pagination"; }
}