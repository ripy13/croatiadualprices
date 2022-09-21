<?php
/**
 * Plugin Name: Croatia dual prices
 * Plugin URI: https://www.cyber.rs/
 * Description: Display dual prices on Woocommerce: HRK / EUR. Exchange rate = 7.53450
 * Author: Nikola Markovic
 * Author URI: https://www.cyber.rs/
 * Version: 1.0.0
 * Text Domain: croatiadualprices
 *
 * @package   croatiadualprices
 * @author    cyber.rs
 * @category  Admin
 * @copyright Copyright (c) 2021, cyber.rs
 *
*/


function priceToFloat($s)
{
    $s = str_replace(',', '.', $s);
    $s = preg_replace("/[^0-9\.]/", "", $s);
    $hasCents = (substr($s, -3, 1) == '.');
    $s = str_replace('.', '', $s);
    if ($hasCents) {
        $s = substr($s, 0, -2) . '.' . substr($s, -2);
    }
    return (float) $s;
}


function convert_idr_to_eur_cart($price)
{
    $convertion_rate = 7.53450;
    $price_float = priceToFloat($price);
    $new_price = $price_float / $convertion_rate;
    return number_format($new_price, 2, '.', '');
}

add_filter('wc_price', 'my_custom_price_format', 10, 3);
function my_custom_price_format($formatted_price, $price, $args)
{

    $price_eur = convert_idr_to_eur_cart($price);

    $currency = 'EUR';
    $currency_symbol = get_woocommerce_currency_symbol($currency);
    $price_eur = $price_eur . $currency_symbol;

    $formatted_price_eur = "<span class='price-eur amount'> / $price_eur</span>";

    return $formatted_price . $formatted_price_eur;
}

function inline_css() {
    echo "<style>
          .cart_item .product-price .price-eur {display:none !important;} 
          .order-total .price-eur{display:none !important;};
          </style>";
}
add_action( 'wp_head', 'inline_css', 0 );

