<?php

/*db connection*/
$thisurl =  $_SERVER['HTTP_HOST'];
if(($thisurl=="www.niftythriftydev.com")||($thisurl=="niftythriftydev.com")){
$dbname = "db145717_niftythrifty";
$connection = mysql_connect("internal-db.s145717.gridserver.com", "db145717", "8uhb8uhb") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");
$top = true;
}
else{
$dbname = "nifty_www";
$connection = mysql_connect("localhost", "nifty_db", "thematr1x") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");
}

//get vars
$action = $_GET['action'];
$type = $_GET['type'];

	$collections_check_array = array('42', '62', '61', '65','68');


$groupby = $_GET['groupby'];

if($groupby&&$action=="product_sales"){
	$action = "product_sales_grouped";
}

$limit = '100';

if($_GET['offset']){
	$offset = $_GET['offset'];
}
else{
	$offset = '0';
}

$today = date("Y-m-d");


$datef = $_GET['datef'];
$datetype = $_GET['datetype'];
$year1 = $_GET['year1'];
$year2 = $_GET['year2'];
$month1 = $_GET['month1'];
$month2 = $_GET['month2'];
$day1 = $_GET['day1'];
$day2 = $_GET['day2'];

$hidedayf = false;
$showlayout=true;

if($year1&&$month1&&$day1){
	if(strlen($month1)==1){
		$month1 = "0" . $month1;
	}
	if(strlen($day1)==1){
		$day1 = "0" . $day1;
	}

	$date1 = $year1 . "-" . $month1 . "-" . $day1;
}

if($year2&&$month2&&$day2){
	if(strlen($month2)==1){
		$month2 = "0" . $month2;
	}
	if(strlen($day2)==1){
		$day2 = "0" . $day2;
	}

	$date2 = $year2 . "-" . $month2 . "-" . $day2;
}


if($datef=="month"){
	$dateformat1 = "%Y %m";
}
elseif($datef=="day"){
	$dateformat1 = "%Y %m %d";
}
elseif($datef=="year"){
	$dateformat1 = "%Y";
}
else{
	$datef = "day";
	$dateformat1 = "%Y %m %d";
}

if(!$datetype){
	$datetype = "totalinrange";
}


if(!$type){
	$type = "home";
}


if($type=="home"){
	$links = "
	<li>Order Listing</li>
	</ul>
	<a href=\"tempadmin.php?type=invoice&action=ship_report\">Shipment Listing</a><br>
	<ul>
	<li>Itemized Order Listing for Shipping</li>
	</ul>

	<a href=\"tempadmin.php?type=invoice&action=invoices_sums\">Invoice Stats</a><br>
	<ul>
	<li>Number of Transactions</li>
	<li>Number of items Sold</li>
	<li>Number of Items per Transaction</li>
	<li>Average Net Sale Value</li>
	<li>Gross Sale Value</li>
	<li>Coupon Discounts</li>
	<li>Credits used</li>
	<li>Net Sale Value</li>
	<li>Shipping</li>
	<li>Net Sale Value with Shipping</li>
	<li>Sales Tax</li>
	<li>Total Transaction Value</li>
	<li>% of transactions that used coupon discounts</li>
	<li>% of transactions that used credits</li>
	</ul>

	<a href=\"tempadmin.php?type=invoice&action=unique_buyers\">Buyers Report</a><br>
	<ul>
	<li>Number of Unique buyers</li>
	</ul>

	<a href=\"tempadmin.php?type=invoice&action=product_sales\">Product Report</a><br>
	<ul>
	<li>Breakdown of products sold, collection, dates, age</li>
	</ul>

	<a href=\"tempadmin.php?type=invoice&action=repeat_customers\">Customer Frequency</a><br>
	<ul>
	<li>Repeat customers, value</li>
	</ul>




	";

	$sqltype = "";
}

if($type=="order"){
	$datefield = "order_date_creation";

}

if($type=="user"){
	$datefield = "user_date_creation";
}

if($type=="invoice"){
	$datefield = "invoice_date";
}

if($type=="invoice"){
	if($_GET['availability_sort']=="sale"){
		$datefield = "collection_date_start";
	}
	else{
		$datefield = "invoice_date";
	}
}

if($action=="coupon_report"){
	$datefield = "i.invoice_date";
}

if($action=="coupon_report"){
	$datefield = "i.invoice_date";
}

if($action=="product_prices"){
	$datefield = "collection_date_start";
}


if($action=="inventory_aging"){
	$datefield = "collection_date_start";
}


if($action=="category_report"){
	$datefield = "collection_date_start";
}



if($date1&&$date2){
	$sqlrange = "AND $datefield > '$date1' AND $datefield < '$date2'";
	$headertext2 = "<h4>Date Range from $date1 to $date2 filtered by $datef</h4>";
}







if($action=="sailthru_test"){
	// Insert in Sailthru mailing list
	include("Plugins/sailthru/sailthru/Sailthru_Client_Exception.php");
	include("Plugins/sailthru/sailthru/Sailthru_Client.php");
	include("Plugins/sailthru/sailthru/Sailthru_Util.php");

	$sailthru_api_key = 'c69f1fc7f28505d974005bcd6ef83187';
	$sailthru_api_secret = '6ff74ad7cea4efe63f29720d37b0b9bf';
	$sailthru_api_list = 'Nifty Plug';

	$sailthru = new Sailthru_Client($sailthru_api_key, $sailthru_api_secret);

	$email_params = array();
	$email_params['email_first_name_from'] = "Babbadoo";
	$email_params['email_first_name_to'] = "Slobbadoo";
	$email_params['email_last_name_to'] = "Johnson";
	$email_params['email_credits'] = "2746";

	//$email_first_name_from = $invite_from->__get('user_first_name');
	//$email_first_name_to = $invite_to->__get('user_first_name');
	//$email_last_name_to = $invite_to->__get('user_last_name');
	//$email_credits = $this->getUserCredits($invite_from->__get('user_id'));


	$sendemail = $sailthru->send('transa_invitejoins', 'jonathan@niftythrifty.com', $email_params, '');


}




