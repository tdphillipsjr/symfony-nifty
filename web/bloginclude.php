<style>
body{
margin:0;
padding:0;
}
</style>
<link rel="stylesheet" type="text/css" href="https://www.niftythrifty.com/Public/Style/Css/Fonts.css" />
<?php
/*db connection*/
$thisurl =  $_SERVER['HTTP_HOST'];
$dbname = "nifty_www";
$connection = mysql_connect("localhost", "nifty_db", "thematr1x") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");

$today = date("Y-m-d");

$action = $_GET['action'];

if($action=="collection"){
	//collection
	$sql = "
	SELECT *
	FROM collection
	WHERE collection_date_start <= '$today'
	and collection_active = 'yes'
	order by collection_date_start desc
	limit 1
	";

	$result = mysql_query($sql) or die($sql);

	while($row=mysql_fetch_array($result)){
		$collection_id = $row['collection_id'];
		$collection_visual_main_panel = "https://www.niftythrifty.com/" . $row['collection_visual_main_panel'];

		echo "<a style=\"font-size:14px; color:#666666; text-decoration:none; font-family:helvetica,arial;\" href='https://www.niftythrifty.com/shop/show_collection/$collection_id' target=_parent><img src='$collection_visual_main_panel' width=180 border=0></a>";

	}
}

if($action=="product"){
	//products
	$sql = "
	SELECT p.*
	from product p, collection c
	where p.collection_id = c.collection_id
	and p.product_visual1_large != ''
	and c.collection_date_start LIKE '$today%'
	order by product_id desc
	limit 1
	";

	$result = mysql_query($sql) or die($sql);
	while($row=mysql_fetch_array($result)){
		$product_id = $row['product_id'];
		$product_name = $row['product_name'];
		$product_visual1_large = "https://www.niftythrifty.com/" . $row['product_visual1_large'];

		if(!strstr($product_visual1_large, "http")){

		}
		echo "
		<a style=\"font-size:14px; color:#666666; text-decoration:none; font-family:helvetica,arial;\" href='https://www.niftythrifty.com/shop/show_item/$product_id' target=_parent>
		<img src='$product_visual1_large' width=180 border=0></a>";
	}
}
?>
