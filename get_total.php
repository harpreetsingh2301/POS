<?php
use Pos\Terminal as Terminal;

require_once 'Terminal.php';

$terminal=new Terminal();


$terminal->setPricing('A',2);
$terminal->setPricing('A',7,4);
$terminal->setPricing('B',12);
$terminal->setPricing('C',1.25);
$terminal->setPricing('C',6,6);
$terminal->setPricing('D',0.15);

$input_array = str_split($_POST['product_txt'], 1);
foreach ($input_array as $code) {
	$terminal->scan($code);
}

echo 'Your cart total is '.number_format($terminal->total,2);
echo '<br> <a href="index.php" >Go back</a>';
