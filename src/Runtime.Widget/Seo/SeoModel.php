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
namespace Runtime\Widget\Seo;

use Runtime\BaseObject;
use Runtime\BaseModel;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Serializer\VectorType;
use Runtime\Widget\Seo\SeoWidget;


class SeoModel extends \Runtime\BaseModel
{
	var $component;
	var $widget_name;
	var $canonical_url;
	var $description;
	var $favicon;
	var $article_published_time;
	var $article_modified_time;
	var $robots;
	var $tags;
	
	
	/**
	 * Process frontend data
	 */
	static function serialize($rules)
	{
		parent::serialize($rules);
		$rules->addType("article_modified_time", new \Runtime\Serializer\StringType());
		$rules->addType("article_published_time", new \Runtime\Serializer\StringType());
		$rules->addType("canonical_url", new \Runtime\Serializer\StringType());
		$rules->addType("description", new \Runtime\Serializer\StringType());
		$rules->addType("favicon", new \Runtime\Serializer\StringType());
		$rules->addType("robots", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType()));
		$rules->addType("tags", new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType()));
	}
	
	
	/**
	 * Init widget params
	 */
	function initParams($params)
	{
		parent::initParams($params);
		if ($params == null) return;
	}
	
	
	/**
	 * Set canonical url
	 */
	function setCanonicalUrl($canonical_url)
	{
		/* Add domain */
		if ($this->layout->request_host)
		{
			$canonical_url = "//" . $this->layout->request_host . $canonical_url;
			if ($this->layout->request_https) $canonical_url = "https:" . $canonical_url;
			else $canonical_url = "http:" . $canonical_url;
		}
		$this->canonical_url = $canonical_url;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Seo.SeoWidget";
		$this->widget_name = "seo";
		$this->canonical_url = "";
		$this->description = "";
		$this->favicon = "";
		$this->article_published_time = "";
		$this->article_modified_time = "";
		$this->robots = new \Runtime\Vector("follow", "index");
		$this->tags = null;
	}
	static function getClassName(){ return "Runtime.Widget.Seo.SeoModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}