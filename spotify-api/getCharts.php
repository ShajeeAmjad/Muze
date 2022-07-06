<?php
require_once(__DIR__ . "/spotify-api.php");
require_once(__DIR__ . "/doSearch.php");

/**
 * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-recommendations
 * @param array $artistIds An array of Spotify IDs for seed artists.
 * @param array $genres An array of any genres in the set of available genre seeds.
 * @param array $trackIds An array of Spotify IDs for a seed track.
 * @return array Search response
 * @throws RequestError
 */
function getCharts(string $imageSize="25vw"): array {
	// get tracks in playlist "Top 50 - United Kingdom" by Spotify (playlist id: 37i9dQZEVXbLnolsZ8PSNw)
	$response = SpotifyRequester::request("/playlists/37i9dQZEVXbLnolsZ8PSNw", array(), false);

	$results = [];
	if (empty($response->tracks)) return $results;
	foreach ($response->tracks->items as $item) {
		$item = $item->track;
		$results[] = array(
			"id" => $item->id,
			"name" => $item->name,
			"artist" => _getArtists($item),
			"biggest_image_url" => ($item->images ?? $item->album->images)[0]->url ?? "",
			"image_tag" => _extractImageTag($item, $imageSize),
			"url" => $item->external_urls->spotify ?? $item->url,
		);
	}

	return $results;
}
