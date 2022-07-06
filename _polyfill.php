<?php
#region add root of project to include_path because PHP is an icky language
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
#endregion


#region Polyfill some php functions for earlier php versions than officially supported
// This lets us use e.g. PHP 8 functions on PHP 7

if (!function_exists('str_contains')) {
	/**
	 * Determine if a string contains a given substring.
	 * https://www.php.net/manual/en/function.str-contains.php
	 * @param string $haystack The string to search in.
	 * @param string $needle The substring to search for in the haystack.
	 * @return bool Returns true if needle is in haystack, false otherwise.
	 */
	function str_contains(string $haystack, string $needle): bool {
		return empty($needle) || mb_strpos($haystack, $needle) !== false;
	}
}

if (!function_exists('str_starts_with')) {
	/**
	 * Checks if a string starts with a given substring.
	 * https://www.php.net/manual/en/function.str-starts-with.php
	 * @param string $haystack The string to search in.
	 * @param string $needle The substring to search for in the haystack.
	 * @return bool Returns true if haystack begins with needle, false otherwise.
	 */
	function str_starts_with(string $haystack, string $needle): bool {
		return strpos( $haystack , $needle ) === 0;
	}
}

#endregion
