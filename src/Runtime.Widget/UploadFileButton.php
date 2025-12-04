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
namespace Runtime\Widget;

use Runtime\Widget\Messages\ValueChangeMessage;

class UploadFileButton extends \Runtime\Widget\Button
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("upload_file_button", $componentHash))])));
		$__v0->push(parent::render());
		
		/* Element input */
		$__v0->element("input", (new \Runtime\Map(["type" => "file"])));
		
		return $__v;
	}
	/**
	 * Button click
	 */
	function onClick($e)
	{
		$upload_file = $this->getRef("upload_file");
		$upload_file->click();
	}
	/**
	 * File upload change event
	 */
	function onFileUploadChange($e)
	{
		$upload_file = $this->getRef("upload_file");
		$file = $upload_file->files[0];
		if ($file)
		{
			$message = new \Runtime\Widget\Messages\ValueChangeMessage(new \Runtime\Map([
				"name" => "file",
				"value" => $file,
				"data" => $this->data,
			]));
			$this->emit($message);
			if ($this->model) $this->model->emit($message);
		}
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".upload_file_button.h-a4c6 input{display: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.UploadFileButton"; }
}