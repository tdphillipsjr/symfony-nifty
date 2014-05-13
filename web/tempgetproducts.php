<?php

//include('Plugins/Crypt.class.php');
//$crypt = new Crypt();

/*db connection*/
$thisurl =  $_SERVER['HTTP_HOST'];
$dbname = "nifty_www";
$connection = mysql_connect("localhost", "nifty_db", "thematr1x") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");

//build vars
$start= $_REQUEST['start'];
$length= $_REQUEST['length'];
$archive= $_REQUEST['archive'];
//$designer_ids= $_REQUEST['designer_ids'];
$order_by= $_REQUEST['order_by'];
$search_term= $_REQUEST['search_term'];
$lookbook_id= $_REQUEST['lookbook_id'];
$tag_id= $_REQUEST['tag_id'];

//$product_type_ids= $_REQUEST['product_type_ids'];

//collection

$today = date("Y-m-d");

if($search_term=='WarehouseSale'){
	$sql_collection = "
	select collection_id from collection where collection_date_start <= '$today' and collection_active = 'yes'
	";
	$result_collection = mysql_query($sql_collection);

	while($row_collection = mysql_fetch_array($result_collection)){
		$collection_ids[]  = $row_collection['collection_id'];
	}
}
else{
	$collection_ids= $_REQUEST['collection_ids'];

}

if(is_array($collection_ids)){
	$collectionadd .= "and p.collection_id in (";
	foreach($collection_ids as $collection_ids_this){
		$collectionadd .= "'" . $collection_ids_this . "',";
	}
	$collectionadd .= "'1234567890')";

	if(count($collection_ids)==1){
		$sqladd .= "and p.product_availability != 'etsy'";
		$sqladd .= "and p.product_availability != 'ebay'";
	}
	else{
		$sqladd .= "and p.product_availability = 'sale'";
	}
}

//categories
$product_category_ids= $_REQUEST['product_category_ids'];
if(is_array($product_category_ids)){
	$categoryadd .= "and pc.product_category_id in (";
	foreach($product_category_ids as $product_category_ids_this){
		//check for cashmere
		if($product_category_ids_this=='348'){
			$whereadd .= ", xproduct_tag xptcashmere";
			$sqladd .= "
				and p.product_id = xptcashmere.product_id
				and xptcashmere.product_tag_id = '348'
			";
			$categoryadd .= "'14',";
		}
		elseif($product_category_ids_this=='321'){
			$whereadd .= ", xproduct_tag xpttshirts";
			$sqladd .= "
				and p.product_id = xpttshirts.product_id
				and xpttshirts.product_tag_id = '321'
			";
		}
		//else other cat
		else{
			$categoryadd .= "'" . $product_category_ids_this . "',";
		}
	}
	$categoryadd .= "'1234567890')";
}



//tags array
$product_tags= $_REQUEST['product_tags'];
if(is_array($product_tags)){
	$i='0';
	foreach($product_tags as $product_tag_id_this){
		if($product_tag_id_this=='304'){
			$whereadd .= ", xproduct_tag xpt$i";
			$sqladd .= "
				and p.product_id = xpt$i.product_id
				and xpt$i.product_tag_id in ('304','305','306')
			";
		}
		elseif($product_tag_id_this){
			$whereadd .= ", xproduct_tag xpt$i";
			$sqladd .= "
				and p.product_id = xpt$i.product_id
				and xpt$i.product_tag_id = '$product_tag_id_this'
			";
		}
		$i++;

	}
}




//size
$product_category_size_ids= $_REQUEST['product_category_size_ids'];
if(is_array($product_category_size_ids)){
	$sizeadd .= "and p.product_category_size_id in (";
	foreach($product_category_size_ids as $product_category_size_ids_this){
		$sizeadd .= "'" . $product_category_size_ids_this . "',";
	}
	$sizeadd .= "'1234567890')";
}

if (empty($start)){
	$start = 0;
}

if (empty($length)){
	$length = 12;
}

if (is_array($order_by)){
	$thisorderby = $order_by[0];
	if($thisorderby == "product_price_lo"){
		$orderbyadd = "order by p.product_price ASC";
	}
	elseif($thisorderby == "product_price_hi"){
		$orderbyadd = "order by p.product_price DESC";
	}
	else{
		$orderbyadd = "order by $thisorderby DESC";
	}
}
elseif($search_term=='WarehouseSale'){
	$orderbyadd = "order by product_id ASC";
}
else{
	$orderbyadd = "order by product_id DESC";
}


