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
 *
*/
namespace Runtime;

use Runtime\BaseModel;
use Runtime\VirtualDom;
use Runtime\Hooks\RuntimeHook;


class DefaultLayout extends \Runtime\Component
{
	function renderCurrentPage()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$model = $this->layout->getPageModel();
		$class_name = $model ? $model->component : "";
		if ($class_name)
		{
			/* Element $class_name */
			$__v->element($class_name, (new \Runtime\Map(["model" => $model])));
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$__v->push($this->renderCurrentPage());
		
		return $__v;
	}
	function renderComponents($components)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		for ($i = 0; $i < $components->count(); $i++)
		{
			$class_name = $components->get($i);
			if ($class_name instanceof \Runtime\VirtualDom)
			{
				$__v->push($class_name);
			}
			else if (\Runtime\rtl::isString($class_name))
			{
				/* Element $class_name */
				$__v->element($class_name);
			}
		}
		
		return $__v;
	}
	function renderHeader()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element title */
		$__v0 = $__v->element("title");
		$__v0->push($this->layout->title);
		
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["name" => "viewport", "content" => "width=device-width, initial-scale=1.0"])));
		$__v->push($this->renderComponents($this->getComponents(\Runtime\Hooks\RuntimeHook::LAYOUT_HEADER)));
		
		return $__v;
	}
	function renderFooter()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderComponents($this->getComponents(\Runtime\Hooks\RuntimeHook::LAYOUT_FOOTER)));
		
		/* Element script */
		$__v0 = $__v->element("script");
		$__v0->push("var app_data =");
		$__v0->push(\Runtime\rtl::jsonEncode($this->container->getData()));
		$__v0->push(";\n\t\tRuntime.rtl.mount(app_data, document.querySelector(\".root_container\"), function (result){\n\t\t\twindow[\"app\"] = result.get(\"app\");\n\t\t\twindow[\"app_layout\"] = result.get(\"layout\");\n\t\t});");
		
		return $__v;
	}
	function renderStyle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element style */
		$__v0 = $__v->element("style");
		$__v0->push($this->layout->getStyle());
		
		return $__v;
	}
	function renderApp()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element html */
		$__v0 = $__v->element("html", (new \Runtime\Map(["lang" => $this->layout->lang])));
		
		/* Element head */
		$__v1 = $__v0->element("head");
		$__v1->push($this->renderHeader());
		$__v1->push($this->renderStyle());
		
		/* Element body */
		$__v2 = $__v0->element("body", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("theme_" . $this->layout->theme, $componentHash))])));
		
		/* Element div */
		$__v3 = $__v2->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("root_container", $componentHash))])));
		$__v3->push($this->render());
		$__v2->push($this->renderFooter());
		
		return $__v;
	}
	var $container;
	function getComponents($name)
	{
		$d = \Runtime\rtl::getContext()->hook($name, new \Runtime\Map([
			"layout" => $this->layout,
			"components" => new \Runtime\Vector(),
		]));
		return $d->get("components");
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->container = null;
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.DefaultLayout"; }
}