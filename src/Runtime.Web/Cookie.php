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
namespace Runtime\Web;

use Runtime\BaseObject;

class Cookie extends \Runtime\BaseObject
{
	var $name;
	var $value;
	var $expires;
	var $domain;
	var $path;
	var $samesite;
	var $secure;
	var $httponly;
	var $changed;
	
	function getOptions()
	{
		$res = new \Runtime\Map();
		if ($this->expires) $res->set("expires", $this->expires);
		if ($this->domain) $res->set("domain", $this->domain);
		if ($this->path) $res->set("path", $this->path);
		if ($this->secure) $res->set("secure", $this->secure);
		if ($this->httponly) $res->set("httponly", $this->httponly);
		if ($this->samesite) $res->set("samesite", $this->samesite);
		return $res->toDict();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->value = "";
		$this->expires = null;
		$this->domain = "";
		$this->path = "/";
		$this->samesite = "Lax";
		$this->secure = false;
		$this->httponly = false;
		$this->changed = false;
	}
	static function getClassName(){ return "Runtime.Web.Cookie"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}