if($action=="unique_buyers"){
	//sql
	$sql = "
	SELECT count(DISTINCT(invoice_user_email)) as total, DATE_FORMAT($datefield,'$dateformat1') as date
	FROM $dbname.$type
	WHERE 1
	$sqlrange
	GROUP BY date
	ORDER by date ASC
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Unique Buyers By Date Range";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Date</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Cumulative</th>";
	$output .= "</tr>";

	$starttotal = 0;
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$date = $row['date'];
		$cumtotal = $total + $cumtotal;

		$output .= "<tr>";
		$output .= "<td>$date</td>";
		$output .= "<td>$total</td>";
		$output .= "<td>$cumtotal</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

if($action=="invoices_sums"){

	//add in first time buyers
	$sql_firsttime = "
	select *
	from invoice i
	where invoice_status = 'paid'
	order by invoice_id asc
	";

	$result_firsttime = mysql_query($sql_firsttime) or die($sql_firsttime);

	$alluserids = array();
	$firsttimebuyersarray = array();

	while($row_firsttime=mysql_fetch_array($result_firsttime)){
		$user_id = $row_firsttime['user_id'];

		if(!in_array($user_id, $alluserids)){
			$alluserids[] = $user_id;
			$invoice_day = str_replace("-", "", substr($row_firsttime['invoice_date'], 0, 10));
			$invoice_month = str_replace("-", "", substr($row_firsttime['invoice_date'], 0, 7));
			$invoice_year = substr($row_firsttime['invoice_date'], 0, 4);

			$firsttimebuyersarray[$invoice_day]++;
			$firsttimebuyersarray[$invoice_month]++;
			$firsttimebuyersarray[$invoice_year]++;
		}


	}

	//add in registrations
	$sql_reg = "
	select *
	from user u
	";

	$result_reg = mysql_query($sql_reg) or die($sql_reg);

	$alluserids = array();
	$regarray = array();

	while($row_reg=mysql_fetch_array($result_reg)){
		$invoice_day = str_replace("-", "", substr($row_reg['user_date_creation'], 0, 10));
		$invoice_month = str_replace("-", "", substr($row_reg['user_date_creation'], 0, 7));
		$invoice_year = substr($row_reg['user_date_creation'], 0, 4);

		$regarray[$invoice_day]++;
		$regarray[$invoice_month]++;
		$regarray[$invoice_year]++;
	}




	//sql
	$sql = "
	SELECT sum(invoice_amount) as invoice_amount, sum(invoice_amount_coupon) as invoice_amount_coupon, sum(invoice_amount_vat) as invoice_amount_vat, sum(invoice_amount_shipping) as invoice_amount_shipping, sum(invoice_amount_credits) as invoice_amount_credits, sum(invoice_amount_total) as invoice_amount_total, count(*) as transactions, sum((LENGTH(invoice_products)-LENGTH(REPLACE(invoice_products, '|', '')))/2) as items, DATE_FORMAT($datefield,'$dateformat1') as date
	FROM $dbname.$type
	WHERE 1
	$sqlrange
	AND invoice_status = 'paid'
	GROUP BY date
	ORDER by date DESC
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Transactions, Items Sold by Date Range";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Date</th>";
	$output .= "<th>Registrations</th>";
	$output .= "<th>Transactions</th>";
	$output .= "<th>1st Time Buyers</th>";

	$output .= "<th>Gross Sale Value</th>";
	$output .= "<th>Coupon Discounts</th>";
	$output .= "<th>Coupon Discounts %</th>";
	$output .= "<th>Tax</th>";
	$output .= "<th>Tax %</th>";
	$output .= "<th>Shipping</th>";
	$output .= "<th>Credits used</th>";
	$output .= "<th>Credits used %</th>";
	$output .= "<th>Net Sale Value (NSV)</th>";
	$output .= "<th>Average NSV</th>";
	$output .= "<th>NSV w/ Shipping</th>";
	$output .= "<th>Total Transaction Value</th>";

	$output .= "<th>Items</th>";
	$output .= "<th>Items/Trans</th>";
	$output .= "</tr>";

	while($row=mysql_fetch_array($result)){
		$invoice_amount = round($row['invoice_amount'],2);
		$invoice_amount_coupon = round($row['invoice_amount_coupon'],2);
		$invoice_amount_vat =  round($row['invoice_amount_vat'],2);
		$invoice_amount_shipping =  round($row['invoice_amount_shipping'],2);
		$invoice_amount_credits =  round($row['invoice_amount_credits'],2);
		$invoice_amount =  round($row['invoice_amount'],2);
		$invoice_amount_total =  round($row['invoice_amount_total'],2);
		$items =  $row['items'];
		$date = $row['date'];
		$transactions = $row['transactions'];


		$itemspertrans = round($items/$totalpurchases,2);

		if($invoice_amount_total>0){
			$coupons_percent = round($invoice_amount_coupon/$invoice_amount_total*100,2);
			$tax_percent = round($invoice_amount_vat/$invoice_amount_total*100,2);
			$credits_percent = round($invoice_amount_credits/$invoice_amount_total*100,2);
		}
		else{
			$coupons_percent = 0;
			$tax_percent = 0;
			$credits_percent = 0;
		}

		$net_sale_value = $invoice_amount-$invoice_amount_coupon-$invoice_amount_credits;

		$avg_net_sale_value = round($net_sale_value/$transactions,2);
		$net_sale_value_shipping = round($net_sale_value+$invoice_amount_shipping,2);

		$datenoslashes = str_replace(" ", "", $date);
		$totalfirstbuyers = $firsttimebuyersarray[$datenoslashes];
		$totalregs = $regarray[$datenoslashes];

		$output .= "<tr>";
		$output .= "<td>$date</td>";
		$output .= "<td>$totalregs</td>";
		$output .= "<td>$transactions</td>";
		$output .= "<td>$totalfirstbuyers</td>";

		$output .= "<td>$invoice_amount</td>";
		$output .= "<td>$invoice_amount_coupon</td>";
		$output .= "<td>$coupons_percent %</td>";
		$output .= "<td>$invoice_amount_vat</td>";
		$output .= "<td>$tax_percent %</td>";
		$output .= "<td>$invoice_amount_shipping</td>";
		$output .= "<td>$invoice_amount_credits</td>";
		$output .= "<td>$credits_percent %</td>";
		$output .= "<td>$net_sale_value</td>";
		$output .= "<td>$avg_net_sale_value</td>";
		$output .= "<td>$net_sale_value_shipping</td>";
		$output .= "<td>$invoice_amount_total</td>";

		$output .= "<td>$items</td>";
		$output .= "<td>$itemspertrans</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}







if($action=="single_invoice"){
	$showlayout=false;

	$invoice_id = $_GET['invoice_id'];

	$sql1 = "
	SELECT *, DATE_FORMAT(invoice_date, '%m-%d-%Y') as invoice_date_formatted
	from invoice
	where invoice_id = '$invoice_id'
	";

	$result1 = mysql_query($sql1) or die($sql1);

	//output
	while($row=mysql_fetch_array($result1)){
		$output_ordernumber =  $row['invoice_id'];
		$output_invoice_date =  $row['invoice_date_formatted'];
		$output_shipping_method =  $row['invoice_shipping_method'];
		$output_invoice_amount_total =  "$" . $row['invoice_amount_total'];

		$output_shipping_address_first_name =  $row['invoice_shipping_address_first_name'];
		$output_shipping_address_last_name =  $row['invoice_shipping_address_last_name'];
		$output_shipping_address_street =  $row['invoice_shipping_address_street'];
		$output_shipping_address_city =  $row['invoice_shipping_address_city'];
		$output_shipping_address_state =  $row['invoice_shipping_address_state'];
		$output_shipping_address_zipcode =  $row['invoice_shipping_address_zipcode'];
		$output_shipping_address_country =  $row['invoice_shipping_address_country'];

		$output_billing_address_first_name =  $row['invoice_billing_address_first_name'];
		$output_billing_address_last_name =  $row['invoice_billing_address_last_name'];
		$output_billing_address_street =  $row['invoice_billing_address_street'];
		$output_billing_address_city =  $row['invoice_billing_address_city'];
		$output_billing_address_state =  $row['invoice_billing_address_state'];
		$output_billing_address_zipcode =  $row['invoice_billing_address_zipcode'];
		$output_billing_address_country =  $row['invoice_billing_address_country'];

		$coupon_id =  $row['coupon_id'];
		$invoice_products = $row['invoice_products'];
		//$items =  (int)$row['items'];

	}

	//output top
	$output .= "
	<img src=/images/images/logo.png>
	<br><br>
	To:<br>
	$invoice_shipping_address_first_name $invoice_shipping_address_last_name
	<br>
	$invoice_shipping_address_street
	<br>
	$invoice_shipping_address_city, $invoice_shipping_address_state $invoice_shipping_address_zipcode
	";

	$output .= "<h3>items in your order</h3>";
	$output .= "<table>";

	$sql_basket_items = "
	select * from basket_item
	where basket_id = (select basket_id from invoice where invoice_id = $invoice_id)
	and basket_item_status = 'payment'
	";

	$result_basket_items = mysql_query($sql_basket_items) or die($sql_basket_items);

	while($row_basket_items=mysql_fetch_array($result_basket_items)){
		$product_id =  $row_basket_items['product_id'];
		$sql_product = "
		select p.*, c.collection_name, pcs.product_category_size_value
		from product p, collection c, product_category_size pcs
		where product_id = '$product_id'
		and p.collection_id = c.collection_id
		and p.product_category_size_id = pcs.product_category_size_id
		";

		$result_product = mysql_query($sql_product) or die($sql_product);

		while($row_product = mysql_fetch_array($result_product)){
			$product_id = $row_product['product_id'];
			$product_code = $row_product['product_code'];
			$product_name = $row_product['product_name'];
			$product_description = $row_product['product_description'];
		if($row['product_visual1_large']==""){
			$product_visual1 = "/" . $row['product_visual1'];
		}
		else{
			$product_visual1 = "/" . $row['product_visual1_large'];
		}
			$product_hashtag = $row_product['product_hashtag'];
			$product_heavy = $row_product['product_heavy'];
			$product_price = "$" . $row_product['product_price'];
			$collection_id = $row_product['collection_id'];
			$collection_name = $row_product['collection_name'];
			$product_category_size_value = $row_product['product_category_size_value'];
		}

		$output_items .= "
		<div class=\"item_row\">
		<div class=\"item_cell_sku\">
			$product_id ($product_code)
		</div>
		";
		$output_items .= "
		<div class=\"item_cell_name\">
			$product_name
		</div>
		";
		$output_items .= "
		<div class=\"item_cell_description\" style=\"overflow:hidden;\">
			$product_description
		</div>
		";
		$output_items .= "
		<div class=\"item_cell_hashtag\">
			$product_price
		</div>
		";
		$output_items .= "
		<div class=\"item_cell_size\">
			$product_category_size_value
		</div>
		</div>
		";

	}
	//total
	$output_items .= "
	<div class=\"item_row\">
		<div class=\"item_cell_sku\"></div>
		<div class=\"item_cell_name\"></div>
		<div class=\"item_cell_description\" style=\"overflow:hidden;\"></div>
		<div class=\"item_cell_hashtag\"></div>
		<div class=\"item_cell_size\" style=\"font-weight:bold;\">Total: $output_invoice_amount_total</div>
	</div>
	";


}


if($action=="ship_report"){
	$hidedayf = true;

	$viewasreport = false;

	if($_GET['viewasreport']){
		$viewasreport = true;
	}
	else{
		$limitsql = "LIMIT 100 OFFSET $offset";
	}

	$output .= "<a href=\"tempadmin.php?type=invoice&action=ship_report&viewasreport=true\">View As Report</a><br><br>";

	if($_GET['invoice_id']){
		$invoice_id_tochange = $_GET['invoice_id'];

		$sql1 = "select invoice_shipping_status
		from invoice
		where invoice_id = '$invoice_id_tochange'
		limit 1
		";

		$result1 = mysql_query($sql1) or die($sql1);

		while($row1 = mysql_fetch_array($result1)){
			$invoice_shipping_status_tochange = $row1['invoice_shipping_status'];
		}

		if($invoice_shipping_status_tochange=="processing"){
			$invoice_shipping_status_tochangeto = "shipped";
		}
		elseif($invoice_shipping_status_tochange=="shipped"){
			$invoice_shipping_status_tochangeto = "processing";
		}

		$sql1 = "update invoice
		set invoice_shipping_status = '$invoice_shipping_status_tochangeto'
		where invoice_id = '$invoice_id_tochange'
		limit 1
		";

		$result1 = mysql_query($sql1) or die($sql1);

	}

	if($_GET['filterview']=="showonlyprocessing"){
		$sqladd_filter = "AND invoice_shipping_status = 'processing'";
	}
	elseif($_GET['filterview']=="showonlyshipped"){
		$sqladd_filter = "AND invoice_shipping_status = 'shipped'";
	}

	$search_first_name = $_GET['search_first_name'];
	$search_last_name = $_GET['search_last_name'];
	$search_email = $_GET['search_email'];

	if($search_first_name){
		$sqladd_filter = "AND invoice_user_first_name = '$search_first_name'";
	}
	if($search_last_name){
		$sqladd_filter = "AND invoice_user_last_name = '$search_last_name'";
	}
	if($search_email){
		$sqladd_filter = "AND invoice_user_email = '$search_email'";
	}

	//sql
	$sql = "
	SELECT *
	FROM $dbname.$type
	WHERE invoice_id = invoice_id
	$sqlrange
	AND invoice_status = 'paid'
	$sqladd_filter
	order by invoice_id DESC $limitsql

	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Shipping Report";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<td colspan=20>";
	$output .= "<form action=tempadmin.php method=get>";
	$output .= "<h4>search invoices</h4>";
	$output .= "First Name: <input type=text name=search_first_name>";
	$output .= "Last Name: <input type=text name=search_last_name>";
	$output .= "Email: <input type=text name=search_email>";
	$output .= "<input type=hidden name=type value=\"invoice\">";
	$output .= "<input type=hidden name=action value=\"ship_report\">";
	$output .= "<input type=submit value=\"Search\">";
	$output .= "</form>";


	$output .= "</td>";
	$output .= "</tr>";


	$output .= "<tr>";
	$output .= "<td colspan=20>";
	$output .= "
	<a href=\"tempadmin.php?action=ship_report&type=invoice\">Show All</a> |
	<a href=\"tempadmin.php?action=ship_report&type=invoice&filterview=showonlyshipped\">Show Only SHIPPED</a> |
	<a href=\"tempadmin.php?action=ship_report&type=invoice&filterview=showonlyprocessing\">Show Only NOT SHIPPED</a> |
	";

	$output .= "</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<th>Transaction#</th>";
	$output .= "<th>Status</th>";
	$output .= "<th>Receipt</th>";
	$output .= "<th>Date</th>";
	$output .= "<th>Gross Sale Amount</th>";
	$output .= "<th>Coupon Discount</th>";
	$output .= "<th>Credits Used</th>";
	$output .= "<th>Net Sale Amount</th>";
	$output .= "<th>Shipping</th>";
	$output .= "<th>Net Sale Amount with Shipping</th>";
	$output .= "<th>Sales Tax</th>";
	$output .= "<th>Total Transaction Value</th>";
	$output .= "<th>Number of Items in Transaction</th>";


	$output .= "<th>User #</th>";
	$output .= "<th>Nth Purchase</th>";
	$output .= "<th>Email</th>";
	$output .= "<th>User First Name</th>";
	$output .= "<th>User Name Last</th>";
	$output .= "<th>Shipping Street Address</th>";
	$output .= "<th>Shipping City</th>";
	$output .= "<th>Shipping State</th>";
	$output .= "<th>Shipping Zipcode</th>";
	$output .= "<th>Shipping Country</th>";
	$output .= "<th>Total Transaction Value</th>";
	$output .= "<th>Number of Items in Transaction</th>";


	if(!$viewasreport){
	$output .= "<th>ITEM SKU</th>";
	$output .= "<th>Item Name</th>";
	$output .= "<th>Item Description</th>";
	$output .= "<th>Item Designer</th>";
	$output .= "<th>Item Image</th>";
	$output .= "<th>Item Collection</th>";
	$output .= "<th>Item Price</th>";
	$output .= "<th>Item Size</th>";
	$output .= "<th>Shipping Method</th>";
	$output .= "<th>Bulky Item</th>";
	$output .= "<th>Total Shipping</th>";
	}

	$output .= "</tr>";

	$addclass = "tablerow1";
	while($row=mysql_fetch_array($result)){
		$invoice_id =  $row['invoice_id'];
		$invoice_num =  $row['invoice_num'];
		$invoice_date =  $row['invoice_date'];
		$user_id =  $row['user_id'];

		//check if first time buyer
		$sql_firsttime = "
		select *
		from invoice i
		where invoice_status = 'paid'
		and user_id = $user_id
		";

		$result_firsttime = mysql_query($sql_firsttime) or die($sql_firsttime);

		$totalorders = mysql_num_rows($result_firsttime);

		$sql_basket_items = "
		select * from basket_item
		where basket_id = (select basket_id from invoice where invoice_id = $invoice_id)
		and basket_item_status = 'payment'
		";

		$result_basket_items = mysql_query($sql_basket_items) or die($sql_basket_items);

		$items = mysql_num_rows($result_basket_items);





		$rowcolor = "";
		if($totalorders>1){
			$firsttime = "yes";
		}
		else{
			$firsttime = "no";
			$rowcolor = "#ffff00";
		}
		//end first time check

		$invoice_user_first_name =  $row['invoice_user_first_name'];
		$invoice_user_last_name =  $row['invoice_user_last_name'];
		$invoice_user_email =  $row['invoice_user_email'];

		$invoice_shipping_method =  $row['invoice_shipping_method'];
		$invoice_shipping_address_first_name =  $row['invoice_shipping_address_first_name'];
		$invoice_shipping_address_last_name =  $row['invoice_shipping_address_last_name'];
		$invoice_shipping_address_street =  $row['invoice_shipping_address_street'];
		$invoice_shipping_address_city =  $row['invoice_shipping_address_city'];
		$invoice_shipping_address_state =  $row['invoice_shipping_address_state'];
		$invoice_shipping_address_zipcode =  $row['invoice_shipping_address_zipcode'];
		$invoice_shipping_address_country =  $row['invoice_shipping_address_country'];
		$coupon_id =  $row['coupon_id'];

		$order_id =  $row['order_id'];
		$basket_id =  $row['basket_id'];
		$invoice_status =  $row['invoice_status'];
		$invoice_amount =  $row['invoice_amount'];
		$invoice_amount_coupon =  $row['invoice_amount_coupon'];
		$invoice_amount_vat =  $row['invoice_amount_vat'];
		$invoice_amount_shipping =  $row['invoice_amount_shipping'];
		$invoice_amount_credits =  $row['invoice_amount_credits'];
		$invoice_amount_total =  $row['invoice_amount_total'];
		$invoice_products =  $row['invoice_products'];

		$invoice_shipping_status =  $row['invoice_shipping_status'];

		if($invoice_shipping_status==""){
			$sql_hack_update = "
			update invoice
			set invoice_shipping_status = 'processing'
			where invoice_id = '$invoice_id'
			limit 1
			";

			$result_hack_update = mysql_query($sql_hack_update) or die($sql_hack_update);
			$invoice_shipping_status = "processing";
		}
		if($invoice_shipping_status=="processing"){
			$invoice_shipping_status_text = "<font color=#ff0000>NOT SHIPPED</font>";
		}
		if($invoice_shipping_status=="shipped"){
			$invoice_shipping_status_text = "<font color=#66CD00>SHIPPED</font>";
		}

		$invoice_shipping_tracking_url =  $row['invoice_shipping_tracking_url'];
		$invoice_user_ip_address =  $row['invoice_user_ip_address'];

		$netsaleamount = $invoice_amount-$invoice_amount_coupon-$invoice_amount_credits;

		$netsaleplusshipp = $netsaleamount+$invoice_amount_shipping;

		$output .= "<tr style=\"background:$rowcolor\">";
		$output .= "<td>$invoice_num</td>";
		$output .= "<td><a href=\"tempadmin.php?action=ship_report&type=invoice&invoice_id=$invoice_id\">$invoice_shipping_status_text</a></td>";
		$output .= "<td><a href=\"tempadmin.php?action=single_invoice&type=invoice&invoice_id=$invoice_id\" target=_blank>View</a></td>";
		$output .= "<td>$invoice_date</td>";
		$output .= "<td>$invoice_amount</td>";
		$output .= "<td>$invoice_amount_coupon</td>";
		$output .= "<td>$invoice_amount_credits</td>";
		$output .= "<td>$netsaleamount</td>";
		$output .= "<td>$invoice_amount_shipping</td>";
		$output .= "<td>$netsaleplusshipp</td>";
		$output .= "<td>$invoice_amount_vat</td>";
		$output .= "<td>$invoice_amount_total</td>";
		$output .= "<td>$items</td>";

		$output .= "<td>$user_id</td>";
		$output .= "<td>$totalorders</td>";
		$output .= "<td>$invoice_user_email</td>";
		$output .= "<td>$invoice_user_first_name</td>";
		$output .= "<td>$invoice_user_last_name</td>";


		$output .= "<td>$invoice_shipping_address_street</td>";
		$output .= "<td>$invoice_shipping_address_city</td>";
		$output .= "<td>$invoice_shipping_address_state</td>";
		$output .= "<td>$invoice_shipping_address_zipcode</td>";
		$output .= "<td>$invoice_shipping_address_country</td>";
		$output .= "<td>$netsaleplusshipp</td>";
		$output .= "<td>$items</td>";


		$invoice_products_array = explode("|", $invoice_products);



		if(!$viewasreport){
		$i=0;
		//for($i=0;$i<$items; $i++){
		while($row_basket_items=mysql_fetch_array($result_basket_items)){
			$product_id =  $row_basket_items['product_id'];

			if($i>0){
			$output .= "<tr >";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";

			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";

			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";
			$output .= "<td></td>";

			}

			$sql_product = "
			select p.*, pcs.product_category_size_value
			from product p, product_category_size pcs
			where product_id = '$product_id'
			and p.product_category_size_id = pcs.product_category_size_id
			";
				$i++;

			$result_product = mysql_query($sql_product) or die($sql_product);

			while($row_product = mysql_fetch_array($result_product)){
				$product_id = $row_product['product_id'];
				$product_name = $row_product['product_name'];
				$product_description = $row_product['product_description'];
			if($row_product['product_visual1_large']==""){
				$product_visual1 = "/" . $row_product['product_visual1'];
			}
			else{
				$product_visual1 = "/" . $row_product['product_visual1_large'];
			}
				$designer_id = $row_product['designer_id'];
				$product_price = $row_product['product_price'];
				$product_heavy = $row_product['product_heavy'];
				$collection_id = $row_product['collection_id'];
				$product_category_size_value = $row_product['product_category_size_value'];
			}

			$output .= "<td>$product_id</td>";
			$output .= "<td>$product_name</td>";
			$output .= "<td>$product_description</td>";

			$designer_name = "NONE LISTED";
			if($designer_id){
				$sql_designer = "
				select designer_name
				from designer
				where designer_id = '$designer_id'
				";

				$result_designer = mysql_query($sql_designer) or die($sql_designer);

				while($row_designer = mysql_fetch_array($result_designer)){
					$designer_name = $row_designer['designer_name'];
				}
			}


			$output .= "<td>$designer_name</td>";
			$output .= "<td><img src=$product_visual1 height=200></td>";

			$sql_collection = "
			select collection_name
			from collection
			where collection_id = '$collection_id'
			";

			$result_collection = mysql_query($sql_collection) or die($sql_collection);

			while($row_collection = mysql_fetch_array($result_collection)){
				$collection_name = $row_collection['collection_name'];
			}

				$collection_name_add = "";

			//check for other collections
			if(in_array($collection_id, $collections_check_array)){
				$product_id1 = (int)$product_id-1;
				$product_id2 = (int)$product_id+1;

				$sql1 = "
				select collection_name
				from collection c, product p
				where product_id = '$product_id1'
				and c.collection_id = p.collection_id
				";

				$result1 = mysql_query($sql1) or die($sql1);

				while($row1=mysql_fetch_array($result1)){
					$collection_name1 = $row1['collection_name'];
				}
				$sql2 = "
				select collection_name
				from collection c, product p
				where product_id = '$product_id2'
				and c.collection_id = p.collection_id
				";

				$result2 = mysql_query($sql2) or die($sql2);

				while($row2=mysql_fetch_array($result2)){
					$collection_name2 = $row2['collection_name'];
				}

				if($collection_name1==$collection_name2){
					if($collection_name1==$collection_name){
						$collection_name_add = "<br><br><i>Cannot approximate original collection :(</i>";
					}
					else{
						$collection_name_add = "<br><br><i>Originally in $collection_name1</i>";
					}
				}
				elseif($collection_name1==$collection_name){
					$collection_name_add = "<br><br><i>Originally in $collection_name2</i>";
				}
				elseif($collection_name2==$collection_name){
					$collection_name_add = "<br><br><i>Originally in $collection_name1</i>";
				}
				else{
					$collection_name_add = "<br><br><i>Originally in $collection_name1 or $collection_name2</i>";
				}
			}


			$output .= "<td>$collection_name$collection_name_add</td>";
			$output .= "<td>$product_price</td>";
			$output .= "<td>$product_category_size_value</td>";
			$output .= "<td>$invoice_shipping_method</td>";
			$output .= "<td>$product_heavy</td>";
			$output .= "<td>$invoice_amount_shipping</td>";

			$output .= "</tr>";

		}
	}
	else{
			$output .= "</tr>";

	}
	if($addclass=="tablerow2"){
		$addclass = "tablerow1";
	}
	elseif($addclass=="tablerow1"){
		$addclass = "tablerow2";
	}
}

	if(!$viewasreport){
		$showbottomlinks=true;
	}
}


if($action=="product_sales"){
	$hidedayf = true;

	if($_GET['colorfilter']=="showgreenonly"){
		$onlysalesql = "AND p.product_availability = 'sale'";
		$availability_sort = "sale";
	}
	if($_GET['product_age_search']){
		$product_age_search = $_GET['product_age_search'];
		$product_age_search_sql = '-'.$product_age_search.' day';
		$format = 'Y-m-j G:i:s';
		$date = date ( $format );
		$datecompare = date ( $format, strtotime ( $product_age_search_sql . $date ) );


		$agesql = "AND p.collection_date_start > '2011-01-01' AND p.collection_date_start < '$datecompare'";
		$addvars .= "&product_age_search=".$_GET['product_age_search'];
	}


	if($_GET['product_name_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_name_search = $_GET['product_name_search'];
		$sqlrange .= "and p.product_name LIKE '%$product_name_search%'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql

		";
		$addvars .= "&product_name_search=".$_GET['product_name_search'];
	}
	elseif($_GET['product_description_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_description_search = $_GET['product_description_search'];
		$sqlrange .= "and p.product_description LIKE '%$product_description_search%'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_description_search=".$_GET['product_description_search'];
	}
	elseif($_GET['product_code_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_code_search = $_GET['product_code_search'];
		$sqlrange .= "and p.product_code LIKE '%$product_code_search%'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_code_search=".$_GET['product_code_search'];
	}
	elseif($_GET['product_id_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_id_search = $_GET['product_id_search'];
		$sqlrange .= "and p.product_id = '$product_id_search'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_id_search=".$_GET['product_id_search'];
	}
	elseif($_GET['product_fabric_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_fabric_search = $_GET['product_fabric_search'];
		$sqlrange .= "and p.product_fabric = '$product_fabric_search'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_fabric_search=".$_GET['product_fabric_search'];
	}
	elseif($_GET['product_collection_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_collection_search = $_GET['product_collection_search'];
		$sqlrange .= "and p.collection_id = '$product_collection_search'";
		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c
		where p.collection_id = c.collection_id
		$sqlrange
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_collection_search=".$_GET['product_collection_search'];
	}
	elseif($_GET['product_tag_search']){
 		$limitsql = "order by p.product_id desc LIMIT 100 OFFSET $offset";
		$product_tag_search = $_GET['product_tag_search'];

		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, p.product_availability,
		TIMESTAMPDIFF(DAY,p.collection_date_start,'$today') AS days_product_available

		from product p, collection c, xproduct_tag xpt
		where p.collection_id = c.collection_id
		and p.product_id = xpt.product_id
		and xpt.product_tag_id = '$product_tag_search'
		$agesql
		$onlysalesql
		$limitsql
		";

		$addvars .= "&product_tag_search=".$_GET['product_tag_search'];
	}
	else{
	//sql
		$showcatdd = true;
		$showsolddd = true;


		if($_GET['category_id']){
			$product_category_id = $_GET['category_id'];

			$sqladd = "
			and pcs.product_category_id = pc.product_category_id
			and pc.product_category_id = '$product_category_id'
			";

			$whereadd = ", product_category pc";

			$addvars .= "&category_id=".$_GET['category_id'];
		$limitsql = "LIMIT 100 OFFSET $offset";
		}
		else{
		$limitsql = "LIMIT 100 OFFSET $offset";

		}

		if($_GET['availability_sort']){
			$availability_sort = $_GET['availability_sort'];

			$addvars .= "&availability_sort=".$_GET['availability_sort'];
		}
		else{
			$availability_sort = "sold";
		}
		if($_GET['colorfilter']=="showgreenonly"){
			$availability_sort = "sale";
		}

		if($availability_sort=="sold"){
			$sqladd .= "
			and i.invoice_products LIKE concat('%',p.product_id,'|',p.product_name,'%')
			and i.invoice_status = 'paid'
			and p.product_availability = 'sold'
			";

			$whereadd .= ", invoice i";

			$orderbysql = "ORDER BY i.invoice_date DESC";

			$seladd = ", i.invoice_id, i.invoice_date";

			$datesql = "i.invoice_date";

		}
		else{
			$sqladd .= "
			and p.product_availability = 'sale'
			";
			$datesql = "'" . date("Y-m-d") . "'";

			$orderbysql = "ORDER BY p.product_id DESC";
		}


		$sql = "
		select p.*, c.collection_id, c.collection_name, c.collection_date_start, c.collection_date_end, c.is_shop,
		TIMESTAMPDIFF(DAY,p.collection_date_start,$datesql) AS days_product_available,
		pt.product_type_name, pcs.product_category_size_name, p.product_availability $seladd
		from product p, collection c, product_type pt, product_category_size pcs $whereadd
		where 1=1
		and p.collection_id = c.collection_id
		and pt.product_type_id = p.product_type_id
		and pcs.product_category_size_id = p.product_category_size_id
		$sqladd
		$agesql
		$sqlrange
		$orderbysql
		$limitsql
		";

		$viewpages = true;
	}

	$result = mysql_query($sql) or die($sql);

	$headertext = "Product Sales Report";

	$output.="<table>";

		$output .= "<tr>";
		$output .= "<td colspan=20>";
		$output .= "<form action=tempadmin.php method=get>";
		$output .= "<h4>Search products</h4>";
		$output .= "item name: <input type=text name=product_name_search><br><br>";
		$output .= "item description: <input type=text name=product_description_search><br><br>";
		$output .= "item #: <input type=text name=product_code_search><br><br>";
		$output .= "inventory #: <input type=text name=product_id_search><br><br>";

		//$output .= "fabric: <select name=product_fabric_search>";
		//$output .= "<option value=''>-- fabric --</option>";

		$sql_fabric = "
		select count(product_id) as total, product_fabric
		from product
		group by product_fabric
		order by total desc
		";

		//$result_fabric = mysql_query($sql_fabric) or die($sql_fabric);

		//while($row_fabric = mysql_fetch_array($result_fabric)){
		//	$product_fabric = $row_fabric['product_fabric'];
		//	$output .= "<option value='$product_fabric'>$product_fabric</option>";
		//}

		//$output .= "</select><br><br>";

		$output .= "collection: <select name=product_collection_search>";
		$output .= "<option value=''>-- collection --</option>";

		$sql_collection = "
		select c.collection_id, c.collection_name
		from collection c
		order by c.collection_name
		";

		$result_collection = mysql_query($sql_collection) or die($sql_collection);

		while($row_collection = mysql_fetch_array($result_collection)){
			$collection_id = $row_collection['collection_id'];
			$collection_name = $row_collection['collection_name'];
			$output .= "<option value='$collection_id'>$collection_name</option>";
		}

		$output .= "</select><br><br>";
		$output .= "tag: <select name=product_tag_search>";
		$output .= "<option value=''>-- tag --</option>";

		$sql_tag = "
		select pt.product_tag_id, pt.product_tag_name
		from product_tag pt
		order by pt.product_tag_name asc
		";

		$result_tag = mysql_query($sql_tag) or die($sql_tag);

		while($row_tag = mysql_fetch_array($result_tag)){
			$product_tag_id = $row_tag['product_tag_id'];
			$product_tag_name = $row_tag['product_tag_name'];
			$output .= "<option value='$product_tag_id'>$product_tag_name</option>";
		}

		$output .= "</select><br><br>";
		$output .= "age: <select name=product_age_search>";
		$output .= "<option value=''>-- older than days --</option>";
		$output .= "<option value='30'>30</option>";
		$output .= "<option value='60'>60</option>";
		$output .= "<option value='90'>90</option>";
		$output .= "<option value='120'>120</option>";
		$output .= "<option value='150'>150</option>";
		$output .= "<option value='200'>200</option>";
		$output .= "<option value='250'>250</option>";
		$output .= "<option value='300'>300</option>";
		$output .= "<option value='350'>350</option>";
		$output .= "<option value='400'>400</option>";

		$output .= "</select><br><br>";
		$output .= "<input type=checkbox name=colorfilter value=showgreenonly> Show available";
		$output .= "<input type=checkbox name=colorfilter value=showgreenyellow> Show available+in collections";
		$output .= "<input type=hidden name=action value=product_sales>";
		$output .= "<input type=submit value=\"Search\">";
		$output .= "</form>";
		$output .= "</td>";
		$output .= "</tr>";
		$output .= "<tr>";
		$output .= "<td colspan=20><span style=\"color:#ff0000;\">red</span> - sold | <span style=\"color:#ffcc00;\">amber</span> - unsold but in active/upcoming collection | <span style=\"color:#00ff00;\">green</span> - unsold in archive";
		$output .= "</td>";
		$output .= "</tr>";


	$output .= "<tr>";
	$output .= "<th>SKU</th>";
	$output .= "<th>Item #</th>";
	$output .= "<th>Name</th>";
	$output .= "<th>Image</th>";
	$output .= "<th>Price</th>";
	$output .= "<th>Date Sold</th>";
	$output .= "<th>Collection Name</th>";
	$output .= "<th>Collection Start Date</th>";
	$output .= "<th>Collection End Date</th>";
	$output .= "<th>Days On Site</th>";
	$output .= "<th>Type</th>";
	$output .= "<th>Size</th>";
	$output .= "<th>Tags</th>";
	$output .= "<th>Availability</th>";
	$output .= "<th>Etsy Description</th>";
	$output .= "</tr>";

	while($row=mysql_fetch_array($result)){
		$product_id = $row['product_id'];
		$product_code = $row['product_code'];
		$product_name = $row['product_name'];
		$product_description = $row['product_description'];
		$product_detailed_condition_value = $row['product_detailed_condition_value'];
		$designer_id = $row['designer_id'];
		if($designer_id){
			$sqldesigner = "
			select designer_name from designer where designer_id = '$designer_id'
			";

			$resultdesigner = mysql_query($sqldesigner);

			while($rowdesigner=mysql_fetch_array($resultdesigner)){
				$designer_name = $rowdesigner['designer_name'];
			}
		}
		$product_tagsize = $row['product_tagsize'];
		$product_measurements = $row['product_measurements'];
		$product_fabric = $row['product_fabric'];


			if($row['product_visual1_large']==""){
				$product_visual1 = "/" . $row['product_visual1'];
			}
			else{
				$product_visual1 = "/" . $row['product_visual1_large'];
			}
		$product_price = $row['product_price'];
		$invoice_date = $row['invoice_date'];
		$collection_id = $row['collection_id'];
		$collection_name = $row['collection_name'];
		$collection_date_start = $row['collection_date_start'];
		$collection_date_end = $row['collection_date_end'];
		$is_shop = $row['is_shop'];
		$todayfull = date("Y-m-d h:i:s");

		$days_product_available = $row['days_product_available'];
		$product_type_name = $row['product_type_name'];
		$product_category_size_name = $row['product_category_size_name'];
		$product_availability = $row['product_availability'];
		$highlightrow = "ff0000";
		if($product_availability=="sale"){
			if($is_shop=="yes"){
				$highlightrow = "00ff00";
			}
			elseif($todayfull<$collection_date_end){
				$highlightrow = "ffcc00";
			}
			else{
				$highlightrow = "00ff00";
			}

		}
		elseif($product_availability=="sold"){
			$highlightrow = "ff0000";
		}

		$sql_tagsexisting = "
		select * from xproduct_tag
		where product_id = '$product_id'
		";

		$result_tagsexisting = mysql_query($sql_tagsexisting) or die($sql_tagsexisting);

		$tagsexisting = array();
		while($row_tagsexisting=mysql_fetch_array($result_tagsexisting)){
			$tagsexisting[] = $row_tagsexisting['product_tag_id'];
		}

		$tagform = "<form action=\"tempadmin.php?action=tags_submit\" method=POST  target=\"_blank\">";
		$tagform .= outputtagcheckbox($tagsexisting);

		$tagform .= "<input type=hidden name=product_id_tag value=$product_id>";
		$tagform .= "<input type=submit value=\"submit tags\">";
		$tagform .= "</form>";

		$product_etsydescription = "
$product_id <br>
$product_code <br>
name - $product_name <br>
description - $product_description <br>
tag size - $product_tagsize <br>
nt size - $product_category_size_name <br>
measurements - $product_measurements <br>
condition - $product_detailed_condition_value <br>
designer - $designer_name <br>
fabric - $product_fabric <br>
price - $product_price
		";

$showthisrow = true;
if($_GET['colorfilter']=="showgreenonly"){
	if($highlightrow=="00ff00"){
		$showthisrow = true;
	}
	else{
		$showthisrow = false;
	}
}
elseif($_GET['colorfilter']=="showgreenyellow"){
	if($highlightrow=="00ff00"){
		$showthisrow = true;
	}
	elseif($highlightrow=="ffcc00"){
		$showthisrow = true;
	}
	else{
		$showthisrow = false;
	}
}

if($showthisrow){
		$output .= "<tr style=\"background:#$highlightrow;\">";
		$output .= "<td>$product_id</td>";
		$output .= "<td>$product_code</td>";
		$output .= "<td>$product_name</td>";
		$output .= "<td><img src=$product_visual1 height=200></td>";
		$output .= "<td>$product_price</td>";
		$output .= "<td>$invoice_date</td>";
		$output .= "<td>$collection_name</td>";
		$output .= "<td>$collection_date_start</td>";
		$output .= "<td>$collection_date_end</td>";
		$output .= "<td>$days_product_available</td>";
		$output .= "<td>$product_type_name</td>";
		$output .= "<td>$product_category_size_name</td>";
		$output .= "<td style=\"background:#ffffff;\">$tagform</td>";
		$output .= "<td><a href=\"tempadmin.php?action=product_archives&type=invoice&collection_id=$collection_id&product_id_mark=$product_id\">$product_availability</a></td>";
		$output .= "<td>$product_etsydescription</td>";
		$output .= "</tr>";
}
	}

	if($viewpages){
	$showbottomlinks=true;
	}


	$output.="</table>";
}

if($action=="product_archives"){
	$hidedayf = true;
	$today = date("Y-m-d");

	if($_GET['collection_id']){

		if($_GET['product_id_mark']){
			$product_id_mark = $_GET['product_id_mark'];

			$sql1 = "select product_availability
			from product
			where product_id = '$product_id_mark'
			limit 1
			";

			$result1 = mysql_query($sql1) or die($sql1);

			while($row1 = mysql_fetch_array($result1)){
				$product_availability_tochange = $row1['product_availability'];
			}

			if($product_availability_tochange=="sale"){
				$product_availability_tochangeto = "sold";
			}
			elseif($product_availability_tochange=="sold"){
				$product_availability_tochangeto = "sale";
			}

			$sql1 = "update product
			set product_availability = '$product_availability_tochangeto'
			where product_id = '$product_id_mark'
			limit 1
			";

			$result1 = mysql_query($sql1) or die($sql1);

		}


		if($_GET['availability_sort']){
			$availability_sort = $_GET['availability_sort'];
		}
		else{
			$availability_sort = "sale";
		}

		$collection_id = $_GET['collection_id'];
		$sqlrange .= "and p.collection_id = '$collection_id'";

		//sql
		$sql = "
		select p.*, c.collection_name, c.collection_date_start, c.collection_date_end,
		TIMESTAMPDIFF(DAY,c.collection_date_start,'$today') AS days_product_available,
		pcs.product_category_size_name, p.product_availability

		from product p, collection c, product_category_size pcs
		where  p.product_availability = '$availability_sort'
		and p.collection_id = c.collection_id
		and pcs.product_category_size_id = p.product_category_size_id
		$sqlrange
		ORDER BY p.product_id DESC
		";

		//and c.collection_date_end < '$today'


		$result = mysql_query($sql) or die($sql);

		$headertext = "Product Sales Report";

		$output.="<table>";

		$output .= "<tr>";
		$output .= "<td><a href=tempadmin.php?type=invoice&action=product_archives&collection_id=$collection_id&availability_sort=sold>show sold</a></td>";
		$output .= "<td><a href=tempadmin.php?type=invoice&action=product_archives&collection_id=$collection_id&availability_sort=sale>show sale</a></td>";
		$output .= "</tr>";



		$output .= "<tr>";
		$output .= "<th>SKU</th>";
		$output .= "<th>Item #</th>";
		$output .= "<th>Name</th>";
		$output .= "<th>Image</th>";
		$output .= "<th>Price</th>";
		$output .= "<th>Date Sold</th>";
		$output .= "<th>Collection Name</th>";
		$output .= "<th>Collection Start Date</th>";
		$output .= "<th>Collection End Date</th>";
		$output .= "<th>Days On Site</th>";
		$output .= "<th>Type</th>";
		$output .= "<th>Size</th>";
		$output .= "<th>Availability</th>";
		$output .= "<th>Etsy Description</th>";

		$output .= "</tr>";


		while($row=mysql_fetch_array($result)){
			$product_id = $row['product_id'];
			$product_code = $row['product_code'];
			$product_name = $row['product_name'];
			$product_description = $row['product_description'];
			$product_detailed_condition_value = $row['product_detailed_condition_value'];
			$designer_id = $row['designer_id'];
			if($designer_id){
				$sqldesigner = "
				select designer_name from designer where designer_id = '$designer_id'
				";

				$resultdesigner = mysql_query($sqldesigner);

				while($rowdesigner=mysql_fetch_array($resultdesigner)){
					$designer_name = $rowdesigner['designer_name'];
				}
			}
			$product_tagsize = $row['product_tagsize'];
			$product_measurements = $row['product_measurements'];
			$product_fabric = $row['product_fabric'];
			if($row['product_visual1_large']==""){
				$product_visual1 = "/" . $row['product_visual1'];
			}
			else{
				$product_visual1 = "/" . $row['product_visual1_large'];
			}

			$product_price = $row['product_price'];
			$invoice_date = $row['invoice_date'];
			$collection_id = $row['collection_id'];
			$collection_name = $row['collection_name'];
			$collection_date_start = $row['collection_date_start'];
			$collection_date_end = $row['collection_date_end'];
			$is_shop = $row['is_shop'];
			$todayfull = date("Y-m-d h:i:s");
			$days_product_available = $row['days_product_available'];
			$product_type_name = $row['product_type_name'];
			$product_category_size_name = $row['product_category_size_name'];
			$product_availability = $row['product_availability'];
			$highlightrow = "";
			if($product_availability=="sale"){
				if($is_shop=="yes"){
					$highlightrow = " style=\"background:#00ff00;\"";
				}
				elseif($todayfull<$collection_date_end){
					$highlightrow = " style=\"background:#00ff00;\"";
				}
				else{
					$highlightrow = " style=\"background:#ffcc00;\"";
				}

			}
			elseif($product_availability=="sold"){
				$highlightrow = " style=\"background:#ff0000;\"";
			}


			$product_etsydescription = "
$product_id <br>
$product_code <br>
name - $product_name <br>
description - $product_description <br>
tag size - $product_tagsize <br>
nt size - $product_category_size_name <br>
measurements - $product_measurements <br>
condition - $product_detailed_condition_value <br>
designer - $designer_name <br>
fabric - $product_fabric <br>
price - $product_price
			";


//**** repeated from ship report *****//
			//check for other collections
			if(in_array($collection_id, $collections_check_array)){
				$product_id1 = (int)$product_id-1;
				$product_id2 = (int)$product_id+1;

				$sql1 = "
				select collection_name
				from collection c, product p
				where product_id = '$product_id1'
				and c.collection_id = p.collection_id
				";

				$result1 = mysql_query($sql1) or die($sql1);

				while($row1=mysql_fetch_array($result1)){
					$collection_name1 = $row1['collection_name'];
				}
				$sql2 = "
				select collection_name
				from collection c, product p
				where product_id = '$product_id2'
				and c.collection_id = p.collection_id
				";

				$result2 = mysql_query($sql2) or die($sql2);

				while($row2=mysql_fetch_array($result2)){
					$collection_name2 = $row2['collection_name'];
				}

				if($collection_name1==$collection_name2){
					if($collection_name1==$collection_name){
						$collection_name_add = "<br><br><i>Cannot approximate original collection :(</i>";
					}
					else{
						$collection_name_add = "<br><br><i>Originally in $collection_name1</i>";
					}
				}
				elseif($collection_name1==$collection_name){
					$collection_name_add = "<br><br><i>Originally in $collection_name2</i>";
				}
				elseif($collection_name2==$collection_name){
					$collection_name_add = "<br><br><i>Originally in $collection_name1</i>";
				}
				else{
					$collection_name_add = "<br><br><i>Originally in $collection_name1 or $collection_name2</i>";
				}
			}
			else{
				$collection_name_add = "";
			}
//**** repeated from ship report *****//

			$output .= "<tr$highlightrow>";
			$output .= "<td>$product_id</td>";
			$output .= "<td>$product_code</td>";
			$output .= "<td>$product_name</td>";
			$output .= "<td><img src=$product_visual1 height=200></td>";
			$output .= "<td>$product_price</td>";
			$output .= "<td>$invoice_date</td>";
			$output .= "<td>$collection_name$collection_name_add</td>";
			$output .= "<td>$collection_date_start</td>";
			$output .= "<td>$collection_date_end</td>";
			$output .= "<td>$days_product_available</td>";
			$output .= "<td>$product_type_name</td>";
			$output .= "<td>$product_category_size_name</td>";
			$output .= "<td><a href=\"tempadmin.php?action=product_archives&type=invoice&collection_id=$collection_id&product_id_mark=$product_id\">$product_availability</a></td>";
			$output .= "<td>$product_etsydescription</td>";
			$output .= "</tr>";
		}
		$output.="</table>";
	}
	else{

//	$sql = "select * from collection where collection_date_end < '$today' order by collection_date_start DESC";
	$sql = "select * from collection order by collection_date_start DESC";


		$result = mysql_query($sql) or die($sql);

		$headertext = "Product Sales Report";

		$output.="<table>";
		$output .= "<tr>";
		$output .= "<th>ID</th>";
		$output .= "<th>Code</th>";
		$output .= "<th>Collection Name</th>";
		$output .= "<th>Collection Start Date</th>";
		$output .= "<th>Collection End Date</th>";
		$output .= "</tr>";

		while($row=mysql_fetch_array($result)){
			$collection_id = $row['collection_id'];
			$collection_code = $row['collection_code'];
			$collection_name = $row['collection_name'];
			$collection_date_start = $row['collection_date_start'];
			$collection_date_end = $row['collection_date_end'];

			$output .= "<tr>";
			$output .= "<td>$collection_id</td>";
			$output .= "<td>$collection_code</td>";
			$output .= "<td><a href=\"tempadmin.php?type=invoice&action=product_archives&collection_id=$collection_id\">$collection_name</a></td>";
			$output .= "<td>$collection_date_start</td>";
			$output .= "<td>$collection_date_end</td>";
			$output .= "</tr>";
		}
		$output.="</table>";















	}
}



if($action=="product_sales_grouped"){
	$hidedayf = true;
	//sql

	//$groupby="collection";

	if($groupby=="collection"){
		$selectfieldsadd = ", c.collection_name as currentname, c.collection_id as currentid, c.collection_date_start, c.collection_date_end";
		$fromadd = ", collection c";
		$whereadd = "and p.collection_id = c.collection_id";
		$groupbyadd = "p.collection_id";

		$sqlextra = "
		select count(p.product_id) as totalproducts, p.collection_date_start, c.collection_id
		from product p, collection c
		where p.collection_date_start = c.collection_date_start
		group by p.collection_date_start
		";

		$resultextra = mysql_query($sqlextra) or die($sqlextra);

		while($rowextra=mysql_fetch_array($resultextra)){
			$totalproducts = $rowextra['totalproducts'];
			$collection_id = $rowextra['collection_id'];
			$totalproductsadd[$collection_id] = $totalproducts;
		}

	}
	elseif($groupby=="type"){
		$selectfieldsadd = ", pt.product_type_name as currentname, pt.product_type_id as currentid";
		$fromadd = ", product_type pt";
		$whereadd = "and pt.product_type_id = p.product_type_id";
		$groupbyadd = "p.product_type_id";

		$sqlextra = "
		select count(product_id) as totalproducts, product_type_id
		from product p
		group by product_type_id
		";

		$resultextra = mysql_query($sqlextra) or die($sqlextra);

		while($rowextra=mysql_fetch_array($resultextra)){
			$totalproducts = $rowextra['totalproducts'];
			$product_type_id = $rowextra['product_type_id'];
			$totalproductsadd[$product_type_id] = $totalproducts;
		}

	}

	elseif($groupby=="size"){
		$selectfieldsadd = ", pcs.product_category_size_name as currentname, pcs.product_category_size_id as currentid";
		$fromadd = ", product_category_size pcs";
		$whereadd = "and pcs.product_category_size_id = p.product_category_size_id";
		$groupbyadd = "p.product_category_size_id";

		$sqlextra = "
		select count(product_id) as totalproducts, product_category_size_id
		from product p
		group by product_category_size_id
		";

		$resultextra = mysql_query($sqlextra) or die($sqlextra);

		while($rowextra=mysql_fetch_array($resultextra)){
			$totalproducts = $rowextra['totalproducts'];
			$product_category_size_id = $rowextra['product_category_size_id'];
			$totalproductsadd[$product_category_size_id] = $totalproducts;
		}
	}


	$sql = "
	select count(p.product_id) as productssold, sum(p.product_price) as totalprice, i.invoice_date $selectfieldsadd
	from product p, invoice i $fromadd
	where i.invoice_products LIKE concat('%',p.product_id,'|',p.product_name,'%')
	and p.product_availability = 'sold'
	and i.invoice_status = 'paid'
	$whereadd


	$sqlrange
	GROUP by $groupbyadd
	ORDER BY i.invoice_date DESC
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Product Sales by Grouping Report - Grouped by <b>$groupby</b>";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>$groupby Name</th>";
	if($groupby=="collection"){
	$output .= "<th>Start Date</th>";
	$output .= "<th>End Date</th>";
	}
	$output .= "<th>Sold</th>";
	$output .= "<th>Unsold</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Total Price</th>";
	$output .= "<th>Avg Price</th>";
	$output .= "</tr>";

	while($row=mysql_fetch_array($result)){
		$currentname = $row['currentname'];
		if($groupby=="collection"){
		$collection_date_start = $row['collection_date_start'];
		$collection_date_end = $row['collection_date_end'];
		}
		$currentid = $row['currentid'];
		$productssold = $row['productssold'];
		$productsall = $totalproductsadd[$currentid];
		if($productsall-$productssold>0){
			$productsunsold = $productsall-$productssold;
		}
		else{
			$productsunsold = 0;
		}
		$totalprice = $row['totalprice'];

		$avgprice = round($totalprice/$productssold,2);
		$output .= "<tr>";
		$output .= "<td>$currentname</td>";
		if($groupby=="collection"){
		$output .= "<td>$collection_date_start</th>";
		$output .= "<td>$collection_date_end</th>";
		}
		$output .= "<td>$productssold</td>";
		$output .= "<td>$productsunsold</td>";
		$output .= "<td>$productsall</td>";
		$output .= "<td>$totalprice</td>";
		$output .= "<td>$avgprice</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

















if($action=="repeat_customers"){
	$hidedayf = true;

	$type = "invoice";
	//sql

	if($datetype=="boughtinrange"){
		$sql = "
		select u.user_id, u.user_first_name, u.user_last_name, count(i.invoice_id) as totalpurchases, sum(invoice_amount) as invoice_amount, sum(invoice_amount_coupon) as invoice_amount_coupon, sum(invoice_amount_vat) as invoice_amount_vat, sum(invoice_amount_shipping) as invoice_amount_shipping, sum(invoice_amount_credits) as invoice_amount_credits, sum(invoice_amount_total) as invoice_amount_total, count(*) as transactions, sum((LENGTH(invoice_products)-LENGTH(REPLACE(invoice_products, '|', '')))/2) as items, DATE_FORMAT($datefield,'$dateformat1') as date
		from invoice i, user u
		where u.user_id = i.user_id
		and i.invoice_status = 'paid'
		group by i.user_id
		order by totalpurchases desc
		";
		$result = mysql_query($sql) or die($sql);
		while($row=mysql_fetch_array($result)){
			if($row['totalpurchases']>1){
				$userids .= "'" . $row['user_id'] . "', ";
			}
		}

		$userids = substr($userids,0,-2);
		$sqlrange .= "and i.user_id in ($userids)";

	}

	$today = date("Y-m-d");

	/////////
	// get logins and store in array
	//////////
	$sql1 = "
	select ul.user_id, count(ul.user_login_id) as logins
	from user_logins ul, user u
	where ul.user_id = u.user_id
	group by user_id
	order by logins desc
	";

	$result1 = mysql_query($sql1) or die($sql1);

	$user_id_logins = array();
	while($row1=mysql_fetch_array($result1)){
		$user_id = $row1['user_id'];
		$logins = $row1['logins'];
		$user_id_logins[$user_id] = $logins;
	}
	/////////
	//////////

	$sql = "
	select u.user_id, u.user_first_name, u.user_last_name, count(i.invoice_id) as totalpurchases, TIMESTAMPDIFF(DAY,u.user_date_creation,'$today') AS customerage, TIMESTAMPDIFF(DAY,u.user_date_last_connection,'$today') AS dayssincelogin, sum(invoice_amount) as invoice_amount, sum(invoice_amount_coupon) as invoice_amount_coupon, sum(invoice_amount_vat) as invoice_amount_vat, sum(invoice_amount_shipping) as invoice_amount_shipping, sum(invoice_amount_credits) as invoice_amount_credits, sum(invoice_amount_total) as invoice_amount_total, count(*) as transactions, sum((LENGTH(invoice_products)-LENGTH(REPLACE(invoice_products, '|', '')))/2) as items, DATE_FORMAT($datefield,'$dateformat1') as date
	from invoice i, user u
	where u.user_id = i.user_id
	and i.invoice_status = 'paid'
	$sqlrange
	group by i.user_id
	order by totalpurchases desc
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Repeat Customer Value Report";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>ID</th>";
	$output .= "<th>user_first_name</th>";
	$output .= "<th>user_last_name</th>";
	$output .= "<th># of purchases</th>";
	$output .= "<th># of logins (since jan5)</th>";
	$output .= "<th>Days Since Reg</th>";
	$output .= "<th>Days Since Login</th>";
	$output .= "<th>Gross Sale Value</th>";
	$output .= "<th>Coupon Discounts</th>";
	$output .= "<th>Coupon Discounts %</th>";
	$output .= "<th>Tax</th>";
	$output .= "<th>Tax %</th>";
	$output .= "<th>Shipping</th>";
	$output .= "<th>Credits used</th>";
	$output .= "<th>Credits used %</th>";
	$output .= "<th>Net Sale Value (NSV)</th>";
	$output .= "<th>Average NSV</th>";
	$output .= "<th>NSV w/ Shipping</th>";
	$output .= "<th>Total Transaction Value</th>";

	$output .= "<th>Items</th>";
	$output .= "<th>Items/Trans</th>";
	$output .= "</tr>";

	$count = 0;
	$all_purchases = 0;
	$all_age = 0;
	$all_repeaters = 0;
	$all_purchases_repeaters = 0;
	$all_age_repeaters = 0;

	while($row=mysql_fetch_array($result)){
		$user_id = $row['user_id'];
		$user_first_name = $row['user_first_name'];
		$user_last_name = $row['user_last_name'];
		$totalpurchases = $row['totalpurchases'];

		//
		$totallogins = $user_id_logins[$user_id];

		$customerage = $row['customerage'];
		$dayssincelogin = $row['dayssincelogin'];
		$invoice_amount = round($row['invoice_amount'],2);
		$invoice_amount_coupon = round($row['invoice_amount_coupon'],2);
		$invoice_amount_vat =  round($row['invoice_amount_vat'],2);
		$invoice_amount_shipping =  round($row['invoice_amount_shipping'],2);
		$invoice_amount_credits =  round($row['invoice_amount_credits'],2);
		$invoice_amount =  round($row['invoice_amount'],2);
		$invoice_amount_total =  round($row['invoice_amount_total'],2);
		$items =  $row['items'];
		$itemspertrans = round($items/$totalpurchases,2);

		if($invoice_amount_total>0){
			$coupons_percent = round($invoice_amount_coupon/$invoice_amount_total*100,2);
			$tax_percent = round($invoice_amount_vat/$invoice_amount_total*100,2);
			$credits_percent = round($invoice_amount_credits/$invoice_amount_total*100,2);
		}
		else{
			$coupons_percent = 0;
			$tax_percent = 0;
			$credits_percent = 0;
		}

		$net_sale_value = $invoice_amount-$invoice_amount_coupon-$invoice_amount_credits;

		$avg_net_sale_value = round($net_sale_value/$totalpurchases,2);
		$net_sale_value_shipping = round($net_sale_value+$invoice_amount_shipping,2);

		$output .= "<tr>";
		$output .= "<td>$user_id</td>";
		$output .= "<td>$user_first_name</td>";
		$output .= "<td>$user_last_name</td>";
		$output .= "<td>$totalpurchases</td>";
		$output .= "<td>$totallogins</td>";
		$output .= "<td>$customerage</td>";
		$output .= "<td>$dayssincelogin</td>";
		$output .= "<td>$invoice_amount</td>";
		$output .= "<td>$invoice_amount_coupon</td>";
		$output .= "<td>$coupons_percent %</td>";
		$output .= "<td>$invoice_amount_vat</td>";
		$output .= "<td>$tax_percent %</td>";
		$output .= "<td>$invoice_amount_shipping</td>";
		$output .= "<td>$invoice_amount_credits</td>";
		$output .= "<td>$credits_percent %</td>";
		$output .= "<td>$net_sale_value</td>";
		$output .= "<td>$avg_net_sale_value</td>";
		$output .= "<td>$net_sale_value_shipping</td>";
		$output .= "<td>$invoice_amount_total</td>";

		$output .= "<td>$items</td>";
		$output .= "<td>$itemspertrans</td>";
		$output .= "</tr>";


		//aggregates
		$count++;
		$all_purchases = $all_purchases + $totalpurchases;
		$all_age = $all_age + $customerage;

		if($totalpurchases>1){
			$all_repeaters++;
			$all_purchases_repeaters = $all_purchases_repeaters + $totalpurchases;
			$all_age_repeaters = $all_age_repeaters + $customerage;
		}











	}
	$output.="</table>";


	if(($datetype=="totalinrange")&&!$date1){
		//extra math
		$avgrepeatrate = round($all_purchases/$count,2);
		$avgcustomerage = round($all_age/$count,2);

		$avgagerepeaters = round($all_age_repeaters/$all_repeaters,2);


		//quick total customers
		$sql_user = "select count(user_id) as total from user";
		$result_user = mysql_query($sql_user) or die($sql_user);
		while($row_user = mysql_fetch_array($result_user)){
			$totalusers = $row_user['total'];
		}

		$nonbuyers = $totalusers - $count;
		$nonbuyerpercent = round($nonbuyers/$totalusers,4)*100;

		$topoutput = "
		Total Buyers: <b>$count</b><br>
		Total Purchases: <b>$all_purchases</b><br>
		Average Repeat Rate: <b>$avgrepeatrate</b><br>
		Average Age of Customers: <b>$avgcustomerage Days</b><br>
		<br><br>
		Total Repeat Buyers:  <b>$all_repeaters</b><br>
		Average Age of Repeat Buyers: <b>$avgagerepeaters Days</b><br>
		<br><br>
		Total Users:  <b>$totalusers</b><br>
		Total Non Buyers:  <b>$nonbuyers</b><br>
		% Non Buyers:  <b>$nonbuyerpercent</b><br>



		<hr><br>
		";
	}

}




if($action=="firstsecond_customers"){
	$hidedayf = true;

	$today = date("Y-m-d");


	/////////
	// get logins and store in array
	//////////
	$sql1 = "
	select ul.user_id, count(ul.user_login_id) as logins
	from user_logins ul, user u
	where ul.user_id = u.user_id
	group by user_id
	order by logins desc
	";

	$result1 = mysql_query($sql1) or die($sql1);

	$user_id_logins = array();
	while($row1=mysql_fetch_array($result1)){
		$user_id = $row1['user_id'];
		$logins = $row1['logins'];
		$user_id_logins[$user_id] = $logins;
	}
	/////////
	//////////


	$sql = "
	select u.user_id, u.user_first_name, u.user_last_name, u.user_email, TIMESTAMPDIFF(DAY,u.user_date_creation,'$today') AS customerage, TIMESTAMPDIFF(DAY,u.user_date_last_connection,'$today') AS dayssincelogin, i.invoice_date, i.invoice_amount_total, TIMESTAMPDIFF(DAY,u.user_date_creation,i.invoice_date) AS dayssincereg, (LENGTH(invoice_products)-LENGTH(REPLACE(invoice_products, '|', '')))/2 as items
	from invoice i, user u
	where u.user_id = i.user_id
	and i.invoice_status = 'paid'
	$sqlrange
	order by user_last_name, i.invoice_date
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Repeat Customer Value Report";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>user_first_name</th>";
	$output .= "<th>user_last_name</th>";
	$output .= "<th>user_email</th>";
	$output .= "<th>Days Since Reg</th>";
	$output .= "<th>Days Since Login</th>";
	$output .= "<th>Invoice Date</th>";
	$output .= "<th>Days Reg To 1st Buy</th>";
	$output .= "<th># Of Logins</th>";
	$output .= "<th>Total purchases</th>";
	$output .= "<th>Total items (purchased)</th>";
	$output .= "<th>Total Transaction Value</th>";
	$output .= "</tr>";

	$count = 0;
	$all_purchases = 0;
	$all_age = 0;
	$all_repeaters = 0;
	$all_purchases_repeaters = 0;
	$all_age_repeaters = 0;
	$count=0;
	while($row=mysql_fetch_array($result)){
		$count++;
		$invoice_date = $row['invoice_date'];

		if($user_id==$row['user_id']){
			$i++;
			$user_id = $row['user_id'];
			//$user_first_name = "";
			//$user_last_name = "";
			//$customerage = "";
			//$dayssincelogin = "";
			$dayssincelast = $row['dayssincereg']-$dayssincereg;
			$dayssincereg = $row['dayssincereg'];
			$items = $items + $row['items'];
			$invoice_amount_total = $invoice_amount_total + $row['invoice_amount_total'];
		}
		else{
			$output .= "<td>$i</td>";
			$output .= "<td>$items</td>";
			$output .= "<td>$invoice_amount_total</td>";
			$output .= "</tr>";
			$i=1;
			$user_id = $row['user_id'];
			$user_first_name = $row['user_first_name'];
			$user_last_name = $row['user_last_name'];
			$user_email = $row['user_email'];
			$customerage = $row['customerage'];
			$dayssincelogin = $row['dayssincelogin'];
			$dayssincereg = $row['dayssincereg'];
			$dayssincelast="";
			$items = (int)$row['items'];
			$invoice_amount_total = $row['invoice_amount_total'];

			if($user_id_logins[$user_id]){
				$numberoflogins = $user_id_logins[$user_id];
			}
			else{
				$numberoflogins = 0;
			}

			$output .= "<tr>";
			$output .= "<td>$user_first_name</td>";
			$output .= "<td>$user_last_name</td>";
			$output .= "<td>$user_email</td>";
			$output .= "<td>$customerage</td>";
			$output .= "<td>$dayssincelogin</td>";
			$output .= "<td>$invoice_date</td>";
			$output .= "<td>$dayssincereg</td>";
			$output .= "<td>$numberoflogins</td>";
			$totaldays = $totaldays + $dayssincereg;
		}

	}
	$output.="</table>";

	//extra math
	$avgdaystofirstbuy = round($totaldays/$count,2);

	$topoutput = "
	Total Buyers: <b>$count</b><br>
	Total Days: <b>$totaldays</b><br>
	Avg Days to 1st Buy: <b>$avgdaystofirstbuy</b><br>

	<hr><br>
	";


}

if($action=="love_report"){


	$headertext = "Love Button Uniques, Clicks By Date Range";
	$datefield = "date_loved";

	$type = "user";

	//sql
	$sql = "
	SELECT count(*) as total, DATE_FORMAT($datefield,'$dateformat1') as date
	FROM user_loved_product
	WHERE 1
	AND love_type = 'link'
	$sqlrange
	GROUP BY date
	ORDER by date ASC
	";

	$result = mysql_query($sql) or die($sql);


	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Date</th>";
	$output .= "<th>Uniques</th>";
	$output .= "<th>Total</th>";
	$output .= "</tr>";

	$starttotal = 0;
	$fullarray = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$date = $row['date'];
		$datewildcard = str_replace(" ", "-", $date) . "%";
		//sql
		$sql2 = "
		SELECT count(distinct(user_id)) as uniques
		FROM user_loved_product
		WHERE $datefield LIKE '$datewildcard'
		AND love_type = 'link'
		";

		$result2 = mysql_query($sql2) or die($sql2);

		while($row2=mysql_fetch_array($result2)){
			$uniques = $row2['uniques'];
		}

		$output .= "<tr>";
		$output .= "<td>$date</td>";
		$output .= "<td>$uniques</td>";
		$output .= "<td>$total</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

if(($action=="login_customers")||($action=="register_customers")){


	if($action=="login_customers"){
		$datefield = "user_date_last_connection";
		$headertext = "Last Login Date By Date Range";
	}
	elseif($action=="register_customers"){
		$datefield = "user_date_creation";
		$headertext = "Register Date By Date Range";
	}

	$type = "user";

	//sql
	$sql = "
	SELECT count(user_id) as total, DATE_FORMAT($datefield,'$dateformat1') as date
	FROM $dbname.$type
	WHERE 1
	$sqlrange
	GROUP BY date
	ORDER by date ASC
	";

	$result = mysql_query($sql) or die($sql);


	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Date</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Cumulative</th>";
	$output .= "</tr>";

	$starttotal = 0;
	$fullarray = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$date = $row['date'];
		$cumtotal = $total + $cumtotal;
		$fullarray[] = array($total, $date, $cumtotal);
	}

	$reversed_fullarray = array_reverse($fullarray);

	foreach($reversed_fullarray as $eacharray){

		$total = $eacharray[0];
		$date = $eacharray[1];
		$cumtotal = $eacharray[2];

		$output .= "<tr>";
		$output .= "<td>$date</td>";
		$output .= "<td>$total</td>";
		$output .= "<td>$cumtotal</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

//credits testing
if($action=="testing_credits"){

	$hidef = true;

	if($_GET['email']){
		$email = trim(str_replace("%40", "@", $_GET['email']));
	//sql
	$sql0 = "
	SELECT user_id from user
	WHERE user_email = '$email'
	";

	$result0 = mysql_query($sql0) or die($sql0);
	while($row0=mysql_fetch_array($result0)){
		$user_id = $row0['user_id'];
	}


	//sql
	$sql = "
	SELECT * from user_credits
	WHERE user_id = '$user_id'
	";

	$result = mysql_query($sql) or die($sql);


	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>user_id</th>";
	$output .= "<th>user_credits_value</th>";
	$output .= "<th>user_credits_date</th>";
	$output .= "<th>user_credits_date_end</th>";
	$output .= "</tr>";

	$starttotal = 0;
	$allcredits = array();
	$oldtotalcredits = 0;

	$hasusedcredits = false;
	$positivecredits = array();
	$negativecredits = array();
	while($row=mysql_fetch_array($result)){
		$user_credits_date = $row['user_credits_date'];
		$user_credits_date_end = $row['user_credits_date_end'];
		$user_credits_value = $row['user_credits_value'];
		$user_id = $row['user_id'];

		$today = date("Y-m-d");
		if($user_credits_date_end>$today){
			$oldtotalcredits = $oldtotalcredits + $user_credits_value;
		}

		$thiscreditarray = array("user_credits_value" => $user_credits_value, "user_credits_date" => $user_credits_date, "user_credits_date_end" => $user_credits_date_end, "beenused" => false);

		if($user_credits_value<0){
			$hasusedcredits = true;
			$negativecredits[] = $thiscreditarray;
		}
		else{
			$positivecredits[] = $thiscreditarray;
		}

		$output .= "<tr>";
		$output .= "<td>$user_id</td>";
		$output .= "<td>$user_credits_value</td>";
		$output .= "<td>$user_credits_date</td>";
		$output .= "<td>$user_credits_date_end</td>";
		$output .= "</tr>";
	}

	$positivecount = count($positivecredits);

	if($hasusedcredits){
		foreach($negativecredits as $creditused){

			$creditsusedtocheck = abs((int)$creditused['user_credits_value']);

			for($i=0; $i<$positivecount; $i++){
				if((!$positivecredits[$i]['beenused'])&&($positivecredits[$i]['user_credits_date_end']>$creditused['user_credits_date'])){
					if($creditsusedtocheck==0){
						//echo "break<br>";
						break;
					}
					//echo $positivecredits[$i]['user_credits_value'] . "<br>";
					if($positivecredits[$i]['user_credits_value']<=$creditsusedtocheck){
						//mark this as used
						//echo "mark this as used<br>";
						$positivecredits[$i]['beenused'] = true;
						$creditsusedtocheck = $creditsusedtocheck - $positivecredits[$i]['user_credits_value'];
					}
					else{
						//echo "credits remaining only partially used<br>";
						$positivecredits[$i]['user_credits_value'] = $positivecredits[$i]['user_credits_value'] - $creditsusedtocheck;
						$creditsusedtocheck = 0;
					}
				}
			}
		}
	}


	//echo "<br><br>summary<br><br>";

	$total = 0;
	for($i=0; $i<$positivecount; $i++){
		//echo $positivecredits[$i]['user_credits_value'] . "<br>";

		if($positivecredits[$i]['beenused']){
			//echo "been used<br>";
		}
		else{
			//echo "not been used<br>";
			$today = date("Y-m-d");
			if($positivecredits[$i]['user_credits_date_end']>$today){
				//echo "not expired";
				$totalcreditsavailable = $totalcreditsavailable + $positivecredits[$i]['user_credits_value'];
			}
		}
	}

	//echo "<br><br><b>old credits available = $oldtotalcredits</b>";
	//echo "<br><br><b>total credits available = $totalcreditsavailable</b>";

	$output.="</table>";

	$output ="<b>total credits available for <b>$email</b> = $totalcreditsavailable</b>";

	}

	$output .= "<h3>Get Total Credits by user Email</h3>";
	$output .= "<form action=\"tempadmin.php\" method=\"get\">";
	$output .= "email address: <input name=\"email\" type=\"text\" value=\"\">";
	$output .= "<input type=\"hidden\" name=\"action\" value=\"testing_credits\">";
	$output .= "<input type=\"submit\" value=\"Search Users\">";

	$output .= "</form>";


}

if($action=="coupon_report"){
	//sql
	$sql = "
	select c.*, count(i.coupon_id) as uses
	from coupon c, invoice i
	where i.coupon_id = c.coupon_id
	$sqlrange
	group by i.coupon_id
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Coupons Used By Date Range";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>ID</th>";
	$output .= "<th>Code</th>";
	$output .= "<th>%</th>";
	$output .= "<th>amount</th>";
	$output .= "<th>Times used</th>";
	$output .= "</tr>";

	$starttotal = 0;
	while($row=mysql_fetch_array($result)){
		$coupon_id = $row['coupon_id'];
		$coupon_code = $row['coupon_code'];
		$coupon_percent = $row['coupon_percent'];
		$coupon_amount = $row['coupon_amount'];
		$uses = $row['uses'];

		$output .= "<tr>";
		$output .= "<td>$coupon_id</td>";
		$output .= "<td>$coupon_code</td>";
		$output .= "<td>$coupon_percent</td>";
		$output .= "<td>$coupon_amount</td>";
		$output .= "<td>$uses</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

if($action=="addcredits"){
	$hidedayf = true;


	if($_GET['credit_first_name']||$_GET['credit_last_name']){

		if($_GET['credit_first_name']){
			$user_first_name = $_GET['credit_first_name'];
			$addsql0 .= "and user_first_name = '$user_first_name'";
		}
		if($_GET['credit_last_name']){
			$user_last_name = $_GET['credit_last_name'];
			$addsql0 .= "and user_last_name = '$user_last_name'";

		}

		$sql0 = "
		select user_email
		from user
		where 1 = 1
		$addsql0
		";

		$result0 = mysql_query($sql0) or die($sql0);

		$totalemails = mysql_num_rows($result0);

		if($totalemails>0){
			$output .= "total of $totalemails found for <b>$user_first_name $user_last_name</b><br>";

			while($row0=mysql_fetch_array($result0)){
				$user_email = $row0['user_email'];
				$output .= "$user_email<br>";
			}
		}

	}

	elseif($_GET['search_email']&&$_GET['credit_amount']){

		$search_email = $_GET['search_email'];
		$credit_amount = $_GET['credit_amount'];

		if($_GET['credit_days']){
			$credit_days = $_GET['credit_days'];
		}
		else{
			$credit_days = '90';
		}

		$sql0 = "
		select user_id
		from user
		where user_email = '$search_email'
		";

		$result0 = mysql_query($sql0) or die($sql0);

		while($row0=mysql_fetch_array($result0)){
			$user_id_insert = $row0['user_id'];
		}

		if(!$user_id_insert){
			$output .= "email does not have a user account<br>";
		}
		else{
			$today = date("Y-m-d");
			$enddate = date('Y-m-d', strtotime("+".$credit_days." days"));

			$sql = "
			INSERT INTO user_credits(user_credits_id, user_credits_date, user_credits_date_end, user_credits_value, user_id)
			VALUES (NULL , '$today', '$enddate', '$credit_amount', '$user_id_insert');
			";

			$result = mysql_query($sql) or die($sql);
			$output .= "insert done<br>";
		}
	}

	$output .= "<form action=tempadmin.php method=get>";
	$output .= "<h4>add credits</h4>";
	$output .= "credit amount: <input type=text name=credit_amount>";
	$output .= "Email: <input type=text name=search_email>";
	$output .= "Days: <input type=text name=credit_days value=90>";
	$output .= "<input type=hidden name=type value=\"invoice\">";
	$output .= "<input type=hidden name=action value=\"addcredits\">";
	$output .= "<input type=submit value=\"Add Credits\">";
	$output .= "</form>";


	$output .= "<br><br><br><br><form action=tempadmin.php method=get>";
	$output .= "<h4>find email by user first/last name</h4>";
	$output .= "First: <input type=text name=credit_first_name>";
	$output .= "Last: <input type=text name=credit_last_name>";
	$output .= "<input type=hidden name=type value=\"invoice\">";
	$output .= "<input type=hidden name=action value=\"addcredits\">";
	$output .= "<input type=submit value=\"Search Users\">";
	$output .= "</form>";


}


if($action=="deactivate_users"){
	if($_GET['search_email']){
		$search_email = $_GET['search_email'];

		$sql0 = "
		select user_id
		from user
		where user_email = '$search_email'
		";

		$result0 = mysql_query($sql0) or die($sql0);

		while($row0=mysql_fetch_array($result0)){
			$user_id_insert = $row0['user_id'];
		}

		if(!$user_id_insert){
			$output .= "email does not have a user account<br>";
		}
		else{


			//sql
			$sql = "
			update user
			set user_active = 'false'
			where user_email = '$search_email'
			limit 1
			";

			$result = mysql_query($sql) or die($sql);
			$output .= "$search_email has been made inactive<br>";
		}
	}

	$output .= "<form action=tempadmin.php method=get>";
	$output .= "<h4>deactivate user</h4>";
	$output .= "Email: <input type=text name=search_email>";
	$output .= "<input type=hidden name=type value=\"invoice\">";
	$output .= "<input type=hidden name=action value=\"deactivate_users\">";
	$output .= "<input type=submit value=\"Search\">";
	$output .= "</form>";


}



if($action=="negative_credits"){
	//sql
	$sql = "
	select *, sum(invoice_amount_credits) as totalnegs, DATE_FORMAT($datefield,'$dateformat1') as date
	from invoice i
	where invoice_amount_credits < 0
	$sqlrange
	GROUP BY date
	ORDER by date DESC
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Coupons Used By Date Range";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>date</th>";
	$output .= "<th>totalnegs</th>";
	$output .= "<th>cumulative</th>";
	$output .= "<th>cumulative x2</th>";
	$output .= "</tr>";

	$starttotal = 0;
	while($row=mysql_fetch_array($result)){
		$date = $row['date'];
		$totalnegs = $row['totalnegs'];
		$cumnegs = $cumnegs + $totalnegs;
		$cumnegsx2 = $cumnegs * 2;

		$output .= "<tr>";
		$output .= "<td>$date</td>";
		$output .= "<td>$totalnegs</td>";
		$output .= "<td>$cumnegs</td>";
		$output .= "<td>$cumnegsx2</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

if($action=="credits_email_list"){

	$datetouse = "2013-04-09";

	$sql0 = "
	select
	u.user_email from user u, user_credits uc
	where u.user_date_creation > '$datetouse'
	and u.user_id = uc.user_id
	and uc.user_credits_value < 0
	";

	$result0 = mysql_query($sql0) or die($sql0);

	while($row0=mysql_fetch_array($result0)){
		$user_email = $row0['user_email'];
		$userstoomit[] = $user_email;
	}


	$sql = "
	select
	u.user_email from user u
	where u.user_date_creation > '$datetouse'
	";

	$result = mysql_query($sql) or die($sql);

	$count_used = 0;
	$count_notused = 0;
	while($row=mysql_fetch_array($result)){
		$user_email = $row['user_email'];
		if(!in_array($user_email, $userstoomit)){
			$output.= $user_email . "<br>";
			$count_used++;
		}
		else{
			$count_notused++;
		}
	}
	$output .= $count_used . " used<br>";
	$output .= $count_notused . " not used<br>";

}

if($action=="email_lists_both"){

	$hidedayf=true;

	$sql0 = "
	select u.user_email
	from user u, invoice i
	where u.user_id = i.user_id
	";

	$result0 = mysql_query($sql0) or die($sql0);

	while($row0=mysql_fetch_array($result0)){
		$user_email = $row0['user_email'];
		$userstoomit[] = $user_email;
	}


	$sql = "
	select
	u.user_email from user u
	";

	$result = mysql_query($sql) or die($sql);

	$count_buy = 0;
	$count_nobuy = 0;
	while($row=mysql_fetch_array($result)){
		$user_email = $row['user_email'];
		if(!in_array($user_email, $userstoomit)){
			$nobuy.= $user_email . "<br>";
			$count_nobuy++;
		}
		else{
			$buy.= $user_email . "<br>";
			$count_buy++;
		}
	}
	$output .= "<h2>" . $count_nobuy . " users not bought</h2>";
	$output .= $nobuy;


	$output .= "<h2>" . $count_buy . " users did buy</h2>";
	$output .= $buy;

}



if($action=="getimages"){
	$hidedayf=true;


	$productids = "('1881', '2431', '2810', '3876', '2959', '2978', '3037', '2983', '1850', '2657', '4027', '3032', '4010', '45', '3206', '3199', '2727', '510', '561', '1701', '2696', '3038', '2704', '3912')";

	$sql = "
	select product_id, product_visual1, product_visual2, product_visual3
	from product p
	where product_id in $productids
	";

	$result = mysql_query($sql) or die($sql);

	while($row=mysql_fetch_array($result)){
		$product_id = $row['product_id'];
		$product_visual1 = "/" . $row['product_visual1'];
		$product_visual2 = $row['product_visual2'];
		$product_visual3 = $row['product_visual3'];
		$output .= "$product_id<br><br>";
		$output .= "<img src=$product_visual1><br><br>";
		$output .= "<img src=https://d2tqxpnkaovy9w.cloudfront.net/Public/Files/$product_visual2><br><br>";
		$output .= "<img src=https://d2tqxpnkaovy9w.cloudfront.net/Public/Files/$product_visual3><br><br>";
	}
}

if($action=="credits_email_list"){

	$datetouse = "2013-03-15";

	$hidedayf=true;

	$sql0 = "
	select u.user_email
	from user u, user_credits uc
	where u.user_id = uc.user_id
	and u.user_date_creation > '$datetouse'
	";

	$result0 = mysql_query($sql0) or die($sql0);

	while($row0=mysql_fetch_array($result0)){
		$user_email = $row0['user_email'];
		$userstoomit[] = $user_email;
	}


	$sql = "
	select u.user_email
	from user u, user_credits uc
	where u.user_id = uc.user_id
	and u.user_date_creation > '$datetouse'
	and uc.user_credits_value < 0
	";

	$result = mysql_query($sql) or die($sql);

	$count_buy = 0;
	$count_nobuy = 0;
	while($row=mysql_fetch_array($result)){
		$user_email = $row['user_email'];
		if(!in_array($user_email, $userstoomit)){
			$nobuy.= $user_email . "<br>";
			$count_nobuy++;
		}
		else{
			$buy.= $user_email . "<br>";
			$count_buy++;
		}
	}
	$output .= $buy;

}

if($action=="product_prices"){

	$hidedayf=true;
	$showcatdd = true;


	if($_GET['category_id']){
		$product_category_id = $_GET['category_id'];

		$sqladd = "
		and p.product_category_size_id = pcs.product_category_size_id
		and pcs.product_category_id = pc.product_category_id
		and pc.product_category_id = '$product_category_id'
		";

		$whereadd = ", product_category_size pcs, product_category pc";
	}


	$sql = "
	select count(p.product_id) as total, p.product_price, p.product_availability
	from product p $whereadd
	where 1=1
	$sqladd
	$sqlrange
	group by p.product_availability, p.product_price
	order by p.product_price
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Product Prices";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>product_price</th>";
	$output .= "<th># Sold</th>";
	$output .= "<th># Unsold</th>";
	$output .= "<th># Listed</th>";
	$output .= "</tr>";

	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$product_price = $row['product_price'];
		$product_availability = $row['product_availability'];

		$allprices[$product_price][$product_availability] = $total;
	}
	foreach($allprices as $price => $totals){

		$sold = $totals['sold'];
		if(!$sold){
			$sold = 0;
		}
		$sale = $totals['sale'];
		if(!$sale){
			$sale = 0;
		}
		$all = $sold + $sale;

		$output .= "<tr>";
		$output .= "<td>$price</td>";
		$output .= "<td>$sold</td>";
		$output .= "<td>$sale</td>";
		$output .= "<td>$all</td>";
		$output .= "</tr>";
	}
	$output.="</table>";

}


if($action=="inventory_aging"){

	$hidedayf=true;
	$showcatdd = true;

/*
	$sql = "
	select count(product_id) as total, sum(product_price) as totalcost
	from product
	where product_availability = 'sold'
	$sqlrange
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Inventory Aging Unsold Items";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th># Unsold</th>";
	$output .= "<th>Total Cost</th>";
	$output .= "</tr>";

	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$totalcost = $row['totalcost'];

		$output .= "<tr>";
		$output .= "<td>$total</td>";
		$output .= "<td>$totalcost</td>";
		$output .= "</tr>";
	}
	$output.="</table>";
*/

	$sql = "
	select product_price, TIMESTAMPDIFF(DAY,collection_date_start,'$today') AS days_product_available
	from product
	where product_availability = 'sale'
	$sqlrange
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Inventory Aging Unsold Items";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Age</th>";
	$output .= "<th># Unsold</th>";
	$output .= "<th>Total Cost</th>";
	$output .= "</tr>";

	$total0_30 = 0;
	$total30_60 = 0;
	$total60_90 = 0;
	$total90_120 = 0;
	$total120_150 = 0;
	$total150 = 0;

	$cost0_30 = 0;
	$cost30_60 = 0;
	$cost60_90 = 0;
	$cost90_120 = 0;
	$cost120_150 = 0;
	$cost150 = 0;



	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$product_price = $row['product_price'];
		$days_product_available = $row['days_product_available'];

		if($days_product_available<30){
			$total0_30++;
			$cost0_30 = $cost0_30 + $product_price;
		}
		elseif($days_product_available>=30&&$days_product_available<60){
			$total30_60++;
			$cost30_60 = $cost30_60 + $product_price;

		}
		elseif($days_product_available>=60&&$days_product_available<90){
			$total60_90++;
			$cost60_90 = $cost60_90 + $product_price;

		}
		elseif($days_product_available>=90&&$days_product_available<120){
			$total90_120++;
			$cost90_120 = $cost90_120 + $product_price;

		}
		elseif($days_product_available>=120&&$days_product_available<150){
			$total120_150++;
			$cost120_150 = $cost120_150 + $product_price;

		}
		elseif($days_product_available>=150){
			$total150++;
			$cost150 = $cost150 + $product_price;

		}
			$total++;
			$cost = $cost + $product_price;

	}




	$output .= "<tr>";
	$output .= "<td>0-30</td>";
	$output .= "<td>$total0_30</td>";
	$output .= "<td>$cost0_30</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>30-60</td>";
	$output .= "<td>$total30_60</td>";
	$output .= "<td>$cost30_60</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>60-90</td>";
	$output .= "<td>$total60_90</td>";
	$output .= "<td>$cost60_90</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>90-120</td>";
	$output .= "<td>$total90_120</td>";
	$output .= "<td>$cost90_120</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>120-150</td>";
	$output .= "<td>$total120_150</td>";
	$output .= "<td>$cost120_150</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>150+</td>";
	$output .= "<td>$total150</td>";
	$output .= "<td>$cost150</td>";
	$output .= "</tr>";
	$output .= "<tr>";
	$output .= "<td>all</td>";
	$output .= "<td>$total</td>";
	$output .= "<td>$cost</td>";
	$output .= "</tr>";
	$output.="</table>";



}

if($action=="product_sizes"){

	$hidedayf=true;
	$hidedates=true;
	$showcatdd = true;

	if($_GET['category_id']){
		$product_category_id = $_GET['category_id'];

		$sqladd = "
		and pcs.product_category_id = '$product_category_id'
		";
	}

	$sql = "
	select count(product_id) as total, pcs.product_category_size_name
	from product p, product_category_size pcs
	where p.product_availability = 'sale'
	and p.product_category_size_id = pcs.product_category_size_id
	$sqladd
	group by (p.product_category_size_id)
	order by pcs.product_category_size_name

	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Total Products By Size";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>#</th>";
	$output .= "<th>Size</th>";
	$output .= "</tr>";

	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$product_category_size_name = $row['product_category_size_name'];

		$output .= "<tr>";
		$output .= "<td>$total</td>";
		$output .= "<td>$product_category_size_name</td>";
		$output .= "</tr>";
	}
	$output.="</table>";

}


if($action=="invoice_locations"){

	$hidedayf=true;
	$hidedates=true;
	$showcatdd = true;


	if($_GET['city']){
		$activefield = "invoice_shipping_address_city";
		$activevalue = "City";
		$changevalue = "State";
	}
	else{
		$activefield = "invoice_shipping_address_state";
		$linkadd = "&city=yes";
		$activevalue = "State";
		$changevalue = "City";
	}

	$sql = "
	select count(invoice_id) as total, sum(invoice_amount_total) as dollars, $activefield
	from invoice
	group by $activefield
	order by total desc
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Total Orders by $activevalue";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Value</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Dollars</th>";
	$output .= "<th><a href=\"tempadmin.php?action=invoice_locations$linkadd\">Change to $changevalue</a></th>";
	$output .= "</tr>";

	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$dollars = "$" . round($row['dollars'], 2);
		$value = $row[$activefield];

		$output .= "<tr>";
		$output .= "<td>$total</td>";
		$output .= "<td>$value</td>";
		$output .= "<td>$dollars</td>";
		$output .= "<td></td>";
		$output .= "</tr>";
	}
	$output.="</table>";
}

function outputtagcheckbox($tagsexisting){
	for($i=1;$i<6;$i++){
		$sql_type0 = "
		select product_tagtype_name from product_tagtype
		where product_tagtype_id = '$i';
		";

		$result_type0 = mysql_query($sql_type0) or die($sql_type0);

		while($row_type0=mysql_fetch_array($result_type0)){
			$product_tagtype_name = $row_type0['product_tagtype_name'];
		}

		$sql_type = "
		select * from product_tag
		where product_tagtype_id = '$i';
		";

		$result_type = mysql_query($sql_type) or die($sql_type);

		$headertext = "Tags";

		$output .= "<h3>$i. $product_tagtype_name</h3>";
		while($row_type=mysql_fetch_array($result_type)){
			$product_tag_id = $row_type['product_tag_id'];
			$product_tag_name = $row_type['product_tag_name'];
			$product_tagtype_id = $row_type['product_tagtype_id'];
			$product_tag_slug = $row_type['product_tag_slug'];

			if(in_array($product_tag_id, $tagsexisting)){
				$output .= "<input type=\"checkbox\" name=\"tagids[]\" value=\"$product_tag_id\" checked> $product_tag_name<br>";
			}
			else{
				$output .= "<input type=\"checkbox\" name=\"tagids[]\" value=\"$product_tag_id\"> $product_tag_name<br>";
			}
		}
	}

	return $output;
}


if($action=="tag_report"){
	$hidedayf=true;
	$hidedates=true;
	$showcatdd = true;

	$sql = "
	select count(p.product_id) as total, sum(p.product_price) as pricetotal, pt.product_tag_name, p.product_availability
	from product p, xproduct_tag xpt, product_tag pt
	where p.product_id = xpt.product_id
	and pt.product_tag_id = xpt.product_tag_id
	group by xpt.product_tag_id, p.product_availability
	order by pt.product_tag_name
	";

	$result = mysql_query($sql) or die($sql);

	$headertext = "Total Products by Tag";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Tag</th>";
	$output .= "<th>Sold</th>";
	$output .= "<th>Sold $</th>";
	$output .= "<th>Unsold</th>";
	$output .= "<th>Unsold $</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Total $</th>";
	$output .= "</tr>";

	$allprices = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$pricetotal = $row['pricetotal'];
		$product_tag_name = $row['product_tag_name'];
		$product_availability = $row['product_availability'];
		$tagarray[$product_tag_name][$product_availability]['total'] = $total;
		$tagarray[$product_tag_name][$product_availability]['pricetotal'] = $pricetotal;

	}
	foreach($tagarray as $thistag => $thistagarray){

		$saletotal = $thistagarray['sale']['total'];
		$salepricetotal = $thistagarray['sale']['pricetotal'];

		$soldtotal = $thistagarray['sold']['total'];
		$soldpricetotal = $thistagarray['sold']['pricetotal'];

		$bothtotal = $saletotal + $soldtotal;
		$bothpricetotal = $salepricetotal + $soldpricetotal;

		$output .= "<tr>";
		$output .= "<td>$thistag</td>";
		$output .= "<td>$soldtotal</td>";
		$output .= "<td>$soldpricetotal</td>";
		$output .= "<td>$saletotal</td>";
		$output .= "<td>$salepricetotal</td>";
		$output .= "<td>$bothtotal</td>";
		$output .= "<td>$bothpricetotal</td>";
		$output .= "</tr>";
	}



	$output.="</table>";

}


if($action=="category_report"){
	$hidedayf=true;
	$hidedates=false;
	$showcatdd = true;

	$sql = "
	select count(product_id) as total, sum(p.product_price) as pricetotal, p.product_availability, pcs.product_category_size_name, pcs.product_category_id, pc.product_category_name
	from product p, product_category_size pcs, product_category pc
	where p.product_availability = 'sale'
	and pcs.product_category_id = pc.product_category_id
	and p.product_category_size_id = pcs.product_category_size_id
	$sqlrange
	group by (pcs.product_category_id)
	order by pcs.product_category_size_name
	";

	$result = mysql_query($sql) or die($sql);

	$allcats = array();
	while($row=mysql_fetch_array($result)){
		$total = $row['total'];
		$pricetotal = $row['pricetotal'];
		$product_availability = $row['product_availability'];
		$product_category_size_name = $row['product_category_size_name'];
		$product_category_id = $row['product_category_id'];
		$product_category_name = $row['product_category_name'];
		$allcats[$product_category_id][$product_availability]['total'] = $total;
		$allcats[$product_category_id][$product_availability]['pricetotal'] = $pricetotal;
		$allcats[$product_category_id][$product_availability]['product_category_name'] = $product_category_name;
	}

	$sql2 = "
	select count(product_id) as total, sum(p.product_price) as pricetotal, p.product_availability, pcs.product_category_size_name, pcs.product_category_id, pc.product_category_name
	from product p, product_category_size pcs, product_category pc
	where p.product_availability = 'sold'
	and pcs.product_category_id = pc.product_category_id
	and p.product_category_size_id = pcs.product_category_size_id
	$sqlrange
	group by (pcs.product_category_id)
	order by pcs.product_category_size_name
	";

	$result2 = mysql_query($sql2) or die($sql2);

	while($row2=mysql_fetch_array($result2)){
		$total = $row2['total'];
		$pricetotal = $row2['pricetotal'];
		$product_availability = $row2['product_availability'];
		$product_category_size_name = $row2['product_category_size_name'];
		$product_category_id = $row2['product_category_id'];
		$product_category_name = $row2['product_category_name'];
		$allcats[$product_category_id][$product_availability]['total'] = $total;
		$allcats[$product_category_id][$product_availability]['pricetotal'] = $pricetotal;
		$allcats[$product_category_id][$product_availability]['product_category_name'] = $product_category_name;
	}




	$headertext = "Total Products by Category";

	$output.="<table>";
	$output .= "<tr>";
	$output .= "<th>Category</th>";
	$output .= "<th>Sold</th>";
	$output .= "<th>Sold $</th>";
	$output .= "<th>Unsold</th>";
	$output .= "<th>Unsold $</th>";
	$output .= "<th>Total</th>";
	$output .= "<th>Total $</th>";
	$output .= "</tr>";

	foreach($allcats as $thiscatarray){

		$thiscat = $thiscatarray['sale']['product_category_name'];
		$saletotal = $thiscatarray['sale']['total'];
		$salepricetotal = $thiscatarray['sale']['pricetotal'];

		$soldtotal = $thiscatarray['sold']['total'];
		$soldpricetotal = $thiscatarray['sold']['pricetotal'];

		$bothtotal = $saletotal + $soldtotal;
		$bothpricetotal = $salepricetotal + $soldpricetotal;

		$output .= "<tr>";
		$output .= "<td>$thiscat</td>";
		$output .= "<td>$soldtotal</td>";
		$output .= "<td>$soldpricetotal</td>";
		$output .= "<td>$saletotal</td>";
		$output .= "<td>$salepricetotal</td>";
		$output .= "<td>$bothtotal</td>";
		$output .= "<td>$bothpricetotal</td>";
		$output .= "</tr>";
	}



	$output.="</table>";

}

if($action=="tags_submit"){


	if($_POST['product_id_tag']){
		$product_id = $_POST['product_id_tag'];

		$sql_tag_delete = "
		delete from xproduct_tag
		where product_id = '$product_id'
		";

		$result_tag_delete = mysql_query($sql_tag_delete) or die($sql_tag_delete);
	}

	if($_POST['product_id_tag']){

		$product_id = $_POST['product_id_tag'];

		$tagids = $_POST['tagids'];

		$headertext = "Tags Submit";

		foreach($tagids as $eachtagid){

			$sql_tag_insert = "
			insert ignore into xproduct_tag (product_id, product_tag_id)
			values ('$product_id', '$eachtagid')
			";

			$result_tag_insert = mysql_query($sql_tag_insert) or die($sql_tag_insert);
			$output .= "inserted tag id $eachtagid for product id $product_id<br>";
		}

	}

}

if($offset==0){
	$offsetnext = $offset + $limit;

	//add vars
	if($_GET['category_id']){
		$addvars .= "&category_id=".$_GET['category_id'];
	}

	$nextlink = "<a href=tempadmin.php?type=$type&action=$action&offset=$offsetnext$addvars style=\"font-size:24px; font-weight:bold;\">next</a>";
	$prevlink = "";
}
else{
	$offsetnext = $offset + $limit;
	$offsetprev = $offset - $limit;

	//add vars
	if($_GET['category_id']){
		$addvars .= "&category_id=".$_GET['category_id'];
	}


	$nextlink = "<a href=tempadmin.php?type=$type&action=$action&offset=$offsetnext$addvars style=\"font-size:24px; font-weight:bold;\">next</a>";
	$prevlink = "<a href=tempadmin.php?type=$type&action=$action&offset=$offsetprev$addvars style=\"font-size:24px; font-weight:bold;\">prev</a>";
}


?>

<html>
<head>
<title>Temp Admin</title>
<style>
body{
	margin:0;
	padding:0;
	font-family:arial;
	font-size:14px;
}

a{
	color:#0000ff;
	font-size:14px;
}

a:hover{
	color:#990000;
}

table{
	text-align:left;
	font-size:10px;
}

td{
	border:1px solid #777777;
}

th{
	background:#666666;
	border:1px solid #777777;
	color:#eeeeee;
}

.tablerow1{
	background:#dddddd;
}

.tablerow2{
	background:#dedede;
}


.output{
	width:98%;
	height:auto;
	border:2px solid #999999;
	overflow:scroll;
}


.content{
	width:100%;
	min-height:500px;
	background:#eeeeee;
	margin: 0 auto;
	text-align:center;
	border:2px solid #000000;
	box-shadow: 10px 10px 5px #888888;
}

.admin_top{
	width:100%;
	height:40px;
	padding:10px;
	font-size:36px;
	background:#333333;
	color:#ffffff;
	font-weight:bold;
	margin: 0 auto;
	text-align:center;

}

.admin_nav{
	width:100%;
	height:auto;
	padding-top:5px;
	padding-bottom:5px;
	font-size:14px;
	background:#eeeeee;
	margin: 0 auto;
	text-align:center;

}

.title_bar{
	width:100%;
	height:50px;
	font-size:24px;
	font-weight:bold;
	color:#6A22CC;
	background:#dddddd;
	border:1px #bbbbbb solid;
	padding:10px;
	margin: 0 auto;
	text-align:center;
}

.title_bar h4{
	color:#666666;
	margin:1px;
	font-size:16px;
}


.admin_nav div.cell{
	padding:5px;
	margin:2px;
	font-size:14px;
	color:#6A22CC;
	background:#ffffff;
	float:left;
	width:100px;
	height:20px;
	border:1px solid #000000;
	box-shadow: 4px 4px 4px #888888;
	text-transform:uppercase;
	cursor:pointer;
}

.admin_nav div.cell:hover{
	color:#ffffff;
	background:#6A22CC;
	cursor:pointer;
}

.admin_nav div.active{
	background:#000000;
	color:#ffffff;
}

.admin_nav div.cell a{
	color:#6A22CC;
	font-weight:bold;
	text-decoration:none;
}

.admin_nav div.cell:hover a{
	color:#ffffff;
}

.admin_nav div.active a{
	color:#ffffff;
}


.filter_bar{
	width:100%;
	height:30px;
	padding:5px;
	font-size:14px;
	background:#eeeeee;
	margin: 0 auto;
	text-align:center;
	font-size:11px;
}

.filter_bar select{
	font-size:11px;
}

.filterbutton{
	background:#ffffff;
	border: #6A22CC 1px solid;

}

.admin_body{
	width:100%;
	height:40px;
	padding:20px;
	font-size:14px;
	color:#333333;
	text-align:left;
	height:auto;
}

.clear{
	clear:both;
}


</style>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>



<script>
$(document).ready(function() {




});

function filter(){
	var type = $("#type").val();
	var year = $("#year").val();
	var month = $("#year").month();
	var day = $("#day").val();
	var datef = $("#datef").val();
	var sqltype = $("#sqltype").val();


	$("#filterform").submit();


}

</script>


</head>

<body>
<?php
if($top){
?>
<div style="width:100%;height:50px;background:#ff0000;color:#ffffff; font-size:50px; text-align:center;">
THIS IS DEV
</div>
<?php
}
?>

<?php
if($showlayout){
?>
<div class="content">

	<div class="admin_top">
		Nifty Thrifty Temporary December Admin Tools
	</div>
	<div class="admin_nav">
		<div class="cell<?php if($action==""){echo " active";} ?>"><a href="tempadmin.php">Home</a></div>
		<div class="cell<?php if($action=="ship_report"){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=ship_report">Shipping</a></div>
		<div class="cell<?php if($action=="invoices_sums"){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=invoices_sums">Sales</a></div>
		<div class="cell<?php if($action=="unique_buyers"){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=unique_buyers">Buyer Totals</a></div>
		<div class="cell<?php if(strstr($action, "product_sales")){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=product_sales">Products</a></div>
		<div class="cell<?php if(strstr($action, "repeat_customers")){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=repeat_customers">Individual Buyers</a></div>
		<div class="cell<?php if(strstr($action, "login_customers")){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=login_customers">Logins (Old)</a></div>
		<div class="cell<?php if(strstr($action, "register_customers")){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=register_customers">Regs</a></div>
		<div class="cell<?php if(strstr($action, "firstsecond_customers")){echo " active";} ?>"><a href="tempadmin.php?type=invoice&action=firstsecond_customers">Buyer Engage</a></div>
		<div class="cell<?php if(strstr($action, "product_archives")){echo " active";} ?>"><a href="tempadmin.php?action=product_archives">Archive</a></div>
		<div class="cell<?php if(strstr($action, "coupon_report")){echo " active";} ?>"><a href="tempadmin.php?action=coupon_report">Coupons Used</a></div>
		<div class="cell<?php if(strstr($action, "addcredits")){echo " active";} ?>"><a href="tempadmin.php?action=addcredits">Add Credits</a></div>
		<div class="cell<?php if(strstr($action, "deactivate_users")){echo " active";} ?>"><a href="tempadmin.php?action=deactivate_users">Deactivate</a></div>
		<div class="cell<?php if(strstr($action, "email_lists_both")){echo " active";} ?>"><a href="tempadmin.php?action=email_lists_both">All User List</a></div>
		<div class="cell<?php if(strstr($action, "credits_email_list")){echo " active";} ?>"><a href="tempadmin.php?action=credits_email_list">Credits Emails</a></div>
		<div class="cell<?php if(strstr($action, "product_prices")){echo " active";} ?>"><a href="tempadmin.php?action=product_prices">Prices</a></div>
		<div class="cell<?php if(strstr($action, "testing_credits")){echo " active";} ?>"><a href="tempadmin.php?action=testing_credits">Credits/Email</a></div>
		<div class="cell<?php if(strstr($action, "invoice_locations")){echo " active";} ?>"><a href="tempadmin.php?action=invoice_locations">Orders by Location</a></div>
		<div class="cell<?php if(strstr($action, "product_sizes")){echo " active";} ?>"><a href="tempadmin.php?action=product_sizes">Sizes</a></div>
		<div class="cell<?php if(strstr($action, "inventory_aging")){echo " active";} ?>"><a href="tempadmin.php?action=inventory_aging">Inv Aging</a></div>
		<div class="cell<?php if(strstr($action, "tag_report")){echo " active";} ?>"><a href="tempadmin.php?action=tag_report">Tag Counts</a></div>
		<div class="cell<?php if(strstr($action, "love_report")){echo " active";} ?>"><a href="tempadmin.php?action=love_report">Love Button</a></div>
		<div class="cell<?php if(strstr($action, "category_report")){echo " active";} ?>"><a href="tempadmin.php?action=category_report">Category Sold</a></div>
		<div class="clear"></div>
	</div>
<?php
if($headertext){
?>
	<div class="title_bar">
		<?php echo $headertext ?>
		<?php echo $headertext2 ?>
	</div>
<?php
}
?>

<!--
		<select id="sqltype" name="sqltype">
		<option value="">-- report type --</option>
		<option value="stats">stats</option>
		<option value="list">list</option>
		<option value="sum">sum</option>
		</select>

		<select id="type" name="type">
		<option value="">-- type --</option>
		<option value="order">order</option>
		<option value="invoice">invoice</option>
		<option value="user">user</option>
		</select>
-->

<?php if($output) { ?>
	<div class="filter_bar">
		<form id="filterform" name="filterform" method="get">
		Filter by:

		<?php if(strstr($action, "product_sales")){ ?>
		Group by: <select id="groupby" name="groupby">
		<option value="">-- type --</option>
		<option value="collection">collection</option>
		<option value="size">size</option>
		<option value="type">type</option>
		</select>
		<?php } ?>

		<?php if(!$hidedates){ ?>
		Date 1:
		<select id="year1" name="year1">
		<option value="">-- year --</option>
		<option value="2012">2012</option>
		<option value="2013">2013</option>
		</select>

		<select id="month1" name="month1">
		<option value="">-- month --</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		</select>

		<select id="day1" name="day1">
		<option value="">-- day --</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
		<option value="25">25</option>
		<option value="26">26</option>
		<option value="27">27</option>
		<option value="28">28</option>
		<option value="29">29</option>
		<option value="30">30</option>
		<option value="31">31</option>
		</select>


		Date 2:
		<select id="year2" name="year2">
		<option value="">-- year --</option>
		<option value="2012">2012</option>
		<option value="2013">2013</option>
		</select>

		<select id="month2" name="month2">
		<option value="">-- month --</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		</select>

		<select id="day2" name="day2">
		<option value="">-- day --</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
		<option value="25">25</option>
		<option value="26">26</option>
		<option value="27">27</option>
		<option value="28">28</option>
		<option value="29">29</option>
		<option value="30">30</option>
		<option value="31">31</option>
		</select>
		<?php } ?>

		<?php if(!$hidedayf){ ?>
		<input type="radio" id="datef" name="datef" value="day"> Da
		<input type="radio" id="datef" name="datef" value="month"> Mo
		<input type="radio" id="datef" name="datef" value="year"> Yr
		<?php } ?>

		<?php if($showcatdd){ ?>
		<select id="category_id" name="category_id">
		<option value="">-- category --</option>
		<option value="3">Scarves &amp; Wraps</option>
		<option value="4">Tops &amp; Blouses</option>
		<option value="5">Skirts</option>
		<option value="6">Jumpsuits &amp; Rompers</option>
		<option value="7">Dresses</option>
		<option value="10">Accessories</option>

		<option value="11">Boots &amp; Shoes</option>
		<option value="13">Pants &amp; Denim</option>
		<option value="14">Sweaters &amp; Cardigans</option>
		<option value="15">Shorts</option>
		<option value="16">Bags</option>

		<option value="18">Beachwear</option>
		<option value="19">Sleepwear</option>
		<option value="17">Home</option>
		<option value="8">Outerwear</option>
		<option value="9">Jackets &amp; Blazers</option>
		</select>
		<?php } ?>

		<?php if($showsolddd){ ?>

		<select id="availability_sort" name="availability_sort">
		<option value="">-- sold/unsold --</option>
		<option value="sold">sold</option>
		<option value="sale">unsold</option>

		</select>
		<?php } ?>

		<?php if($action=="repeat_customers"){ ?>
		<input type="radio" id="datetype" name="datetype" value="totalinrange" selected> total in range
		<input type="radio" id="datetype" name="datetype" value="boughtinrange"> bought in range
		<?php } ?>

		<!--<input type="button" onclick="filter();" value="Filter JS!">-->
		<input type="submit" class="filterbutton" value="Filter Submit!">

		<input type="hidden" id="action" name="action" value="<?php echo $action ?>">
		<input type="hidden" id="type" name="type" value="<?php echo $type ?>">
		<input type="hidden" id="sqltype" name="sqltype" value="<?php echo $sqltype ?>">
		</form>


	</div>

	<?php if($topoutput){ ?>

		<?php echo $topoutput; ?>
	<?php } ?>

		<?php if($showbottomlinks){
			$output.="<table>";
			$output .= "<tr>";
			$output .= "<td colspan=10 align=center>$prevlink | $nextlink</td>";
			$output .= "</tr>";
			$output.="</table>";
		}
		?>
		<?php echo $output; ?>
<?php
}
else{
?>
	<div class="admin_body">
<?php echo $links;?>
		<div class="clear"></div>
	</div>


<?php
}
?>





</div>
<?php
}
else{
?>

    <link rel="stylesheet" type="text/css" href="https://www.niftythrifty.com/Public/Style/Css/Fonts.css" />

<style>
body{
color: #868686;
font-family: Volkorn-Regular;
font-size: 14px;
}

a{
	color:#ff7070;
	text-decoration:none;
}


.label_container{
	width:960px;
}

.logo_row{
	width:960px;
	text-align:center;
}

.slipordernumber_row{
	width:960px;
	height:100px;
	text-align:center;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.slipordernumber_row h3{
	float:left;
	text-align:left;

	font-style:italic;
	font-weight:normal;
	color:#000000;
	font-size:24px;
}

.slipordernumber_row_ordernumber{
	float:right;
	margin-right:30px;
	background:#eeeeee;
	color:#000000;
	padding:15px;
	font-size:18px;
}

.deliverymethod_row{
	width:960px;
	height:90px;
	text-align:center;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.item_wrapper{
	width:960px;
	height:auto;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.item_row{
	width:960px;
	height:40px;
}

.item_cell_sku{
	float:left;
	width:100px;
	height:40px;
}

.item_cell_name{
	float:left;
	width:200px;
	height:40px;
}

.item_cell_description{
	float:left;
	width:400px;
	height:40px;
}

.item_cell_hashtag{
	float:left;
	width:140px;
	height:40px;
}

.item_cell_collection{
	float:left;
	width:100px;
	height:40px;
}

.item_cell_size{
	float:right;
	text-align:right;
	width:50px;
	height:40px;
}






.address_row{
	width:960px;
	height:100px;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.address_cell{
	width:400px;
	float:left;
	height:100px;
}

.niftyfeed_row{
	width:960px;
	height:180px;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.niftyfeed_left{
	float:left;
	width:450px;
	height:180px;
}

.niftyfeed_right{
	float:right;
	width:450px;
	height:180px;
}

.returns_row{
	width:960px;
	height:auto;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}


.footer_row{
	width:960px;
	height:60px;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.footer_cell{
	width:300px;
	float:left;
	height:60px;
}

.footer_cell b{
	font-weight:bold;
	color:#000000;
}

.logo_date{
	width:100px;
	float:right;
}

.highlight{
	color:#ff7070;
	font-weight:bold;
	text-decoration:none;
}

.label_container_row{
	width:960px;
	padding:20px;
	border-bottom: 1px dashed #cccccc;
}

.label_container_row_left{
	width:450px;
	float:left;
	text-align:left;
}


.label_container_row_right{
	width:500px;
	float:left;
	text-align:right;
}

.clear{
	clear:both;
}

</style>


<div class="label_container">

	<div class="logo_row">
		<img src=http://niftythrifty.com/images/images/logo.png>
		<div class="logo_date">
			<br><br><br>
			<span class="highlight">Order date:</span><br>
			<?php echo $output_invoice_date; ?>
		</div>
	</div>

	<div class="clear"></div>

	<div class="slipordernumber_row" style="border-bottom: 1px dashed #cccccc;">
		<h3>Packing Slip</h3>
		<div class="slipordernumber_row_ordernumber">
			order #<?php echo $output_ordernumber; ?>
		</div>
	</div>

	<div class="clear"></div>

	<div class="deliverymethod_row">
		<div class="label_container_row_left">
			<span class="highlight">Delivery Method</span><br>
			<?php echo $output_shipping_method; ?>
		</div>
		<div class="label_container_row_right">
			<span class="highlight">Shipping date:</span><br>
			<?php echo $output_invoice_date; ?>
		</div>
	</div>

	<div class="clear"></div>

	<div class="item_wrapper">
		<div class="item_row">
			<div class="item_cell_sku">
				<span class="highlight">Item #</span>
			</div>
			<div class="item_cell_name">
				<span class="highlight">Name</span>
			</div>
			<div class="item_cell_description">
				<span class="highlight">Description</span>
			</div>
			<div class="item_cell_hashtag">
				<span class="highlight">Price</span>
			</div>
			<div class="item_cell_size">
				<span class="highlight">Size</span>
			</div>
		</div>
		<?php echo $output_items; ?>
	</div>


	<div class="clear"></div>
	<div class="address_row"">
		<div class="address_cell">
			<span class="highlight">Shipping To</span><br>

			<?php echo $output_shipping_address_first_name; ?> <?php echo $output_shipping_address_last_name; ?><br>
			<?php echo $output_shipping_address_street; ?><br>
			<?php echo $output_shipping_address_city; ?>, <?php echo $output_shipping_address_state; ?> <?php echo $output_shipping_address_zipcode; ?>
		</div>
		<div class="address_cell">
			<span class="highlight">Billing To:</span><br>
			<?php echo $output_billing_address_first_name; ?> <?php echo $output_billing_address_last_name; ?><br>
			<?php echo $output_billing_address_street; ?><br>
			<?php echo $output_billing_address_city; ?>, <?php echo $output_billing_address_state; ?> <?php echo $output_billing_address_zipcode; ?>
		</div>
	</div>

	<div class="clear"></div>
<!--
	<div class="niftyfeed_row"">
		<div class="niftyfeed_left">
			<img src="http://niftythrifty.com/Public/Files/images/niftyfeed_packing.png">
		</div>
		<div class="niftyfeed_right">
			<span class="highlight">Don't forget!</span><br>
			We'd love to see you in your latest purchase, so don't forget to take a quick snap, put it on instagram with your item #hastag, and select the picture on your account section!
		</div>
	</div>

	<div class="clear"></div>
-->
	<div class="returns_row"">
		<span class="highlight">Returns</span><br>
If you're not completely satisfied with your Nifty purchase, you can return the item within 30 days for a full refund. We pick up the return shipping fee! Nifty. Thrifty. Easy, right? Just follow these steps:
<br><br>
<b>1. MARK THE RECEIPT</b> - Mark the items you're returning on the invoice that came with your order, and include in your return shipment.
<br><br>
<b>2. MAIL THE ITEM BACK TO US</b> - Returns are free! As a courtesy, we've also included a return shipping label with your order. Simply pack the items you're returning, and use this label if you'd like your return to be free of charge. Drop off the item at any UPS pick up location, or schedule a pick up by calling 1-800-742-5877.
You can also ship anything to us using your own carrier, but please note: we cannot reimburse you for those charges. For free returns, you MUST use the return shipping label provided by NiftyThrifty.
<br><br>
<b>3. REFUND</b> - Refunds are made to the same method of payment as your order, and are processed within 10 days from the arrival of your return package. An email from “Authroize.Net” will be sent to you once return transaction has been processed. When this appears on your credit card statement will depend on your bank.
<br><br>
*EXCHANGES<br>
Due to the one-of-a-kind nature of our merchandise, we cannot offer exchanges.
	</div>

	<div class="clear"></div>

	<div class="footer_row"">
		<div class="footer_cell"">
			<b>Customer Service</b>
			<br>
			help@niftythrifty.com
		</div>
		<div class="footer_cell"">
			<b>Nifty Thrifty, Inc.</b>
			<br>
			37 Greenpoint Avenue - Suite A3A
			<br>
			Brooklyn (Greenpoint), NY 11222
		</div>
		<div class="footer_cell"">
			<b>web</b>
			<br>
			www.niftythrifty.com
		</div>
	</div>

</div>






















<?php
}
?>
</body>
</html>