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
use Runtime\Chain;
use Runtime\Message;
use Runtime\Method;


class Listener extends \Runtime\BaseObject
{
	var $object;
	var $listeners;
	
	
	/**
	 * Constructor
	 */
	function __construct($object)
	{
		parent::__construct();
		$this->object = $object;
	}
	
	
	/**
	 * Clear listeners
	 */
	function clear($message_name)
	{
		$this->listeners->set($message_name, new \Runtime\Chain());
	}
	
	
	/**
	 * Add listener
	 */
	function add($message_name, $f, $priority = 100)
	{
		if (!($f instanceof \Runtime\Method)) return;
		if (!$this->listeners->has($message_name))
		{
			$this->clearListener($message_name);
		}
		$chain = $this->listeners->get($message_name);
		$chain->add($f, $priority);
		$chain->sort();
	}
	
	
	/**
	 * Emit message
	 */
	function emit($message)
	{
		if ($message->src == null)
		{
			$message->src = $this->object;
		}
		$this->emitMessage(message::getClassName(), $message);
		$this->emitMessage($message->name, $message);
	}
	
	
	/**
	 * Emit async message
	 */
	function emitAsync($message)
	{
		if ($message->src == null)
		{
			$message->src = $this->object;
		}
		$res1 = $this->emitMessage(message::getClassName(), $message);
		$res2 = $this->emitMessage($message->name, $message);
	}
	
	
	/**
	 * Emit message
	 */
	function emitMessage($message_name, $message)
	{
		if (!$this->listeners->has($message_name)) return;
		$chain = $this->listeners->get($message_name);
		return $chain->apply(new \Runtime\Vector($message));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->object = null;
		$this->listeners = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Listener"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}