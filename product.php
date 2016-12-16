<?php 
if(!isset($_GET["id"]) && !isset($_GET["category"])) {
  exit(0); 
} else {
  $TITLE_RATING = 0.35;
  $CONTENT_RATING = 0.65;

  //Affect
  $RATING = 0.3; // 30%
  $LONGEVITY = 0.33; // 33%
  $POPULARITY = 0.37; // 37%
  $enableOptions = true;
  
  $category = strtolower($_GET["category"]);
  $productId = $_GET["id"];
  
  $dataSetPath = __DIR__ . '/dataset/amazon/' . $category . '/' . $productId . '.json';
  $data = null;
  
  if(file_exists($dataSetPath)) {
    
  	$data= json_decode(file_get_contents($dataSetPath));
  } 

  $authorPath = __DIR__ . '/dataset/author/amazon.json';
  $authors = json_decode(file_get_contents($authorPath), true);
  
  $totalReview = count($data->Reviews);
  
  $reviewScore = array();
  
  $reviewStatistic = array("title" => array("neu" => 0, "neg" => 0, "pos" => 0),
                           "content" => array("neu" => 0, "neg" => 0, "pos" => 0),
                           "overall" => 0);
                           
  if($totalReview > 0) {
    require_once __DIR__ . '/autoload.php';
    $sentiment = new \PHPInsight\Sentiment();
    $options = array();
    foreach($data->Reviews as $review) {
        if($enableOptions) {
            $authorScore = isset($authors[$review->Author]) ? $authors[$review->Author]["count"] : 0;
            $options = array("RATING" => array("value" => floatval($review->Overall),
                "mid" => 3,
                "score" => $RATING),
                "LONGEVITY" => array("value" => date('Y') - date('Y', strtotime($review->Date)),
                    "mid" => 5,
                    "score" => $LONGEVITY),
                "POPULARITY" => array("value" => isset($authors[$review->Author]) ? $authors[$review->Author]["count"] : 0,
                    "mid" => isset($authors[$review->Author]) ? 100 : 0,
                    "score" => $POPULARITY),

            );
        }

      $titleScore = $sentiment->score($review->Title, $options);

      $contentScore = $sentiment->score($review->Content, $options);
       
      $titleCategorise = $sentiment->categorise($review->Title, $options);
      $contentCategorise = $sentiment->categorise($review->Content, $options);
      
      $reviewScore[$review->ReviewID] = array("title" => array("value" => $review->Title, 
                                                              "score" => $titleScore, 
                                                              "result" => $titleCategorise),
                                              "content" => array("value" => $review->Content, 
                                                              "score" => $contentScore, 
                                                              "result" => $contentCategorise),
                                              
                                              "author" => $review->Author,
                                              "date" => $review->Date,
                                              "point" => $review->Overall,
                                              );
      
      switch($titleCategorise) {
        case "neu": $reviewStatistic["title"]["neu"]++; break;
        case "neg": $reviewStatistic["title"]["neg"]++; break;
        case "pos": $reviewStatistic["title"]["pos"]++; break;
      }   
      
      switch($contentCategorise) {
        case "neu": $reviewStatistic["content"]["neu"]++; break;
        case "neg": $reviewStatistic["content"]["neg"]++; break;
        case "pos": $reviewStatistic["content"]["pos"]++; break;
      }  
      
      $reviewStatistic["overall"] += $review->Overall;
      
    }//end foreach
    
    $reviewStatistic["overall"] =  round($reviewStatistic["overall"] / $totalReview , 2);
    
    
    $sentimentScore = array("neu" => ($reviewStatistic["title"]["neu"] * $TITLE_RATING + $reviewStatistic["content"]["neu"] * $CONTENT_RATING)/$totalReview,
                              "neg" => ($reviewStatistic["title"]["neg"] * $TITLE_RATING + $reviewStatistic["content"]["neg"] * $CONTENT_RATING)/$totalReview,
                              "pos" => ($reviewStatistic["title"]["pos"] * $TITLE_RATING + $reviewStatistic["content"]["pos"] * $CONTENT_RATING)/$totalReview,
                             );
  }
}
?>
<!DOCTYPE html>
<html lang="en">
	
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/dashboard.css" />
	<link href="bower_components/bootstrap-star-rating/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js")></script>
	<script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="bower_components/bootstrap-star-rating/js/star-rating.js" type="text/javascript"></script>
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
            <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="category.php?name=cameras">Cameras</a></li>
            <li><a href="category.php?name=laptops">Laptops</a></li>
            <li><a href="category.php?name=mobile phone">Mobile Phone</a></li>
            <li><a href="category.php?name=tablets">Tablets</a></li>
            <li><a href="category.php?name=television">Television</a></li>
          </ul>
          
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">DataSet </h1>
          <h2 class="sub-header">
            <?php echo ucwords($data->ProductInfo->ProductID); ?>
          </h2>
          <div class="table-responsive">
            <table class="table table-striped" style="width: 100%">
              <tbody>
                   
                    <tr>
                        <td>Products ID</td>
                        <td><?php echo $data->ProductInfo->ProductID; ?></td>
                    </tr>
                    
                    <tr>
                      <td>Product Name</td>
                      <td><?php echo $data->ProductInfo->Name; ?><td>
                    </tr>
                    
                    <tr>
                      <td>Product Image</td>
                      <td><?php if(!empty($data->ProductInfo->ImgURL) && $data->ProductInfo->ImgURL != "NULL") { ?>
                        <img src="<?php echo $data->ProductInfo->ImgURL; ?>"></img>
                      <?php }; ?><td>
                    </tr>
                    
                    <tr>
                      <td>Price</td>
                      <td><?php echo $data->ProductInfo->Price; ?><td>
                    </tr>
                    
                    <tr>
                      <td>Review</td>
                      <td>
                        <a target ="_blank" href="/dataset/amazon/<?php echo $category . "/" . $productId . ".json"  ; ?>"><?php echo $totalReview; ?></a>
                      <td>
                    </tr>
                    
                    <tr>
                      
                      <td colspan="2">
                        <!--Sentiment -->
                        <p> Sentiment</p>
                        <div class="progress" title="click to see detail" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" style="cursor:pointer;">
                         
                          <div title = "Positive" class="progress-bar progress-bar-success" style="width: <?php echo 100*($sentimentScore["pos"]);?>%">
                            <span><?php echo 100*$sentimentScore["pos"]; ?>%</span>
                          </div>
                          <div title = "Neutral" class="progress-bar progress-bar-warning" style="width: <?php echo 100*($sentimentScore["neu"]);?>%">
                            <span><?php echo 100*$sentimentScore["neu"];?>%</span>
                          </div>
                          <div title = "Negative" class="progress-bar progress-bar-danger" style="width: <?php echo 100*($sentimentScore["neg"]);?>%">
                            <span><?php echo 100*$sentimentScore["neg"];?>%</span>
                          </div>
                        </div> <!--End Sentiment -->
                        
                        <div class="collapse" id="collapseExample2">
                          <p>Title Sentiment</p>
                          <!--Title Sentiment -->
                          <div class="progress">
                            
                            <div title = "Positive" class="progress-bar progress-bar-success" style="width: <?php echo 100*($reviewStatistic["title"]["pos"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["title"]["pos"]; ?></span>
                            </div>
                            <div title = "Neutral" class="progress-bar progress-bar-warning" style="width: <?php echo 100*($reviewStatistic["title"]["neu"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["title"]["neu"]; ?></span>
                            </div>
                            <div title = "Negative" class="progress-bar progress-bar-danger" style="width: <?php echo 100*($reviewStatistic["title"]["neg"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["title"]["neg"]; ?></span>
                            </div>
                          </div> <!--End Title Sentiment -->
                          <p>Content Sentiment</p>
                          <!--Content Sentiment -->
                          <div class="progress">
                            <div title = "Positive" class="progress-bar progress-bar-success" style="width: <?php echo 100*($reviewStatistic["content"]["pos"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["content"]["pos"]; ?></span>
                            </div>
                            <div title = "Neutral" class="progress-bar progress-bar-warning" style="width: <?php echo 100*($reviewStatistic["content"]["neu"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["content"]["neu"]; ?></span>
                            </div>
                            <div title = "Negative" class="progress-bar progress-bar-danger" style="width: <?php echo 100*($reviewStatistic["content"]["neg"]/$totalReview);?>%">
                              <span><?php echo $reviewStatistic["content"]["neg"]; ?></span>
                            </div>
                          </div> <!--End Content Sentiment -->
                        </div><!--sentiment Toogle -->
                      </td>
                    </tr>
                    
                    
                    <tr>
                      
                      <td colspan ="2"; style="word-wrap: break-word;">
                        <div class="blog-comment">
                          <h4>Detail</h4>
                          <ul class="comments">
                            <?php foreach($reviewScore as $reviewScoreItem) { 
                              $titleClass = $reviewScoreItem["title"]["result"] == "neu" ? "bg-warning" : ($reviewScoreItem["title"]["result"] == "pos" ? "bg-success" : "bg-danger");
                              $contentClass = $reviewScoreItem["content"]["result"] == "neu" ? "bg-warning" : ($reviewScoreItem["content"]["result"] == "pos" ? "bg-success" : "bg-danger");;
                            ?>
                             <div class="post-comments">
                              <p class="meta"><?php echo $reviewScoreItem["date"]; ?> <a href="#"><?php echo $reviewScoreItem["author"]; ?></a> says : 
                              <i class="pull-right"><input id="input-7-xs" class="rating rating-loading" value="<?php echo $reviewScoreItem["point"]; ?>" data-min="0" data-max="5" data-step="0.5" data-size="xs"></i></p>
                              <p class='<?php echo $titleClass;?>'> <?php echo $reviewScoreItem["title"]["value"]; ?></p>
                              <p class='<?php echo $contentClass;?>'> <?php echo $reviewScoreItem["content"]["value"]; ?></p>
                            </div>
                            <?php } ?>
                          </ul>
                          
                          
                        
                        </div>
                       
                      </td>
                    </tr>
                
              </tbody>
            </table>
            
            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Full Detail</a>
                          <div class="collapse" id="collapseExample">
                            <pre>
                            <pre>
                            <?php print_r( $sentimentScore ); ?>
                            </pre>
                            </pre>
                            <pre>
                            <?php print_r($reviewScore); ?>
                            </pre>
                          </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>