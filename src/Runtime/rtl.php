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
use Runtime\Context;
use Runtime\Date;
use Runtime\DateTime;
use Runtime\Method;
use Runtime\Entity\Provider;
use Runtime\Exceptions\AssertException;
use Runtime\SerializeInterface;
use Runtime\Serializer\ObjectType;
use Runtime\VirtualDom;


class rtl
{
	/* Log level */
	
	/**
	 * Fatal error. Application stoped
	 */
	const LOG_FATAL = 0;
	
	/**
	 * Critical error. Application damaged, but works
	 */
	const LOG_CRITICAL = 2;
	
	/**
	 * Any Application error or exception
	 */
	const LOG_ERROR = 4;
	
	/**
	 * Log warning. Developer should attention to this
	 */
	const LOG_WARNING = 6;
	
	/**
	 * Information about any event
	 */
	const LOG_INFO = 8;
	
	/**
	 * Debug level 1
	 */
	const LOG_DEBUG = 10;
	
	/**
	 * Debug level 2
	 */
	const LOG_DEBUG2 = 12;
	
	/* Status codes */
	const STATUS_PLAN = 0;
	const STATUS_DONE = 1;
	const STATUS_PROCESS = 100;
	const STATUS_FAIL = -1;
	
	/* Errors */
	const ERROR_NULL = 0;
	const ERROR_OK = 1;
	const ERROR_PROCCESS = 100;
	const ERROR_FALSE = -100;
	const ERROR_RUNTIME = -1;
	const ERROR_UNKNOWN = -1;
	const ERROR_INDEX_OUT_OF_RANGE = -2;
	const ERROR_STOP_ITERATION = -3;
	const ERROR_ITEM_NOT_FOUND = -5;
	const ERROR_OBJECT_ALLREADY_EXISTS = -6;
	const ERROR_ASSERT = -7;
	const ERROR_REQUEST = -8;
	const ERROR_RESPONSE = -9;
	const ERROR_CSRF_TOKEN = -10;
	const ERROR_VALIDATION = -12;
	const ERROR_SERIALIZATION = -14;
	const ERROR_ASSIGN = -15;
	const ERROR_AUTH = -16;
	const ERROR_DUPLICATE = -17;
	const ERROR_API_NOT_FOUND = -18;
	const ERROR_API_WRONG_FORMAT = -19;
	const ERROR_API_WRONG_APP_NAME = -20;
	const ERROR_API = -21;
	const ERROR_CURL = -22;
	const ERROR_FATAL = -99;
	const ERROR_HTTP_CONTINUE = -100;
	const ERROR_HTTP_SWITCH = -101;
	const ERROR_HTTP_PROCESSING = -102;
	const ERROR_HTTP_OK = -200;
	const ERROR_HTTP_BAD_GATEWAY = -502;
	const ERROR_USER = -10000;
	
