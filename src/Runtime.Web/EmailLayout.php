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
*/
namespace Runtime\Web;

use Runtime\Web\BaseLayoutModel;

class EmailLayout extends \Runtime\Web\DefaultLayout
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element title */
		$__v0 = $__v->element("title");
		$__v0->push($this->layout->title);
		
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["charset" => $this->layout->content_type])));
		
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["http-equiv" => "Content-Type", "content" => "text/html; " . $this->layout->content_type])));
		
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["http-equiv" => "Content-Language", "content" => $this->layout->getLocale()])));
		
		return $__v;
	}
	function renderCSS()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$css = $this->getCSS();
		if ($css)
		{
			/* Element style */
			$__v0 = $__v->element("style", (new \Runtime\Map(["id" => "core-css", "class" => \Runtime\rs::className(new \Runtime\Vector("components", $componentHash))])));
			$__v0->push("@raw");
			$__v0->push($css);
		}
		
		return $__v;
	}
	function renderCoreUI()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element html */
		$__v0 = $__v->element("html", (new \Runtime\Map(["lang" => $this->layout->getLocale()])));
		
		/* Element head */
		$__v1 = $__v0->element("head");
		$__v1->push(static::renderTitle());
		$__v1->push(static::renderCSS());
		
		/* Element body */
		$__v2 = $__v0->element("body");
		$__v2->push(static::render());
		
		return $__v;
	}
	/**
	 * Returns CSS
	 */
	function getCSS()
	{
		return \Runtime\Web\BaseLayoutModel::getCSS($this->getComponents());
	}
	/**
	 * Returns components
	 */
	function getComponents()
	{
		$res = new \Runtime\Vector();
		$cache = new \Runtime\Map();
		/* Init components */
		$components = $this->layout->components->copy();
		$components->push($this->layout->getPageClassName());
		$components->push(static::getClassName());
		/* Extends components */
		\Runtime\Web\BaseLayoutModel::_getRequiredComponents($res, $cache, $components);
		return $res->removeDuplicates();
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Web.EmailLayout"; }
}