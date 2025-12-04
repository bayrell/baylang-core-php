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

use Runtime\lib;
use Runtime\BaseObject;
use Runtime\BaseProvider;
use Runtime\FactoryInterface;
use Runtime\Method;
use Runtime\Entity\Entity;
use Runtime\Entity\Provider;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Exceptions\RuntimeException;
use Runtime\Hooks\RuntimeHook;


class Context extends \Runtime\BaseObject
{
	var $environments;
	var $providers;
	var $modules;
	var $entities;
	var $cli_args;
	var $base_path;
	var $start_time;
	var $initialized;
	var $started;
	
	
	/**
	 * Returns true if is initialized
	 */
	function isInitialized(){ return $this->initialized; }
	
	
	/**
	 * Returns true if is started
	 */
	function isStarted(){ return $this->started; }
	
	
	/**
	 * Returns start time
	 */
	function getStartTime(){ return $this->start_time; }
	
	
	/**
	 * Returns base path
	 */
	function getBasePath(){ return $this->base_path; }
	
	
	/**
	 * Returns entities
	 */
	function getEntities($class_name = "")
	{
		if ($class_name == "")
		{
			return $this->entities->slice();
		}
		return $this->entities->filter(function ($entity_name) use (&$class_name){ return \Runtime\rtl::isInstanceOf($entity_name, $class_name); });
	}
	
	
	/**
	 * Returns environments
	 */
	function getEnvironments(){ return $this->environments->copy(); }
	
	
	/**
	 * Create new instance
	 */
	function factory($name, $params = null)
	{
		$item = $this->entities->find(function ($entity) use (&$name){ return $entity->name == $name; });
		if (!$item && !($item instanceof \Runtime\FactoryInterface))
		{
			throw new \Runtime\Exceptions\ItemNotFound($name, "Factory");
		}
		return $item->createInstance($params);
	}
	
	
	/**
	 * Returns provider by name
	 */
	function provider($provider_name)
	{
		if (!$this->providers->has($provider_name))
		{
			throw new \Runtime\Exceptions\ItemNotFound($provider_name, "Provider");
		}
		return $this->providers->get($provider_name);
	}
	
	
	/**
	 * Return environment
	 */
	function env($name)
	{
		$value = $this->environments->get($name);
		$res = new \Runtime\Map(["name" => $name, "value" => $value]);
		$this->hook(\Runtime\Hooks\RuntimeHook::ENV, $res);
		return $res->get("value");
	}
	
	
	/**
	 * Init params
	 */
	static function initParams($params)
	{
		
		/* Setup default environments */
		if (!$params->has("environments")) $params->set("environments", new \Runtime\Map());
		$env = $params->get("environments");
		if (!$env->has("CLOUD_ENV")) $env->set("CLOUD_ENV", false);
		if (!$env->has("DEBUG")) $env->set("DEBUG", false);
		if (!$env->has("LOCALE")) $env->set("LOCALE", "en_US");
		if (!$env->has("TZ")) $env->set("TZ", "UTC");
		if (!$env->has("TZ_OFFSET")) $env->set("TZ_OFFSET", 0);
		return $params;
	}
	
	
	/**
	 * Constructor
	 */
	function __construct($params)
	{
		parent::__construct();
		if ($params->has("base_path")) $this->base_path = $params->get("base_path");
		if ($params->has("cli_args")) $this->cli_args = $params->get("cli_args");
		if ($params->has("environments")) $this->environments = $params->get("environments");
		if ($params->has("modules")) $this->modules = $params->get("modules");
		if ($params->has("start_time")) $this->start_time = $params->get("start_time");
	}
	
	
	/**
	 * Init
	 */
	function init($params)
	{
		if ($this->initialized) return;
		/* Extend modules */
		$this->modules = static::getRequiredModules($this->modules);
		/* Get modules entities */
		$this->entities = static::getEntitiesFromModules($this->modules);
		/* Create providers */
		$providers = $this->getEntities("Runtime.Entity.Provider");
		$this->createProviders($providers);
		/* Create providers from params */
		if ($params->has("providers"))
		{
			$this->createProviders($params->get("providers"));
		}
		/* Init providers */
		$this->initProviders();
		/* Hook init app */
		$this->hook(\Runtime\Hooks\RuntimeHook::INIT);
		/* Set initialized */
		$this->initialized = true;
	}
	
	
	/**
	 * Start context
	 */
	function start()
	{
		if ($this->started) return;
		/* Start providers */
		$this->startProviders();
		/* Hook start app */
		$this->hook(\Runtime\Hooks\RuntimeHook::START);
		/* Set started */
		$this->started = true;
	}
	
	
	/**
	 * Run context
	 */
	function run()
	{
		return 0;
	}
	
	
	/**
	 * Create providers
	 */
	function createProviders($providers)
	{
		for ($i = 0; $i < $providers->count(); $i++)
		{
			$factory = $providers->get($i);
			/* Create provider */
			$provider = $factory->createInstance();
			if (!$provider)
			{
				throw new \Runtime\Exceptions\RuntimeException("Can't to create provider '" . $factory->name . "'");
			}
			/* Add provider */
			$this->registerProvider($factory->name, $provider);
		}
	}
	
	
	/**
	 * Register provider
	 */
	function registerProvider($provider_name, $provider)
	{
		if ($this->initialized) return;
		if (!($provider instanceof \Runtime\BaseProvider))
		{
			throw new \Runtime\Exceptions\RuntimeException("Provider '" . $provider_name . "' must be intstanceof BaseProvider");
		}
		$this->providers->set($provider_name, $provider);
	}
	
	
	/**
	 * Init providers
	 */
	function initProviders()
	{
		$providers_names = \Runtime\rtl::list($this->providers->keys());
		for ($i = 0; $i < $providers_names->count(); $i++)
		{
			$provider_name = $providers_names->get($i);
			$provider = $this->providers->get($provider_name);
			$provider->init();
		}
	}
	
	
	/**
	 * Start providers
	 */
	function startProviders()
	{
		$providers_names = \Runtime\rtl::list($this->providers->keys());
		for ($i = 0; $i < $providers_names->count(); $i++)
		{
			$provider_name = $providers_names->get($i);
			$provider = $this->providers->get($provider_name);
			$provider->start();
		}
	}
	
	
	/**
	 * Call hook
	 */
	function hook($hook_name, $params = null)
	{
		$hook = $this->provider("hook");
		return $hook->apply($hook_name, $params);
	}
	
	
	/**
	 * Translate message
	 */
	function translate($s, $params = null)
	{
		if ($params == null) return $s;
		return \Runtime\rs::format($s, $params);
	}
	
	
	/**
	 * Returns all modules
	 * @param Collection<string> modules
	 * @return Collection<string>
	 */
	static function getRequiredModules($modules)
	{
		$res = new \Runtime\Vector();
		$cache = new \Runtime\Map();
		static::_getRequiredModules($res, $cache, $modules);
		return $res->removeDuplicates();
	}
	
	
	/**
	 * Returns required modules
	 * @param string class_name
	 * @return Collection<string>
	 */
	static function _getRequiredModules($res, $cache, $modules)
	{
		if ($modules == null) return;
		for ($i = 0; $i < $modules->count(); $i++)
		{
			$module_name = $modules->get($i);
			if (!$cache->has($module_name))
			{
				$cache->set($module_name, true);
				$f = new \Runtime\Method($module_name . ".ModuleDescription", "requiredModules");
				if ($f->exists())
				{
					$sub_modules = $f->apply();
					if ($sub_modules != null)
					{
						$sub_modules_keys = \Runtime\rtl::list($sub_modules->keys());
						static::_getRequiredModules($res, $cache, $sub_modules_keys);
					}
					$res->push($module_name);
				}
			}
		}
	}
	
	
	/**
	 * Returns modules entities
	 */
	static function getEntitiesFromModules($modules)
	{
		return $modules->reduce(function ($entities, $module_name)
		{
			$f = new \Runtime\Method($module_name . ".ModuleDescription", "entities");
			if (!$f->exists()) return $entities;
			$arr = $f->apply();
			if (!$arr) return $entities;
			return $entities->concat($arr);
		}, new \Runtime\Vector());
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->environments = new \Runtime\Map();
		$this->providers = new \Runtime\Map();
		$this->modules = new \Runtime\Vector();
		$this->entities = new \Runtime\Vector();
		$this->cli_args = new \Runtime\Vector();
		$this->base_path = "";
		$this->start_time = 0;
		$this->initialized = false;
		$this->started = false;
	}
	static function getClassName(){ return "Runtime.Context"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}