	/* Serializer */
	const ALLOW_OBJECTS = 1;
	const JSON_PRETTY = 8;
	
	
	/**
	 * Define class
	 */
	static function defClass($obj)
	{
	}
	
	
	/**
	 * Find class instance by name. If class does not exists return null.
	 * @return var - class instance
	 */
	static function findClass($class_name)
	{
		return "\\" . preg_replace("/\\./", "\\", $class_name);
	}
	
	
	/**
	 * Return class name
	 */
	static function className($class_name)
	{
		if (static::isString($class_name)) return $class_name;
		return str_replace("\\", ".", get_class($class_name));
	}
	
	
	/**
	 * Returns parent class name
	 */
	static function parentClassName($class_name)
	{
		$class_name = static::findClass($class_name);
		$class_name = get_parent_class($class_name);
		return str_replace("\\", ".", $class_name);
	}
	
	
	/**
	 * Returns parents
	 */
	static function getParents($class_name, $last_name = "")
	{
		$result = new \Runtime\Vector();
		$parent_class_name = $class_name;
		while ($parent_class_name != "" && $parent_class_name != $last_name)
		{
			$result->push($parent_class_name);
			$parent_class_name = static::parentClassName($parent_class_name);
		}
		return $result;
	}
	
	
	/**
	 * Returns true if class instanceof class_name
	 * @return bool
	 */
	static function isInstanceOf($obj, $class_name)
	{
		if ($obj == null) return false;
		$obj = static::className($obj);
		if (!static::isString($obj)) return false;
		$parent_class_name = $obj;
		while ($parent_class_name != "")
		{
			if ($parent_class_name == $class_name) return true;
			$parent_class_name = static::parentClassName($parent_class_name);
		}
	}
	
	
	/**
	 * Returns true if class implements interface
	 */
	static function isImplements($obj, $interface_name)
	{
		if ($obj == null) return false;
		$obj = static::findClass($obj);
		if (!$obj) return false;
		while ($obj != null)
		{
			if ($obj->getInterfaces && $obj->getInterfaces()->indexOf($interface_name) >= 0) return true;
			$obj = static::findClass(static::parentClassName($obj));
		}
	}
	
	
	/**
	 * Class exists
	 */
	static function classExists($class_name)
	{
		$obj = static::findClass($class_name);
		return $obj != null;
	}
	
	
	/**
	 * Returns true if class exists
	 * @return bool
	 */
	static function methodExists($class_name, $method_name)
	{
		if (gettype($class_name) == "object")
		{
			try
			{
				$r = new \ReflectionMethod(get_class($class_name),$method_name);
				if (!$r) return false;
				if ($r->isStatic()) return false;
				return true;
			}
			catch (\Exception $e) {}
			
			return false;
		}
		
		$class_name = static::findClass($class_name);
		if (!class_exists($class_name)) return false;
		
		try
		{
			$r = new \ReflectionMethod($class_name,$method_name);
			if (!$r) return false;
			if (!$r->isStatic()) return false;
			return true;
		}
		catch (\Exception $e) {}
		
		return false;
	}
	
	
	/**
	 * Create object by class_name. If class name does not exists return null
	 * @return Object
	 */
	static function newInstance($class_name, $args = null)
	{
		$class_name = static::findClass($class_name);
		if ($args == null)
			return new $class_name();
		$r = new \ReflectionClass($class_name);
		$arr = $args->_arr;
		return $r->newInstanceArgs($arr);
	}
	
	
	/**
	 * Apply method
	 */
	static function apply($f, $args)
	{
		if ($f instanceof \Runtime\Method) return $f->apply($args);
		if ($args) $args = $args->toArray();
		else $args = [];
		
		return call_user_func_array($f, $args);
	}
	
	
	/**
	 * Assert
	 */
	static function assert($value, $message)
	{
		if (!$value) throw new \Runtime\Exceptions\AssertException($message);
	}
	
	
	/**
	 * Return attr value by name
	 */
	static function attr($item, $name, $def_val = null)
	{
		return property_exists($item, $name) ? $item->$name : $def_val;
	}
	
	
	/**
	 * Set attr to item
	 */
	static function setAttr($item, $name, $value)
	{
		if (property_exists($item, $name))
		{
			$item->$name = $value;
		}
	}
	
	
	/**
	 * Return true if value is exists
	 * @param var value
	 * @return bool
	 */
	static function exists($value)
	{
		return isset($value);
	}
	
	
	/**
	 * Returns value type
	 */
	static function getType($value)
	{
		if ($value === null) return "null";
		if (static::isString($value)) return "string";
		if (static::isInteger($value)) return "integer";
		if (static::isFloat($value)) return "float";
		if (static::isBoolean($value)) return "boolean";
		if ($value instanceof \Runtime\Vector) return "vector";
		if ($value instanceof \Runtime\Map) return "map";
		return "object";
	}
	
	
	/**
	 * Returns true if value is boolean
	 * @param var value
	 * @return bool
	 */
	static function isBoolean($value)
	{
		return $value === true || $value === false;
	}
	
	
	/**
	 * Returns true if value is integer
	 * @param var value
	 * @return bool
	 */
	static function isInteger($value)
	{
		return is_int($value);
	}
	
	
	/**
	 * Returns true if float
	 */
	static function isFloat($value)
	{
		return is_float($value);
	}
	
	
	/**
	 * Returns true if value is string
	 * @param var value
	 * @return bool
	 */
	static function isString($value)
	{
		return is_string($value);
	}
	
	
	/**
	 * Returns true if object
	 */
	static function isObject($value)
	{
		if (is_object($value)) return $value instanceof \Runtime\BaseObject;
		return false;
	}
	
	
	/**
	 * Returns true if value is collection
	 */
	static function isCollection($value)
	{
		return $value instanceof \Runtime\Vector;
	}
	
	
	/**
	 * Returns true if value is map
	 */
	static function isMap($value)
	{
		return $value instanceof \Runtime\Map;
	}
	
	
	/**
	 * Returns true if function
	 */
	static function isFunction($value)
	{
		return is_callable($value);
	}
	
	
	/**
	 * Translate
	 */
	static function translate($s, $params)
	{
		return \Runtime\rtl::getContext()->translate($s, $params);
	}
	
	
	/* ================================= Lib Functions =================================== */
	static function compare($f = null, $order = "asc")
	{
		return function ($a, $b) use (&$order, &$f)
		{
			if ($f)
			{
				$a = $f($a);
				$b = $f($b);
			}
			if ($a == $b) return 0;
			if ($order == "asc") return $a < $b ? -1 : 1;
			return $a > $b ? -1 : 1;
		};
	}
	
