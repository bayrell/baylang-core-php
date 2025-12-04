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
namespace Runtime\Widget\Gallery;

use Runtime\Widget\Gallery\GalleryDialog;
use Runtime\Widget\Dialog\DialogModel;


class GalleryDialogModel extends \Runtime\Widget\Dialog\DialogModel
{
	var $component;
	var $current;
	
	
	/**
	 * Returns items
	 */
	function getItems(){ return $this->parent_widget->getItems(); }
	
	
	/**
	 * Returns item
	 */
	function getItem($pos){ return $this->parent_widget->getItem($pos); }
	
	
	/**
	 * Returns image
	 */
	function getImage($pos){ return $this->parent_widget->getBigImage($pos); }
	
	
	/**
	 * Returns true if image is contains
	 */
	function getImageContains()
	{
		return $this->parent_widget->dialog_image_contains === true || $this->parent_widget->dialog_image_contains == "1" || $this->parent_widget->dialog_image_contains == "true";
	}
	
	
	
	/**
	 * Returns current image
	 */
	function getCurrentImage(){ return $this->getImage($this->current); }
	
	
	/**
	 * Select image
	 */
	function select($pos)
	{
		$count = $this->getItems()->count();
		if ($count == 0)
		{
			$this->current = 0;
			return;
		}
		$this->current = $pos % $count;
		if ($this->current < 0)
		{
			$this->current = ($this->current + $count) % $count;
		}
	}
	
	
	/**
	 * Show prev image
	 */
	function prev()
	{
		$this->select($this->current - 1);
	}
	
	
	/**
	 * Show next image
	 */
	function next()
	{
		$this->select($this->current + 1);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->component = "Runtime.Widget.Gallery.GalleryDialog";
		$this->current = 0;
	}
	static function getClassName(){ return "Runtime.Widget.Gallery.GalleryDialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}