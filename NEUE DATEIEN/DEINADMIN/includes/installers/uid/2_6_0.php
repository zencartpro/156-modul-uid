<?php
/**
 * @package UID
 * @copyright Copyright 2003-2019 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: 2_6_0.php 2016-10-05 17:13:51Z webchills $
 */
 
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'UID'
LIMIT 1;");


$db->Execute("INSERT IGNORE INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, last_modified, use_function, set_function) VALUES
('Check the UID number', 'ENTRY_TVA_INTRACOM_CHECK', 'true', 'Check the Customers UID number by the europa.eu.int server<br /><b>Default: true</b>', @gid, 1, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'', ''false''),'),
('UID number of the store', 'TVA_SHOP_INTRACOM', 'XXXXXXXX', 'Enter your own UID number', @gid, 2, NOW(), NOW(), NULL, NULL),
('Minimum characters for the UID number', 'ENTRY_TVA_INTRACOM_MIN_LENGTH', '10', 'Required characters for VAT number<br/>Set to 0 if you dont want checking.', @gid, 12, NOW(), NOW(), NULL, NULL)");

$db->Execute("REPLACE INTO ".TABLE_CONFIGURATION_LANGUAGE." (configuration_title, configuration_key, configuration_description, configuration_language_id) VALUES
('UID Nummer überprüfen', 'ENTRY_TVA_INTRACOM_CHECK', '<br />Soll die UID Nummer am europa.eu.int server überprüft werden<br /><br /><b>Voreinstellung: true</b><br />', 43),
('UID Nummer des Shops', 'TVA_SHOP_INTRACOM', '<br />Geben Sie hier Ihre eigene UID Nummer ein<br />', 43),
('UID Nummer - Minimale Länge', 'ENTRY_TVA_INTRACOM_MIN_LENGTH','<br />Erforderliche Zeichenlänge für die UID-Nummer<br/>Stellen Sie auf 0, wenn die Nummer nicht überprüft werden soll<br />', 43)");


//check if entry_tva_intracom column already exists - if not add it
    $sql ="SHOW COLUMNS FROM ".TABLE_ADDRESS_BOOK." LIKE 'entry_tva_intracom'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ADDRESS_BOOK." ADD entry_tva_intracom VARCHAR(32) DEFAULT NULL AFTER entry_company";
        $db->Execute($sql);
    }
    
//check if billing_tva_intracom column already exists - if not add it
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS." LIKE 'billing_tva_intracom'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS." ADD billing_tva_intracom VARCHAR(32) AFTER billing_company";
        $db->Execute($sql);
    }


// delete old configuration/ menu
$admin_page = 'configProdUID';
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
// add configuration menu
if (!zen_page_key_exists($admin_page)) {
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'UID'
LIMIT 1;");
$db->Execute("INSERT INTO " . TABLE_ADMIN_PAGES . " (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES 
('configProdUID','BOX_CONFIGURATION_PRODUCT_UID','FILENAME_CONFIGURATION',CONCAT('gID=',@gid),'configuration','Y',@gid)");
$messageStack->add('UID Modul erfolgreich installiert.', 'success');  
}

