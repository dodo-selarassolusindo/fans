<?php

namespace PHPMaker2024\prj_fans;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use Closure;

/**
 * Table class for fans
 */
class Fans extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $FansID;
    public $Nama;
    public $Gender;
    public $NomorHP;
    public $TahunKelahiran;
    public $Kota;
    public $Profesi;
    public $Hobi;
    public $AcaraID;
    public $RadioID;
    public $Keterangan;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "fans";
        $this->TableName = 'fans';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "fans";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UseColumnVisibility = true;
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // FansID
        $this->FansID = new DbField(
            $this, // Table
            'x_FansID', // Variable name
            'FansID', // Name
            '`FansID`', // Expression
            '`FansID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`FansID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->FansID->InputTextType = "text";
        $this->FansID->Raw = true;
        $this->FansID->IsAutoIncrement = true; // Autoincrement field
        $this->FansID->IsPrimaryKey = true; // Primary key field
        $this->FansID->Nullable = false; // NOT NULL field
        $this->FansID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->FansID->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['FansID'] = &$this->FansID;

        // Nama
        $this->Nama = new DbField(
            $this, // Table
            'x_Nama', // Variable name
            'Nama', // Name
            '`Nama`', // Expression
            '`Nama`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Nama`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Nama->InputTextType = "text";
        $this->Nama->Nullable = false; // NOT NULL field
        $this->Nama->Required = true; // Required field
        $this->Nama->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['Nama'] = &$this->Nama;

        // Gender
        $this->Gender = new DbField(
            $this, // Table
            'x_Gender', // Variable name
            'Gender', // Name
            '`Gender`', // Expression
            '`Gender`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Gender`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->Gender->InputTextType = "text";
        $this->Gender->Raw = true;
        $this->Gender->Lookup = new Lookup($this->Gender, 'fans', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->Gender->OptionCount = 2;
        $this->Gender->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Gender->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['Gender'] = &$this->Gender;

        // NomorHP
        $this->NomorHP = new DbField(
            $this, // Table
            'x_NomorHP', // Variable name
            'NomorHP', // Name
            '`NomorHP`', // Expression
            '`NomorHP`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`NomorHP`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->NomorHP->InputTextType = "text";
        $this->NomorHP->Nullable = false; // NOT NULL field
        $this->NomorHP->Required = true; // Required field
        $this->NomorHP->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['NomorHP'] = &$this->NomorHP;

        // TahunKelahiran
        $this->TahunKelahiran = new DbField(
            $this, // Table
            'x_TahunKelahiran', // Variable name
            'TahunKelahiran', // Name
            '`TahunKelahiran`', // Expression
            '`TahunKelahiran`', // Basic search expression
            200, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`TahunKelahiran`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->TahunKelahiran->InputTextType = "text";
        $this->TahunKelahiran->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['TahunKelahiran'] = &$this->TahunKelahiran;

        // Kota
        $this->Kota = new DbField(
            $this, // Table
            'x_Kota', // Variable name
            'Kota', // Name
            '`Kota`', // Expression
            '`Kota`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Kota`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->Kota->InputTextType = "text";
        $this->Kota->Raw = true;
        $this->Kota->Nullable = false; // NOT NULL field
        $this->Kota->Required = true; // Required field
        $this->Kota->setSelectMultiple(false); // Select one
        $this->Kota->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->Kota->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->Kota->Lookup = new Lookup($this->Kota, 'lokasi', false, 'LokasiID', ["Nama","","",""], '', '', [], [], [], [], [], [], false, '', '', "`Nama`");
        $this->Kota->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Kota->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['Kota'] = &$this->Kota;

        // Profesi
        $this->Profesi = new DbField(
            $this, // Table
            'x_Profesi', // Variable name
            'Profesi', // Name
            '`Profesi`', // Expression
            '`Profesi`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Profesi`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Profesi->InputTextType = "text";
        $this->Profesi->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Profesi'] = &$this->Profesi;

        // Hobi
        $this->Hobi = new DbField(
            $this, // Table
            'x_Hobi', // Variable name
            'Hobi', // Name
            '`Hobi`', // Expression
            '`Hobi`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Hobi`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Hobi->InputTextType = "text";
        $this->Hobi->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Hobi'] = &$this->Hobi;

        // AcaraID
        $this->AcaraID = new DbField(
            $this, // Table
            'x_AcaraID', // Variable name
            'AcaraID', // Name
            '`AcaraID`', // Expression
            '`AcaraID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`AcaraID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->AcaraID->InputTextType = "text";
        $this->AcaraID->Raw = true;
        $this->AcaraID->setSelectMultiple(false); // Select one
        $this->AcaraID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->AcaraID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->AcaraID->Lookup = new Lookup($this->AcaraID, 'acara', false, 'AcaraID', ["Nama","","",""], '', '', [], [], [], [], [], [], false, '', '', "`Nama`");
        $this->AcaraID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->AcaraID->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['AcaraID'] = &$this->AcaraID;

        // RadioID
        $this->RadioID = new DbField(
            $this, // Table
            'x_RadioID', // Variable name
            'RadioID', // Name
            '`RadioID`', // Expression
            '`RadioID`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`RadioID`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->RadioID->InputTextType = "text";
        $this->RadioID->Raw = true;
        $this->RadioID->setSelectMultiple(false); // Select one
        $this->RadioID->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->RadioID->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->RadioID->Lookup = new Lookup($this->RadioID, 'radio', false, 'RadioID', ["Nama","","",""], '', '', [], [], [], [], [], [], false, '', '', "`Nama`");
        $this->RadioID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->RadioID->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['RadioID'] = &$this->RadioID;

        // Keterangan
        $this->Keterangan = new DbField(
            $this, // Table
            'x_Keterangan', // Variable name
            'Keterangan', // Name
            '`Keterangan`', // Expression
            '`Keterangan`', // Basic search expression
            200, // Type
            65535, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Keterangan`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Keterangan->InputTextType = "text";
        $this->Keterangan->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Keterangan'] = &$this->Keterangan;

        // Add Doctrine Cache
        $this->Cache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "fans";
    }

    // Get FROM clause (for backward compatibility)
    public function sqlFrom()
    {
        return $this->getSqlFrom();
    }

    // Set FROM clause
    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    // Get SELECT clause
    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select($this->sqlSelectFields());
    }

    // Get list of fields
    private function sqlSelectFields()
    {
        $useFieldNames = false;
        $fieldNames = [];
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($this->Fields as $field) {
            $expr = $field->Expression;
            $customExpr = $field->CustomDataType?->convertToPHPValueSQL($expr, $platform) ?? $expr;
            if ($customExpr != $expr) {
                $fieldNames[] = $customExpr . " AS " . QuotedName($field->Name, $this->Dbid);
                $useFieldNames = true;
            } else {
                $fieldNames[] = $expr;
            }
        }
        return $useFieldNames ? implode(", ", $fieldNames) : "*";
    }

    // Get SELECT clause (for backward compatibility)
    public function sqlSelect()
    {
        return $this->getSqlSelect();
    }

    // Set SELECT clause
    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    // Get WHERE clause
    public function getSqlWhere()
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    // Get WHERE clause (for backward compatibility)
    public function sqlWhere()
    {
        return $this->getSqlWhere();
    }

    // Set WHERE clause
    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    // Get GROUP BY clause
    public function getSqlGroupBy()
    {
        return $this->SqlGroupBy != "" ? $this->SqlGroupBy : "";
    }

    // Get GROUP BY clause (for backward compatibility)
    public function sqlGroupBy()
    {
        return $this->getSqlGroupBy();
    }

    // set GROUP BY clause
    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    // Get HAVING clause
    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    // Get HAVING clause (for backward compatibility)
    public function sqlHaving()
    {
        return $this->getSqlHaving();
    }

    // Set HAVING clause
    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    // Get ORDER BY clause
    public function getSqlOrderBy()
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    // Get ORDER BY clause (for backward compatibility)
    public function sqlOrderBy()
    {
        return $this->getSqlOrderBy();
    }

    // set ORDER BY clause
    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return ($allow & Allow::ADD->value) == Allow::ADD->value;
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return ($allow & Allow::EDIT->value) == Allow::EDIT->value;
            case "delete":
                return ($allow & Allow::DELETE->value) == Allow::DELETE->value;
            case "view":
                return ($allow & Allow::VIEW->value) == Allow::VIEW->value;
            case "search":
                return ($allow & Allow::SEARCH->value) == Allow::SEARCH->value;
            case "lookup":
                return ($allow & Allow::LOOKUP->value) == Allow::LOOKUP->value;
            default:
                return ($allow & Allow::LIST->value) == Allow::LIST->value;
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $sqlwrk = $sql instanceof QueryBuilder // Query builder
            ? (clone $sql)->resetQueryPart("orderBy")->getSQL()
            : $sql;
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            in_array($this->TableType, ["TABLE", "VIEW", "LINKTABLE"]) &&
            preg_match($pattern, $sqlwrk) &&
            !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*SELECT\s+DISTINCT\s+/i', $sqlwrk) &&
            !preg_match('/\s+ORDER\s+BY\s+/i', $sqlwrk)
        ) {
            $sqlcnt = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlcnt = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlcnt);
        if ($cnt !== false) {
            return (int)$cnt;
        }
        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        $result = $conn->executeQuery($sqlwrk);
        $cnt = $result->rowCount();
        if ($cnt == 0) { // Unable to get record count, count directly
            while ($result->fetch()) {
                $cnt++;
            }
        }
        return $cnt;
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->setValue($field->Expression, $parm);
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $queryBuilder = $this->insertSql($rs);
            $result = $queryBuilder->executeStatement();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->FansID->setDbValue($conn->lastInsertId());
            $rs['FansID'] = $this->FansID->DbValue;
        }
        return $result;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->set($field->Expression, $parm);
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->executeStatement();
            $success = $success > 0 ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['FansID']) && !EmptyValue($this->FansID->CurrentValue)) {
                $rs['FansID'] = $this->FansID->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('FansID', $rs)) {
                AddFilter($where, QuotedName('FansID', $this->Dbid) . '=' . QuotedValue($rs['FansID'], $this->FansID->DataType, $this->Dbid));
            }
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->executeStatement();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from result set or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->FansID->DbValue = $row['FansID'];
        $this->Nama->DbValue = $row['Nama'];
        $this->Gender->DbValue = $row['Gender'];
        $this->NomorHP->DbValue = $row['NomorHP'];
        $this->TahunKelahiran->DbValue = $row['TahunKelahiran'];
        $this->Kota->DbValue = $row['Kota'];
        $this->Profesi->DbValue = $row['Profesi'];
        $this->Hobi->DbValue = $row['Hobi'];
        $this->AcaraID->DbValue = $row['AcaraID'];
        $this->RadioID->DbValue = $row['RadioID'];
        $this->Keterangan->DbValue = $row['Keterangan'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`FansID` = @FansID@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->FansID->CurrentValue : $this->FansID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->FansID->CurrentValue = $keys[0];
            } else {
                $this->FansID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('FansID', $row) ? $row['FansID'] : null;
        } else {
            $val = !EmptyValue($this->FansID->OldValue) && !$current ? $this->FansID->OldValue : $this->FansID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@FansID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("fanslist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        return match ($pageName) {
            "fansview" => $Language->phrase("View"),
            "fansedit" => $Language->phrase("Edit"),
            "fansadd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "fanslist";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "FansView",
            Config("API_ADD_ACTION") => "FansAdd",
            Config("API_EDIT_ACTION") => "FansEdit",
            Config("API_DELETE_ACTION") => "FansDelete",
            Config("API_LIST_ACTION") => "FansList",
            default => ""
        };
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "fanslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("fansview", $parm);
        } else {
            $url = $this->keyUrl("fansview", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "fansadd?" . $parm;
        } else {
            $url = "fansadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("fansedit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("fanslist", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("fansadd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("fanslist", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("fansdelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"FansID\":" . VarToJson($this->FansID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->FansID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->FansID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($this->PageID != "grid" && $fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($this->PageID != "grid" && !$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") .
                (is_array($fld->EditValue) ? str_replace("%c", count($fld->EditValue), $Language->phrase("FilterCount")) : '') .
                '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport;
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, 0))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, 0))) // Other API
                : []; // Non-API
            if (($keyValue = Param("FansID") ?? Route("FansID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif ($isApi && (($keyValue = Key(0) ?? $keyValues[0] ?? null) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->FansID->CurrentValue = $key;
            } else {
                $this->FansID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load result set based on filter/sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->FansID->setDbValue($row['FansID']);
        $this->Nama->setDbValue($row['Nama']);
        $this->Gender->setDbValue($row['Gender']);
        $this->NomorHP->setDbValue($row['NomorHP']);
        $this->TahunKelahiran->setDbValue($row['TahunKelahiran']);
        $this->Kota->setDbValue($row['Kota']);
        $this->Profesi->setDbValue($row['Profesi']);
        $this->Hobi->setDbValue($row['Hobi']);
        $this->AcaraID->setDbValue($row['AcaraID']);
        $this->RadioID->setDbValue($row['RadioID']);
        $this->Keterangan->setDbValue($row['Keterangan']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "FansList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("app.view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // FansID

        // Nama

        // Gender

        // NomorHP

        // TahunKelahiran

        // Kota

        // Profesi

        // Hobi

        // AcaraID

        // RadioID

        // Keterangan

        // FansID
        $this->FansID->ViewValue = $this->FansID->CurrentValue;

        // Nama
        $this->Nama->ViewValue = $this->Nama->CurrentValue;

        // Gender
        if (strval($this->Gender->CurrentValue) != "") {
            $this->Gender->ViewValue = $this->Gender->optionCaption($this->Gender->CurrentValue);
        } else {
            $this->Gender->ViewValue = null;
        }

        // NomorHP
        $this->NomorHP->ViewValue = $this->NomorHP->CurrentValue;

        // TahunKelahiran
        $this->TahunKelahiran->ViewValue = $this->TahunKelahiran->CurrentValue;

        // Kota
        $curVal = strval($this->Kota->CurrentValue);
        if ($curVal != "") {
            $this->Kota->ViewValue = $this->Kota->lookupCacheOption($curVal);
            if ($this->Kota->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->Kota->Lookup->getTable()->Fields["LokasiID"]->searchExpression(), "=", $curVal, $this->Kota->Lookup->getTable()->Fields["LokasiID"]->searchDataType(), "");
                $sqlWrk = $this->Kota->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->Kota->Lookup->renderViewRow($rswrk[0]);
                    $this->Kota->ViewValue = $this->Kota->displayValue($arwrk);
                } else {
                    $this->Kota->ViewValue = FormatNumber($this->Kota->CurrentValue, $this->Kota->formatPattern());
                }
            }
        } else {
            $this->Kota->ViewValue = null;
        }

        // Profesi
        $this->Profesi->ViewValue = $this->Profesi->CurrentValue;

        // Hobi
        $this->Hobi->ViewValue = $this->Hobi->CurrentValue;

        // AcaraID
        $curVal = strval($this->AcaraID->CurrentValue);
        if ($curVal != "") {
            $this->AcaraID->ViewValue = $this->AcaraID->lookupCacheOption($curVal);
            if ($this->AcaraID->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->AcaraID->Lookup->getTable()->Fields["AcaraID"]->searchExpression(), "=", $curVal, $this->AcaraID->Lookup->getTable()->Fields["AcaraID"]->searchDataType(), "");
                $sqlWrk = $this->AcaraID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->AcaraID->Lookup->renderViewRow($rswrk[0]);
                    $this->AcaraID->ViewValue = $this->AcaraID->displayValue($arwrk);
                } else {
                    $this->AcaraID->ViewValue = FormatNumber($this->AcaraID->CurrentValue, $this->AcaraID->formatPattern());
                }
            }
        } else {
            $this->AcaraID->ViewValue = null;
        }

        // RadioID
        $curVal = strval($this->RadioID->CurrentValue);
        if ($curVal != "") {
            $this->RadioID->ViewValue = $this->RadioID->lookupCacheOption($curVal);
            if ($this->RadioID->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->RadioID->Lookup->getTable()->Fields["RadioID"]->searchExpression(), "=", $curVal, $this->RadioID->Lookup->getTable()->Fields["RadioID"]->searchDataType(), "");
                $sqlWrk = $this->RadioID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->RadioID->Lookup->renderViewRow($rswrk[0]);
                    $this->RadioID->ViewValue = $this->RadioID->displayValue($arwrk);
                } else {
                    $this->RadioID->ViewValue = FormatNumber($this->RadioID->CurrentValue, $this->RadioID->formatPattern());
                }
            }
        } else {
            $this->RadioID->ViewValue = null;
        }

        // Keterangan
        $this->Keterangan->ViewValue = $this->Keterangan->CurrentValue;

        // FansID
        $this->FansID->HrefValue = "";
        $this->FansID->TooltipValue = "";

        // Nama
        $this->Nama->HrefValue = "";
        $this->Nama->TooltipValue = "";

        // Gender
        $this->Gender->HrefValue = "";
        $this->Gender->TooltipValue = "";

        // NomorHP
        $this->NomorHP->HrefValue = "";
        $this->NomorHP->TooltipValue = "";

        // TahunKelahiran
        $this->TahunKelahiran->HrefValue = "";
        $this->TahunKelahiran->TooltipValue = "";

        // Kota
        $this->Kota->HrefValue = "";
        $this->Kota->TooltipValue = "";

        // Profesi
        $this->Profesi->HrefValue = "";
        $this->Profesi->TooltipValue = "";

        // Hobi
        $this->Hobi->HrefValue = "";
        $this->Hobi->TooltipValue = "";

        // AcaraID
        $this->AcaraID->HrefValue = "";
        $this->AcaraID->TooltipValue = "";

        // RadioID
        $this->RadioID->HrefValue = "";
        $this->RadioID->TooltipValue = "";

        // Keterangan
        $this->Keterangan->HrefValue = "";
        $this->Keterangan->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // FansID
        $this->FansID->setupEditAttributes();
        $this->FansID->EditValue = $this->FansID->CurrentValue;

        // Nama
        $this->Nama->setupEditAttributes();
        if (!$this->Nama->Raw) {
            $this->Nama->CurrentValue = HtmlDecode($this->Nama->CurrentValue);
        }
        $this->Nama->EditValue = $this->Nama->CurrentValue;
        $this->Nama->PlaceHolder = RemoveHtml($this->Nama->caption());

        // Gender
        $this->Gender->EditValue = $this->Gender->options(false);
        $this->Gender->PlaceHolder = RemoveHtml($this->Gender->caption());

        // NomorHP
        $this->NomorHP->setupEditAttributes();
        if (!$this->NomorHP->Raw) {
            $this->NomorHP->CurrentValue = HtmlDecode($this->NomorHP->CurrentValue);
        }
        $this->NomorHP->EditValue = $this->NomorHP->CurrentValue;
        $this->NomorHP->PlaceHolder = RemoveHtml($this->NomorHP->caption());

        // TahunKelahiran
        $this->TahunKelahiran->setupEditAttributes();
        if (!$this->TahunKelahiran->Raw) {
            $this->TahunKelahiran->CurrentValue = HtmlDecode($this->TahunKelahiran->CurrentValue);
        }
        $this->TahunKelahiran->EditValue = $this->TahunKelahiran->CurrentValue;
        $this->TahunKelahiran->PlaceHolder = RemoveHtml($this->TahunKelahiran->caption());

        // Kota
        $this->Kota->setupEditAttributes();
        $this->Kota->PlaceHolder = RemoveHtml($this->Kota->caption());

        // Profesi
        $this->Profesi->setupEditAttributes();
        if (!$this->Profesi->Raw) {
            $this->Profesi->CurrentValue = HtmlDecode($this->Profesi->CurrentValue);
        }
        $this->Profesi->EditValue = $this->Profesi->CurrentValue;
        $this->Profesi->PlaceHolder = RemoveHtml($this->Profesi->caption());

        // Hobi
        $this->Hobi->setupEditAttributes();
        if (!$this->Hobi->Raw) {
            $this->Hobi->CurrentValue = HtmlDecode($this->Hobi->CurrentValue);
        }
        $this->Hobi->EditValue = $this->Hobi->CurrentValue;
        $this->Hobi->PlaceHolder = RemoveHtml($this->Hobi->caption());

        // AcaraID
        $this->AcaraID->setupEditAttributes();
        $this->AcaraID->PlaceHolder = RemoveHtml($this->AcaraID->caption());

        // RadioID
        $this->RadioID->setupEditAttributes();
        $this->RadioID->PlaceHolder = RemoveHtml($this->RadioID->caption());

        // Keterangan
        $this->Keterangan->setupEditAttributes();
        if (!$this->Keterangan->Raw) {
            $this->Keterangan->CurrentValue = HtmlDecode($this->Keterangan->CurrentValue);
        }
        $this->Keterangan->EditValue = $this->Keterangan->CurrentValue;
        $this->Keterangan->PlaceHolder = RemoveHtml($this->Keterangan->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $result, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$result || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->FansID);
                    $doc->exportCaption($this->Nama);
                    $doc->exportCaption($this->Gender);
                    $doc->exportCaption($this->NomorHP);
                    $doc->exportCaption($this->TahunKelahiran);
                    $doc->exportCaption($this->Kota);
                    $doc->exportCaption($this->Profesi);
                    $doc->exportCaption($this->Hobi);
                    $doc->exportCaption($this->AcaraID);
                    $doc->exportCaption($this->RadioID);
                    $doc->exportCaption($this->Keterangan);
                } else {
                    $doc->exportCaption($this->FansID);
                    $doc->exportCaption($this->Nama);
                    $doc->exportCaption($this->Gender);
                    $doc->exportCaption($this->NomorHP);
                    $doc->exportCaption($this->TahunKelahiran);
                    $doc->exportCaption($this->Kota);
                    $doc->exportCaption($this->Profesi);
                    $doc->exportCaption($this->Hobi);
                    $doc->exportCaption($this->AcaraID);
                    $doc->exportCaption($this->RadioID);
                    $doc->exportCaption($this->Keterangan);
                }
                $doc->endExportRow();
            }
        }
        $recCnt = $startRec - 1;
        $stopRec = $stopRec > 0 ? $stopRec : PHP_INT_MAX;
        while (($row = $result->fetch()) && $recCnt < $stopRec) {
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = RowType::VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->FansID);
                        $doc->exportField($this->Nama);
                        $doc->exportField($this->Gender);
                        $doc->exportField($this->NomorHP);
                        $doc->exportField($this->TahunKelahiran);
                        $doc->exportField($this->Kota);
                        $doc->exportField($this->Profesi);
                        $doc->exportField($this->Hobi);
                        $doc->exportField($this->AcaraID);
                        $doc->exportField($this->RadioID);
                        $doc->exportField($this->Keterangan);
                    } else {
                        $doc->exportField($this->FansID);
                        $doc->exportField($this->Nama);
                        $doc->exportField($this->Gender);
                        $doc->exportField($this->NomorHP);
                        $doc->exportField($this->TahunKelahiran);
                        $doc->exportField($this->Kota);
                        $doc->exportField($this->Profesi);
                        $doc->exportField($this->Hobi);
                        $doc->exportField($this->AcaraID);
                        $doc->exportField($this->RadioID);
                        $doc->exportField($this->Keterangan);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected($rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted($rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