	/* =============================== Convert Functions ================================= */
	/**
	 * Convert generator to list
	 */
	static function list($generator)
	{
		return \Runtime\Vector::create(iterator_to_array($generator));
		return $generator;
	}
	
	
	/**
	 * Convert to int
	 */
	static function toInt($s)
	{
		return (int)$s;
	}
	
	
	/**
	 * Convert value to string
	 * @param var value
	 * @return string
	 */
	static function toStr($value)
	{
		$t = gettype($value);
		if ($value === null) return "";
		if (is_string($value)) return $value;
		if ($value instanceof \Runtime\StringInterface) return $value->toString();
		if (is_int($value) or is_float($value) or is_int($value))
			return (string)$value;
		if ($value === true) return "1";
		if ($value === false) return "";
		return (string)$value;
	}
	
	
	/**
	 * Convert primitive to object
	 */
	static function toNative($obj)
	{
		if ($obj instanceof \Runtime\Vector)
		{
			return $obj->map(function ($value) { return static::toNative($value); });
		}
		else if ($obj instanceof \Runtime\Map)
		{
			return $obj->map(function ($value) { return static::toNative($value); })->toObject();
		}
		return $obj;
	}
	
	
	/**
	 * Convert native to primitive
	 */
	static function fromNative($obj)
	{
		if ($obj === null) return null;
		if (is_array($obj)) return \Runtime\Vector::create(
			array_map(function ($item) { return static::fromNative($item); }, $obj)
		);
		if (is_object($obj)) return \Runtime\Map::create($obj)->map(
			function ($item) { return static::fromNative($item); }
		);
		return $obj;
	}
	
	
	/**
	 * Assign object
	 */
	static function assign($obj, $data, $errors = new \Runtime\Vector())
	{
		$rules = new \Runtime\Serializer\ObjectType();
		return $rules->filter($data, $errors, $obj);
	}
	
	
	/**
	 * Serialize object
	 */
	static function serialize($obj)
	{
		$rules = new \Runtime\Serializer\ObjectType();
		return $rules->encode($obj);
	}
	
	
	/**
	 * Json Decode
	 */
	static function jsonDecode($obj)
	{
		return static::fromNative(json_decode($obj));
	}
	
	
	/**
	 * Json encode
	 */
	static function jsonEncode($obj, $flags = 0)
	{
		$f = 0;
		if (($flags & static::JSON_PRETTY) == static::JSON_PRETTY) $f = $f | JSON_PRETTY_PRINT;
		return json_encode($obj, $f | JSON_UNESCAPED_SLASHES);
	}
	
	
	/**
	 * Copy object
	 */
	static function copy($obj)
	{
		$data = static::serialize($obj);
		$item = static::newInstance($obj::getClassName());
		static::assign($item, $data);
		return $item;
	}
	
	
	/* ================================== IO Functions =================================== */
	/**
	 * Print message to output
	 */
	static function print($message, $new_line = true, $type = "")
	{
		$output = \Runtime\rtl::getContext()->provider("output");
		$output->print($message, $new_line, $type);
	}
	
	
	/**
	 * Print error message to output
	 */
	static function error($message)
	{
		$output = \Runtime\rtl::getContext()->provider("output");
		$output->error($message);
	}
	
	
	/**
	 * Color message to output
	 */
	static function color($color, $message)
	{
		$output = \Runtime\rtl::getContext()->provider("output");
		return $output->color($color, $message);
	}
	
	
	/**
	 * Log message
	 */
	static function log($type, $message)
	{
		$p = \Runtime\rtl::getContext()->provider("log");
		$p->log($type, $message);
	}
	
	
	/**
	 * Read line from input
	 */
	static function input()
	{
		$input = \Runtime\rtl::getContext()->provider("input");
		return $input->input();
	}
	
	
	/* ================================= Math Functions ================================== */
	/**
	 * Abs
	 */
	static function abs($value)
	{
		return abs($value);
	}
	
	
	/**
	 * Returns max a and b
	 */
	static function max($a, $b){ return $a > $b ? $a : $b; }
	
	
	/**
	 * Returns min a and b
	 */
	static function min($a, $b){ return $a < $b ? $a : $b; }
	
	
	/**
	 * Ceil
	 */
	static function ceil($value)
	{
		return (int)ceil($value);
	}
	
	
	/**
	 * Floor
	 */
	static function floor($value)
	{
		return (int)floor($value);
	}
	
	
	/**
	 * Round
	 */
	static function round($value)
	{
		return round($value);
	}
	
	
	/**
	 * Returns random value x, where 0 <= x < 1
	 * @return double
	 */
	static function urandom()
	{
		return mt_rand() / (mt_getrandmax() + 1);
	}
	
	
	/**
	 * Returns random value x, where a <= x <= b
	 * @param int a
	 * @param int b
	 * @return int
	 */
	static function random($a, $b)
	{
		return static::round(static::urandom() * ($b - $a) + $a);
	}
	
	
	/* ================================ Context Functions ================================ */
	
