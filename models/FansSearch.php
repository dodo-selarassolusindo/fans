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
 * Page class
 */
class FansSearch extends Fans
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "FansSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "fanssearch";

    // Audit Trail
    public $AuditTrailOnAdd = true;
    public $AuditTrailOnEdit = true;
    public $AuditTrailOnDelete = true;
    public $AuditTrailOnView = false;
    public $AuditTrailOnViewData = false;
    public $AuditTrailOnSearch = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->FansID->setVisibility();
        $this->Nama->setVisibility();
        $this->Gender->setVisibility();
        $this->NomorHP->setVisibility();
        $this->TahunKelahiran->setVisibility();
        $this->Kota->setVisibility();
        $this->Profesi->setVisibility();
        $this->Hobi->setVisibility();
        $this->AcaraID->setVisibility();
        $this->RadioID->setVisibility();
        $this->Keterangan->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'fans';
        $this->TableName = 'fans';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (fans)
        if (!isset($GLOBALS["fans"]) || $GLOBALS["fans"]::class == PROJECT_NAMESPACE . "fans") {
            $GLOBALS["fans"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'fans');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return $Response?->getBody() ?? ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (WithJsonResponse()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = SameString($pageName, "fansview"); // If View page, no primary button
                } else { // List page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Result set
            while ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['FansID'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->FansID->Visible = false;
        }
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->Gender);
        $this->setupLookupOptions($this->Kota);
        $this->setupLookupOptions($this->AcaraID);
        $this->setupLookupOptions($this->RadioID);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Get action
        $this->CurrentAction = Post("action");
        if ($this->isSearch()) {
            // Build search string for advanced search, remove blank field
            $this->loadSearchValues(); // Get search values
            $srchStr = $this->validateSearch() ? $this->buildAdvancedSearch() : "";
            if ($srchStr != "") {
                $srchStr = "fanslist" . "?" . $srchStr;
                // Do not return Json for UseAjaxActions
                if ($this->IsModal && $this->UseAjaxActions) {
                    $this->IsModal = false;
                }
                $this->terminate($srchStr); // Go to list page
                return;
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = RowType::SEARCH;
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->FansID); // FansID
        $this->buildSearchUrl($srchUrl, $this->Nama); // Nama
        $this->buildSearchUrl($srchUrl, $this->Gender); // Gender
        $this->buildSearchUrl($srchUrl, $this->NomorHP); // NomorHP
        $this->buildSearchUrl($srchUrl, $this->TahunKelahiran); // TahunKelahiran
        $this->buildSearchUrl($srchUrl, $this->Kota); // Kota
        $this->buildSearchUrl($srchUrl, $this->Profesi); // Profesi
        $this->buildSearchUrl($srchUrl, $this->Hobi); // Hobi
        $this->buildSearchUrl($srchUrl, $this->AcaraID); // AcaraID
        $this->buildSearchUrl($srchUrl, $this->RadioID); // RadioID
        $this->buildSearchUrl($srchUrl, $this->Keterangan); // Keterangan
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, $fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        [
            "value" => $fldVal,
            "operator" => $fldOpr,
            "condition" => $fldCond,
            "value2" => $fldVal2,
            "operator2" => $fldOpr2
        ] = $CurrentForm->getSearchValues($fldParm);
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldDataType = $fld->DataType;
        $value = ConvertSearchValue($fldVal, $fldOpr, $fld); // For testing if numeric only
        $value2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld); // For testing if numeric only
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $value);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $value2);
        if (in_array($fldOpr, ["BETWEEN", "NOT BETWEEN"])) {
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld) && IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&y_" . $fldParm . "=" . urlencode($fldVal2) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld);
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif (in_array($fldOpr, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) . "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif (in_array($fldOpr2, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // FansID
        if ($this->FansID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Nama
        if ($this->Nama->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Gender
        if ($this->Gender->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // NomorHP
        if ($this->NomorHP->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // TahunKelahiran
        if ($this->TahunKelahiran->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Kota
        if ($this->Kota->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Profesi
        if ($this->Profesi->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Hobi
        if ($this->Hobi->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // AcaraID
        if ($this->AcaraID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // RadioID
        if ($this->RadioID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Keterangan
        if ($this->Keterangan->AdvancedSearch->get()) {
            $hasValue = true;
        }
        return $hasValue;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // FansID
        $this->FansID->RowCssClass = "row";

        // Nama
        $this->Nama->RowCssClass = "row";

        // Gender
        $this->Gender->RowCssClass = "row";

        // NomorHP
        $this->NomorHP->RowCssClass = "row";

        // TahunKelahiran
        $this->TahunKelahiran->RowCssClass = "row";

        // Kota
        $this->Kota->RowCssClass = "row";

        // Profesi
        $this->Profesi->RowCssClass = "row";

        // Hobi
        $this->Hobi->RowCssClass = "row";

        // AcaraID
        $this->AcaraID->RowCssClass = "row";

        // RadioID
        $this->RadioID->RowCssClass = "row";

        // Keterangan
        $this->Keterangan->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
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
        } elseif ($this->RowType == RowType::SEARCH) {
            // FansID
            $this->FansID->setupEditAttributes();
            $this->FansID->EditValue = $this->FansID->AdvancedSearch->SearchValue;
            $this->FansID->PlaceHolder = RemoveHtml($this->FansID->caption());

            // Nama
            $this->Nama->setupEditAttributes();
            if (!$this->Nama->Raw) {
                $this->Nama->AdvancedSearch->SearchValue = HtmlDecode($this->Nama->AdvancedSearch->SearchValue);
            }
            $this->Nama->EditValue = HtmlEncode($this->Nama->AdvancedSearch->SearchValue);
            $this->Nama->PlaceHolder = RemoveHtml($this->Nama->caption());

            // Gender
            $this->Gender->EditValue = $this->Gender->options(false);
            $this->Gender->PlaceHolder = RemoveHtml($this->Gender->caption());

            // NomorHP
            $this->NomorHP->setupEditAttributes();
            if (!$this->NomorHP->Raw) {
                $this->NomorHP->AdvancedSearch->SearchValue = HtmlDecode($this->NomorHP->AdvancedSearch->SearchValue);
            }
            $this->NomorHP->EditValue = HtmlEncode($this->NomorHP->AdvancedSearch->SearchValue);
            $this->NomorHP->PlaceHolder = RemoveHtml($this->NomorHP->caption());

            // TahunKelahiran
            $this->TahunKelahiran->setupEditAttributes();
            if (!$this->TahunKelahiran->Raw) {
                $this->TahunKelahiran->AdvancedSearch->SearchValue = HtmlDecode($this->TahunKelahiran->AdvancedSearch->SearchValue);
            }
            $this->TahunKelahiran->EditValue = HtmlEncode($this->TahunKelahiran->AdvancedSearch->SearchValue);
            $this->TahunKelahiran->PlaceHolder = RemoveHtml($this->TahunKelahiran->caption());

            // Kota
            $curVal = trim(strval($this->Kota->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->Kota->AdvancedSearch->ViewValue = $this->Kota->lookupCacheOption($curVal);
            } else {
                $this->Kota->AdvancedSearch->ViewValue = $this->Kota->Lookup !== null && is_array($this->Kota->lookupOptions()) && count($this->Kota->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Kota->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->Kota->EditValue = array_values($this->Kota->lookupOptions());
                if ($this->Kota->AdvancedSearch->ViewValue == "") {
                    $this->Kota->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Kota->Lookup->getTable()->Fields["LokasiID"]->searchExpression(), "=", $this->Kota->AdvancedSearch->SearchValue, $this->Kota->Lookup->getTable()->Fields["LokasiID"]->searchDataType(), "");
                }
                $sqlWrk = $this->Kota->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->Kota->Lookup->renderViewRow($rswrk[0]);
                    $this->Kota->AdvancedSearch->ViewValue = $this->Kota->displayValue($arwrk);
                } else {
                    $this->Kota->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->Kota->EditValue = $arwrk;
            }
            $this->Kota->PlaceHolder = RemoveHtml($this->Kota->caption());

            // Profesi
            $this->Profesi->setupEditAttributes();
            if (!$this->Profesi->Raw) {
                $this->Profesi->AdvancedSearch->SearchValue = HtmlDecode($this->Profesi->AdvancedSearch->SearchValue);
            }
            $this->Profesi->EditValue = HtmlEncode($this->Profesi->AdvancedSearch->SearchValue);
            $this->Profesi->PlaceHolder = RemoveHtml($this->Profesi->caption());

            // Hobi
            $this->Hobi->setupEditAttributes();
            if (!$this->Hobi->Raw) {
                $this->Hobi->AdvancedSearch->SearchValue = HtmlDecode($this->Hobi->AdvancedSearch->SearchValue);
            }
            $this->Hobi->EditValue = HtmlEncode($this->Hobi->AdvancedSearch->SearchValue);
            $this->Hobi->PlaceHolder = RemoveHtml($this->Hobi->caption());

            // AcaraID
            $curVal = trim(strval($this->AcaraID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->AcaraID->AdvancedSearch->ViewValue = $this->AcaraID->lookupCacheOption($curVal);
            } else {
                $this->AcaraID->AdvancedSearch->ViewValue = $this->AcaraID->Lookup !== null && is_array($this->AcaraID->lookupOptions()) && count($this->AcaraID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->AcaraID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->AcaraID->EditValue = array_values($this->AcaraID->lookupOptions());
                if ($this->AcaraID->AdvancedSearch->ViewValue == "") {
                    $this->AcaraID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->AcaraID->Lookup->getTable()->Fields["AcaraID"]->searchExpression(), "=", $this->AcaraID->AdvancedSearch->SearchValue, $this->AcaraID->Lookup->getTable()->Fields["AcaraID"]->searchDataType(), "");
                }
                $sqlWrk = $this->AcaraID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->AcaraID->Lookup->renderViewRow($rswrk[0]);
                    $this->AcaraID->AdvancedSearch->ViewValue = $this->AcaraID->displayValue($arwrk);
                } else {
                    $this->AcaraID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->AcaraID->EditValue = $arwrk;
            }
            $this->AcaraID->PlaceHolder = RemoveHtml($this->AcaraID->caption());

            // RadioID
            $curVal = trim(strval($this->RadioID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->RadioID->AdvancedSearch->ViewValue = $this->RadioID->lookupCacheOption($curVal);
            } else {
                $this->RadioID->AdvancedSearch->ViewValue = $this->RadioID->Lookup !== null && is_array($this->RadioID->lookupOptions()) && count($this->RadioID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->RadioID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->RadioID->EditValue = array_values($this->RadioID->lookupOptions());
                if ($this->RadioID->AdvancedSearch->ViewValue == "") {
                    $this->RadioID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->RadioID->Lookup->getTable()->Fields["RadioID"]->searchExpression(), "=", $this->RadioID->AdvancedSearch->SearchValue, $this->RadioID->Lookup->getTable()->Fields["RadioID"]->searchDataType(), "");
                }
                $sqlWrk = $this->RadioID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->RadioID->Lookup->renderViewRow($rswrk[0]);
                    $this->RadioID->AdvancedSearch->ViewValue = $this->RadioID->displayValue($arwrk);
                } else {
                    $this->RadioID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->RadioID->EditValue = $arwrk;
            }
            $this->RadioID->PlaceHolder = RemoveHtml($this->RadioID->caption());

            // Keterangan
            $this->Keterangan->setupEditAttributes();
            $this->Keterangan->EditValue = HtmlEncode($this->Keterangan->AdvancedSearch->SearchValue);
            $this->Keterangan->PlaceHolder = RemoveHtml($this->Keterangan->caption());
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->FansID->AdvancedSearch->SearchValue)) {
            $this->FansID->addErrorMessage($this->FansID->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->FansID->AdvancedSearch->load();
        $this->Nama->AdvancedSearch->load();
        $this->Gender->AdvancedSearch->load();
        $this->NomorHP->AdvancedSearch->load();
        $this->TahunKelahiran->AdvancedSearch->load();
        $this->Kota->AdvancedSearch->load();
        $this->Profesi->AdvancedSearch->load();
        $this->Hobi->AdvancedSearch->load();
        $this->AcaraID->AdvancedSearch->load();
        $this->RadioID->AdvancedSearch->load();
        $this->Keterangan->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("fanslist"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_Gender":
                    break;
                case "x_Kota":
                    break;
                case "x_AcaraID":
                    break;
                case "x_RadioID":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == "success") {
            //$msg = "your success message";
        } elseif ($type == "failure") {
            //$msg = "your failure message";
        } elseif ($type == "warning") {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
