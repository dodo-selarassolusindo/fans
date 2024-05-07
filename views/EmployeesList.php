<?php

namespace PHPMaker2024\prj_fans;

// Page object
$EmployeesList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="femployeessrch" id="femployeessrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="femployeessrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentForm;
var femployeessrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("femployeessrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="femployeessrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="femployeessrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="femployeessrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="femployeessrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-header-options">
<?php $Page->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employees">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_employees" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_employeeslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = RowType::HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <th data-name="EmployeeID" class="<?= $Page->EmployeeID->headerCellClass() ?>"><div id="elh_employees_EmployeeID" class="employees_EmployeeID"><?= $Page->renderFieldHeader($Page->EmployeeID) ?></div></th>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
        <th data-name="LastName" class="<?= $Page->LastName->headerCellClass() ?>"><div id="elh_employees_LastName" class="employees_LastName"><?= $Page->renderFieldHeader($Page->LastName) ?></div></th>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
        <th data-name="FirstName" class="<?= $Page->FirstName->headerCellClass() ?>"><div id="elh_employees_FirstName" class="employees_FirstName"><?= $Page->renderFieldHeader($Page->FirstName) ?></div></th>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <th data-name="_Title" class="<?= $Page->_Title->headerCellClass() ?>"><div id="elh_employees__Title" class="employees__Title"><?= $Page->renderFieldHeader($Page->_Title) ?></div></th>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
        <th data-name="TitleOfCourtesy" class="<?= $Page->TitleOfCourtesy->headerCellClass() ?>"><div id="elh_employees_TitleOfCourtesy" class="employees_TitleOfCourtesy"><?= $Page->renderFieldHeader($Page->TitleOfCourtesy) ?></div></th>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
        <th data-name="BirthDate" class="<?= $Page->BirthDate->headerCellClass() ?>"><div id="elh_employees_BirthDate" class="employees_BirthDate"><?= $Page->renderFieldHeader($Page->BirthDate) ?></div></th>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
        <th data-name="HireDate" class="<?= $Page->HireDate->headerCellClass() ?>"><div id="elh_employees_HireDate" class="employees_HireDate"><?= $Page->renderFieldHeader($Page->HireDate) ?></div></th>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
        <th data-name="Address" class="<?= $Page->Address->headerCellClass() ?>"><div id="elh_employees_Address" class="employees_Address"><?= $Page->renderFieldHeader($Page->Address) ?></div></th>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
        <th data-name="City" class="<?= $Page->City->headerCellClass() ?>"><div id="elh_employees_City" class="employees_City"><?= $Page->renderFieldHeader($Page->City) ?></div></th>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
        <th data-name="Region" class="<?= $Page->Region->headerCellClass() ?>"><div id="elh_employees_Region" class="employees_Region"><?= $Page->renderFieldHeader($Page->Region) ?></div></th>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
        <th data-name="PostalCode" class="<?= $Page->PostalCode->headerCellClass() ?>"><div id="elh_employees_PostalCode" class="employees_PostalCode"><?= $Page->renderFieldHeader($Page->PostalCode) ?></div></th>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
        <th data-name="Country" class="<?= $Page->Country->headerCellClass() ?>"><div id="elh_employees_Country" class="employees_Country"><?= $Page->renderFieldHeader($Page->Country) ?></div></th>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
        <th data-name="HomePhone" class="<?= $Page->HomePhone->headerCellClass() ?>"><div id="elh_employees_HomePhone" class="employees_HomePhone"><?= $Page->renderFieldHeader($Page->HomePhone) ?></div></th>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
        <th data-name="Extension" class="<?= $Page->Extension->headerCellClass() ?>"><div id="elh_employees_Extension" class="employees_Extension"><?= $Page->renderFieldHeader($Page->Extension) ?></div></th>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
        <th data-name="Photo" class="<?= $Page->Photo->headerCellClass() ?>"><div id="elh_employees_Photo" class="employees_Photo"><?= $Page->renderFieldHeader($Page->Photo) ?></div></th>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
        <th data-name="ReportsTo" class="<?= $Page->ReportsTo->headerCellClass() ?>"><div id="elh_employees_ReportsTo" class="employees_ReportsTo"><?= $Page->renderFieldHeader($Page->ReportsTo) ?></div></th>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <th data-name="_Username" class="<?= $Page->_Username->headerCellClass() ?>"><div id="elh_employees__Username" class="employees__Username"><?= $Page->renderFieldHeader($Page->_Username) ?></div></th>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
        <th data-name="_Password" class="<?= $Page->_Password->headerCellClass() ?>"><div id="elh_employees__Password" class="employees__Password"><?= $Page->renderFieldHeader($Page->_Password) ?></div></th>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <th data-name="_Email" class="<?= $Page->_Email->headerCellClass() ?>"><div id="elh_employees__Email" class="employees__Email"><?= $Page->renderFieldHeader($Page->_Email) ?></div></th>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <th data-name="Activated" class="<?= $Page->Activated->headerCellClass() ?>"><div id="elh_employees_Activated" class="employees_Activated"><?= $Page->renderFieldHeader($Page->Activated) ?></div></th>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
        <th data-name="_UserLevel" class="<?= $Page->_UserLevel->headerCellClass() ?>"><div id="elh_employees__UserLevel" class="employees__UserLevel"><?= $Page->renderFieldHeader($Page->_UserLevel) ?></div></th>
<?php } ?>
<?php if ($Page->Avatar->Visible) { // Avatar ?>
        <th data-name="Avatar" class="<?= $Page->Avatar->headerCellClass() ?>"><div id="elh_employees_Avatar" class="employees_Avatar"><?= $Page->renderFieldHeader($Page->Avatar) ?></div></th>
<?php } ?>
<?php if ($Page->ActiveStatus->Visible) { // ActiveStatus ?>
        <th data-name="ActiveStatus" class="<?= $Page->ActiveStatus->headerCellClass() ?>"><div id="elh_employees_ActiveStatus" class="employees_ActiveStatus"><?= $Page->renderFieldHeader($Page->ActiveStatus) ?></div></th>
<?php } ?>
<?php if ($Page->MessengerColor->Visible) { // MessengerColor ?>
        <th data-name="MessengerColor" class="<?= $Page->MessengerColor->headerCellClass() ?>"><div id="elh_employees_MessengerColor" class="employees_MessengerColor"><?= $Page->renderFieldHeader($Page->MessengerColor) ?></div></th>
<?php } ?>
<?php if ($Page->CreatedAt->Visible) { // CreatedAt ?>
        <th data-name="CreatedAt" class="<?= $Page->CreatedAt->headerCellClass() ?>"><div id="elh_employees_CreatedAt" class="employees_CreatedAt"><?= $Page->renderFieldHeader($Page->CreatedAt) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$') {
    if (
        $Page->CurrentRow !== false &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->fetch();
    }
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <td data-name="EmployeeID"<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_EmployeeID" class="el_employees_EmployeeID">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->LastName->Visible) { // LastName ?>
        <td data-name="LastName"<?= $Page->LastName->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_LastName" class="el_employees_LastName">
<span<?= $Page->LastName->viewAttributes() ?>>
<?= $Page->LastName->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->FirstName->Visible) { // FirstName ?>
        <td data-name="FirstName"<?= $Page->FirstName->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_FirstName" class="el_employees_FirstName">
<span<?= $Page->FirstName->viewAttributes() ?>>
<?= $Page->FirstName->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Title->Visible) { // Title ?>
        <td data-name="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees__Title" class="el_employees__Title">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
        <td data-name="TitleOfCourtesy"<?= $Page->TitleOfCourtesy->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_TitleOfCourtesy" class="el_employees_TitleOfCourtesy">
<span<?= $Page->TitleOfCourtesy->viewAttributes() ?>>
<?= $Page->TitleOfCourtesy->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->BirthDate->Visible) { // BirthDate ?>
        <td data-name="BirthDate"<?= $Page->BirthDate->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_BirthDate" class="el_employees_BirthDate">
<span<?= $Page->BirthDate->viewAttributes() ?>>
<?= $Page->BirthDate->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->HireDate->Visible) { // HireDate ?>
        <td data-name="HireDate"<?= $Page->HireDate->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_HireDate" class="el_employees_HireDate">
<span<?= $Page->HireDate->viewAttributes() ?>>
<?= $Page->HireDate->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address->Visible) { // Address ?>
        <td data-name="Address"<?= $Page->Address->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Address" class="el_employees_Address">
<span<?= $Page->Address->viewAttributes() ?>>
<?= $Page->Address->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->City->Visible) { // City ?>
        <td data-name="City"<?= $Page->City->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_City" class="el_employees_City">
<span<?= $Page->City->viewAttributes() ?>>
<?= $Page->City->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Region->Visible) { // Region ?>
        <td data-name="Region"<?= $Page->Region->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Region" class="el_employees_Region">
<span<?= $Page->Region->viewAttributes() ?>>
<?= $Page->Region->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PostalCode->Visible) { // PostalCode ?>
        <td data-name="PostalCode"<?= $Page->PostalCode->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_PostalCode" class="el_employees_PostalCode">
<span<?= $Page->PostalCode->viewAttributes() ?>>
<?= $Page->PostalCode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Country->Visible) { // Country ?>
        <td data-name="Country"<?= $Page->Country->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Country" class="el_employees_Country">
<span<?= $Page->Country->viewAttributes() ?>>
<?= $Page->Country->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->HomePhone->Visible) { // HomePhone ?>
        <td data-name="HomePhone"<?= $Page->HomePhone->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_HomePhone" class="el_employees_HomePhone">
<span<?= $Page->HomePhone->viewAttributes() ?>>
<?= $Page->HomePhone->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Extension->Visible) { // Extension ?>
        <td data-name="Extension"<?= $Page->Extension->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Extension" class="el_employees_Extension">
<span<?= $Page->Extension->viewAttributes() ?>>
<?= $Page->Extension->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Photo->Visible) { // Photo ?>
        <td data-name="Photo"<?= $Page->Photo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Photo" class="el_employees_Photo">
<span<?= $Page->Photo->viewAttributes() ?>>
<?= $Page->Photo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
        <td data-name="ReportsTo"<?= $Page->ReportsTo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_ReportsTo" class="el_employees_ReportsTo">
<span<?= $Page->ReportsTo->viewAttributes() ?>>
<?= $Page->ReportsTo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Username->Visible) { // Username ?>
        <td data-name="_Username"<?= $Page->_Username->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees__Username" class="el_employees__Username">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Password->Visible) { // Password ?>
        <td data-name="_Password"<?= $Page->_Password->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees__Password" class="el_employees__Password">
<span<?= $Page->_Password->viewAttributes() ?>>
<?= $Page->_Password->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_Email->Visible) { // Email ?>
        <td data-name="_Email"<?= $Page->_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees__Email" class="el_employees__Email">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Activated->Visible) { // Activated ?>
        <td data-name="Activated"<?= $Page->Activated->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Activated" class="el_employees_Activated">
<span<?= $Page->Activated->viewAttributes() ?>>
<?= $Page->Activated->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
        <td data-name="_UserLevel"<?= $Page->_UserLevel->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees__UserLevel" class="el_employees__UserLevel">
<span<?= $Page->_UserLevel->viewAttributes() ?>>
<?= $Page->_UserLevel->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Avatar->Visible) { // Avatar ?>
        <td data-name="Avatar"<?= $Page->Avatar->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_Avatar" class="el_employees_Avatar">
<span<?= $Page->Avatar->viewAttributes() ?>>
<?= $Page->Avatar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ActiveStatus->Visible) { // ActiveStatus ?>
        <td data-name="ActiveStatus"<?= $Page->ActiveStatus->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_ActiveStatus" class="el_employees_ActiveStatus">
<span<?= $Page->ActiveStatus->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->ActiveStatus->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->MessengerColor->Visible) { // MessengerColor ?>
        <td data-name="MessengerColor"<?= $Page->MessengerColor->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_MessengerColor" class="el_employees_MessengerColor">
<span<?= $Page->MessengerColor->viewAttributes() ?>>
<?= $Page->MessengerColor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->CreatedAt->Visible) { // CreatedAt ?>
        <td data-name="CreatedAt"<?= $Page->CreatedAt->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_employees_CreatedAt" class="el_employees_CreatedAt">
<span<?= $Page->CreatedAt->viewAttributes() ?>>
<?= $Page->CreatedAt->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }

    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close result set
$Page->Recordset?->free();
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Page->FooterOptions?->render("body") ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("employees");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
