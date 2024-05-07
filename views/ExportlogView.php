<?php

namespace PHPMaker2024\prj_fans;

// Page object
$ExportlogView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="fexportlogview" id="fexportlogview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { exportlog: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fexportlogview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fexportlogview")
        .setPageId("view")
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="exportlog">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->FileId->Visible) { // FileId ?>
    <tr id="r_FileId"<?= $Page->FileId->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog_FileId"><?= $Page->FileId->caption() ?></span></td>
        <td data-name="FileId"<?= $Page->FileId->cellAttributes() ?>>
<span id="el_exportlog_FileId">
<span<?= $Page->FileId->viewAttributes() ?>>
<?= $Page->FileId->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DateTime->Visible) { // DateTime ?>
    <tr id="r_DateTime"<?= $Page->DateTime->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog_DateTime"><?= $Page->DateTime->caption() ?></span></td>
        <td data-name="DateTime"<?= $Page->DateTime->cellAttributes() ?>>
<span id="el_exportlog_DateTime">
<span<?= $Page->DateTime->viewAttributes() ?>>
<?= $Page->DateTime->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User->Visible) { // User ?>
    <tr id="r_User"<?= $Page->User->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog_User"><?= $Page->User->caption() ?></span></td>
        <td data-name="User"<?= $Page->User->cellAttributes() ?>>
<span id="el_exportlog_User">
<span<?= $Page->User->viewAttributes() ?>>
<?= $Page->User->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_ExportType->Visible) { // ExportType ?>
    <tr id="r__ExportType"<?= $Page->_ExportType->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog__ExportType"><?= $Page->_ExportType->caption() ?></span></td>
        <td data-name="_ExportType"<?= $Page->_ExportType->cellAttributes() ?>>
<span id="el_exportlog__ExportType">
<span<?= $Page->_ExportType->viewAttributes() ?>>
<?= $Page->_ExportType->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Table->Visible) { // Table ?>
    <tr id="r__Table"<?= $Page->_Table->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog__Table"><?= $Page->_Table->caption() ?></span></td>
        <td data-name="_Table"<?= $Page->_Table->cellAttributes() ?>>
<span id="el_exportlog__Table">
<span<?= $Page->_Table->viewAttributes() ?>>
<?= $Page->_Table->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->KeyValue->Visible) { // KeyValue ?>
    <tr id="r_KeyValue"<?= $Page->KeyValue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog_KeyValue"><?= $Page->KeyValue->caption() ?></span></td>
        <td data-name="KeyValue"<?= $Page->KeyValue->cellAttributes() ?>>
<span id="el_exportlog_KeyValue">
<span<?= $Page->KeyValue->viewAttributes() ?>>
<?= $Page->KeyValue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Filename->Visible) { // Filename ?>
    <tr id="r_Filename"<?= $Page->Filename->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog_Filename"><?= $Page->Filename->caption() ?></span></td>
        <td data-name="Filename"<?= $Page->Filename->cellAttributes() ?>>
<span id="el_exportlog_Filename">
<span<?= $Page->Filename->viewAttributes() ?>>
<?= $Page->Filename->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->__Request->Visible) { // Request ?>
    <tr id="r___Request"<?= $Page->__Request->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_exportlog___Request"><?= $Page->__Request->caption() ?></span></td>
        <td data-name="__Request"<?= $Page->__Request->cellAttributes() ?>>
<span id="el_exportlog___Request">
<span<?= $Page->__Request->viewAttributes() ?>>
<?= $Page->__Request->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
