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

use Runtime\re;
use Runtime\Math;


class rs
{
	/**
	 * Returns string lenght
	 * @param string s The string
	 * @return int
	 */
	static function strlen($s)
	{
		if (gettype($s) != "string") return 0;
		return @mb_strlen($s);
	}
	
	
	/**
	 * Returns substring
	 * @param string s The string
	 * @param int start
	 * @param int length
	 * @return string
	 */
	static function substr($s, $start, $length = null)
	{
		if ($length === null)
		{
			return mb_substr($s, $start);
		}
		return mb_substr($s, $start, $length);
	}
	
	
	/**
	 * Returns char from string at the position
	 * @param string s The string
	 * @param int pos The position
	 * @return string
	 */
	static function charAt($s, $pos)
	{
		return static::substr($s, $pos, 1);
	}
	
	
	/**
	 * Returns ASCII symbol by code
	 * @param int code
	 */
	static function chr($code)
	{
		return mb_chr($code);
	}
	
	
	/**
	 * Returns ASCII symbol code
	 * @param char ch
	 */
	static function ord($ch)
	{
		return mb_ord($ch);
	}
	
	
	/**
	 * Convert string to lower case
	 * @param string s
	 * @return string
	 */
	static function lower($s)
	{
		return mb_strtolower($s);
	}
	
	
	/**
	 * Convert string to upper case
	 * @param string s
	 * @return string
	 */
	static function upper($s)
	{
		return mb_strtoupper($s);
	}
	
	
	/**
	 * Compare strings
	 */
	static function compare($a, $b)
	{
		return strcmp($a, $b);
	}
	
	
	/**
	 * Заменяет одну строку на другую
	 */
	static function replace($search, $item, $s)
	{
		return str_replace($search, $item, $s);
	}
	
	
	/**
	 * Возвращает повторяющуюся строку
	 * @param {string} s - повторяемая строка
	 * @param {integer} n - количество раз, которые нужно повторить строку s
	 * @return {string} строка
	 */
	static function str_repeat($s, $n)
	{
		if ($n <= 0) return "";
		return str_repeat($s, $n);
	}
	
	
	/**
	 * Разбивает строку на подстроки
	 * @param string delimiter - regular expression
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение
	 * @return Collection<string>
	 */
	static function split($delimiter, $s, $limit = -1)
	{
		$arr = [];
		if ($limit < 0) $arr = explode($delimiter, $s);
		else $arr = explode($delimiter, $s, $limit);
		return \Runtime\Vector::create($arr);
	}
	
	
	/**
	 * Разбивает строку на подстроки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Collection<string>
	 */
	static function splitArr($delimiters, $s, $limit = -1)
	{
		$pattern = "[".implode("", $delimiters->_getArr())."]";
		$pattern = str_replace("/", "\/", $pattern);
		$arr = preg_split("/".$pattern."/", $s, $limit);
		return \Runtime\Vector::create($arr);
	}
	
	
	/**
	 * Соединяет строки
	 * @param string ch - разделитель
	 * @param string s - строка, которую нужно разбить
	 * @param integer limit - ограничение 
	 * @return Vector<string>
	 */
	static function join($ch, $arr)
	{
		if ($arr == null) return "";
		return implode($ch, $arr->_arr);
	}
	
	
	/**
	 * Join
	 */
	static function join_path($arr)
	{
		$path = static::join("/", $arr);
		$path = \Runtime\re::replace("\\/+", "/", $path);
		$path = \Runtime\re::replace("\\/\\.\\/", "/", $path);
		$path = \Runtime\re::replace("\\/+\$", "", $path);
		return $path;
	}
	
	
	/**
	 * Удаляет лишние символы слева и справа
	 * @param {string} s - входная строка
	 * @return {integer} новая строка
	 */
	static function trim($s, $ch = "")
	{
		if ($ch=="")
			return trim($s);
		return trim($s, $ch);
	}
	
	
	/**
	 * Remove first slash
	 */
	static function removeFirstSlash($path)
	{
		$i = 0;
		$sz = static::strlen($path);
		while ($i < $sz && static::substr($path, $i, 1) == "/") $i++;
		return static::substr($path, $i);
	}
	
	
	/**
	 * Remove last slash
	 */
	static function removeLastSlash($path)
	{
		$i = static::strlen($path) - 1;
		while ($i >= 0 && static::substr($path, $i, 1) == "/") $i--;
		return static::substr($path, 0, $i + 1);
	}
	
	
	/**
	 * Add first slash
	 */
	static function addFirstSlash($path)
	{
		if (\Runtime\rs::substr($path, 0, 1) == "/") return $path;
		return "/" . $path;
	}
	
	
	/**
	 * Add last slash
	 */
	static function addLastSlash($path)
	{
		if (\Runtime\rs::substr($path, \Runtime\rs::strlen($path) - 1, 1) == "/") return $path;
		return $path . "/";
	}
	
	
	/**
	 * Разбивает путь файла на составляющие
	 * @param {string} filepath путь к файлу
	 * @return {json} Объект вида:
	 *         dirname    - папка, в которой находиться файл
	 *         basename   - полное имя файла
	 *         extension  - расширение файла
	 *         filename   - имя файла без расширения
	 */
	static function pathinfo($filepath)
	{
		$arr1 = static::split(".", $filepath);
		$arr2 = static::split("/", $filepath);
		$extension = $arr1->pop();
		$basename = $arr2->pop();
		$dirname = static::join("/", $arr2);
		$ext_length = static::strlen($extension);
		if ($ext_length > 0) $ext_length++;
		$filename = static::substr($basename, 0, -1 * $ext_length);
		return new \Runtime\Map([
			"filepath" => $filepath,
			"extension" => $extension,
			"basename" => $basename,
			"dirname" => $dirname,
			"filename" => $filename,
		]);
	}
	
	
	/**
	 * Возвращает имя файла без расширения
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function filename($filepath)
	{
		$ret = static::pathinfo($filepath);
		$res = $ret->get("basename");
		$ext = $ret->get("extension");
		if ($ext != "")
		{
			$sz = 0 - \Runtime\rs::strlen($ext) - 1;
			$res = \Runtime\rs::substr($res, 0, $sz);
		}
		return $res;
	}
	
	
	/**
	 * Возвращает полное имя файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} полное имя файла
	 */
	static function basename($filepath)
	{
		$ret = static::pathinfo($filepath);
		$res = $ret->get("basename");
		return $res;
	}
	
	
	/**
	 * Возвращает расширение файла
	 * @param {string} filepath - путь к файлу
	 * @return {string} расширение файла
	 */
	static function extname($filepath)
	{
		$ret = static::pathinfo($filepath);
		$res = $ret->get("extension");
		return $res;
	}
	
	
	/**
	 * Возвращает путь к папке, содержащий файл
	 * @param {string} filepath - путь к файлу
	 * @return {string} путь к папке, содержащий файл
	 */
	static function dirname($filepath)
	{
		$ret = static::pathinfo($filepath);
		$res = $ret->get("dirname");
		return $res;
	}
	
	
	/**
	 * New line to br
	 */
	static function nl2br($s)
	{
		return static::replace("\n", "<br/>", $s);
	}
	
	
	/**
	 * Remove spaces
	 */
	static function spaceless($s)
	{
		$s = \Runtime\re::replace(" +", " ", $s);
		$s = \Runtime\re::replace("\t", "", $s);
		$s = \Runtime\re::replace("\n", "", $s);
		return $s;
	}
	
	
	/**
	 * Ищет позицию первого вхождения подстроки search в строке s.
	 * @param {string} s - строка, в которой производится поиск 
	 * @param {string} search - строка, которую ищем 
	 * @param {string} offset - если этот параметр указан, 
	 *                 то поиск будет начат с указанного количества символов с начала строки.  
	 * @return {variable} Если строка найдена, то возвращает позицию вхождения, начиная с 0.
	 *                    Если строка не найдена, то вернет -1
	 */
	static function indexOf($s, $search, $offset = 0)
	{
		if ($search == ""){
			return -1;
		}
		$res = mb_strpos($s, $search, $offset);
		if ($res === false)
			return -1;
		return $res;
	}
	
	
	/**
	 * URL encode
	 * @param string s
	 * @return string
	 */
	static function url_encode($s)
	{
		return urlencode($s);
	}
	
	
	/**
	 * Decode HTML
	 */
	static function htmlDecode($s)
	{
		return html_entity_decode($s);
	}
	static function decodeHtml($s){ return static::htmlDecode($s); }
	
	
	/**
	 * Escape HTML special chars
	 * @param string s
	 * @return string
	 */
	static function htmlEscape($s)
	{
		if ($s == null) return "";
		return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5);
	}
	static function escapeHtml($s){ return static::htmlEscape($s); }
	
	
	/**
	 * Base64 encode
	 * @param string s
	 * @return string
	 */
	static function base64_encode($s)
	{
		return base64_encode($s);
	}
	
	
	/**
	 * Base64 decode
	 * @param string s
	 * @return string
	 */
	static function base64_decode($s)
	{
		return base64_decode($s);
	}
	
	
	/**
	 * Base64 encode
	 * @param string s
	 * @return string
	 */
	static function base64_encode_url($s)
	{
		$s = base64_encode($s);
		$s = str_replace('+', '-', $s);
		$s = str_replace('/', '_', $s);
		$s = str_replace('=', '', $s);
		return $s;
	}
	
	
	/**
	 * Base64 decode
	 * @param string s
	 * @return string
	 */
	static function base64_decode_url($s)
	{
		$c = 4 - strlen($s) % 4;
		if ($c < 4 && $c > 0) $s .= str_repeat('=', $c);
		$s = str_replace('-', '+', $s);
		$s = str_replace('_', '/', $s);
		return base64_decode($s);
	}
	
	
	/**
	 * Parser url
	 * @param string s The string
	 * @return int
	 */
	static function parse_url($s)
	{
		$pos = static::indexOf($s, "#");
		$s = $pos >= 0 ? static::substr($s, 0, $pos) : $s;
		$hash = $pos >= 0 ? static::substr($s, $pos + 1) : "";
		$pos = static::indexOf($s, "?");
		$uri = $pos >= 0 ? static::substr($s, 0, $pos) : $s;
		$query = $pos >= 0 ? static::substr($s, $pos + 1) : "";
		$arr = static::split("&", $query);
		$arr2 = $arr->filter(function ($s){ return $s != ""; })->transition(function ($item)
		{
			$arr = static::split("=", $item);
			return new \Runtime\Vector($arr[1], $arr[0]);
		});
		return new \Runtime\Map([
			"uri" => $uri,
			"query" => $query,
			"query_arr" => $arr2,
			"hash" => $hash,
		]);
	}
	
	
	/**
	 * Returns string lenght
	 * @param string s The string
	 * @return int
	 */
	static function url_get_add($s, $key, $value)
	{
		$r = static::parse_url($s);
		$s1 = $r->get("uri");
		$s2 = $r->get("query");
		$find = false;
		$arr = static::split("&", $s2);
		$arr = $arr->map(function ($s) use (&$key, &$value, &$find)
		{
			$arr = static::split("=", $s);
			if ($arr[0] == $key)
			{
				$find = true;
				if ($key != "" && $value != "")
				{
					return $key . "=" . static::htmlEscape($value);
				}
				return "";
			}
			return $s;
		})->filter(function ($s){ return $s != ""; });
		if (!$find && $value != "")
		{
			$arr->push($key . "=" . static::htmlEscape($value));
		}
		$s = $s1;
		$s2 = static::join("&", $arr);
		if ($s2 != "") $s = $s . "?" . $s2;
		return $s;
	}
	
	
	/**
	 * Strip tags
	 */
	static function strip_tags($content, $allowed_tags = null)
	{
		if ($allowed_tags == null)
		{
			$content = \Runtime\re::replace("<[^>]+>", "", $content);
			$content = \Runtime\rs::trim(\Runtime\rs::spaceless($content));
			return $content;
		}
		$matches = \Runtime\re::matchAll("<[^>]+>", $content, "i");
		if ($matches)
		{
			for ($i = 0; $i < $matches->count(); $i++)
			{
				$match = $matches[$i];
				$tag_str = $match[0];
				$tag_match = \Runtime\re::matchAll("<(\\/|)([a-zA-Z]+)(|[^>]*)>", $tag_str, "i");
				if ($tag_match)
				{
					$tag_name = static::lower($tag_match[0][2]);
					if ($allowed_tags->indexOf($tag_name) == -1)
					{
						$content = static::replace($tag_str, "", $content);
					}
				}
			}
		}
		$content = \Runtime\rs::trim(\Runtime\rs::spaceless($content));
		return $content;
	}
	
	
	/**
	 * Generate uuid
	 */
	static function uid()
	{
		$bytes = bin2hex(random_bytes(16));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	
	
	/**
	 * Generate timestamp based uuid
	 */
	static function time_uid()
	{
		$bytes = dechex(time()) . bin2hex(random_bytes(12));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	
	
	/**
	 * Join class name
	 */
	static function className($arr)
	{
		return \Runtime\rs::join(" ", $arr->map(function ($s){ return static::trim($s); })->filter(function ($s){ return $s != ""; }));
	}
	
	
	/**
	 * Merge styles
	 */
	static function mergeStyles($class_name, $items)
	{
		return \Runtime\rs::join(" ", $items->map(function ($s){ return static::trim($s); })->filter(function ($s){ return $s != ""; })->map(function ($item) use (&$class_name){ return $class_name . "--" . $item; }));
	}
	
	
	/**
	 * Hash function
	 * @param string
	 * @return int hash
	 */
	static function hash($s, $x = 257, $p = 1000000007)
	{
		$h = 0;
		$sz = \Runtime\rs::strlen($s);
		for ($i = 0; $i < $sz; $i++)
		{
			$ch = \Runtime\rs::ord(\Runtime\rs::charAt($s, $i, 1));
			$h = ($h * $x + $ch) % $p;
		}
		return $h;
	}
	
	
	/**
	 * Returns CSS Hash
	 */
	static function getCssHash($class_name)
	{
		$h = \Runtime\rs::hash($class_name, 337, 65537) + 65537;
		$res = \Runtime\rs::toHex($h * 337 % 65537);
		return \Runtime\rs::substr($res, -4);
	}
	
	
	/**
	 * Returns component hash
	 */
	static function getComponentHash($class_name)
	{
		if (!\Runtime\rtl::isString($class_name)) $class_name = \Runtime\rtl::className($class_name);
		$global_hash = \Runtime\rtl::getContext()->provider("hash");
		$component_hash = $global_hash->get("component_hash");
		if (!$component_hash)
		{
			$component_hash = new \Runtime\Map();
			$global_hash->set("component_hash", $component_hash);
		}
		if ($component_hash->has($class_name)) return $component_hash->get($class_name);
		$result = new \Runtime\Vector();
		$item_name = $class_name;
		while ($item_name != "" && $item_name != "Runtime.Component")
		{
			$result->push("h-" . static::getCssHash($item_name));
			$item_name = \Runtime\rtl::parentClassName($item_name);
		}
		$value = \Runtime\rs::join(" ", $result);
		$component_hash->set($class_name, $value);
		return $value;
	}
	
	
	/**
	 * Convert int to hex
	 * @param int
	 * @return string
	 */
	static function toHex($h)
	{
		$r = "";
		$a = "0123456789abcdef";
		while ($h >= 0)
		{
			$c = $h & 15;
			$h = $h >> 4;
			$r = \Runtime\rs::charAt($a, $c) . $r;
			if ($h == 0) break;
		}
		return $r;
	}
	
	
	/**
	 * Hex decode
	 */
	static function hexdec($s)
	{
		return hexdec($s);
	}
	
	
	/**
	 * Generate random string
	 * @var len - string length
	 * @var spec
	 *   - a - alpha
	 *   - n - numberic
	 * @return string
	 */
	static function random_string($len = 8, $spec = "aun")
	{
		$s = "";
		$res = "";
		$sz = \Runtime\rs::strlen($spec);
		for ($i = 0; $i < $sz; $i++)
		{
			$ch = $spec[$i];
			if ($ch == "a")
			{
				$s .= "qwertyuiopasdfghjklzxcvbnm";
			}
			if ($ch == "u")
			{
				$s .= "QWERTYUIOPASDFGHJKLZXCVBNM";
			}
			else if ($ch == "n")
			{
				$s .= "0123456789";
			}
			else if ($ch == "s")
			{
				$s .= "!@#\$%^&*()-_+='\":;'.,<>?/|~";
			}
		}
		$sz_s = \Runtime\rs::strlen($s);
		for ($i = 0; $i < $len; $i++)
		{
			$code = \Runtime\rtl::random(0, $sz_s - 1);
			$res .= $s[$code];
		}
		return $res;
	}
	
	
	/**
	 * Format string
	 */
	static function format($s, $params = null)
	{
		if ($params == null) return $s;
		$params->each(function ($value, $key) use (&$s)
		{
			$s = \Runtime\rs::replace("%" . $key . "%", $value, $s);
		});
		return $s;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.rs"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}