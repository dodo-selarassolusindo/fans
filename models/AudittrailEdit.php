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
class AudittrailEdit extends Audittrail
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "AudittrailEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "audittrailedit";

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
        $this->Id->setVisibility();
        $this->DateTime->setVisibility();
        $this->Script->setVisibility();
        $this->User->setVisibility();
        $this->_Action->setVisibility();
        $this->_Table->setVisibility();
        $this->Field->setVisibility();
        $this->KeyValue->setVisibility();
        $this->OldValue->setVisibility();
        $this->NewValue->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'audittrail';
        $this->TableName = 'audittrail';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (audittrail)
        if (!isset($GLOBALS["audittrail"]) || $GLOBALS["audittrail"]::class == PROJECT_NAMESPACE . "audittrail") {
            $GLOBALS["audittrail"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'audittrail');
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
                        $result["view"] = SameString($pageName, "audittrailview"); // If View page, no primary button
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
            $key .= @$ar['Id'];
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
            $this->Id->Visible = false;
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
        $this->setupLookupOptions($this->User);

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
            if (($keyValue = Get("Id") ?? Key(0) ?? Route(2)) !== null) {
                $this->Id->setQueryStringValue($keyValue);
                $this->Id->setOldValue($this->Id->QueryStringValue);
            } elseif (Post("Id") !== null) {
                $this->Id->setFormValue(Post("Id"));
                $this->Id->setOldValue($this->Id->FormValue);
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
                if (($keyValue = Get("Id") ?? Route("Id")) !== null) {
                    $this->Id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->Id->CurrentValue = null;
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
                        $this->terminate("audittraillist"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "audittraillist") {
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
                        if (GetPageName($returnUrl) != "audittraillist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "audittraillist"; // Return list page content
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

        // Check field name 'Id' first before field var 'x_Id'
        $val = $CurrentForm->hasValue("Id") ? $CurrentForm->getValue("Id") : $CurrentForm->getValue("x_Id");
        if (!$this->Id->IsDetailKey) {
            $this->Id->setFormValue($val);
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

        // Check field name 'Script' first before field var 'x_Script'
        $val = $CurrentForm->hasValue("Script") ? $CurrentForm->getValue("Script") : $CurrentForm->getValue("x_Script");
        if (!$this->Script->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Script->Visible = false; // Disable update for API request
            } else {
                $this->Script->setFormValue($val);
            }
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

        // Check field name 'Action' first before field var 'x__Action'
        $val = $CurrentForm->hasValue("Action") ? $CurrentForm->getValue("Action") : $CurrentForm->getValue("x__Action");
        if (!$this->_Action->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_Action->Visible = false; // Disable update for API request
            } else {
                $this->_Action->setFormValue($val);
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

        // Check field name 'Field' first before field var 'x_Field'
        $val = $CurrentForm->hasValue("Field") ? $CurrentForm->getValue("Field") : $CurrentForm->getValue("x_Field");
        if (!$this->Field->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Field->Visible = false; // Disable update for API request
            } else {
                $this->Field->setFormValue($val);
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

        // Check field name 'OldValue' first before field var 'x_OldValue'
        $val = $CurrentForm->hasValue("OldValue") ? $CurrentForm->getValue("OldValue") : $CurrentForm->getValue("x_OldValue");
        if (!$this->OldValue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->OldValue->Visible = false; // Disable update for API request
            } else {
                $this->OldValue->setFormValue($val);
            }
        }

        // Check field name 'NewValue' first before field var 'x_NewValue'
        $val = $CurrentForm->hasValue("NewValue") ? $CurrentForm->getValue("NewValue") : $CurrentForm->getValue("x_NewValue");
        if (!$this->NewValue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NewValue->Visible = false; // Disable update for API request
            } else {
                $this->NewValue->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Id->CurrentValue = $this->Id->FormValue;
        $this->DateTime->CurrentValue = $this->DateTime->FormValue;
        $this->DateTime->CurrentValue = UnFormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern());
        $this->Script->CurrentValue = $this->Script->FormValue;
        $this->User->CurrentValue = $this->User->FormValue;
        $this->_Action->CurrentValue = $this->_Action->FormValue;
        $this->_Table->CurrentValue = $this->_Table->FormValue;
        $this->Field->CurrentValue = $this->Field->FormValue;
        $this->KeyValue->CurrentValue = $this->KeyValue->FormValue;
        $this->OldValue->CurrentValue = $this->OldValue->FormValue;
        $this->NewValue->CurrentValue = $this->NewValue->FormValue;
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
        $this->Id->setDbValue($row['Id']);
        $this->DateTime->setDbValue($row['DateTime']);
        $this->Script->setDbValue($row['Script']);
        $this->User->setDbValue($row['User']);
        $this->_Action->setDbValue($row['Action']);
        $this->_Table->setDbValue($row['Table']);
        $this->Field->setDbValue($row['Field']);
        $this->KeyValue->setDbValue($row['KeyValue']);
        $this->OldValue->setDbValue($row['OldValue']);
        $this->NewValue->setDbValue($row['NewValue']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Id'] = $this->Id->DefaultValue;
        $row['DateTime'] = $this->DateTime->DefaultValue;
        $row['Script'] = $this->Script->DefaultValue;
        $row['User'] = $this->User->DefaultValue;
        $row['Action'] = $this->_Action->DefaultValue;
        $row['Table'] = $this->_Table->DefaultValue;
        $row['Field'] = $this->Field->DefaultValue;
        $row['KeyValue'] = $this->KeyValue->DefaultValue;
        $row['OldValue'] = $this->OldValue->DefaultValue;
        $row['NewValue'] = $this->NewValue->DefaultValue;
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

        // Id
        $this->Id->RowCssClass = "row";

        // DateTime
        $this->DateTime->RowCssClass = "row";

        // Script
        $this->Script->RowCssClass = "row";

        // User
        $this->User->RowCssClass = "row";

        // Action
        $this->_Action->RowCssClass = "row";

        // Table
        $this->_Table->RowCssClass = "row";

        // Field
        $this->Field->RowCssClass = "row";

        // KeyValue
        $this->KeyValue->RowCssClass = "row";

        // OldValue
        $this->OldValue->RowCssClass = "row";

        // NewValue
        $this->NewValue->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // Id
            $this->Id->ViewValue = $this->Id->CurrentValue;

            // DateTime
            $this->DateTime->ViewValue = $this->DateTime->CurrentValue;
            $this->DateTime->ViewValue = FormatDateTime($this->DateTime->ViewValue, $this->DateTime->formatPattern());

            // Script
            $this->Script->ViewValue = $this->Script->CurrentValue;

            // User
            $this->User->ViewValue = $this->User->CurrentValue;
            $curVal = strval($this->User->CurrentValue);
            if ($curVal != "") {
                $this->User->ViewValue = $this->User->lookupCacheOption($curVal);
                if ($this->User->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->User->Lookup->getTable()->Fields["EmployeeID"]->searchExpression(), "=", $curVal, $this->User->Lookup->getTable()->Fields["EmployeeID"]->searchDataType(), "");
                    $sqlWrk = $this->User->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->User->Lookup->renderViewRow($rswrk[0]);
                        $this->User->ViewValue = $this->User->displayValue($arwrk);
                    } else {
                        $this->User->ViewValue = $this->User->CurrentValue;
                    }
                }
            } else {
                $this->User->ViewValue = null;
            }

            // Action
            $this->_Action->ViewValue = $this->_Action->CurrentValue;

            // Table
            $this->_Table->ViewValue = $this->_Table->CurrentValue;

            // Field
            $this->Field->ViewValue = $this->Field->CurrentValue;

            // KeyValue
            $this->KeyValue->ViewValue = $this->KeyValue->CurrentValue;

            // OldValue
            $this->OldValue->ViewValue = $this->OldValue->CurrentValue;

            // NewValue
            $this->NewValue->ViewValue = $this->NewValue->CurrentValue;

            // Id
            $this->Id->HrefValue = "";

            // DateTime
            $this->DateTime->HrefValue = "";

            // Script
            $this->Script->HrefValue = "";

            // User
            $this->User->HrefValue = "";

            // Action
            $this->_Action->HrefValue = "";

            // Table
            $this->_Table->HrefValue = "";

            // Field
            $this->Field->HrefValue = "";

            // KeyValue
            $this->KeyValue->HrefValue = "";

            // OldValue
            $this->OldValue->HrefValue = "";

            // NewValue
            $this->NewValue->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // Id
            $this->Id->setupEditAttributes();
            $this->Id->EditValue = $this->Id->CurrentValue;

            // DateTime
            $this->DateTime->setupEditAttributes();
            $this->DateTime->EditValue = HtmlEncode(FormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern()));
            $this->DateTime->PlaceHolder = RemoveHtml($this->DateTime->caption());

            // Script
            $this->Script->setupEditAttributes();
            if (!$this->Script->Raw) {
                $this->Script->CurrentValue = HtmlDecode($this->Script->CurrentValue);
            }
            $this->Script->EditValue = HtmlEncode($this->Script->CurrentValue);
            $this->Script->PlaceHolder = RemoveHtml($this->Script->caption());

            // User
            $this->User->setupEditAttributes();
            if (!$this->User->Raw) {
                $this->User->CurrentValue = HtmlDecode($this->User->CurrentValue);
            }
            $this->User->EditValue = HtmlEncode($this->User->CurrentValue);
            $curVal = strval($this->User->CurrentValue);
            if ($curVal != "") {
                $this->User->EditValue = $this->User->lookupCacheOption($curVal);
                if ($this->User->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->User->Lookup->getTable()->Fields["EmployeeID"]->searchExpression(), "=", $curVal, $this->User->Lookup->getTable()->Fields["EmployeeID"]->searchDataType(), "");
                    $sqlWrk = $this->User->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->User->Lookup->renderViewRow($rswrk[0]);
                        $this->User->EditValue = $this->User->displayValue($arwrk);
                    } else {
                        $this->User->EditValue = HtmlEncode($this->User->CurrentValue);
                    }
                }
            } else {
                $this->User->EditValue = null;
            }
            $this->User->PlaceHolder = RemoveHtml($this->User->caption());

            // Action
            $this->_Action->setupEditAttributes();
            if (!$this->_Action->Raw) {
                $this->_Action->CurrentValue = HtmlDecode($this->_Action->CurrentValue);
            }
            $this->_Action->EditValue = HtmlEncode($this->_Action->CurrentValue);
            $this->_Action->PlaceHolder = RemoveHtml($this->_Action->caption());

            // Table
            $this->_Table->setupEditAttributes();
            if (!$this->_Table->Raw) {
                $this->_Table->CurrentValue = HtmlDecode($this->_Table->CurrentValue);
            }
            $this->_Table->EditValue = HtmlEncode($this->_Table->CurrentValue);
            $this->_Table->PlaceHolder = RemoveHtml($this->_Table->caption());

            // Field
            $this->Field->setupEditAttributes();
            if (!$this->Field->Raw) {
                $this->Field->CurrentValue = HtmlDecode($this->Field->CurrentValue);
            }
            $this->Field->EditValue = HtmlEncode($this->Field->CurrentValue);
            $this->Field->PlaceHolder = RemoveHtml($this->Field->caption());

            // KeyValue
            $this->KeyValue->setupEditAttributes();
            $this->KeyValue->EditValue = HtmlEncode($this->KeyValue->CurrentValue);
            $this->KeyValue->PlaceHolder = RemoveHtml($this->KeyValue->caption());

            // OldValue
            $this->OldValue->setupEditAttributes();
            $this->OldValue->EditValue = HtmlEncode($this->OldValue->CurrentValue);
            $this->OldValue->PlaceHolder = RemoveHtml($this->OldValue->caption());

            // NewValue
            $this->NewValue->setupEditAttributes();
            $this->NewValue->EditValue = HtmlEncode($this->NewValue->CurrentValue);
            $this->NewValue->PlaceHolder = RemoveHtml($this->NewValue->caption());

            // Edit refer script

            // Id
            $this->Id->HrefValue = "";

            // DateTime
            $this->DateTime->HrefValue = "";

            // Script
            $this->Script->HrefValue = "";

            // User
            $this->User->HrefValue = "";

            // Action
            $this->_Action->HrefValue = "";

            // Table
            $this->_Table->HrefValue = "";

            // Field
            $this->Field->HrefValue = "";

            // KeyValue
            $this->KeyValue->HrefValue = "";

            // OldValue
            $this->OldValue->HrefValue = "";

            // NewValue
            $this->NewValue->HrefValue = "";
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
            if ($this->Id->Visible && $this->Id->Required) {
                if (!$this->Id->IsDetailKey && EmptyValue($this->Id->FormValue)) {
                    $this->Id->addErrorMessage(str_replace("%s", $this->Id->caption(), $this->Id->RequiredErrorMessage));
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
            if ($this->Script->Visible && $this->Script->Required) {
                if (!$this->Script->IsDetailKey && EmptyValue($this->Script->FormValue)) {
                    $this->Script->addErrorMessage(str_replace("%s", $this->Script->caption(), $this->Script->RequiredErrorMessage));
                }
            }
            if ($this->User->Visible && $this->User->Required) {
                if (!$this->User->IsDetailKey && EmptyValue($this->User->FormValue)) {
                    $this->User->addErrorMessage(str_replace("%s", $this->User->caption(), $this->User->RequiredErrorMessage));
                }
            }
            if ($this->_Action->Visible && $this->_Action->Required) {
                if (!$this->_Action->IsDetailKey && EmptyValue($this->_Action->FormValue)) {
                    $this->_Action->addErrorMessage(str_replace("%s", $this->_Action->caption(), $this->_Action->RequiredErrorMessage));
                }
            }
            if ($this->_Table->Visible && $this->_Table->Required) {
                if (!$this->_Table->IsDetailKey && EmptyValue($this->_Table->FormValue)) {
                    $this->_Table->addErrorMessage(str_replace("%s", $this->_Table->caption(), $this->_Table->RequiredErrorMessage));
                }
            }
            if ($this->Field->Visible && $this->Field->Required) {
                if (!$this->Field->IsDetailKey && EmptyValue($this->Field->FormValue)) {
                    $this->Field->addErrorMessage(str_replace("%s", $this->Field->caption(), $this->Field->RequiredErrorMessage));
                }
            }
            if ($this->KeyValue->Visible && $this->KeyValue->Required) {
                if (!$this->KeyValue->IsDetailKey && EmptyValue($this->KeyValue->FormValue)) {
                    $this->KeyValue->addErrorMessage(str_replace("%s", $this->KeyValue->caption(), $this->KeyValue->RequiredErrorMessage));
                }
            }
            if ($this->OldValue->Visible && $this->OldValue->Required) {
                if (!$this->OldValue->IsDetailKey && EmptyValue($this->OldValue->FormValue)) {
                    $this->OldValue->addErrorMessage(str_replace("%s", $this->OldValue->caption(), $this->OldValue->RequiredErrorMessage));
                }
            }
            if ($this->NewValue->Visible && $this->NewValue->Required) {
                if (!$this->NewValue->IsDetailKey && EmptyValue($this->NewValue->FormValue)) {
                    $this->NewValue->addErrorMessage(str_replace("%s", $this->NewValue->caption(), $this->NewValue->RequiredErrorMessage));
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

        // DateTime
        $this->DateTime->setDbValueDef($rsnew, UnFormatDateTime($this->DateTime->CurrentValue, $this->DateTime->formatPattern()), $this->DateTime->ReadOnly);

        // Script
        $this->Script->setDbValueDef($rsnew, $this->Script->CurrentValue, $this->Script->ReadOnly);

        // User
        $this->User->setDbValueDef($rsnew, $this->User->CurrentValue, $this->User->ReadOnly);

        // Action
        $this->_Action->setDbValueDef($rsnew, $this->_Action->CurrentValue, $this->_Action->ReadOnly);

        // Table
        $this->_Table->setDbValueDef($rsnew, $this->_Table->CurrentValue, $this->_Table->ReadOnly);

        // Field
        $this->Field->setDbValueDef($rsnew, $this->Field->CurrentValue, $this->Field->ReadOnly);

        // KeyValue
        $this->KeyValue->setDbValueDef($rsnew, $this->KeyValue->CurrentValue, $this->KeyValue->ReadOnly);

        // OldValue
        $this->OldValue->setDbValueDef($rsnew, $this->OldValue->CurrentValue, $this->OldValue->ReadOnly);

        // NewValue
        $this->NewValue->setDbValueDef($rsnew, $this->NewValue->CurrentValue, $this->NewValue->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['DateTime'])) { // DateTime
            $this->DateTime->CurrentValue = $row['DateTime'];
        }
        if (isset($row['Script'])) { // Script
            $this->Script->CurrentValue = $row['Script'];
        }
        if (isset($row['User'])) { // User
            $this->User->CurrentValue = $row['User'];
        }
        if (isset($row['Action'])) { // Action
            $this->_Action->CurrentValue = $row['Action'];
        }
        if (isset($row['Table'])) { // Table
            $this->_Table->CurrentValue = $row['Table'];
        }
        if (isset($row['Field'])) { // Field
            $this->Field->CurrentValue = $row['Field'];
        }
        if (isset($row['KeyValue'])) { // KeyValue
            $this->KeyValue->CurrentValue = $row['KeyValue'];
        }
        if (isset($row['OldValue'])) { // OldValue
            $this->OldValue->CurrentValue = $row['OldValue'];
        }
        if (isset($row['NewValue'])) { // NewValue
            $this->NewValue->CurrentValue = $row['NewValue'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("audittraillist"), "", $this->TableVar, true);
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
                case "x_User":
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
