<?php
require_once(__DIR__ . "/spotify-api.php");

function _getArtists(stdClass $item): string {
	// (the @ suppresses warnings if these properties don't exist)
	@$artistsObject = $item->artists ?? $item->album->artists;

	if (empty($artistsObject)) {
		// playlists have an owner property
		if (!empty($item->owner)) return $item->owner->display_name;

		// otherwise, return an empty array if we've failed to find any artists
		return "";
	}

	// loop through artistsObject and extract the artist's name from each object
	$artists = array_map(function($artist) {
		return $artist->name;
	}, $artistsObject);

	// return comma delimited list of artists
	return implode(", ", $artists);
}

function _extractImageTag(stdClass $item, string $sizes="25vw"): string {
	$images = $item->images ?? $item->album->images ?? [];

	// collect an array of images and their sizes in the form of "elva-fairy-480w.jpg 480w"
	$srcset = array_map(function ($image) {
		return "$image->url $image->width";
	}, $images);

	// default to the biggest image
	$defaultImageUrl = $images[0]->url ?? "";

	return '
		<img srcset="'.implode(", ", $srcset).'"
			sizes="'.$sizes.'"
			src="'.$defaultImageUrl.'"
			alt="'.$item->name.'" >
		';
}

/**
 * https://developer.spotify.com/documentation/web-api/reference/#/operations/search
 * @param string $searchTerm Your search query.
 * @param array $contentTypes An array of SPOTIFY_CONTENT_TYPEs to search across.
 * @return array Search response
 * @throws RequestError
 */
function doSearch(string $searchTerm, array $contentTypes, string $imageSize="25vw"): array {
	$response = SpotifyRequester::request("/search", array(
		"q" => $searchTerm,
		"type" => implode(",", $contentTypes),
		// "limit" => 20,
		// "offset" => 0,  // todo: implement pagination via the offset property
	), false);

	$results = [];
	foreach (SPOTIFY_CONTENT_TYPE::$ALL as $type_singular) {
		$type_plural = $type_singular . "s"; // response keys are plural
		if (!property_exists($response, $type_plural)) continue;

		$results[$type_singular] = [];
		foreach ($response->{$type_plural}->items as $item) {
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
