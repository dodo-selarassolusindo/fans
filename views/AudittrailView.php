<?php

namespace PHPMaker2024\prj_fans;

// Page object
$AudittrailView = &$Page;
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
<form name="faudittrailview" id="faudittrailview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audittrail: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var faudittrailview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("faudittrailview")
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
<input type="hidden" name="t" value="audittrail">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Id->Visible) { // Id ?>
    <tr id="r_Id"<?= $Page->Id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_Id"><?= $Page->Id->caption() ?></span></td>
        <td data-name="Id"<?= $Page->Id->cellAttributes() ?>>
<span id="el_audittrail_Id">
<span<?= $Page->Id->viewAttributes() ?>>
<?= $Page->Id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DateTime->Visible) { // DateTime ?>
    <tr id="r_DateTime"<?= $Page->DateTime->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_DateTime"><?= $Page->DateTime->caption() ?></span></td>
        <td data-name="DateTime"<?= $Page->DateTime->cellAttributes() ?>>
<span id="el_audittrail_DateTime">
<span<?= $Page->DateTime->viewAttributes() ?>>
<?= $Page->DateTime->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Script->Visible) { // Script ?>
    <tr id="r_Script"<?= $Page->Script->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_Script"><?= $Page->Script->caption() ?></span></td>
        <td data-name="Script"<?= $Page->Script->cellAttributes() ?>>
<span id="el_audittrail_Script">
<span<?= $Page->Script->viewAttributes() ?>>
<?= $Page->Script->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User->Visible) { // User ?>
    <tr id="r_User"<?= $Page->User->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_User"><?= $Page->User->caption() ?></span></td>
        <td data-name="User"<?= $Page->User->cellAttributes() ?>>
<span id="el_audittrail_User">
<span<?= $Page->User->viewAttributes() ?>>
<?= $Page->User->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Action->Visible) { // Action ?>
    <tr id="r__Action"<?= $Page->_Action->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail__Action"><?= $Page->_Action->caption() ?></span></td>
        <td data-name="_Action"<?= $Page->_Action->cellAttributes() ?>>
<span id="el_audittrail__Action">
<span<?= $Page->_Action->viewAttributes() ?>>
<?= $Page->_Action->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_Table->Visible) { // Table ?>
    <tr id="r__Table"<?= $Page->_Table->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail__Table"><?= $Page->_Table->caption() ?></span></td>
        <td data-name="_Table"<?= $Page->_Table->cellAttributes() ?>>
<span id="el_audittrail__Table">
<span<?= $Page->_Table->viewAttributes() ?>>
<?= $Page->_Table->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Field->Visible) { // Field ?>
    <tr id="r_Field"<?= $Page->Field->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_Field"><?= $Page->Field->caption() ?></span></td>
        <td data-name="Field"<?= $Page->Field->cellAttributes() ?>>
<span id="el_audittrail_Field">
<span<?= $Page->Field->viewAttributes() ?>>
<?= $Page->Field->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->KeyValue->Visible) { // KeyValue ?>
    <tr id="r_KeyValue"<?= $Page->KeyValue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_KeyValue"><?= $Page->KeyValue->caption() ?></span></td>
        <td data-name="KeyValue"<?= $Page->KeyValue->cellAttributes() ?>>
<span id="el_audittrail_KeyValue">
<span<?= $Page->KeyValue->viewAttributes() ?>>
<?= $Page->KeyValue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->OldValue->Visible) { // OldValue ?>
    <tr id="r_OldValue"<?= $Page->OldValue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_OldValue"><?= $Page->OldValue->caption() ?></span></td>
        <td data-name="OldValue"<?= $Page->OldValue->cellAttributes() ?>>
<span id="el_audittrail_OldValue">
<span<?= $Page->OldValue->viewAttributes() ?>>
<?= $Page->OldValue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NewValue->Visible) { // NewValue ?>
    <tr id="r_NewValue"<?= $Page->NewValue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audittrail_NewValue"><?= $Page->NewValue->caption() ?></span></td>
        <td data-name="NewValue"<?= $Page->NewValue->cellAttributes() ?>>
<span id="el_audittrail_NewValue">
<span<?= $Page->NewValue->viewAttributes() ?>>
<?= $Page->NewValue->getViewValue() ?></span>
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
