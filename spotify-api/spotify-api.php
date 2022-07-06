<?php
require_once(__DIR__ . "/../all-apis/Requester.php");

const CLIENT_ID = "66220fc47eb94cf7ac431646dc4c43cb";
const CLIENT_SECRET = "8685a359e1a244cda6a1c1486b7b7abb";



/**
 * An enum of possible content types with the Spotify API.
 * Source: https://developer.spotify.com/documentation/web-api/reference/#/operations/search.
 */
class SPOTIFY_CONTENT_TYPE {
	public const ALBUM = "album";
	public const ARTIST = "artist";
	public const TRACK = "track";

	public static array $ALL = array(
		self::TRACK, self::ALBUM, self::ARTIST
	);
}


class SpotifyRequester extends BaseRequester {
	public static string $URL_PREFIX = "https://api.spotify.com/v1";

	/**
	 * Initialise the Spotify API access token
	 * @return void
	 */
	public static function __staticConstructor() {
		if (!isset($_SESSION["spotify_auth_code"])) {
			// Use Client Credentials Flow to get an access token
			// https://developer.spotify.com/documentation/general/guides/authorization/client-credentials/

			try {
				$response = self::request("https://accounts.spotify.com/api/token", array(
					"grant_type" => "client_credentials"
				), true);
			} catch (RequestError $e) {
				// todo: display error page to user rather than raw text
				echo $e->getMessage();
				die;
			}
			$_SESSION["spotify_auth_code"] = $response->access_token;
		}
	}

	protected static function pre_curl_request($curl, &$data) {
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			self::getAuthorizationHeader()
		));
	}

	/**
	 * Valid access token following the format: Bearer <Access Token> if we have an access token.
	 * Otherwise, use Basic validation (to request an access token).
	 * @return string Header string beginning with "Authorization: "
	 */
	private static function getAuthorizationHeader(): string {
		if (isset($_SESSION["spotify_auth_code"])) {
			return "Authorization: Bearer " . $_SESSION["spotify_auth_code"];
		} else {
			return "Authorization: Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);
		}
	}
}

SpotifyRequester::__staticConstructor();
