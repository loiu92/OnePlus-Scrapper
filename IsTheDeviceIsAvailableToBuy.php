<?php
$oneplus_link = 'https://oneplus.net/xman/product/info?param=%7B%22store%22%3A%22fr%22%2C%22id%22%3A409%2C%22ids%22%3A%5B%22397%22%2C%22405%22%2C%22401%22%5D%7D'; // URL to Json of OnePlus.net
$productId = "405"; // OnePlus3T 128GB
$skuId = '0101090210'; // OnePlus3T 128GB

$return = get_oneplus_content($oneplus_link); // Get Content of OnePlus.net JSON

$return = json_decode($return); // Json Decode to easily to use Data
$isAvailable = isProductAvailable($return, $productId, $skuId); // Check If Choose Device is Available

if ($isAvailable == 0)        // Action to do if there is no stock of the device.
  return 84;                  // Stop the script
else                          //Action to do if there is stock
  mail_oneplus($isAvailable); // Send Email with number in stock

function get_oneplus_content($oneplus_link)
{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $oneplus_link);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $return = curl_exec($curl);
        curl_close($curl);
        return $return;
}

function isProductAvailable($return, $productId, $skuId)
{
        foreach($return->data->children as $product)
        {
                if ($product->id == $productId && $product->sku == $skuId)
                        return $product->stock;
        };
        return (-1);
}

function mail_oneplus($isAvailable)
{
        $to = "your@email.com";
        $subject = "OnePlus 3t en stock";
        $message = "Bonjour,\n\nSi tu recois ce mail,\nCela veut dire qu'il y a $isAvailable OnePlus 3T 128GB en stock\nN'hesite pas a commander.";
        $headers = "From: bot@yourmail.com\n";
        mail($to, $subject, $message, $headers);
}
?>
