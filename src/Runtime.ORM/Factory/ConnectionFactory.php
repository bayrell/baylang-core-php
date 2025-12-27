<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2024 "Ildar Bikmamatov" <support@bayrell.org>
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
namespace Runtime\ORM\Factory;

use Runtime\BaseObject;
use Runtime\BaseProvider;
use Runtime\Entity\Entity;
use Runtime\ORM\Connection;
use Runtime\ORM\Provider;


class ConnectionFactory extends \Runtime\Entity\Entity
{
	/**
	 * Create connection
	 */
	function createConnection()
	{
		return null;
	}
	
	
	/**
	 * Register connections
	 */
	function registerConnections($provider)
	{
		/* Create connection */
		$conn = $this->createConnection();
		/* Add connection */
		$provider->addConnection($conn);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.ORM.Factory.ConnectionFactory"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}