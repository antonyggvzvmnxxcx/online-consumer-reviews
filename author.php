<?php
$dataSetPath = __DIR__ . '/dataset/amazon/';
$authorPath = __DIR__ . '/dataset/author/amazon.json';
$authors = array();
if(isset($_GET["size"])) {
    $size = $_GET["size"];
} else {
    $size = 50;
}
if(isset($_GET["refresh"])) {

    $dataSetFolders = array();
    if(file_exists($dataSetPath)) {
        $dataSetFolders = scandir($dataSetPath);
    }
    $i = 0;
    // Folders

    foreach($dataSetFolders as $dataSetFolder) {
        if($dataSetFolder == "." || $dataSetFolder == "..") continue;
        if(!is_dir($dataSetPath . $dataSetFolder)) continue;
        $dataSetFiles = scandir($dataSetPath . $dataSetFolder);

        //Json Files
        foreach($dataSetFiles as $dataSetFile) {
            if($dataSetFile == "." || $dataSetFile == "..") continue;
            $dataSetFilePath = $dataSetPath . $dataSetFolder . DIRECTORY_SEPARATOR . $dataSetFile;
            if(file_exists($dataSetFilePath)) {
                $JsonData= json_decode(file_get_contents($dataSetFilePath));
                foreach($JsonData->Reviews as $review) {
                    if(empty($review->Author) || empty($JsonData->ProductInfo->ProductID)) continue;

                    if(!array_key_exists($review->Author, $authors) && !empty($review->Author) ) {
                        $authors[$review->Author] = array("name" => $review->Author,
                            "count" => 1,
                            "product" => array($JsonData->ProductInfo->ProductID),
                            "category" => $dataSetFolder
                        );
                    } else {
                        $authors[$review->Author]["count"]++;
                        $authors[$review->Author]["product"][] = $JsonData->ProductInfo->ProductID;
                    }
                }
            }
        }//end files
        $i++;
        ?>
    <?php }//end folders
    file_put_contents($authorPath, json_encode($authors));
} else {
    $authors = json_decode(file_get_contents($authorPath), true);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/dashboard.css" />
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js")></script>
    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <title>Online Consumer Review</title>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Online Consumer Review</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Help</a></li>
            </ul>
            <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li><a href="#">Dataset <span class="sr-only">(current)</span></a></li>
                <li class="active"><a href="author.php">Author</a></li>
                <li><a href="dictionary.php">Dictionary</a></li>
            </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Author</h1>
            <h2 class="sub-header">Amazon <a href="author.php?refresh=1">Refresh</a></h2>
            <div class="table-responsive">

                <!--Render Data -->
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Popular</th>
                        <th>Products's Reviews</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                $i = 1;
                foreach($authors as $author => $authorData) { ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $authorData["name"]; ?></td>
                            <td><?php echo $authorData["count"]?></td>
                            <td><?php
                                foreach($authorData["product"] as $product) {
                                    $dataSetPath = __DIR__ . '/dataset/amazon/' . $authorData["category"] . '/' . $product . '.json';
                                    if(file_exists($dataSetPath)) echo "<a href=' ". $dataSetPath ."'> " . $product. "</a>, ";
                                }
                                ?>
                            </td>

                        </tr>
                    <?php
                    if($i == $size) {
                        break;
                    } else {
                        $i++;
                    }
                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




</body>
</html>