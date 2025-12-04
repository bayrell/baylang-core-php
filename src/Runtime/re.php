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
namespace Runtime;


class re
{
	/**
	 * Разбивает строку на подстроки
	 * @param string delimiter - regular expression
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение
	 * @return Vector<string>
	 */
	static function split($delimiter, $s, $limit = -1)
	{
		$arr = preg_split("/" . $delimiter . "/", $s, $limit);
		return \Runtime\Vector::from($arr);
	}
	
	
	/**
	 * Search regular expression
	 * @param string r regular expression
	 * @param string s string
	 * @return bool
	 */
	static function match($r, $s, $pattern = "")
	{
		$matches = [];
		if (preg_match("/" . $r . "/" . $pattern, $s, $matches))
		{
			return $matches != null;
		}
		
		return false;
	}
	
	
	/**
	 * Search regular expression
	 * @param string r regular expression
	 * @param string s string
	 * @return Collection result
	 */
	static function matchAll($r, $s, $pattern = "")
	{
		$matches = [];
		if (preg_match_all("/" . $r . "/" . $pattern, $s, $matches))
		{
			$res = [];
			foreach ($matches as $index1 => $obj1)
			{
				foreach ($obj1 as $index2 => $val)
				{
					if (!isset($res[$index2])) $res[$index2] = [];
					$res[$index2][$index1] = $val;
				}
			}
			$res = array_map
			(
				function ($item) { return \Runtime\Vector::create($item); },
				$res
			);
			return \Runtime\Vector::create($res);
		}
		
		return null;
		return null;
	}
	
	
	/**
	 * Replace with regular expression
	 * @param string r - regular expression
	 * @param string replace - new value
	 * @param string s - replaceable string
	 * @return string
	 */
	static function replace($r, $replace, $s, $pattern = "")
	{
		return preg_replace("/" . $r . "/" . $pattern, $replace, $s);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.re"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}