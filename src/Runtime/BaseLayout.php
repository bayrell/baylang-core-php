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

use Runtime\ApiResult;
use Runtime\BaseModel;
use Runtime\BaseStorage;
use Runtime\BusInterface;
use Runtime\DefaultLayout;
use Runtime\Method;
use Runtime\Hooks\RuntimeHook;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\Serializer;
use Runtime\Serializer\StringType;
use Runtime\Serializer\VectorType;


class BaseLayout extends \Runtime\BaseModel
{
	var $storage;
	var $components;
	var $pages;
	var $component;
	var $current_page_model;
	var $name;
	var $lang;
	var $title;
	var $theme;
	
	
	/**
	 * Init params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		$this->layout = $this;
	}
	
	
	/**
	 * Init widget settings
	 */
	function initWidget($params)
	{
		parent::initWidget($params);
		/* Init storage */
		$this->initStorage();
	}
	
	
	/**
	 * Init storage
	 */
	function initStorage()
	{
		$this->storage = $this->createWidget("Runtime.BaseStorage");
	}
	
	
	/**
	 * Serialize object
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("components", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType()));
		$rules->addType("current_page_model", new \Runtime\Serializer\StringType());
		$rules->addType("lang", new \Runtime\Serializer\StringType());
		$rules->addType("theme", new \Runtime\Serializer\StringType());
		$rules->addType("title", new \Runtime\Serializer\StringType());
		$rules->addType("storage", new \Runtime\Serializer\ObjectType(new \Runtime\Map(["class_name" => "Runtime.BaseStorage"])));
		$rules->addType("pages", new \Runtime\Serializer\MapType(new \Runtime\Serializer\ObjectType(new \Runtime\Map([
			"autocreate" => true,
			"extends" => "Runtime.BaseModel",
			"create" => function ($layout, $rules, $data)
			{
				return $layout->createWidget($rules->class_name, $data);
			},
		]))));
	}
	
	
	/**
	 * Add component
	 */
	function addComponent($class_name)
	{
		$this->components->push($class_name);
	}
	
	
	/**
	 * Returns page model
	 */
	function getPageModel(){ return $this->pages->get($this->current_page_model); }
	
	
	/**
	 * Set page model
	 */
	function setPageModel($class_name, $params = null)
	{
		if (!$params) $params = new \Runtime\Map();
		$this->current_page_model = $class_name;
		$page = $this->pages->get($class_name);
		if (!$page)
		{
			$page = $this->createWidget($class_name, $params);
			$this->pages->set($class_name, $page);
		}
		return $page;
	}
	
	
	/**
	 * Set page title
	 */
	function setPageTitle($title, $full_title = false)
	{
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::TITLE, new \Runtime\Map([
			"layout" => $this,
			"title" => $title,
			"title_orig" => $title,
			"full_title" => $full_title,
		]));
		$this->title = $res->get("title");
	}
	
	
	/**
	 * Returns object
	 */
	function get($name){ return $this->storage->frontend->get($name); }
	
	
	/**
	 * Returns site name
	 */
	function getSiteName(){ return ""; }
	
	
	/**
	 * Create url
	 */
	function url($name, $params = null)
	{
		$router = $this->get("router");
		return $router->url($name, $params);
	}
	
	
	/**
	 * Send api
	 */
	function sendApi($params)
	{
		$api = \Runtime\rtl::getContext()->provider("api");
		$params->set("storage", $this->storage->backend);
		return $api->send($params);
	}
	
	
	/**
	 * Translate
	 */
	function translate($text, $params = null)
	{
		$s = $text->has($this->lang) ? $text->get($this->lang) : $text->get($this->getDefaultLang());
		return \Runtime\rs::format($s, $params);
	}
	
	
	/**
	 * Returns default lang
	 */
	function getDefaultLang(){ return "en"; }
	
	
	/**
	 * Assets
	 */
	function assets($path)
	{
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::ASSETS, new \Runtime\Map([
			"layout" => $this,
			"path" => $path,
		]));
		return $res->get("path");
	}
	
	
	/**
	 * Returns required components
	 */
	static function getRequiredComponents($component, $result, $hash)
	{
		if ($hash->has($component)) return;
		$components = \Runtime\rtl::getParents($component, "Runtime.Component")->filter(function ($class_name) use (&$hash){ return !$hash->has($class_name); });
		$components->each(function ($class_name) use (&$hash)
		{
			$hash->set($class_name, true);
		});
		$f = new \Runtime\Method($component, "getRequiredComponents");
		if ($f->exists())
		{
			$items = $f->apply();
			for ($i = 0; $i < $items->count(); $i++)
			{
				$name = $items->get($i);
				if (!$hash->has($name))
				{
					static::getRequiredComponents($name, $result, $hash);
				}
			}
		}
		$result->appendItems($components);
	}
	
	
	/**
	 * Returns all components
	 */
	function getComponents()
	{
		$hash = new \Runtime\Map();
		$res = \Runtime\rtl::getContext()->hook(\Runtime\Hooks\RuntimeHook::COMPONENTS, new \Runtime\Map([
			"components" => $this->components->slice(),
		]));
		$result_components = new \Runtime\Vector();
		$components = $res->get("components");
		for ($i = 0; $i < $components->count(); $i++)
		{
			$class_name = $components->get($i);
			static::getRequiredComponents($class_name, $result_components, $hash);
		}
		return $result_components;
	}
	
	
	/**
	 * Returns style
	 */
	function getStyle()
	{
		$content = new \Runtime\Vector();
		$components = $this->getComponents();
		for ($i = 0; $i < $components->count(); $i++)
		{
			$class_name = $components->get($i);
			$f = new \Runtime\Method($class_name, "getComponentStyle");
			if (!$f->exists()) continue;
			$content->push($f->apply());
		}
		return \Runtime\rs::join("", $content);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->storage = null;
		$this->components = new \Runtime\Vector();
		$this->pages = new \Runtime\Map();
		$this->component = "Runtime.DefaultLayout";
		$this->current_page_model = "";
		$this->name = "";
		$this->lang = "en";
		$this->title = "";
		$this->theme = "light";
	}
	static function getClassName(){ return "Runtime.BaseLayout"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}