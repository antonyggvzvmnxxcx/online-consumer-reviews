<?php
$dictionariesPath= __DIR__ . '/lib/PHPInsight/dictionaries/';
$dataPath= __DIR__ . '/lib/PHPInsight/data/';
$dictionariesClass = array("ign", "neg", "neu", "pos", "prefix");
$dictionariesFiles = array();
if(file_exists($dictionariesPath)) {
	$dictionariesFiles = scandir($dictionariesPath);
}

foreach($dictionariesFiles as $dictionariesFile) {
	if($dictionariesFile == "." || $dictionariesFile == "..") continue;
	$dictionariesFilePath = $dictionariesPath  . $dictionariesFile;
	if(file_exists($dictionariesFilePath)) {
		include($dictionariesFilePath);
		
	}
}
echo "<br />";
foreach($dictionariesClass as $dictionariesClassItem) {
	$serializeData = serialize(${$dictionariesClassItem});
	$dataFilePath = $dataPath. "data.". $dictionariesClassItem. ".php";
	
	if(file_exists($dataFilePath)) {
		file_put_contents($dataFilePath, $serializeData);
		echo "Refesh $dictionariesClassItem Data Successfully.";
		echo "<br />";
	}

}