	static $_global_context = null;
	
	
	/**
	 * Returns global context
	 * @return Context
	 */
	static function getContext()
	{
		if (!static::$_global_context) return null;
		return static::$_global_context;
	}
	
	
	/**
	 * Set global context
	 * @param Context context
	 */
	static function setContext($context)
	{
		static::$_global_context = $context;
	}
	
	
	/**
	 * Create context
	 */
	static function createContext($params)
	{
		/* Create contenxt */
		$context = new \Runtime\Context(\Runtime\Context::initParams($params));
		/* Setup global context */
		static::setContext($context);
		/* Init context */
		$context->init($params);
		return $context;
	}
	
	
	/**
	 * Create app
	 */
	static function createApp($app, $modules)
	{
		$params = new \Runtime\Map([
			"providers" => new \Runtime\Vector(
				new \Runtime\Entity\Provider("app", $app),
			),
			"modules" => $modules,
		]);
		$context = static::createContext($params);
		$context->start();
		return $context;
	}
	
	
	/**
	 * Run app
	 */
	static function runApp($app, $modules)
	{
		try
		{
			$context = static::createApp($app, $modules);
			$provider = $context->provider("app");
			return $provider->main();
		}
		catch (\Exception $e)
		{
			throw $e;
		}
	}
	
	
	/**
	 * Mount app
	 */
	static function mount($app_data, $element, $callback = null)
	{
		$app_data = static::fromNative($app_data);
		/* Create context */
		$environments = $app_data->get("environments");
		$modules = $app_data->get("modules");
		$context = static::createContext(new \Runtime\Map([
			"environments" => $environments,
			"modules" => $modules,
		]));
		/* Start context */
		$context->start();
		/* Render app */
		$render = $context->factory("render");
		$result = $render->mount($app_data, $element);
		if ($callback)
		{
			$callback($result);
		}
	}
	
	
	/**
	 * Render Virtual Dom
	 */
	static function render($vdom){ return \Runtime\rtl::getContext()->provider("render")->render($vdom); }
	
	
	/* ================================= Other Functions ================================= */
	static function wait($timeout)
	{
		sleep($timeout / 1000);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.rtl"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}
