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

use Runtime\Serializer;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\IntegerType;
use Runtime\Serializer\MapType;
use Runtime\Serializer\StringType;
use Runtime\Serializer\TypeError;
use Runtime\Serializer\VectorType;
use Runtime\Unit\AssertHelper;
use Runtime\Unit\Test;


class Basic
{
	function checkString1()
	{
		$api_value = "User";
		$correct_value = "User";
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\StringType();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkString2()
	{
		$api_value = 1;
		$correct_value = "1";
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\StringType();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkString3()
	{
		$api_value = 1;
		$correct_value = "";
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\StringType(new \Runtime\Map(["convert" => false]));
		$new_value = $rule->filter($api_value, $errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 1, "Should be errors");
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkInteger1()
	{
		$api_value = 1;
		$correct_value = 1;
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\IntegerType();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkInteger2()
	{
		$api_value = "1";
		$correct_value = 1;
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\IntegerType();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkInteger3()
	{
		$api_value = new \Runtime\Vector();
		$correct_value = 1;
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\IntegerType();
		$new_value = $rule->filter($api_value, $errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 1, "Should be errors");
	}
	
	
	
	function checkMap1()
	{
		$api_value = new \Runtime\Map([
			"name" => "User",
		]);
		$correct_value = new \Runtime\Map([
			"name" => "User",
		]);
		$rule = new \Runtime\Serializer\MapType(new \Runtime\Serializer\StringType());
		$errors = new \Runtime\Vector();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkMap2()
	{
		$api_value = new \Runtime\Map([
			"name" => 1,
		]);
		$correct_value = new \Runtime\Map([
			"name" => "1",
		]);
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\MapType(new \Runtime\Serializer\StringType());
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkMap3()
	{
		$api_value = new \Runtime\Map([
			"name" => "User",
			"fruits" => new \Runtime\Vector("apple", "banana", "cherry", "grape", "orange", "watermelon"),
		]);
		$correct_value = new \Runtime\Map([
			"name" => "User",
			"fruits" => new \Runtime\Vector("apple", "banana", "cherry", "grape", "orange", "watermelon"),
		]);
		$rule = new \Runtime\Serializer\MapType(new \Runtime\Map([
			"name" => new \Runtime\Serializer\StringType(),
			"fruits" => new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType()),
		]));
		$errors = new \Runtime\Vector();
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkVector1()
	{
		$api_value = new \Runtime\Vector("a", "b", "c");
		$correct_value = new \Runtime\Vector("a", "b", "c");
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType());
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	
	function checkVector2()
	{
		$api_value = new \Runtime\Vector(1);
		$correct_value = new \Runtime\Vector("1");
		$errors = new \Runtime\Vector();
		$rule = new \Runtime\Serializer\VectorType(new \Runtime\Serializer\StringType());
		$new_value = $rule->filter($api_value, $errors);
		$errors = \Runtime\Serializer\TypeError::getMessages($errors);
		\Runtime\Unit\AssertHelper::equalValue($errors->count(), 0, \Runtime\rs::join(", ", $errors));
		\Runtime\Unit\AssertHelper::equalValue($correct_value, $new_value);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Test.Serialize.Basic"; }
	static function getMethodsList()
	{
		return new \Runtime\Vector("checkString1", "checkString2", "checkString3", "checkInteger1", "checkInteger2", "checkInteger3", "checkMap1", "checkMap2", "checkMap3", "checkVector1", "checkVector2");
	}
	static function getMethodInfoByName($field_name)
	{
		if ($field_name == "checkString1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkString2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkString3") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkInteger1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkInteger2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkInteger3") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkMap1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkMap2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkMap3") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkVector1") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		if ($field_name == "checkVector2") return new \Runtime\Vector(
			new \Runtime\Unit\Test(new \Runtime\Map())
		);
		return null;
	}
}