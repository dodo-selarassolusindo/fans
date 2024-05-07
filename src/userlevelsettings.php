<?php
/**
 * PHPMaker 2024 User Level Settings
 */
namespace PHPMaker2024\prj_fans;

/**
 * User levels
 *
 * @var array<int, string>
 * [0] int User level ID
 * [1] string User level name
 */
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"]];

/**
 * User level permissions
 *
 * @var array<string, int, int>
 * [0] string Project ID + Table name
 * [1] int User level ID
 * [2] int Permissions
 */
$USER_LEVEL_PRIVS = [["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}acara","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}acara","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}employees","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}employees","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}fans","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}fans","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}lokasi","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}lokasi","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}radio","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}radio","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevelpermissions","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevelpermissions","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevels","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}userlevels","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}beranda.php","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}beranda.php","0","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}audittrail","-2","0"],
    ["{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}audittrail","0","0"]];

/**
 * Tables
 *
 * @var array<string, string, string, bool, string>
 * [0] string Table name
 * [1] string Table variable name
 * [2] string Table caption
 * [3] bool Allowed for update (for userpriv.php)
 * [4] string Project ID
 * [5] string URL (for OthersController::index)
 */
$USER_LEVEL_TABLES = [["acara","acara","Acara",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","acaralist"],
    ["employees","employees","Pengguna",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","employeeslist"],
    ["fans","fans","Fans",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","fanslist"],
    ["lokasi","lokasi","Kota",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","lokasilist"],
    ["radio","radio","Radio",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","radiolist"],
    ["userlevelpermissions","userlevelpermissions","Hak Akses Detail",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","userlevelpermissionslist"],
    ["userlevels","userlevels","Hak Akses",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","userlevelslist"],
    ["beranda.php","beranda","Beranda",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","beranda"],
    ["audittrail","audittrail","audittrail",true,"{E350C7F6-798A-4284-BC0B-A2D29EB5BB2F}","audittraillist"]];