//if($archive == 'true'){
	if($search_term=='under35'){
		$sqladd .= "and p.product_price < '35'";
		//$orderbyadd = "order by p.product_price desc";
		$orderbyadd = "order by p.product_id desc";
	}
	elseif($search_term=='WarehouseSale'){
		$sqladd .= "and p.product_old_price > '0'";
		//$orderbyadd = "order by p.product_price desc";
		$orderbyadd = "order by p.product_id asc";
	}
	elseif($search_term!=''){
		$search_term_sql = "%" . $search_term . "%";
		$search_term = trim($search_term);
		$search_term_array = explode("%20", $search_term);
		if(count($search_term_array)==1){
			$sqladd .= "and p.product_name LIKE '%$search_term_sql%'";
		}
		else{
			foreach($search_term_array as $search_term_mult){
				$search_term_mult_sql = "%" . $search_term_mult . "%";
				$sqladd .= "and p.product_name LIKE '%$search_term_mult%'";
			}
		}
	}
	if($tag_id){
		$whereadd = ", xproduct_tag xpt";
		$sqladd .= "
			and p.product_id = xpt.product_id
			and xpt.product_tag_id = '$tag_id'
		";
	}

//}


$sql = "
SELECT p.*, pcs.product_category_size_value, pc.product_category_name
from product p, product_category pc, product_category_size pcs $whereadd
where p.product_category_size_id = pcs.product_category_size_id
and pc.product_category_id = pcs.product_category_id
$sizeadd
$collectionadd
$categoryadd
$sqladd
$orderbyadd
";
//echo $sql;

$result = mysql_query($sql) or die($sql);

$productcount = mysql_num_rows($result);

$end = $start + $length;
$i=0;
while($row=mysql_fetch_array($result)){
if($i>=$start&&$i<$end){
	$product_id = $row['product_id'];
	$product_name = $row['product_name'];
			if(!$row['product_visual1_large']){
				$product_visual1 = "https://www.niftythrifty.com/" . $row['product_visual1'];
				$product_visual1_html = "
					<img class=\"product_img\" src=\"$product_visual1\" />
				";
			}
			else{
				$product_visual1 = "https://www.niftythrifty.com/" . $row['product_visual1_large'];
				$product_visual1_html = "
					<img class=\"product_img2\" src=\"$product_visual1\"/>
				";
			}
	$product_old_price = $row['product_old_price'];
	$product_availability = $row['product_availability'];
	$product_price = $row['product_price'];

	//$product_hash = $crypt->code($product_id);
	//$product_hash = str_replace('-plus-','+', $product_hash);
	$product_category_size_value = $row['product_category_size_value'];

	$designer_id = $row['designer_id'];
	$designer_name = "";
	if($designer_id){
		$sql1 = "select designer_name from designer where designer_id = '$designer_id' limit 1";
		$result1 = mysql_query($sql1) or die($sql1);
		while($row1=mysql_fetch_array($result1)){
			$designer_name = $row1['designer_name'];
		}
	}

	$output .= "
			<div class=\"product\">
				<div id=\"addtocarthoverdiv\">
					<span class=\"addtolovehover\" name=\"love$product_id\" id=\"love$product_id\"></span>
					";

	if($product_availability=="sale"){
	$output .= "
					<span class=\"addtocarthover\" name=\"$product_id\" id=\"$product_id\">add to cart</span>
					";
	}
	$output .= "
				</div>
				<div class=\"img\">
					<div class=\"loveheartongallery\"></div>

					<a href=\"/shop/show_item/$product_id\" title=\"$product_name\">
						$product_visual1_html
					</a>
				</div>

				<div class=\"infos\">
					<div class=\"product_separator\"></div>
					<div class=\"product_name\">$product_name</div>

					<div class=\"product_designer\">$designer_name</div>
				";
	if($product_old_price!=0){
	$output .= "
						<div class=\"price old\">$ $product_old_price</div>
				";
	}
	$output .= "

					<div class=\"price\">$ $product_price</div>
					<div class=\"size\">
						<div class=\"size_left\"></div>
						<div class=\"size_value\">$product_category_size_value</div>
						<div class=\"size_right\"></div>
					</div>
					<div class=\"clear\"></div>
				</div>

				<div class=\"product_separator\"></div>

				";
	if($product_availability!="sale"){
	$output .= "
					<div class=\"status $product_availability\"></div>
				";
	}
	$output .= "
			</div>


	";
}
$i++;
}

?>

<script type="text/javascript">
	productSearchCount = <?php echo $productcount; ?>;
</script>

<?php
echo $output;
?>
