<?php

namespace PHPMaker2024\prj_fans;

// Page object
$AudittrailDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audittrail: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var faudittraildelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("faudittraildelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="faudittraildelete" id="faudittraildelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="audittrail">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->Id->Visible) { // Id ?>
        <th class="<?= $Page->Id->headerCellClass() ?>"><span id="elh_audittrail_Id" class="audittrail_Id"><?= $Page->Id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DateTime->Visible) { // DateTime ?>
        <th class="<?= $Page->DateTime->headerCellClass() ?>"><span id="elh_audittrail_DateTime" class="audittrail_DateTime"><?= $Page->DateTime->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Script->Visible) { // Script ?>
        <th class="<?= $Page->Script->headerCellClass() ?>"><span id="elh_audittrail_Script" class="audittrail_Script"><?= $Page->Script->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User->Visible) { // User ?>
        <th class="<?= $Page->User->headerCellClass() ?>"><span id="elh_audittrail_User" class="audittrail_User"><?= $Page->User->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Action->Visible) { // Action ?>
        <th class="<?= $Page->_Action->headerCellClass() ?>"><span id="elh_audittrail__Action" class="audittrail__Action"><?= $Page->_Action->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Table->Visible) { // Table ?>
        <th class="<?= $Page->_Table->headerCellClass() ?>"><span id="elh_audittrail__Table" class="audittrail__Table"><?= $Page->_Table->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Field->Visible) { // Field ?>
        <th class="<?= $Page->Field->headerCellClass() ?>"><span id="elh_audittrail_Field" class="audittrail_Field"><?= $Page->Field->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->Id->Visible) { // Id ?>
        <td<?= $Page->Id->cellAttributes() ?>>
<span id="">
<span<?= $Page->Id->viewAttributes() ?>>
<?= $Page->Id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DateTime->Visible) { // DateTime ?>
        <td<?= $Page->DateTime->cellAttributes() ?>>
<span id="">
<span<?= $Page->DateTime->viewAttributes() ?>>
<?= $Page->DateTime->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Script->Visible) { // Script ?>
        <td<?= $Page->Script->cellAttributes() ?>>
<span id="">
<span<?= $Page->Script->viewAttributes() ?>>
<?= $Page->Script->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User->Visible) { // User ?>
        <td<?= $Page->User->cellAttributes() ?>>
<span id="">
<span<?= $Page->User->viewAttributes() ?>>
<?= $Page->User->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Action->Visible) { // Action ?>
        <td<?= $Page->_Action->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Action->viewAttributes() ?>>
<?= $Page->_Action->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Table->Visible) { // Table ?>
        <td<?= $Page->_Table->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Table->viewAttributes() ?>>
<?= $Page->_Table->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Field->Visible) { // Field ?>
        <td<?= $Page->Field->cellAttributes() ?>>
<span id="">
<span<?= $Page->Field->viewAttributes() ?>>
<?= $Page->Field->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
