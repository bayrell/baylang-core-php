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
use Runtime\Serializer;
use Runtime\VirtualDom;
use Runtime\Hooks\RuntimeHook;


class RenderProvider extends \Runtime\BaseProvider
{
	var $enable_ssr;
	var $components;
	
	
	/**
	 * Create layout
	 */
	function createLayout($app_data)
	{
		$class_name = $app_data->get("class");
		$layout_data = $app_data->get("layout");
		$layout = \Runtime\rtl::newInstance($class_name);
		\Runtime\rtl::assign($layout, $layout_data);
		return $window["Vue"]->reactive($layout);
	}
	
	
	/**
	 * Create App
	 */
	function createApp($layout)
	{
		$app = null;
		$registerLayout = null;
		$component = \Runtime\rtl::findClass($layout->component);
		$props = new \Runtime\Map();
		$Vue = $window["Vue"];
		if ($this->enable_ssr)
		{
			$app = $Vue->createSSRApp($component, $props);
		}
		else
		{
			$app = $Vue->createApp($component, $props);
		}
		$app->use($registerLayout($layout));
		return $app;
	}
	
	
	/**
	 * Mount
	 */
	function mount($app_data, $element)
	{
		$layout = $this->createLayout($app_data);
		$app = $this->createApp($layout);
		$app->mount($element, true);
		\Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::MOUNT, new \Runtime\Map([
			"app" => $app,
			"layout" => $layout,
			"data" => $app_data,
		]));
		return new \Runtime\Map([
			"app" => $app,
			"layout" => $layout,
		]);
	}
	
	
	/**
	 * Add replace component
	 */
	function addComponent($component, $name)
	{
		$this->components->set($component, $name);
	}
	
	
	/**
	 * Returns find element
	 */
	function findElement($vdom)
	{
		if ($vdom->is_component)
		{
			$name = $vdom->name;
			if ($this->components->has($name)) $name = $this->components->get($name);
			return \Runtime\rtl::findClass($name);
		}
		return $vdom->name;
	}
	
	
	/**
	 * Render
	 */
	function render($vdom)
	{
		if (!($vdom instanceof \Runtime\VirtualDom)) return $vdom;
		$content = new \Runtime\Vector();
		if (!$vdom->attrs->has("@raw"))
		{
			for ($i = 0; $i < $vdom->items->count(); $i++)
			{
				$item = $vdom->items->get($i);
				$content->push($this->render($item));
			}
		}
		$h = $window["Vue"]->h;
		if ($vdom->name == "")
		{
			if ($content->count() == 1) return $content->get(0);
			return $content;
		}
		$children = $content;
		if ($vdom->is_component)
		{
		}
		if ($children instanceof \Runtime\Vector)
		{
			$children = $children->flatten()->filter(function ($item){ return $item != null && $item != ""; });
		}
		$attrs = $vdom->attrs;
		if ($attrs instanceof \Runtime\Map)
		{
			$attrs = $attrs->mapWithKeys(function ($value, $key)
			{
				if ($key == "@ref") $key = "ref";
				return new \Runtime\Vector($value, $key);
			})->filter(function ($value, $key){ return \Runtime\rs::charAt($key, 0) != "@"; });
			$attrs = $attrs->toObject();
			if ($vdom->attrs->has("@raw"))
			{
				$attrs["innerHTML"] = $vdom->attrs->get("@raw");
			}
		}
		$name = $this->findElement($vdom);
		return $h($name, $attrs, $children);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->enable_ssr = true;
		$this->components = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Providers.RenderProvider"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}