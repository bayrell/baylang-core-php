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
namespace Runtime;

use Runtime\BaseObject;
use Runtime\BaseModel;
use Runtime\Component;
use Runtime\Serializer;
use Runtime\VirtualDom;
use Runtime\Hooks\RuntimeHook;
use Runtime\Providers\RenderContent;


class RenderContainer extends \Runtime\BaseObject
{
	var $layout;
	
	
	/**
	 * Create layout
	 */
	function createLayout($layout_name)
	{
		$class_name = "Runtime.BaseLayout";
		/* Get layout params */
		$params = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::LAYOUT_NAME, new \Runtime\Map([
			"class_name" => $class_name,
			"layout_name" => $layout_name,
		]));
		$this->layout = \Runtime\rtl::newInstance($params->get("class_name"), new \Runtime\Vector($params));
		$this->layout->name = $layout_name;
		/* Call create layout */
		\Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::CREATE_LAYOUT, new \Runtime\Map([
			"container" => $this,
		]));
		return $this->layout;
	}
	
	
	/**
	 * Change layout
	 */
	function changeLayout($layout_name)
	{
		if ($this->layout && $this->layout->name == $layout_name) return;
		/* Save widgets */
		$old_widgets = $this->layout ? $this->layout->widgets : new \Runtime\Map();
		/* Create new layout */
		$this->createLayout($layout_name);
		/* Restore widgets */
		$keys = \Runtime\rtl::list($old_widgets->keys());
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$key = $keys->get($i);
			$widget = $old_widgets->get($key);
			$widget->parent_widget = $this->layout;
			$this->layout->widgets->set($key, $widget);
		}
		/* Restore storage */
		if ($old_widgets->has("storage")) $this->layout->storage = $old_widgets->get("storage");
		/* Call create layout */
		\Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::CHANGE_LAYOUT, new \Runtime\Map([
			"container" => $this,
		]));
	}
	
	
	/**
	 * Resolve container
	 */
	function resolve(){}
	
	
	/**
	 * Render page model
	 */
	function renderPageModel($model_name, $params = null)
	{
		/* Set page model */
		$this->layout->setPageModel($model_name, $params);
		/* Action index */
		$page_model = $this->layout->getPageModel();
		if ($page_model)
		{
			$page_model->loadData($this);
			if ($page_model == $this->layout->getPageModel()) $page_model->buildTitle($this);
		}
	}
	
	
	/**
	 * Load data
	 */
	function loadData()
	{
		$this->layout->loadData();
	}
	
	
	/**
	 * Returns data
	 */
	function getData()
	{
		$layout_data = \Runtime\rtl::serialize($this->layout);
		$data = new \Runtime\Map([
			"modules" => \Runtime\rtl::getContext()->modules,
			"class" => $this->layout::getClassName(),
			"layout" => $layout_data,
			"environments" => new \Runtime\Map([
				"CLOUD_ENV" => \Runtime\rtl::getContext()->env("CLOUD_ENV"),
				"DEBUG" => \Runtime\rtl::getContext()->env("DEBUG"),
				"LOCALE" => \Runtime\rtl::getContext()->env("LOCALE"),
				"TZ" => \Runtime\rtl::getContext()->env("TZ"),
				"TZ_OFFSET" => \Runtime\rtl::getContext()->env("TZ_OFFSET"),
			]),
		]);
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::CREATE_CONTAINER_DATA, new \Runtime\Map([
			"container" => $this,
			"data" => $data,
		]));
		return $res->get("data");
	}
	
	
	/**
	 * Render app
	 */
	function renderApp()
	{
		$component = \Runtime\rtl::newInstance($this->layout->component);
		$component->container = $this;
		$component->layout = $this->layout;
		$vdom = $component->renderApp();
		return $vdom->render();
	}
	
	
	/**
	 * Render layout
	 */
	function render()
	{
		$vdom = new \Runtime\VirtualDom();
		$vdom->setName($this->layout->component);
		$vdom->setAttrs(new \Runtime\Map(["layout" => $this->layout]));
		return $vdom->render();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->layout = null;
	}
	static function getClassName(){ return "Runtime.RenderContainer"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}