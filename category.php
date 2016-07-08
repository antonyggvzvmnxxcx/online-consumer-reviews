<?php 
if(!isset($_GET["name"])) {
  exit(0); 
} else {
  $category = strtolower($_GET["name"]);
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
          <a class="navbar-brand" href="#">Online Consumer Review</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
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
            <li><a href="/">Overview <span class="sr-only">(current)</span></a></li>
            <li class="<?php echo $category == 'cameras' ? 'active' :'' ?>"><a href="category.php?name=cameras">Cameras</a></li>
            <li class="<?php echo $category == 'laptops' ? 'active' :'' ?>"><a href="category.php?name=laptops">Laptops</a></li>
            <li class="<?php echo $category == 'mobile phone' ? 'active' :'' ?>"><a href="category.php?name=mobile phone">Mobile Phone</a></li>
            <li class="<?php echo $category == 'tablets' ? 'active' :'' ?>"><a href="category.php?name=tablets">Tablets</a></li>
            <li class="<?php echo $category == 'television' ? 'active' :'' ?>"><a href="category.php?name=television">Television</a></li>
          </ul>
          
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">DataSet </h1>
          <h2 class="sub-header">
            <form class="form-inline">
              <div class="form-group">
              <label for="product_search"><?php echo ucwords($category); ?> </label>
              <input type="text" class="form-control" id="product_search" placeholder="Type Products Here....">
            </div>
              
            </form>
          </h2>
          <div class="table-responsive">
            <table id="tbl_product" class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Products ID</th>
                  <th>Products Name</th>
                  <th>Review</th>
                </tr>
              </thead>
              <tbody>
                    <?php
                    $dataSetPath = __DIR__ . '/dataset/amazon/' . $category;
                    $dataSetFolders = array();
                    if(file_exists($dataSetPath)) {
                    	$dataSetFolders = scandir($dataSetPath);
                    }
                    $i = 0;
                    foreach($dataSetFolders as $dataSetFolder) {
                      if($dataSetFolder == "." || $dataSetFolder == "..") continue;
                      
                        $data = json_decode(file_get_contents($dataSetPath . "/" . $dataSetFolder));
                        $itemName = str_replace(".json", " " , $dataSetFolder);
                        $i++;
                    ?>
                    <tr id="<?php echo $data->ProductInfo->ProductID; ?>">
                        <td><?php echo $i; ?></td>
                        <td ><a href="product.php?id=<?php echo $data->ProductInfo->ProductID; ?>&category=<?php echo $category; ?>">
                          <?php echo $data->ProductInfo->ProductID; ?>
                          </a>
                        </td>
                        <td>
                          <?php echo $data->ProductInfo->Name; ?>
                        </td>
                        <td><a target ="_blank" href="/dataset/amazon/<?php echo $category. "/" . $dataSetFolder; ?>"><?php echo count($data->Reviews); ?></a><td>
                        
                    </tr>
                <?php } ?>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    

    <script type="text/javascript">
      $(document).ready(function() {
        $("input#product_search").keypress(function(e) {
          
           var code = e.keyCode || e.which;
           if(code == 13) { //Enter keycode
              e.preventDefault();
              var search_id = $(this).val();
              console.log(search_id);
              $("#tbl_product tr").each(function(index, target){
                var id = $(this).attr("id");
                if(id == undefined) return;
                if(id.toLowerCase().indexOf(search_id.toLowerCase()) > -1) {
                  $(this).show();
                  
                } else {
                  $(this).hide();
                }
                
              });
           }
          
          
        });
      });
    </script>
  </body>
</html>