<?php
/**
 * functions_vat_mod.php
 * Verifying functions for VAT-Mod for Zen Cart
 *
 * @package functions
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: functions_vatmod.php 2020-05-01 17:36:51 webchills $
 */
 
 
// Always add tax to a products price
function zen_add_tax_invoice($price, $tax) {
global $currencies;

return zen_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + zen_calculate_tax($price, $tax);
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function		: zen_verif_tva 
// Arguments	: num_tva   VAT INTRACOM number to be checked
// Return		: true  - valid VAT number
//				: false - invalid VAT number
//
// Description : function for validating VAT INTRACOM number through the europa.eu.int server
//               The zen_verif_tva() function is converted from a script written by didou (didou@nexen.net).
//               The original script is available at http://www.nexen.net/index.php
//							 Modified by JeanLuc (February, 5th 2004)
//							 Updated by JeanLuc (July, 23th 2004)
//							 Updated by Beez & vike (September, 16th 2006)
//
// Valid VAT INTRACOM number structure:
//    Austria			AT + 9 numeric and alphanumeric characters 
//    Belgium			BE + 9 numeric characters 
//    Bulgaria			BG + 9 or 10 numeric characters
//    Cyprus 			CY + 8 numeric characters + 1 alphabetic character  
//    Czech Republic 	CZ + 8 or 9 or 10 numeric characters 
//    Denmark			DK + 8 numeric characters 
//    Estonia 			EE + 9 numeric characters 
//    Finland			FI + 8 numeric characters 
//    France			FR + 2 chiffres (informatic key) + No. SIREN (9 figures) 
//    Germany			DE + 9 numeric characters 
//    Greece			EL + 9 numeric characters 
//    Hungary 			HU + 8 numeric characters 
//    Irlande			IE + 8 numeric and alphabetic characters 
//    Italy				IT + 11 numeric characters 
//    Latvia 			LV + 11 numeric characterss 
//    Lithuania 		LT + 9 or 12 numeric characters 
//    Luxembourg		LU + 8 numeric characters 
//    Malta 			MT + 8 numeric characters 
//    Netherlands		NL + 12 alphanumeric characters, one of them a letter 
//    Poland	 		PL + 10 numeric characters 
//    Portugal 			PT + 9 numeric characters 
//    Slovakia  		SK + 9 or 10 numeric characters 
//    Spain 			ES + 9 characters 
//    Sweden 			SE + 12 numeric characters 
//    United Kingdom 	GB + 5 to 9 numeric characters 
//    Romania			RO + 2 to 9 numeric characters
//    Slovenia 			SI + 8 numeric characters
//    Croatia 			HR + 11 numeric characters
//
////////////////////////////////////////////////////////////////////////////////////////////////
function zen_verif_tva($num_tva){
$num_tva=preg_replace('/ +/', "", $num_tva);
$prefix = substr($num_tva, 0, 2);
if (array_search($prefix, zen_get_tva_intracom_array() ) === false) {
return 'false';
}

$tva = substr($num_tva, 2);	
// fix for Spain
$url='https://ec.europa.eu/taxation_customs/vies/vatResponse.html?locale=EN&memberStateCode=' . $prefix . '&number=' . $tva .'&traderName=';
//$url = 'http://ec.europa.eu/taxation_customs/vies/viesquer.do?ms=' . $prefix . '&iso='.$prefix.'&vat=' . $tva;
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
$monfd = curl_exec($ch);

if ( preg_match("/invalid VAT number/i", $monfd) ) {
return 'false';
} elseif ( preg_match("/valid VAT number/i", $monfd) ){
return 'true';
} else {
$myVerif = 'no_verif';
}
return $myVerif;
}

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function	: zen_get_tva_intracom_array 
// Return		: array
//
// Description	: Array for linking the ISO code of each country of EU and the first 2 letters of the vat number
//			(for Greece or France metropolitaine , it's different)
//             
//							  by JeanLuc (July, 23th 2004)             
//
////////////////////////////////////////////////////////////////////////////////////////////////
function zen_get_tva_intracom_array() {
$intracom_array = array('AT'=>'AT',    //Austria
'BE'=>'BE',	//Belgium
'DK'=>'DK',	//Denmark
'FI'=>'FI',	//Finland
'FR'=>'FR',	//France
'FX'=>'FR',	//France metropolitaine
'DE'=>'DE',	//Germany
'GR'=>'EL',	//Greece
'IE'=>'IE',	//Irland
'IT'=>'IT',	//Italy
'LU'=>'LU',	//Luxembourg
'NL'=>'NL',	//Netherlands
'PT'=>'PT',	//Portugal
'ES'=>'ES',	//Spain
'SE'=>'SE',	//Sweden
'GB'=>'GB',	//United Kingdom
'CY'=>'CY',	//Cyprus
'EE'=>'EE',	//Estonia
'HU'=>'HU',	//Hungary
'LV'=>'LV',	//Latvia
'LT'=>'LT',	//Lithuania
'MT'=>'MT',	//Malta
'PL'=>'PL',	//Poland
'SK'=>'SK', //Slovakia
'CZ'=>'CZ',	//Czech Republic
'SI'=>'SI', //Slovania
'RO'=>'RO', //Romania
'HR'=>'HR', //Croatia
'BG'=>'BG'); //Bulgaria
return $intracom_array;
}