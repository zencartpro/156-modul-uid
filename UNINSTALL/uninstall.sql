################################################################################
# UID Uninstall Zen-Cart 1.5.6 - 2019-09-09 - webchills
# NUR AUSFÜHREN WENN SIE DAS MODUL AUS DER DATENBANK ENTFERNEN WOLLEN!!!!!
################################################################################

DELETE FROM configuration WHERE configuration_key = 'ENTRY_TVA_INTRACOM_CHECK';
DELETE FROM configuration WHERE configuration_key = 'TVA_SHOP_INTRACOM';
DELETE FROM configuration WHERE configuration_key = 'UID_MODUL_VERSION';
DELETE FROM configuration WHERE configuration_key = 'ENTRY_TVA_INTRACOM_MIN_LENGTH';
DELETE FROM configuration_language WHERE configuration_key = 'ENTRY_TVA_INTRACOM_CHECK';
DELETE FROM configuration_language WHERE configuration_key = 'TVA_SHOP_INTRACOM';
DELETE FROM configuration_language WHERE configuration_key = 'ENTRY_TVA_INTRACOM_MIN_LENGTH';
DELETE FROM configuration_group WHERE configuration_group_title LIKE 'UID';

DELETE FROM admin_pages WHERE page_key='configProdUID';

ALTER TABLE address_book DROP COLUMN entry_tva_intracom;
ALTER TABLE orders DROP COLUMN billing_tva_intracom;