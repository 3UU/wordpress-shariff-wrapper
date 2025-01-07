<?php /** @noinspection PhpCSValidationInspection */
/**
 * Generates an QR code for bitcoin.
 *
 * @package Shariff Wrapper
 */

// Includes php class for QR code generation.
require './includes/phpqrcode.php';

// Gets the bitcoin address.
$bitcoinaddress = htmlspecialchars( $_GET['bitcoinaddress'] );

// Creates the page.
echo '<html lang="en"><head><title>Bitcoin</title></head><body>';
echo '<div style="text-align:center;"><h1>Bitcoin</h1></div>';
echo '<p style="text-align:center;"><a href="bitcoin:' . $bitcoinaddress . '">bitcoin:' . $bitcoinaddress . '</a></p>';
echo '<p style="text-align:center;">';
QRcode::svg( $bitcoinaddress, false, 'h', 5 );
echo '</p>';
echo '<p style="text-align:center;">Information: <a href="https://www.bitcoin.org" target="_blank">bitcoin.org</a></p>';
echo '</body></html>';
