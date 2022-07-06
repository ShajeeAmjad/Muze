<?php
require_once(__DIR__ . "/spotify-api.php");
require_once(__DIR__ . "/doSearch.php");

/**
 * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-an-albums-tracks
 * @param string $searchTerm Your search query.
 * @param array $contentTypes An array of SPOTIFY_CONTENT_TYPEs to search across.
 * @return array Search response
 * @throws RequestError
 */
function getAlbumTracks(string $albumId): array {
	$response = SpotifyRequester::request("/albums/$albumId/tracks", array(
		"limit" => 50,
	), false);

	$results = [];
	foreach ($response->items as $item) {
		$results[] = array(
			"id" => $item->id,
			"name" => $item->name,
			"artist" => _getArtists($item),
			"url" => $item->external_urls->spotify ?? $item->url,
		);
	}

	return $results;
}
