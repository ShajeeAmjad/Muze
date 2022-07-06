<?php include_once(__DIR__ . "/../spotify-api/doSearch.php"); ?>
<?php if (isset($_GET["searchTerm"])) { ?>
    <style>
        .inline-list {
            overflow-x: auto;
            display: flex;
            list-style-type: none;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .inline-list li {
            display: inline-block;
            padding: 1rem;
        }
        .inline-list li h2 {
            width: 20vw;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .inline-list li img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
        }
    </style>

    <?php
    // get all types selected with the checkboxes
    $types = array_filter(SPOTIFY_CONTENT_TYPE::$ALL, function($type) { return array_key_exists($type, $_GET); });

	// use all types if none are specified
    if (empty($types)) $types = SPOTIFY_CONTENT_TYPE::$ALL;
    ?>

    <h1>Search results for '<?=$_GET["searchTerm"]?>'</h1>
    <p>
        Chosen content types:
	    <?=implode(", ", $types);?>
    </p>

    <?php $results = doSearch($_GET["searchTerm"], $types); ?>

    <?php foreach ($types as $type) {
		if (!array_key_exists($type, $results)) continue; ?>
            
            <ul class="inline-list">
                <?php foreach ($results[$type]->items as $result) { ?>
                    <li>
                        <?=SearchComponent::extractImageTag($result)?>
                        <h2><?=$result->name?></h2>
                    </li>
                <?php } ?>
            </ul>

	<?php } ?>

<?php } else { ?>

    <form action="search.php">
        <label>
            <input name="searchTerm" placeholder="Search terms">
        </label>
        <div>
            Content types:
            <?php foreach (SPOTIFY_CONTENT_TYPE::$ALL as $type) { ?>
                <label>
                    <input type="checkbox" name="<?=$type?>">
                    <?=$type?>
                </label>
                &nbsp;
                &nbsp;
            <?php } ?>
        </div>

        <button type="submit">Search</button>
    </form>
<?php } ?>
