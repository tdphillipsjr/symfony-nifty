<?php
/*db connection*/
$dbname = "nifty_www";
$connection = mysql_connect("localhost", "nifty_db", "thematr1x") or die("Couldn't connect.");
$db = mysql_select_db($dbname) or die("Couldn't select database");


$upd = "UPDATE product p, basket_item bi
           SET p.product_availability = 'sale',
               bi.basket_item_status  = 'expired'
         WHERE p.product_id = bi.product_id
           AND bi.basket_item_status = 'valid'
           AND bi.basket_item_date_end < DATE_ADD(NOW(), INTERVAL 1 HOUR)";

$del = "DELETE
          FROM basket_item
         WHERE basket_item_status = 'expired'";

$result = mysql_query($upd) or die($upd);
mysql_query($del) or die($del);

?>