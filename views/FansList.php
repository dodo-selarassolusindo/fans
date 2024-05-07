<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
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
<form name="ffanssrch" id="ffanssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="ffanssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentForm;
var ffanssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("ffanssrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="ffanssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="ffanssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="ffanssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="ffanssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="fans">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_fans" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_fanslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->FansID->Visible) { // FansID ?>
        <th data-name="FansID" class="<?= $Page->FansID->headerCellClass() ?>"><div id="elh_fans_FansID" class="fans_FansID"><?= $Page->renderFieldHeader($Page->FansID) ?></div></th>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
        <th data-name="Nama" class="<?= $Page->Nama->headerCellClass() ?>"><div id="elh_fans_Nama" class="fans_Nama"><?= $Page->renderFieldHeader($Page->Nama) ?></div></th>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
        <th data-name="Gender" class="<?= $Page->Gender->headerCellClass() ?>"><div id="elh_fans_Gender" class="fans_Gender"><?= $Page->renderFieldHeader($Page->Gender) ?></div></th>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
        <th data-name="NomorHP" class="<?= $Page->NomorHP->headerCellClass() ?>"><div id="elh_fans_NomorHP" class="fans_NomorHP"><?= $Page->renderFieldHeader($Page->NomorHP) ?></div></th>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
        <th data-name="TahunKelahiran" class="<?= $Page->TahunKelahiran->headerCellClass() ?>"><div id="elh_fans_TahunKelahiran" class="fans_TahunKelahiran"><?= $Page->renderFieldHeader($Page->TahunKelahiran) ?></div></th>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
        <th data-name="Kota" class="<?= $Page->Kota->headerCellClass() ?>"><div id="elh_fans_Kota" class="fans_Kota"><?= $Page->renderFieldHeader($Page->Kota) ?></div></th>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
        <th data-name="Profesi" class="<?= $Page->Profesi->headerCellClass() ?>"><div id="elh_fans_Profesi" class="fans_Profesi"><?= $Page->renderFieldHeader($Page->Profesi) ?></div></th>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
        <th data-name="Hobi" class="<?= $Page->Hobi->headerCellClass() ?>"><div id="elh_fans_Hobi" class="fans_Hobi"><?= $Page->renderFieldHeader($Page->Hobi) ?></div></th>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
        <th data-name="AcaraID" class="<?= $Page->AcaraID->headerCellClass() ?>"><div id="elh_fans_AcaraID" class="fans_AcaraID"><?= $Page->renderFieldHeader($Page->AcaraID) ?></div></th>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
        <th data-name="RadioID" class="<?= $Page->RadioID->headerCellClass() ?>"><div id="elh_fans_RadioID" class="fans_RadioID"><?= $Page->renderFieldHeader($Page->RadioID) ?></div></th>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
        <th data-name="Keterangan" class="<?= $Page->Keterangan->headerCellClass() ?>"><div id="elh_fans_Keterangan" class="fans_Keterangan"><?= $Page->renderFieldHeader($Page->Keterangan) ?></div></th>
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
    <?php if ($Page->FansID->Visible) { // FansID ?>
        <td data-name="FansID"<?= $Page->FansID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_FansID" class="el_fans_FansID">
<span<?= $Page->FansID->viewAttributes() ?>>
<?= $Page->FansID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Nama->Visible) { // Nama ?>
        <td data-name="Nama"<?= $Page->Nama->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Nama" class="el_fans_Nama">
<span<?= $Page->Nama->viewAttributes() ?>>
<?= $Page->Nama->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Gender->Visible) { // Gender ?>
        <td data-name="Gender"<?= $Page->Gender->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Gender" class="el_fans_Gender">
<span<?= $Page->Gender->viewAttributes() ?>>
<?= $Page->Gender->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NomorHP->Visible) { // NomorHP ?>
        <td data-name="NomorHP"<?= $Page->NomorHP->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_NomorHP" class="el_fans_NomorHP">
<span<?= $Page->NomorHP->viewAttributes() ?>>
<?= $Page->NomorHP->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
        <td data-name="TahunKelahiran"<?= $Page->TahunKelahiran->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_TahunKelahiran" class="el_fans_TahunKelahiran">
<span<?= $Page->TahunKelahiran->viewAttributes() ?>>
<?= $Page->TahunKelahiran->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Kota->Visible) { // Kota ?>
        <td data-name="Kota"<?= $Page->Kota->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Kota" class="el_fans_Kota">
<span<?= $Page->Kota->viewAttributes() ?>>
<?= $Page->Kota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Profesi->Visible) { // Profesi ?>
        <td data-name="Profesi"<?= $Page->Profesi->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Profesi" class="el_fans_Profesi">
<span<?= $Page->Profesi->viewAttributes() ?>>
<?= $Page->Profesi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Hobi->Visible) { // Hobi ?>
        <td data-name="Hobi"<?= $Page->Hobi->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Hobi" class="el_fans_Hobi">
<span<?= $Page->Hobi->viewAttributes() ?>>
<?= $Page->Hobi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->AcaraID->Visible) { // AcaraID ?>
        <td data-name="AcaraID"<?= $Page->AcaraID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_AcaraID" class="el_fans_AcaraID">
<span<?= $Page->AcaraID->viewAttributes() ?>>
<?= $Page->AcaraID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->RadioID->Visible) { // RadioID ?>
        <td data-name="RadioID"<?= $Page->RadioID->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_RadioID" class="el_fans_RadioID">
<span<?= $Page->RadioID->viewAttributes() ?>>
<?= $Page->RadioID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Keterangan->Visible) { // Keterangan ?>
        <td data-name="Keterangan"<?= $Page->Keterangan->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_fans_Keterangan" class="el_fans_Keterangan">
<span<?= $Page->Keterangan->viewAttributes() ?>>
<?= $Page->Keterangan->getViewValue() ?></span>
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
    ew.addEventHandlers("fans");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>