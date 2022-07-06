<?php
require_once(__DIR__ . "/lastfm-api.php");


/**
 * last.fm only tells us e.g. "small", and not the actual pixel size of an image.
 * This array maps the descriptor to the pixel size.
 */
$mapRelativeSizeToPixels = array(
	"small" => 34,
	"medium" => 64,
	"large" => 174,
	"extralarge" => 300,
	"mega" => 300
);


/**
 * https://www.last.fm/api/show/track.search
 * @param string $searchTerm Your search query.
 * @return array Search response (contains songs only, no album/artist results)
 * @throws RequestError
 */
function doSearch(string $searchTerm, string $imageSize="25vw"): array {
	global $mapRelativeSizeToPixels;

	$response = LastfmRequester::request("/", array(
		"method" => "track.search",
		"track" => $searchTerm
	), false);
	$tracks = [];
	foreach ($response->results->trackmatches->track as $track) {
		$srcset = [];
		foreach ($track->image as $image) {
			if (empty($image->{"#text"})) continue; // API sometimes gives us an empty string instead of a link; skip
			$srcset[] = $image->{"#text"} . " " . $mapRelativeSizeToPixels[$image->size] . "w";
		}
		$tracks[] = array(
			"name" => $track->name,
			"artist" => $track->artist,
			"biggest_image_url" => $track->image[-1]->{"#text"} ?? "",
			"image_tag" => '<img class="lastfm-image"
								srcset="'.implode(", ", $srcset).'"
								src="'.($track->image[0]->{"#text"} ?? "").'"
								sizes="'.$imageSize.'"
								width="300" height="300"
								alt="'.$track->name.' by '.$track->artist.'"
								>'
		);
	}

	$response = LastfmRequester::request("/", array(
		"method" => "album.search",
		"album" => $searchTerm
	), false);
	$albums = [];
	foreach ($response->results->albummatches->album as $album) {
		$srcset = [];
		foreach ($album->image as $image) {
			if (empty($image->{"#text"})) continue; // API sometimes gives us an empty string instead of a link; skip
			$srcset[] = $image->{"#text"} . " " . $mapRelativeSizeToPixels[$image->size] . "w";
		}
		$albums[] = array(
			"name" => $album->name,
			"artist" => $album->artist,
			"biggest_image_url" => $album->image[-1]->{"#text"} ?? "",
			"image_tag" => '<img class="lastfm-image"
								srcset="'.implode(", ", $srcset).'"
								src="'.($album->image[0]->{"#text"} ?? "").'"
								sizes="'.$imageSize.'"
								width="300" height="300"
								alt="'.$album->name.' by '.$album->artist.'"
								>'
		);
	}

	$response = LastfmRequester::request("/", array(
		"method" => "artist.search",
		"artist" => $searchTerm
	), false);
	$artists = [];
	foreach ($response->results->artistmatches->artist as $artist) {
		$srcset = [];
		foreach ($artist->image as $image) {
			if (empty($image->{"#text"})) continue; // API sometimes gives us an empty string instead of a link; skip
			$srcset[] = $image->{"#text"} . " " . $mapRelativeSizeToPixels[$image->size] . "w";
		}
		$artists[] = array(
			"name" => $artist->name,
			"artist" => $artist->name,
			"biggest_image_url" => $artist->image[-1]->{"#text"} ?? "",
			"image_tag" => '<img class="lastfm-image"
								srcset="'.implode(", ", $srcset).'"
								src="'.($artist->image[0]->{"#text"} ?? "").'"
								sizes="'.$imageSize.'"
								width="300" height="300"
								alt="'.$artist->name.'"
								>'
		);
	}


	return array(
		LASTFM_CONTENT_TYPE::TRACK => $tracks,
		LASTFM_CONTENT_TYPE::ALBUM => $albums,
		LASTFM_CONTENT_TYPE::ARTIST => $artists,
	);
}
