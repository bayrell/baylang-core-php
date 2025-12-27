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
namespace Runtime\Widget\Seo;


class SeoWidget extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Canonical url */
		if ($this->model->canonical_url)
		{
			/* Element link */
			$__v->element("link", (new \Runtime\Map(["rel" => "canonical", "href" => $this->model->canonical_url])));
		}
		/* Locale */
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["property" => "og:locale", "content" => $this->layout->lang])));
		/* Title and description */
		/* Element meta */
		$__v->element("meta", (new \Runtime\Map(["property" => "og:title", "content" => $this->layout->title])));
		
		if ($this->model->description != "")
		{
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "og:description", "content" => $this->model->description])));
			
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["name" => "description", "content" => $this->model->description])));
		}
		/* Site name */
		$site_name = $this->layout->getSiteName();
		if ($site_name)
		{
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "og:site_name", "content" => $site_name])));
			
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "article:publisher", "content" => $site_name])));
		}
		/* Robots */
		if ($this->model->robots)
		{
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["name" => "robots", "content" => \Runtime\rs::join(",", $this->model->robots)])));
		}
		/* Tags */
		if ($this->model->tags != null && $this->model->tags->count() > 0)
		{
			for ($i = 0; $i < $this->model->tags->count(); $i++)
			{
				/* Element meta */
				$__v->element("meta", (new \Runtime\Map(["property" => "article:tag", "content" => $this->model->tags[$i]])));
			}
		}
		/* Article time */
		if ($this->model->article_published_time)
		{
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "article:published_time", "content" => $this->model->article_published_time])));
		}
		
		if ($this->model->article_modified_time)
		{
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "article:article_modified_time", "content" => $this->model->article_modified_time])));
			
			/* Element meta */
			$__v->element("meta", (new \Runtime\Map(["property" => "og:article_modified_time", "content" => $this->model->article_modified_time])));
		}
		
		return $__v;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Seo.SeoWidget"; }
}