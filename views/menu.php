<?php

namespace PHPMaker2024\prj_fans;

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(8, "mi_beranda", $Language->menuPhrase("8", "MenuText"), "beranda", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}beranda.php'), false, false, "", "", true, false);
$topMenu->addMenuItem(5, "mi_radio", $Language->menuPhrase("5", "MenuText"), "radiolist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}radio'), false, false, "", "", true, false);
$topMenu->addMenuItem(1, "mi_acara", $Language->menuPhrase("1", "MenuText"), "acaralist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}acara'), false, false, "", "", true, false);
$topMenu->addMenuItem(4, "mi_lokasi", $Language->menuPhrase("4", "MenuText"), "lokasilist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}lokasi'), false, false, "", "", true, false);
$topMenu->addMenuItem(3, "mi_fans", $Language->menuPhrase("3", "MenuText"), "fanslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}fans'), false, false, "", "", true, false);
$topMenu->addMenuItem(2, "mi_employees", $Language->menuPhrase("2", "MenuText"), "employeeslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}employees'), false, false, "", "", true, false);
$topMenu->addMenuItem(7, "mi_userlevels", $Language->menuPhrase("7", "MenuText"), "userlevelslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevels'), false, false, "", "", true, false);
$topMenu->addMenuItem(9, "mi_audittrail", $Language->menuPhrase("9", "MenuText"), "audittraillist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}audittrail'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(8, "mi_beranda", $Language->menuPhrase("8", "MenuText"), "beranda", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}beranda.php'), false, false, "", "", true, true);
$sideMenu->addMenuItem(5, "mi_radio", $Language->menuPhrase("5", "MenuText"), "radiolist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}radio'), false, false, "", "", true, true);
$sideMenu->addMenuItem(1, "mi_acara", $Language->menuPhrase("1", "MenuText"), "acaralist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}acara'), false, false, "", "", true, true);
$sideMenu->addMenuItem(4, "mi_lokasi", $Language->menuPhrase("4", "MenuText"), "lokasilist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}lokasi'), false, false, "", "", true, true);
$sideMenu->addMenuItem(3, "mi_fans", $Language->menuPhrase("3", "MenuText"), "fanslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}fans'), false, false, "", "", true, true);
$sideMenu->addMenuItem(2, "mi_employees", $Language->menuPhrase("2", "MenuText"), "employeeslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}employees'), false, false, "", "", true, true);
$sideMenu->addMenuItem(7, "mi_userlevels", $Language->menuPhrase("7", "MenuText"), "userlevelslist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevels'), false, false, "", "", true, true);
$sideMenu->addMenuItem(9, "mi_audittrail", $Language->menuPhrase("9", "MenuText"), "audittraillist", -1, "", AllowListMenu('{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}audittrail'), false, false, "", "", true, true);
echo $sideMenu->toScript();
