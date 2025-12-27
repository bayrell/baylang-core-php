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
namespace Runtime\Test\Serialize;

use Runtime\BaseLayout;
use Runtime\BaseStorage;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\IntegerType;
use Runtime\Serializer\MapType;
use Runtime\Serializer\ObjectType;
use Runtime\Serializer\StringType;
use Runtime\Serializer\TypeError;
use Runtime\Serializer\VectorType;
use Runtime\Exceptions\AssertException;
use Runtime\Unit\AssertHelper;
use Runtime\Unit\Test;
use Runtime\Test\Serialize\Data\Contact;
use Runtime\Test\Serialize\Data\ContactEmail;
use Runtime\Test\Serialize\Data\ContactList;
use Runtime\Test\Serialize\Data\ContactPhone;
use Runtime\Test\Serialize\Data\PageModel;
use Runtime\Test\Serialize\Data\User;
use Runtime\Widget\Table\TableModel;


class Object
{
	function assignObject1()
	{
		$data = new \Runtime\Map([
			"name" => "User",
		]);
		$errors = new \Runtime\Vector();
		$user = new \Runtime\Test\Serialize\Data\User();
		\Runtime\rtl::assign($user, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalType($user->name, "string");
		\Runtime\Unit\AssertHelper::equalValue($user->name, "User");
	}
	
	
	
	function assignObject2()
	{
		$data = new \Runtime\Map([
			"name" => new \Runtime\Vector(),
		]);
		$errors = new \Runtime\Vector();
		$user = new \Runtime\Test\Serialize\Data\User();
		\Runtime\rtl::assign($user, $data, $errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 1, "Should be errors");
	}
	
	
	
	function assignObject3()
	{
		$data = new \Runtime\Map([
			"name" => "User",
			"roles" => new \Runtime\Vector(
				"admin",
				"user",
			),
		]);
		$errors = new \Runtime\Vector();
		$user = new \Runtime\Test\Serialize\Data\User();
		\Runtime\rtl::assign($user, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalType($user->name, "string");
		\Runtime\Unit\AssertHelper::equalValue($user->name, "User");
		\Runtime\Unit\AssertHelper::equalType($user->roles, "vector");
		\Runtime\Unit\AssertHelper::equalValue($user->roles, new \Runtime\Vector("admin", "user"));
	}
	
	
	
	function assignObject4()
	{
		$data = new \Runtime\Map([
			"name" => "User",
			"contact" => new \Runtime\Map([
				"kind" => "email",
				"email" => "info@example.com",
			]),
		]);
		$errors = new \Runtime\Vector();
		$user = new \Runtime\Test\Serialize\Data\User();
		\Runtime\rtl::assign($user, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalType($user->name, "string");
		\Runtime\Unit\AssertHelper::equalValue($user->name, "User");
		$contact = $user->contact;
		\Runtime\Unit\AssertHelper::equalType($contact, "object");
		\Runtime\Unit\AssertHelper::equalValue($contact::getClassName(), "Runtime.Test.Serialize.Data.ContactEmail");
		\Runtime\Unit\AssertHelper::equalValue($contact->email, "info@example.com");
	}
	
	
	
	function assignObject5()
	{
		$data = new \Runtime\Map([
			"name" => "User",
			"contact" => new \Runtime\Map(),
		]);
		$errors = new \Runtime\Vector();
		$user = new \Runtime\Test\Serialize\Data\User();
		\Runtime\rtl::assign($user, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalType($user->name, "string");
		\Runtime\Unit\AssertHelper::equalValue($user->name, "User");
		\Runtime\Unit\AssertHelper::equalValue($user->contact, null);
	}
	
	
	
	function assignObject6()
	{
		$data = new \Runtime\Map([
			"items" => new \Runtime\Vector(
				new \Runtime\Map([
					"kind" => "email",
					"email" => "info@example.com",
				]),
				new \Runtime\Map([
					"kind" => "phone",
					"phone" => "+70000000000",
				]),
			),
		]);
		$errors = new \Runtime\Vector();
		$item = new \Runtime\Test\Serialize\Data\ContactList();
		\Runtime\rtl::assign($item, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($item->items->count(), 2);
		\Runtime\Unit\AssertHelper::equalClass($item->items->get(0), "Runtime.Test.Serialize.Data.ContactEmail");
		\Runtime\Unit\AssertHelper::equalClass($item->items->get(1), "Runtime.Test.Serialize.Data.ContactPhone");
		\Runtime\Unit\AssertHelper::equalValue($item->items->get(0)->email, "info@example.com");
		\Runtime\Unit\AssertHelper::equalValue($item->items->get(1)->phone, "+70000000000");
	}
	
	
	
	function assignObject7()
	{
		$data = new \Runtime\Map([
			"frontend" => new \Runtime\Map([
				"user" => new \Runtime\Map([
					"__class_name__" => "Runtime.Test.Serialize.Data.User",
					"name" => "User",
					"contact" => new \Runtime\Map([
						"kind" => "email",
						"email" => "info@example.com",
					]),
				]),
			]),
		]);
		$errors = new \Runtime\Vector();
		$item = new \Runtime\BaseStorage();
		\Runtime\rtl::assign($item, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		$user = $item->frontend->get("user");
		\Runtime\Unit\AssertHelper::equalClass($user, "Runtime.Test.Serialize.Data.User");
		\Runtime\Unit\AssertHelper::equalClass($user->contact, "Runtime.Test.Serialize.Data.ContactEmail");
		\Runtime\Unit\AssertHelper::equalValue($user->contact->email, "info@example.com");
	}
	
	
	
	function assignObject8()
	{
		$data = new \Runtime\Map([
			"user" => new \Runtime\Map([
				"name" => "User",
				"contact" => new \Runtime\Map([
					"kind" => "email",
					"email" => "info@example.com",
				]),
			]),
		]);
		$errors = new \Runtime\Vector();
		$item = new \Runtime\Test\Serialize\Data\PageModel();
		\Runtime\rtl::assign($item, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		$user = $item->user;
		\Runtime\Unit\AssertHelper::equalClass($user, "Runtime.Test.Serialize.Data.User");
		\Runtime\Unit\AssertHelper::equalClass($user->contact, "Runtime.Test.Serialize.Data.ContactEmail");
		\Runtime\Unit\AssertHelper::equalValue($user->contact->email, "info@example.com");
	}
	
	
	
	function assignObject9()
	{
		$data = new \Runtime\Map([
			"table" => new \Runtime\Map([
				"items" => new \Runtime\Vector(
					new \Runtime\Map(["name" => "User"]),
				),
			]),
		]);
		$layout = new \Runtime\BaseLayout();
		$errors = new \Runtime\Vector();
		$item = new \Runtime\Test\Serialize\Data\PageModel(new \Runtime\Map(["layout" => $layout]));
		\Runtime\rtl::assign($item, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		$table = $item->table;
		\Runtime\Unit\AssertHelper::equalClass($table, "Runtime.Widget.Table.TableModel");
		\Runtime\Unit\AssertHelper::equalValue($table->component, "App.Table");
		\Runtime\Unit\AssertHelper::equalClass($table->items, "Runtime.Vector");
		\Runtime\Unit\AssertHelper::equalValue($table->items->count(), 1, "Error table count items");
		\Runtime\Unit\AssertHelper::equalValue($table->layout, $layout, "Layout not found");
		$user = $table->items->get(0);
		\Runtime\Unit\AssertHelper::equalClass($user, "Runtime.Map");
		\Runtime\Unit\AssertHelper::equalValue($user->get("name"), "User");
	}
	
	
	
	function assignLayout()
	{
		$data = new \Runtime\Map([
			"current_page_model" => "page_model",
			"pages" => new \Runtime\Map([
				"page_model" => new \Runtime\Map([
					"__class_name__" => "Runtime.Test.Serialize.Data.PageModel",
					"user" => new \Runtime\Map([
						"name" => "User",
					]),
				]),
			]),
		]);
		$errors = new \Runtime\Vector();
		$layout = new \Runtime\BaseLayout();
		\Runtime\rtl::assign($layout, $data, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		$model = $layout->getPageModel();
		\Runtime\Unit\AssertHelper::equalClass($model, "Runtime.Test.Serialize.Data.PageModel");
		\Runtime\Unit\AssertHelper::equalClass($model->user, "Runtime.Test.Serialize.Data.User");
		\Runtime\Unit\AssertHelper::equalValue($model->user->name, "User");
		\Runtime\Unit\AssertHelper::equalValue($model->layout, $layout, "Layout not found");
	}
	
	
	
	function serializeObject1()
	{
		$user = new \Runtime\Test\Serialize\Data\User();
		$user->name = "User";
		$api_value = \Runtime\rtl::serialize($user);
		$correct_value = new \Runtime\Map([
			"name" => "User",
			"roles" => new \Runtime\Vector(),
			"contact" => null,
		]);
		\Runtime\Unit\AssertHelper::equalValue($api_value, $correct_value);
	}
	
	
	
	function serializeObject2()
	{
		$user = new \Runtime\Test\Serialize\Data\User();
		$user->name = new \Runtime\Vector();
		$api_value = \Runtime\rtl::serialize($user);
		$correct_value = new \Runtime\Map([
			"name" => "",
			"roles" => new \Runtime\Vector(),
			"contact" => null,
		]);
		\Runtime\Unit\AssertHelper::equalValue($api_value, $correct_value);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Test.Serialize.Object"; }
	static function getMethodsList()
	{
		return new \Runtime\Vector("assignObject1", "assignObject2", "assignObject3", "assignObject4", "assignObject5", "assignObject6", "assignObject7", "assignObject8", "assignObject9", "assignLayout", "serializeObject1", "serializeObject2");
	}
	static function getMethodInfoByName($field_name)
	{
		if ($field_name == "assignObject1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject3") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject4") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject5") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject6") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject7") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject8") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignObject9") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "assignLayout") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "serializeObject1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "serializeObject2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		return null;
	}
}