<?php
/*db connection*/
$thisurl =  $_SERVER['HTTP_HOST'];
//echo $thisurl;
if(($thisurl=="www.niftythriftydev.com")||($thisurl=="niftythriftydev.com")){
$dbname = "db145717_niftythrifty";
$connection = mysql_connect("internal-db.s145717.gridserver.com", "db145717", "8uhb8uhb") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");
}

// diehardgamefan
elseif($thisurl=="niftythrifty.com"){
$dbname = "nifty_www";
$connection = mysql_connect("127.0.0.1", "root", "auyeSh5i") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");
}
else{
$dbname = "nifty_www";
$connection = mysql_connect("127.0.0.1", "root", "auyeSh5i") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");
}

$action = $_GET['action'];

if($action=="generate"){

	$currentpath = $_SERVER['DOCUMENT_ROOT'];
	$buildfilename = $currentpath . "/products.xml";

	$output .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<feed xmlns=\"http://www.w3.org/2005/Atom\" xmlns:g=\"http://base.google.com/ns/1.0\">
    <title>Nifty Thrifty Products</title>
	";

	$sql = "
	SELECT p.*, pcs.product_category_size_value, pc.product_category_name
	from product p, product_category pc, product_category_size pcs
	where p.product_category_size_id = pcs.product_category_size_id
	and pc.product_category_id = pcs.product_category_id
	and p.product_availability = 'sale'
	";
	//echo $sql;

	$result = mysql_query($sql) or die($sql);
	$productcount = mysql_num_rows($result);

	$end = $start + $length;
	$i=0;
	while($row=mysql_fetch_array($result)){
		$product_id = $row['product_id'];
		$product_name = $row['product_name'];
		$product_description = $row['product_description'];
		$product_category_name = $row['product_category_name'];
		$product_visual1 = $row['product_visual1'];
		$product_price = $row['product_price'];
		$product_category_size_value = $row['product_category_size_value'];

		$output .= "
		<entry xmlns=\"http://www.w3.org/2005/Atom\"
    xmlns:app='http://www.w3.org/2007/app'
    xmlns:gd=\"http://schemas.google.com/g/2005\"
    xmlns:sc=\"http://schemas.google.com/structuredcontent/2009\"
    xmlns:scp=\"http://schemas.google.com/structuredcontent/2009/products\" >
    <app:control>
        <sc:required_destination dest=\"ProductSearch\"/>
    </app:control>
    ";
		$output .= "<g:id>$product_id</g:id>";
		$output .= "<title>$product_name</title>";
		$output .= "<description>$product_name</description>";
		$output .= "<g:google_product_category>Apparel &amp; Accessories</g:google_product_category>";
		$output .= "<g:product_type>$product_category_name</g:product_type>";
		$output .= "<link>https://niftythrifty.com/Collections/Product/Id/$product_id.sls</link>";
		$output .= "<g:image_link>$product_visual1</g:image_link>";
		$output .= "<g:condition>used</g:condition>";
		$output .= "<g:availability>in stock</g:availability>";
		$output .= "<g:price>$product_price</g:price>";
		$output .= "<title>$product_name</title>";
		$output .= "<title>$product_name</title>";
		$output .= "</entry>
		";
	}


	$output .= "</feed>";

	// fopen file thing etc
	$f = fopen ($buildfilename, 'w');
	fputs ($f, $output);
	fclose ($f);
	echo "
	<a href=\"products.xml\" target=\"_blank\">View Feed</a>
	";
}

else{
	echo "
	<a href=\"productfeed.php?action=generate\">Generate New Product Feed XML</a>
	";
}


