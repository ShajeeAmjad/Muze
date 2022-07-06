<?php
require_once(__DIR__ . "/../all-apis/Requester.php");

const API_KEY = "eeae367405a610b0a35e7c3864ce7a9d";
const SHARED_SECRET = "b56530f5abb0aa5b7d851fa2dbb60673";

class LastfmRequester extends BaseRequester {
	public static string $URL_PREFIX = "http://ws.audioscrobbler.com/2.0";

	protected static function pre_curl_request($curl, &$data) {
		$data["api_key"] = API_KEY;
		$data["format"] = "json";
	}
}

/**
 * An enum of possible content types with the Genius API.
 */
class LASTFM_CONTENT_TYPE {
	public const TRACK = "track";
	public const ALBUM = "album";
	public const ARTIST = "artist";

	public static array $ALL = array(
		self::TRACK, self::ALBUM, self::ARTIST
	);
}

