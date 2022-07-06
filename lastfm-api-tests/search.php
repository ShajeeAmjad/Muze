<?php include_once(__DIR__ . "/../lastfm-api/doSearch.php"); ?>
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

    <h1>Search results for '<?=$_GET["searchTerm"]?>'</h1>

    <?php $results = doSearch($_GET["searchTerm"]); ?>
            
    <ul class="inline-list">
        <?php foreach ($results as $result) { ?>
            <li>
                <?=$result["image_tag"]?>
                <h2><?=$result["name"]?></h2>
                <p><?=$result["artist"]?></p>
            </li>
        <?php } ?>
    </ul>

<?php } else { ?>

    <form action="search.php">
        <label>
            Search term:
            <input name="searchTerm" placeholder="Search terms">
        </label>

        <button type="submit">Search</button>
    </form>
<?php } ?>
