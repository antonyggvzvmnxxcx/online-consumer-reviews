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
            <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="category.php?name=cameras">Cameras</a></li>
            <li><a href="category.php?name=laptops">Laptops</a></li>
            <li><a href="category.php?name=mobile phone">Mobile Phone</a></li>
            <li><a href="category.php?name=tablets">Tablets</a></li>
            <li><a href="category.php?name=television">Television</a></li>
          </ul>
          
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">DataSet</h1>
          <h2 class="sub-header">Amazon</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Categories</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                    <?php
                    $dataSetPath = __DIR__ . '/dataset/amazon/';
                    $dataSetFolders = array();
                    if(file_exists($dataSetPath)) {
                    	$dataSetFolders = scandir($dataSetPath);
                    }
                    $i = 0;
                    foreach($dataSetFolders as $dataSetFolder) {
                        if($dataSetFolder == "." || $dataSetFolder == "..") continue;
                        if(!is_dir($dataSetPath . $dataSetFolder)) continue;
                        $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><a href="category.php?name=<?php echo $dataSetFolder; ?>"><?php echo ucwords($dataSetFolder); ?></a></td>
                        <td><?php echo count(scandir($dataSetPath . $dataSetFolder)); ?><td>
                        
                    </tr>
                <?php } ?>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    

    
  </body>
</html>