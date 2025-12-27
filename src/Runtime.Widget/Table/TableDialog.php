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
namespace Runtime\Widget\Table;

use Runtime\Widget\Button;
use Runtime\Widget\Table\Manager;


class TableDialog extends \Runtime\Widget\Dialog\Dialog
{
	function renderTitle()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$action = $this->manager->getDialogAction();
		$__v->push($this->manager->getDialogTitle($action));
		
		return $__v;
	}
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__content", $componentHash))])));
		
		if ($this->manager->dialog_action == "save")
		{
			$__v0->push($this->renderSlot("save"));
		}
		else if ($this->manager->dialog_action == "delete")
		{
			/* Element div */
			$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__delete_message", $componentHash))])));
			$__v1->push($this->manager->getDialogTitle("delete_message"));
			$__v0->push($this->renderWidget($this->manager->form->result, new \Runtime\Map([
				"class" => "result--center",
			])));
		}
		
		return $__v;
	}
	function renderFooter()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("dialog__footer", $componentHash))])));
		
		$action = $this->manager->getDialogAction();
		if ($action == "add")
		{
			/* Element Runtime.Widget.Button */
			$__v1 = $__v0->element("Runtime.Widget.Button");
			
			/* Content */
			$__v1->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Cancel");
				return $__v;
			});
			
			/* Element Runtime.Widget.Button */
			$__v2 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("button--primary", $componentHash))])));
			
			/* Content */
			$__v2->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Create");
				return $__v;
			});
		}
		else if ($action == "edit")
		{
			/* Element Runtime.Widget.Button */
			$__v3 = $__v0->element("Runtime.Widget.Button");
			
			/* Content */
			$__v3->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Cancel");
				return $__v;
			});
			
			/* Element Runtime.Widget.Button */
			$__v4 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("button--primary", $componentHash))])));
			
			/* Content */
			$__v4->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Save");
				return $__v;
			});
		}
		else if ($action == "delete")
		{
			/* Element Runtime.Widget.Button */
			$__v5 = $__v0->element("Runtime.Widget.Button");
			
			/* Content */
			$__v5->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Cancel");
				return $__v;
			});
			
			/* Element Runtime.Widget.Button */
			$__v6 = $__v0->element("Runtime.Widget.Button", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("button--danger", $componentHash))])));
			
			/* Content */
			$__v6->slot("default", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				$__v->push("Delete");
				return $__v;
			});
		}
		
		return $__v;
	}
	var $manager;
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->manager = null;
	}
	static function getComponentStyle(){ return ""; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button"); }
	static function getClassName(){ return "Runtime.Widget.Table.TableDialog"; }
}