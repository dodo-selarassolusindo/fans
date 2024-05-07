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
class FansEdit extends Fans
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "FansEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "fansedit";

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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

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
                if (
                    SameString($pageName, GetPageName($this->getListUrl())) ||
                    SameString($pageName, GetPageName($this->getViewUrl())) ||
                    SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "fansview"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                        $this->clearFailureMessage();
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("FansID") ?? Key(0) ?? Route(2)) !== null) {
                $this->FansID->setQueryStringValue($keyValue);
                $this->FansID->setOldValue($this->FansID->QueryStringValue);
            } elseif (Post("FansID") !== null) {
                $this->FansID->setFormValue(Post("FansID"));
                $this->FansID->setOldValue($this->FansID->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("FansID") ?? Route("FansID")) !== null) {
                    $this->FansID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->FansID->CurrentValue = null;
                }
            }

            // Load result set
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("fanslist"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "fanslist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "fanslist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "fanslist"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'FansID' first before field var 'x_FansID'
        $val = $CurrentForm->hasValue("FansID") ? $CurrentForm->getValue("FansID") : $CurrentForm->getValue("x_FansID");
        if (!$this->FansID->IsDetailKey) {
            $this->FansID->setFormValue($val);
        }

        // Check field name 'Nama' first before field var 'x_Nama'
        $val = $CurrentForm->hasValue("Nama") ? $CurrentForm->getValue("Nama") : $CurrentForm->getValue("x_Nama");
        if (!$this->Nama->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Nama->Visible = false; // Disable update for API request
            } else {
                $this->Nama->setFormValue($val);
            }
        }

        // Check field name 'Gender' first before field var 'x_Gender'
        $val = $CurrentForm->hasValue("Gender") ? $CurrentForm->getValue("Gender") : $CurrentForm->getValue("x_Gender");
        if (!$this->Gender->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Gender->Visible = false; // Disable update for API request
            } else {
                $this->Gender->setFormValue($val);
            }
        }

        // Check field name 'NomorHP' first before field var 'x_NomorHP'
        $val = $CurrentForm->hasValue("NomorHP") ? $CurrentForm->getValue("NomorHP") : $CurrentForm->getValue("x_NomorHP");
        if (!$this->NomorHP->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NomorHP->Visible = false; // Disable update for API request
            } else {
                $this->NomorHP->setFormValue($val);
            }
        }

        // Check field name 'TahunKelahiran' first before field var 'x_TahunKelahiran'
        $val = $CurrentForm->hasValue("TahunKelahiran") ? $CurrentForm->getValue("TahunKelahiran") : $CurrentForm->getValue("x_TahunKelahiran");
        if (!$this->TahunKelahiran->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TahunKelahiran->Visible = false; // Disable update for API request
            } else {
                $this->TahunKelahiran->setFormValue($val);
            }
        }

        // Check field name 'Kota' first before field var 'x_Kota'
        $val = $CurrentForm->hasValue("Kota") ? $CurrentForm->getValue("Kota") : $CurrentForm->getValue("x_Kota");
        if (!$this->Kota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Kota->Visible = false; // Disable update for API request
            } else {
                $this->Kota->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Profesi' first before field var 'x_Profesi'
        $val = $CurrentForm->hasValue("Profesi") ? $CurrentForm->getValue("Profesi") : $CurrentForm->getValue("x_Profesi");
        if (!$this->Profesi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Profesi->Visible = false; // Disable update for API request
            } else {
                $this->Profesi->setFormValue($val);
            }
        }

        // Check field name 'Hobi' first before field var 'x_Hobi'
        $val = $CurrentForm->hasValue("Hobi") ? $CurrentForm->getValue("Hobi") : $CurrentForm->getValue("x_Hobi");
        if (!$this->Hobi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Hobi->Visible = false; // Disable update for API request
            } else {
                $this->Hobi->setFormValue($val);
            }
        }

        // Check field name 'AcaraID' first before field var 'x_AcaraID'
        $val = $CurrentForm->hasValue("AcaraID") ? $CurrentForm->getValue("AcaraID") : $CurrentForm->getValue("x_AcaraID");
        if (!$this->AcaraID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->AcaraID->Visible = false; // Disable update for API request
            } else {
                $this->AcaraID->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'RadioID' first before field var 'x_RadioID'
        $val = $CurrentForm->hasValue("RadioID") ? $CurrentForm->getValue("RadioID") : $CurrentForm->getValue("x_RadioID");
        if (!$this->RadioID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->RadioID->Visible = false; // Disable update for API request
            } else {
                $this->RadioID->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Keterangan' first before field var 'x_Keterangan'
        $val = $CurrentForm->hasValue("Keterangan") ? $CurrentForm->getValue("Keterangan") : $CurrentForm->getValue("x_Keterangan");
        if (!$this->Keterangan->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Keterangan->Visible = false; // Disable update for API request
            } else {
                $this->Keterangan->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->FansID->CurrentValue = $this->FansID->FormValue;
        $this->Nama->CurrentValue = $this->Nama->FormValue;
        $this->Gender->CurrentValue = $this->Gender->FormValue;
        $this->NomorHP->CurrentValue = $this->NomorHP->FormValue;
        $this->TahunKelahiran->CurrentValue = $this->TahunKelahiran->FormValue;
        $this->Kota->CurrentValue = $this->Kota->FormValue;
        $this->Profesi->CurrentValue = $this->Profesi->FormValue;
        $this->Hobi->CurrentValue = $this->Hobi->FormValue;
        $this->AcaraID->CurrentValue = $this->AcaraID->FormValue;
        $this->RadioID->CurrentValue = $this->RadioID->FormValue;
        $this->Keterangan->CurrentValue = $this->Keterangan->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array $row Record
     * @return void
     */
    public function loadRowValues($row = null)
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
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

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['FansID'] = $this->FansID->DefaultValue;
        $row['Nama'] = $this->Nama->DefaultValue;
        $row['Gender'] = $this->Gender->DefaultValue;
        $row['NomorHP'] = $this->NomorHP->DefaultValue;
        $row['TahunKelahiran'] = $this->TahunKelahiran->DefaultValue;
        $row['Kota'] = $this->Kota->DefaultValue;
        $row['Profesi'] = $this->Profesi->DefaultValue;
        $row['Hobi'] = $this->Hobi->DefaultValue;
        $row['AcaraID'] = $this->AcaraID->DefaultValue;
        $row['RadioID'] = $this->RadioID->DefaultValue;
        $row['Keterangan'] = $this->Keterangan->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = ExecuteQuery($sql, $conn);
            if ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
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
            $this->Kota->ViewValue = $this->Kota->CurrentValue;
            $this->Kota->ViewValue = FormatNumber($this->Kota->ViewValue, $this->Kota->formatPattern());

            // Profesi
            $this->Profesi->ViewValue = $this->Profesi->CurrentValue;

            // Hobi
            $this->Hobi->ViewValue = $this->Hobi->CurrentValue;

            // AcaraID
            $this->AcaraID->ViewValue = $this->AcaraID->CurrentValue;
            $this->AcaraID->ViewValue = FormatNumber($this->AcaraID->ViewValue, $this->AcaraID->formatPattern());

            // RadioID
            $this->RadioID->ViewValue = $this->RadioID->CurrentValue;
            $this->RadioID->ViewValue = FormatNumber($this->RadioID->ViewValue, $this->RadioID->formatPattern());

            // Keterangan
            $this->Keterangan->ViewValue = $this->Keterangan->CurrentValue;

            // FansID
            $this->FansID->HrefValue = "";

            // Nama
            $this->Nama->HrefValue = "";

            // Gender
            $this->Gender->HrefValue = "";

            // NomorHP
            $this->NomorHP->HrefValue = "";

            // TahunKelahiran
            $this->TahunKelahiran->HrefValue = "";

            // Kota
            $this->Kota->HrefValue = "";

            // Profesi
            $this->Profesi->HrefValue = "";

            // Hobi
            $this->Hobi->HrefValue = "";

            // AcaraID
            $this->AcaraID->HrefValue = "";

            // RadioID
            $this->RadioID->HrefValue = "";

            // Keterangan
            $this->Keterangan->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // FansID
            $this->FansID->setupEditAttributes();
            $this->FansID->EditValue = $this->FansID->CurrentValue;

            // Nama
            $this->Nama->setupEditAttributes();
            if (!$this->Nama->Raw) {
                $this->Nama->CurrentValue = HtmlDecode($this->Nama->CurrentValue);
            }
            $this->Nama->EditValue = HtmlEncode($this->Nama->CurrentValue);
            $this->Nama->PlaceHolder = RemoveHtml($this->Nama->caption());

            // Gender
            $this->Gender->EditValue = $this->Gender->options(false);
            $this->Gender->PlaceHolder = RemoveHtml($this->Gender->caption());

            // NomorHP
            $this->NomorHP->setupEditAttributes();
            if (!$this->NomorHP->Raw) {
                $this->NomorHP->CurrentValue = HtmlDecode($this->NomorHP->CurrentValue);
            }
            $this->NomorHP->EditValue = HtmlEncode($this->NomorHP->CurrentValue);
            $this->NomorHP->PlaceHolder = RemoveHtml($this->NomorHP->caption());

            // TahunKelahiran
            $this->TahunKelahiran->setupEditAttributes();
            if (!$this->TahunKelahiran->Raw) {
                $this->TahunKelahiran->CurrentValue = HtmlDecode($this->TahunKelahiran->CurrentValue);
            }
            $this->TahunKelahiran->EditValue = HtmlEncode($this->TahunKelahiran->CurrentValue);
            $this->TahunKelahiran->PlaceHolder = RemoveHtml($this->TahunKelahiran->caption());

            // Kota
            $this->Kota->setupEditAttributes();
            $this->Kota->EditValue = $this->Kota->CurrentValue;
            $this->Kota->PlaceHolder = RemoveHtml($this->Kota->caption());
            if (strval($this->Kota->EditValue) != "" && is_numeric($this->Kota->EditValue)) {
                $this->Kota->EditValue = FormatNumber($this->Kota->EditValue, null);
            }

            // Profesi
            $this->Profesi->setupEditAttributes();
            if (!$this->Profesi->Raw) {
                $this->Profesi->CurrentValue = HtmlDecode($this->Profesi->CurrentValue);
            }
            $this->Profesi->EditValue = HtmlEncode($this->Profesi->CurrentValue);
            $this->Profesi->PlaceHolder = RemoveHtml($this->Profesi->caption());

            // Hobi
            $this->Hobi->setupEditAttributes();
            if (!$this->Hobi->Raw) {
                $this->Hobi->CurrentValue = HtmlDecode($this->Hobi->CurrentValue);
            }
            $this->Hobi->EditValue = HtmlEncode($this->Hobi->CurrentValue);
            $this->Hobi->PlaceHolder = RemoveHtml($this->Hobi->caption());

            // AcaraID
            $this->AcaraID->setupEditAttributes();
            $this->AcaraID->EditValue = $this->AcaraID->CurrentValue;
            $this->AcaraID->PlaceHolder = RemoveHtml($this->AcaraID->caption());
            if (strval($this->AcaraID->EditValue) != "" && is_numeric($this->AcaraID->EditValue)) {
                $this->AcaraID->EditValue = FormatNumber($this->AcaraID->EditValue, null);
            }

            // RadioID
            $this->RadioID->setupEditAttributes();
            $this->RadioID->EditValue = $this->RadioID->CurrentValue;
            $this->RadioID->PlaceHolder = RemoveHtml($this->RadioID->caption());
            if (strval($this->RadioID->EditValue) != "" && is_numeric($this->RadioID->EditValue)) {
                $this->RadioID->EditValue = FormatNumber($this->RadioID->EditValue, null);
            }

            // Keterangan
            $this->Keterangan->setupEditAttributes();
            if (!$this->Keterangan->Raw) {
                $this->Keterangan->CurrentValue = HtmlDecode($this->Keterangan->CurrentValue);
            }
            $this->Keterangan->EditValue = HtmlEncode($this->Keterangan->CurrentValue);
            $this->Keterangan->PlaceHolder = RemoveHtml($this->Keterangan->caption());

            // Edit refer script

            // FansID
            $this->FansID->HrefValue = "";

            // Nama
            $this->Nama->HrefValue = "";

            // Gender
            $this->Gender->HrefValue = "";

            // NomorHP
            $this->NomorHP->HrefValue = "";

            // TahunKelahiran
            $this->TahunKelahiran->HrefValue = "";

            // Kota
            $this->Kota->HrefValue = "";

            // Profesi
            $this->Profesi->HrefValue = "";

            // Hobi
            $this->Hobi->HrefValue = "";

            // AcaraID
            $this->AcaraID->HrefValue = "";

            // RadioID
            $this->RadioID->HrefValue = "";

            // Keterangan
            $this->Keterangan->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->FansID->Visible && $this->FansID->Required) {
                if (!$this->FansID->IsDetailKey && EmptyValue($this->FansID->FormValue)) {
                    $this->FansID->addErrorMessage(str_replace("%s", $this->FansID->caption(), $this->FansID->RequiredErrorMessage));
                }
            }
            if ($this->Nama->Visible && $this->Nama->Required) {
                if (!$this->Nama->IsDetailKey && EmptyValue($this->Nama->FormValue)) {
                    $this->Nama->addErrorMessage(str_replace("%s", $this->Nama->caption(), $this->Nama->RequiredErrorMessage));
                }
            }
            if ($this->Gender->Visible && $this->Gender->Required) {
                if ($this->Gender->FormValue == "") {
                    $this->Gender->addErrorMessage(str_replace("%s", $this->Gender->caption(), $this->Gender->RequiredErrorMessage));
                }
            }
            if ($this->NomorHP->Visible && $this->NomorHP->Required) {
                if (!$this->NomorHP->IsDetailKey && EmptyValue($this->NomorHP->FormValue)) {
                    $this->NomorHP->addErrorMessage(str_replace("%s", $this->NomorHP->caption(), $this->NomorHP->RequiredErrorMessage));
                }
            }
            if ($this->TahunKelahiran->Visible && $this->TahunKelahiran->Required) {
                if (!$this->TahunKelahiran->IsDetailKey && EmptyValue($this->TahunKelahiran->FormValue)) {
                    $this->TahunKelahiran->addErrorMessage(str_replace("%s", $this->TahunKelahiran->caption(), $this->TahunKelahiran->RequiredErrorMessage));
                }
            }
            if ($this->Kota->Visible && $this->Kota->Required) {
                if (!$this->Kota->IsDetailKey && EmptyValue($this->Kota->FormValue)) {
                    $this->Kota->addErrorMessage(str_replace("%s", $this->Kota->caption(), $this->Kota->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->Kota->FormValue)) {
                $this->Kota->addErrorMessage($this->Kota->getErrorMessage(false));
            }
            if ($this->Profesi->Visible && $this->Profesi->Required) {
                if (!$this->Profesi->IsDetailKey && EmptyValue($this->Profesi->FormValue)) {
                    $this->Profesi->addErrorMessage(str_replace("%s", $this->Profesi->caption(), $this->Profesi->RequiredErrorMessage));
                }
            }
            if ($this->Hobi->Visible && $this->Hobi->Required) {
                if (!$this->Hobi->IsDetailKey && EmptyValue($this->Hobi->FormValue)) {
                    $this->Hobi->addErrorMessage(str_replace("%s", $this->Hobi->caption(), $this->Hobi->RequiredErrorMessage));
                }
            }
            if ($this->AcaraID->Visible && $this->AcaraID->Required) {
                if (!$this->AcaraID->IsDetailKey && EmptyValue($this->AcaraID->FormValue)) {
                    $this->AcaraID->addErrorMessage(str_replace("%s", $this->AcaraID->caption(), $this->AcaraID->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->AcaraID->FormValue)) {
                $this->AcaraID->addErrorMessage($this->AcaraID->getErrorMessage(false));
            }
            if ($this->RadioID->Visible && $this->RadioID->Required) {
                if (!$this->RadioID->IsDetailKey && EmptyValue($this->RadioID->FormValue)) {
                    $this->RadioID->addErrorMessage(str_replace("%s", $this->RadioID->caption(), $this->RadioID->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->RadioID->FormValue)) {
                $this->RadioID->addErrorMessage($this->RadioID->getErrorMessage(false));
            }
            if ($this->Keterangan->Visible && $this->Keterangan->Required) {
                if (!$this->Keterangan->IsDetailKey && EmptyValue($this->Keterangan->FormValue)) {
                    $this->Keterangan->addErrorMessage(str_replace("%s", $this->Keterangan->caption(), $this->Keterangan->RequiredErrorMessage));
                }
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // Nama
        $this->Nama->setDbValueDef($rsnew, $this->Nama->CurrentValue, $this->Nama->ReadOnly);

        // Gender
        $this->Gender->setDbValueDef($rsnew, $this->Gender->CurrentValue, $this->Gender->ReadOnly);

        // NomorHP
        $this->NomorHP->setDbValueDef($rsnew, $this->NomorHP->CurrentValue, $this->NomorHP->ReadOnly);

        // TahunKelahiran
        $this->TahunKelahiran->setDbValueDef($rsnew, $this->TahunKelahiran->CurrentValue, $this->TahunKelahiran->ReadOnly);

        // Kota
        $this->Kota->setDbValueDef($rsnew, $this->Kota->CurrentValue, $this->Kota->ReadOnly);

        // Profesi
        $this->Profesi->setDbValueDef($rsnew, $this->Profesi->CurrentValue, $this->Profesi->ReadOnly);

        // Hobi
        $this->Hobi->setDbValueDef($rsnew, $this->Hobi->CurrentValue, $this->Hobi->ReadOnly);

        // AcaraID
        $this->AcaraID->setDbValueDef($rsnew, $this->AcaraID->CurrentValue, $this->AcaraID->ReadOnly);

        // RadioID
        $this->RadioID->setDbValueDef($rsnew, $this->RadioID->CurrentValue, $this->RadioID->ReadOnly);

        // Keterangan
        $this->Keterangan->setDbValueDef($rsnew, $this->Keterangan->CurrentValue, $this->Keterangan->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['Nama'])) { // Nama
            $this->Nama->CurrentValue = $row['Nama'];
        }
        if (isset($row['Gender'])) { // Gender
            $this->Gender->CurrentValue = $row['Gender'];
        }
        if (isset($row['NomorHP'])) { // NomorHP
            $this->NomorHP->CurrentValue = $row['NomorHP'];
        }
        if (isset($row['TahunKelahiran'])) { // TahunKelahiran
            $this->TahunKelahiran->CurrentValue = $row['TahunKelahiran'];
        }
        if (isset($row['Kota'])) { // Kota
            $this->Kota->CurrentValue = $row['Kota'];
        }
        if (isset($row['Profesi'])) { // Profesi
            $this->Profesi->CurrentValue = $row['Profesi'];
        }
        if (isset($row['Hobi'])) { // Hobi
            $this->Hobi->CurrentValue = $row['Hobi'];
        }
        if (isset($row['AcaraID'])) { // AcaraID
            $this->AcaraID->CurrentValue = $row['AcaraID'];
        }
        if (isset($row['RadioID'])) { // RadioID
            $this->RadioID->CurrentValue = $row['RadioID'];
        }
        if (isset($row['Keterangan'])) { // Keterangan
            $this->Keterangan->CurrentValue = $row['Keterangan'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("fanslist"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
