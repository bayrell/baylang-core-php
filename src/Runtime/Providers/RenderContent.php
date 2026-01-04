<?php
/*!
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
namespace Runtime\Providers;

use Runtime\BaseProvider;
use Runtime\Component;
use Runtime\VirtualDom;


class RenderContent extends \Runtime\BaseProvider
{
	var $items;
	var $components;
	
	
	/**
	 * Add replace component
	 */
	function addComponent($component, $name)
	{
		$this->components->set($component, $name);
	}
	
	
	/**
	 * Find component
	 */
	function findComponent($vdom)
	{
		$name = $vdom->name;
		if ($this->components->has($name)) $name = $this->compoentns->get($name);
		return $name;
	}
	
	
	/**
	 * Render
	 */
	function render($vdom, $content, $parent_vdom = null)
	{
		if ($vdom === null) return;
		if (!($vdom instanceof \Runtime\VirtualDom))
		{
			if ($parent_vdom && $parent_vdom->is_raw)
			{
				$content->push($vdom);
			}
			else $content->push(\Runtime\rs::escapeHtml($vdom));
			return;
		}
		if ($vdom->is_component)
		{
			$component_name = $this->findComponent($vdom);
			$component = \Runtime\rtl::newInstance($component_name);
			$component->parent_component = $this->items->count() > 0 ? $this->items->last() : null;
			$component->layout = $vdom->component ? $vdom->component->layout : null;
			$component->_slots = $vdom->slots;
			$keys = \Runtime\rtl::list($vdom->attrs->keys());
			for ($i = 0; $i < $keys->count(); $i++)
			{
				$attr_name = $keys->get($i);
				\Runtime\rtl::setAttr($component, $attr_name, $vdom->attrs->get($attr_name));
			}
			$item = $component->render();
			$this->items->push($component);
			$this->render($item, $content);
			$this->items->pop();
		}
		else
		{
			$item_result = new \Runtime\Vector();
			if (!$vdom->attrs->has("@raw"))
			{
				for ($i = 0; $i < $vdom->items->count(); $i++)
				{
					$item = $vdom->items->get($i);
					$this->render($item, $item_result, $vdom);
				}
			}
			else
			{
				$item_result->push($vdom->attrs->get("@raw"));
			}
			if ($vdom->name != "")
			{
				$attr_items = new \Runtime\Vector();
				if ($vdom->attrs)
				{
					$keys = \Runtime\rtl::list($vdom->attrs->keys());
					for ($i = 0; $i < $keys->count(); $i++)
					{
						$attr_name = $keys->get($i);
						$value = \Runtime\rs::trim($vdom->attrs->get($attr_name));
						if ($value == "") continue;
						if (\Runtime\rs::charAt($attr_name, 0) == "@") continue;
						$attr_items->push($attr_name . "=\"" . \Runtime\rs::escapeHtml($value) . "\"");
					}
				}
				$attrs = $attr_items->count() > 0 ? " " . \Runtime\rs::join(" ", $attr_items) : "";
				if ($vdom->name == "br" || $vdom->name == "hr" || $vdom->name == "link")
				{
					$content->push("<" . $vdom->name . $attrs . "/>");
				}
				else
				{
					$content->push("<" . $vdom->name . $attrs . ">");
					$content->appendItems($item_result);
					$content->push("</" . $vdom->name . ">");
				}
			}
			else
			{
				$is_fragment = $vdom->is_render && $vdom->items->count() > 1;
				if ($is_fragment) $content->push("<!--[-->");
				$content->appendItems($item_result);
				if ($is_fragment) $content->push("<!--]-->");
			}
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->items = new \Runtime\Vector();
		$this->components = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Providers.RenderContent"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}