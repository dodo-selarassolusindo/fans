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
class ExportlogEdit extends Exportlog
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ExportlogEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "exportlogedit";

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
        $this->FileId->setVisibility();
        $this->DateTime->setVisibility();
        $this->User->setVisibility();
        $this->_ExportType->setVisibility();
        $this->_Table->setVisibility();
        $this->KeyValue->setVisibility();
        $this->Filename->setVisibility();
        $this->__Request->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'exportlog';
        $this->TableName = 'exportlog';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (exportlog)
        if (!isset($GLOBALS["exportlog"]) || $GLOBALS["exportlog"]::class == PROJECT_NAMESPACE . "exportlog") {
            $GLOBALS["exportlog"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'exportlog');
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
                        $result["view"] = SameString($pageName, "exportlogview"); // If View page, no primary button
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
            $key .= @$ar['FileId'];
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
            if (($keyValue = Get("FileId") ?? Key(0) ?? Route(2)) !== null) {
                $this->FileId->setQueryStringValue($keyValue);
                $this->FileId->setOldValue($this->FileId->QueryStringValue);
            } elseif (Post("FileId") !== null) {
                $this->FileId->setFormValue(Post("FileId"));
                $this->FileId->setOldValue($this->FileId->FormValue);
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
                if (($keyValue = Get("FileId") ?? Route("FileId")) !== null) {
                    $this->FileId->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->FileId->CurrentValue = null;
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
                        $this->terminate("exportloglist"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "exportloglist") {
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
                        if (GetPageName($returnUrl) != "exportloglist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "exportloglist"; // Return list page content
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

        // Check field name 'FileId' first before field var 'x_FileId'
        $val = $CurrentForm->hasValue("FileId") ? $CurrentForm->getValue("FileId") : $CurrentForm->getValue("x_FileId");
        if (!$this->FileId->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->FileId->Visible = false; // Disable update for API request
            } else {
                $this->FileId->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_FileId")) {
            $this->FileId->setOldValue($CurrentForm->getValue("o_FileId"));
        }

        // Check field name 'DateTime' first before field var 'x_DateTime'
        $val = $CurrentForm->hasValue("DateTime") ? $CurrentForm->getValue("DateTime") : $CurrentForm->getValue("x_DateTime");
        if (!$this->DateTime->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->DateTime->Visible = false; // Disable update for API request
            } else {
                $this->DateTime->setFormValue($val, true, $validate);
            }
            $this->DateTime->CurrentValue = UnFormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern());
        }

        // Check field name 'User' first before field var 'x_User'
        $val = $CurrentForm->hasValue("User") ? $CurrentForm->getValue("User") : $CurrentForm->getValue("x_User");
        if (!$this->User->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User->Visible = false; // Disable update for API request
            } else {
                $this->User->setFormValue($val);
            }
        }

        // Check field name 'ExportType' first before field var 'x__ExportType'
        $val = $CurrentForm->hasValue("ExportType") ? $CurrentForm->getValue("ExportType") : $CurrentForm->getValue("x__ExportType");
        if (!$this->_ExportType->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_ExportType->Visible = false; // Disable update for API request
            } else {
                $this->_ExportType->setFormValue($val);
            }
        }

        // Check field name 'Table' first before field var 'x__Table'
        $val = $CurrentForm->hasValue("Table") ? $CurrentForm->getValue("Table") : $CurrentForm->getValue("x__Table");
        if (!$this->_Table->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Table->Visible = false; // Disable update for API request
            } else {
                $this->_Table->setFormValue($val);
            }
        }

        // Check field name 'KeyValue' first before field var 'x_KeyValue'
        $val = $CurrentForm->hasValue("KeyValue") ? $CurrentForm->getValue("KeyValue") : $CurrentForm->getValue("x_KeyValue");
        if (!$this->KeyValue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->KeyValue->Visible = false; // Disable update for API request
            } else {
                $this->KeyValue->setFormValue($val);
            }
        }

        // Check field name 'Filename' first before field var 'x_Filename'
        $val = $CurrentForm->hasValue("Filename") ? $CurrentForm->getValue("Filename") : $CurrentForm->getValue("x_Filename");
        if (!$this->Filename->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Filename->Visible = false; // Disable update for API request
            } else {
                $this->Filename->setFormValue($val);
            }
        }

        // Check field name 'Request' first before field var 'x___Request'
        $val = $CurrentForm->hasValue("Request") ? $CurrentForm->getValue("Request") : $CurrentForm->getValue("x___Request");
        if (!$this->__Request->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->__Request->Visible = false; // Disable update for API request
            } else {
                $this->__Request->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->FileId->CurrentValue = $this->FileId->FormValue;
        $this->DateTime->CurrentValue = $this->DateTime->FormValue;
        $this->DateTime->CurrentValue = UnFormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern());
        $this->User->CurrentValue = $this->User->FormValue;
        $this->_ExportType->CurrentValue = $this->_ExportType->FormValue;
        $this->_Table->CurrentValue = $this->_Table->FormValue;
        $this->KeyValue->CurrentValue = $this->KeyValue->FormValue;
        $this->Filename->CurrentValue = $this->Filename->FormValue;
        $this->__Request->CurrentValue = $this->__Request->FormValue;
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
        $this->FileId->setDbValue($row['FileId']);
        $this->DateTime->setDbValue($row['DateTime']);
        $this->User->setDbValue($row['User']);
        $this->_ExportType->setDbValue($row['ExportType']);
        $this->_Table->setDbValue($row['Table']);
        $this->KeyValue->setDbValue($row['KeyValue']);
        $this->Filename->setDbValue($row['Filename']);
        $this->__Request->setDbValue($row['Request']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['FileId'] = $this->FileId->DefaultValue;
        $row['DateTime'] = $this->DateTime->DefaultValue;
        $row['User'] = $this->User->DefaultValue;
        $row['ExportType'] = $this->_ExportType->DefaultValue;
        $row['Table'] = $this->_Table->DefaultValue;
        $row['KeyValue'] = $this->KeyValue->DefaultValue;
        $row['Filename'] = $this->Filename->DefaultValue;
        $row['Request'] = $this->__Request->DefaultValue;
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

        // FileId
        $this->FileId->RowCssClass = "row";

        // DateTime
        $this->DateTime->RowCssClass = "row";

        // User
        $this->User->RowCssClass = "row";

        // ExportType
        $this->_ExportType->RowCssClass = "row";

        // Table
        $this->_Table->RowCssClass = "row";

        // KeyValue
        $this->KeyValue->RowCssClass = "row";

        // Filename
        $this->Filename->RowCssClass = "row";

        // Request
        $this->__Request->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // FileId
            $this->FileId->ViewValue = $this->FileId->CurrentValue;

            // DateTime
            $this->DateTime->ViewValue = $this->DateTime->CurrentValue;
            $this->DateTime->ViewValue = FormatDateTime($this->DateTime->ViewValue, $this->DateTime->formatPattern());

            // User
            $this->User->ViewValue = $this->User->CurrentValue;

            // ExportType
            $this->_ExportType->ViewValue = $this->_ExportType->CurrentValue;

            // Table
            $this->_Table->ViewValue = $this->_Table->CurrentValue;

            // KeyValue
            $this->KeyValue->ViewValue = $this->KeyValue->CurrentValue;

            // Filename
            $this->Filename->ViewValue = $this->Filename->CurrentValue;

            // Request
            $this->__Request->ViewValue = $this->__Request->CurrentValue;

            // FileId
            $this->FileId->HrefValue = "";

            // DateTime
            $this->DateTime->HrefValue = "";

            // User
            $this->User->HrefValue = "";

            // ExportType
            $this->_ExportType->HrefValue = "";

            // Table
            $this->_Table->HrefValue = "";

            // KeyValue
            $this->KeyValue->HrefValue = "";

            // Filename
            $this->Filename->HrefValue = "";

            // Request
            $this->__Request->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // FileId
            $this->FileId->setupEditAttributes();
            if (!$this->FileId->Raw) {
                $this->FileId->CurrentValue = HtmlDecode($this->FileId->CurrentValue);
            }
            $this->FileId->EditValue = HtmlEncode($this->FileId->CurrentValue);
            $this->FileId->PlaceHolder = RemoveHtml($this->FileId->caption());

            // DateTime
            $this->DateTime->setupEditAttributes();
            $this->DateTime->EditValue = HtmlEncode(FormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern()));
            $this->DateTime->PlaceHolder = RemoveHtml($this->DateTime->caption());

            // User
            $this->User->setupEditAttributes();
            if (!$this->User->Raw) {
                $this->User->CurrentValue = HtmlDecode($this->User->CurrentValue);
            }
            $this->User->EditValue = HtmlEncode($this->User->CurrentValue);
            $this->User->PlaceHolder = RemoveHtml($this->User->caption());

            // ExportType
            $this->_ExportType->setupEditAttributes();
            if (!$this->_ExportType->Raw) {
                $this->_ExportType->CurrentValue = HtmlDecode($this->_ExportType->CurrentValue);
            }
            $this->_ExportType->EditValue = HtmlEncode($this->_ExportType->CurrentValue);
            $this->_ExportType->PlaceHolder = RemoveHtml($this->_ExportType->caption());

            // Table
            $this->_Table->setupEditAttributes();
            if (!$this->_Table->Raw) {
                $this->_Table->CurrentValue = HtmlDecode($this->_Table->CurrentValue);
            }
            $this->_Table->EditValue = HtmlEncode($this->_Table->CurrentValue);
            $this->_Table->PlaceHolder = RemoveHtml($this->_Table->caption());

            // KeyValue
            $this->KeyValue->setupEditAttributes();
            if (!$this->KeyValue->Raw) {
                $this->KeyValue->CurrentValue = HtmlDecode($this->KeyValue->CurrentValue);
            }
            $this->KeyValue->EditValue = HtmlEncode($this->KeyValue->CurrentValue);
            $this->KeyValue->PlaceHolder = RemoveHtml($this->KeyValue->caption());

            // Filename
            $this->Filename->setupEditAttributes();
            if (!$this->Filename->Raw) {
                $this->Filename->CurrentValue = HtmlDecode($this->Filename->CurrentValue);
            }
            $this->Filename->EditValue = HtmlEncode($this->Filename->CurrentValue);
            $this->Filename->PlaceHolder = RemoveHtml($this->Filename->caption());

            // Request
            $this->__Request->setupEditAttributes();
            $this->__Request->EditValue = HtmlEncode($this->__Request->CurrentValue);
            $this->__Request->PlaceHolder = RemoveHtml($this->__Request->caption());

            // Edit refer script

            // FileId
            $this->FileId->HrefValue = "";

            // DateTime
            $this->DateTime->HrefValue = "";

            // User
            $this->User->HrefValue = "";

            // ExportType
            $this->_ExportType->HrefValue = "";

            // Table
            $this->_Table->HrefValue = "";

            // KeyValue
            $this->KeyValue->HrefValue = "";

            // Filename
            $this->Filename->HrefValue = "";

            // Request
            $this->__Request->HrefValue = "";
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
            if ($this->FileId->Visible && $this->FileId->Required) {
                if (!$this->FileId->IsDetailKey && EmptyValue($this->FileId->FormValue)) {
                    $this->FileId->addErrorMessage(str_replace("%s", $this->FileId->caption(), $this->FileId->RequiredErrorMessage));
                }
            }
            if ($this->DateTime->Visible && $this->DateTime->Required) {
                if (!$this->DateTime->IsDetailKey && EmptyValue($this->DateTime->FormValue)) {
                    $this->DateTime->addErrorMessage(str_replace("%s", $this->DateTime->caption(), $this->DateTime->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->DateTime->FormValue, $this->DateTime->formatPattern())) {
                $this->DateTime->addErrorMessage($this->DateTime->getErrorMessage(false));
            }
            if ($this->User->Visible && $this->User->Required) {
                if (!$this->User->IsDetailKey && EmptyValue($this->User->FormValue)) {
                    $this->User->addErrorMessage(str_replace("%s", $this->User->caption(), $this->User->RequiredErrorMessage));
                }
            }
            if ($this->_ExportType->Visible && $this->_ExportType->Required) {
                if (!$this->_ExportType->IsDetailKey && EmptyValue($this->_ExportType->FormValue)) {
                    $this->_ExportType->addErrorMessage(str_replace("%s", $this->_ExportType->caption(), $this->_ExportType->RequiredErrorMessage));
                }
            }
            if ($this->_Table->Visible && $this->_Table->Required) {
                if (!$this->_Table->IsDetailKey && EmptyValue($this->_Table->FormValue)) {
                    $this->_Table->addErrorMessage(str_replace("%s", $this->_Table->caption(), $this->_Table->RequiredErrorMessage));
                }
            }
            if ($this->KeyValue->Visible && $this->KeyValue->Required) {
                if (!$this->KeyValue->IsDetailKey && EmptyValue($this->KeyValue->FormValue)) {
                    $this->KeyValue->addErrorMessage(str_replace("%s", $this->KeyValue->caption(), $this->KeyValue->RequiredErrorMessage));
                }
            }
            if ($this->Filename->Visible && $this->Filename->Required) {
                if (!$this->Filename->IsDetailKey && EmptyValue($this->Filename->FormValue)) {
                    $this->Filename->addErrorMessage(str_replace("%s", $this->Filename->caption(), $this->Filename->RequiredErrorMessage));
                }
            }
            if ($this->__Request->Visible && $this->__Request->Required) {
                if (!$this->__Request->IsDetailKey && EmptyValue($this->__Request->FormValue)) {
                    $this->__Request->addErrorMessage(str_replace("%s", $this->__Request->caption(), $this->__Request->RequiredErrorMessage));
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

        // Check field with unique index (FileId)
        if ($this->FileId->CurrentValue != "") {
            $filterChk = "(`FileId` = '" . AdjustSql($this->FileId->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->FileId->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->FileId->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);

        // Check for duplicate key when key changed
        if ($updateRow) {
            $newKeyFilter = $this->getRecordFilter($rsnew);
            if ($newKeyFilter != $oldKeyFilter) {
                $rsChk = $this->loadRs($newKeyFilter)->fetch();
                if ($rsChk !== false) {
                    $keyErrMsg = str_replace("%f", $newKeyFilter, $Language->phrase("DupKey"));
                    $this->setFailureMessage($keyErrMsg);
                    $updateRow = false;
                }
            }
        }
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

        // FileId
        $this->FileId->setDbValueDef($rsnew, $this->FileId->CurrentValue, $this->FileId->ReadOnly);

        // DateTime
        $this->DateTime->setDbValueDef($rsnew, UnFormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern()), $this->DateTime->ReadOnly);

        // User
        $this->User->setDbValueDef($rsnew, $this->User->CurrentValue, $this->User->ReadOnly);

        // ExportType
        $this->_ExportType->setDbValueDef($rsnew, $this->_ExportType->CurrentValue, $this->_ExportType->ReadOnly);

        // Table
        $this->_Table->setDbValueDef($rsnew, $this->_Table->CurrentValue, $this->_Table->ReadOnly);

        // KeyValue
        $this->KeyValue->setDbValueDef($rsnew, $this->KeyValue->CurrentValue, $this->KeyValue->ReadOnly);

        // Filename
        $this->Filename->setDbValueDef($rsnew, $this->Filename->CurrentValue, $this->Filename->ReadOnly);

        // Request
        $this->__Request->setDbValueDef($rsnew, $this->__Request->CurrentValue, $this->__Request->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['FileId'])) { // FileId
            $this->FileId->CurrentValue = $row['FileId'];
        }
        if (isset($row['DateTime'])) { // DateTime
            $this->DateTime->CurrentValue = $row['DateTime'];
        }
        if (isset($row['User'])) { // User
            $this->User->CurrentValue = $row['User'];
        }
        if (isset($row['ExportType'])) { // ExportType
            $this->_ExportType->CurrentValue = $row['ExportType'];
        }
        if (isset($row['Table'])) { // Table
            $this->_Table->CurrentValue = $row['Table'];
        }
        if (isset($row['KeyValue'])) { // KeyValue
            $this->KeyValue->CurrentValue = $row['KeyValue'];
        }
        if (isset($row['Filename'])) { // Filename
            $this->Filename->CurrentValue = $row['Filename'];
        }
        if (isset($row['Request'])) { // Request
            $this->__Request->CurrentValue = $row['Request'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("exportloglist"), "", $this->TableVar, true);
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
