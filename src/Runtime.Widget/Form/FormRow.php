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
namespace Runtime\Widget\Form;


class FormRow extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row", $this->class, static::mergeStyles("form_row", $this->styles), $componentHash))])));
		
		if ($this->checkSlot("label"))
		{
			/* Element div */
			$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row__label", $componentHash))])));
			$__v1->push($this->renderSlot("label"));
		}
		
		if ($this->checkSlot("content"))
		{
			/* Element div */
			$__v2 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row__content", $componentHash))])));
			$__v2->push($this->renderSlot("content"));
		}
		
		if ($this->checkSlot("result"))
		{
			/* Element div */
			$__v3 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row__result", $componentHash))])));
			$__v3->push($this->renderSlot("result"));
		}
		
		if ($this->error->count() > 0)
		{
			/* Element div */
			$__v4 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("form_row__error", $componentHash))])));
			
			for ($i = 0; $i < $this->error->count(); $i++)
			{
				/* Element div */
				$__v5 = $__v4->element("div");
				$__v5->push($this->error->get($i));
			}
		}
		
		return $__v;
	}
	var $styles;
	var $error;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->styles = new \Runtime\Vector();
		$this->error = new \Runtime\Vector();
	}
	static function getComponentStyle(){ return ".form_row.h-df7a{margin-bottom: 10px}.form_row.h-df7a:last-child{margin-bottom: 0px}.form_row__label.h-df7a{margin-bottom: 5px}.form_row--flex.h-df7a{display: flex;align-items: center}.form_row--flex__label.h-df7a, .form_row--flex__label__content.h-df7a{width: 50%}.form_row__error.h-df7a{color: var(--widget-color-danger);margin-top: var(--widget-space)}.form_row__error--hide.h-df7a{display: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Form.FormRow"; }
}