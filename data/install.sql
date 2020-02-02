--
-- Base Table
--
CREATE TABLE `account` (
  `Account_ID` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `account`
  ADD PRIMARY KEY (`Account_ID`);

ALTER TABLE `account`
  MODIFY `Account_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Permissions
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Account\\Controller\\AccountController', 'Add', '', '', 0),
('edit', 'OnePlace\\Account\\Controller\\AccountController', 'Edit', '', '', 0),
('index', 'OnePlace\\Account\\Controller\\AccountController', 'Index', 'My Account', '/account', 1),
('list', 'OnePlace\\Account\\Controller\\ApiController', 'List', '', '', 1),
('view', 'OnePlace\\Account\\Controller\\AccountController', 'View', '', '', 0),
('dump', 'OnePlace\\Account\\Controller\\ExportController', 'Excel Dump', '', '', 0),
('index', 'OnePlace\\Account\\Controller\\SearchController', 'Search', '', '', 0);

--
-- Form
--
INSERT INTO `core_form` (`form_key`, `label`, `entity_class`, `entity_tbl_class`) VALUES
('account-single', 'Account', 'OnePlace\\Account\\Model\\Account', 'OnePlace\\Account\\Model\\AccountTable');

--
-- Index List
--
INSERT INTO `core_index_table` (`table_name`, `form`, `label`) VALUES
('account-index', 'account-single', 'Account Index');

--
-- Tabs
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES ('account-base', 'account-single', 'Account', 'Base', 'fas fa-cogs', '', '0', '', '');

--
-- Buttons
--
INSERT INTO `core_form_button` (`Button_ID`, `label`, `icon`, `title`, `href`, `class`, `append`, `form`, `mode`, `filter_check`, `filter_value`) VALUES
(NULL, 'Save Account', 'fas fa-save', 'Save Account', '#', 'primary saveForm', '', 'account-single', 'link', '', ''),
(NULL, 'Edit Account', 'fas fa-edit', 'Edit Account', '/account/edit/##ID##', 'primary', '', 'account-view', 'link', '', ''),
(NULL, 'Add Account', 'fas fa-plus', 'Add Account', '/account/add', 'primary', '', 'account-index', 'link', '', ''),
(NULL, 'Export Accounts', 'fas fa-file-excel', 'Export Accounts', '/account/export', 'primary', '', 'account-index', 'link', '', ''),
(NULL, 'Find Accounts', 'fas fa-searh', 'Find Accounts', '/account/search', 'primary', '', 'account-index', 'link', '', ''),
(NULL, 'Export Accounts', 'fas fa-file-excel', 'Export Accounts', '#', 'primary initExcelDump', '', 'account-search', 'link', '', ''),
(NULL, 'New Search', 'fas fa-searh', 'New Search', '/account/search', 'primary', '', 'account-search', 'link', '', '');

--
-- Fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'text', 'Name', 'label', 'account-base', 'account-single', 'col-md-3', '/account/view/##ID##', '', 0, 1, 0, '', '', '');

--
-- Default Widgets
--
INSERT INTO `core_widget` (`Widget_ID`, `widget_name`, `label`, `permission`) VALUES
(NULL, 'account_dailystats', 'Account - Daily Stats', 'index-Account\\Controller\\AccountController'),
(NULL, 'account_taginfo', 'Account - Tag Info', 'index-Account\\Controller\\AccountController');

COMMIT;