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
function getRecommendations(array $artistIds = [], array $genres = [], array $trackIds = [], string $imageSize="25vw"): array {
	$response = SpotifyRequester::request("/recommendations", array(
		"seed_artists" => implode(",", $artistIds),
		"seed_genres" => implode(",", $genres),
		"seed_tracks" => implode(",", $trackIds),
	), false);

	$results = [];
	foreach (SPOTIFY_CONTENT_TYPE::$ALL as $type_singular) {
		$type_plural = $type_singular . "s"; // response keys are plural

		$results[$type_singular] = [];
		if (!property_exists($response, $type_plural)) continue;

		foreach ($response->{$type_plural} as $item) {
			$results[$type_singular][] = array(
				"id" => $item->id,
				"name" => $item->name,
				"artist" => _getArtists($item),
				"biggest_image_url" => ($item->images ?? $item->album->images)[0]->url ?? "",
				"image_tag" => _extractImageTag($item, $imageSize),
				"url" => $item->external_urls->spotify ?? $item->url,
			);
		}
	}

	return $results;
}
