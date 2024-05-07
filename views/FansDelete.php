<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ffansdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffansdelete")
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
<form name="ffansdelete" id="ffansdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="fans">
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
<?php if ($Page->FansID->Visible) { // FansID ?>
        <th class="<?= $Page->FansID->headerCellClass() ?>"><span id="elh_fans_FansID" class="fans_FansID"><?= $Page->FansID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
        <th class="<?= $Page->Nama->headerCellClass() ?>"><span id="elh_fans_Nama" class="fans_Nama"><?= $Page->Nama->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
        <th class="<?= $Page->Gender->headerCellClass() ?>"><span id="elh_fans_Gender" class="fans_Gender"><?= $Page->Gender->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
        <th class="<?= $Page->NomorHP->headerCellClass() ?>"><span id="elh_fans_NomorHP" class="fans_NomorHP"><?= $Page->NomorHP->caption() ?></span></th>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
        <th class="<?= $Page->TahunKelahiran->headerCellClass() ?>"><span id="elh_fans_TahunKelahiran" class="fans_TahunKelahiran"><?= $Page->TahunKelahiran->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
        <th class="<?= $Page->Kota->headerCellClass() ?>"><span id="elh_fans_Kota" class="fans_Kota"><?= $Page->Kota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
        <th class="<?= $Page->Profesi->headerCellClass() ?>"><span id="elh_fans_Profesi" class="fans_Profesi"><?= $Page->Profesi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
        <th class="<?= $Page->Hobi->headerCellClass() ?>"><span id="elh_fans_Hobi" class="fans_Hobi"><?= $Page->Hobi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
        <th class="<?= $Page->AcaraID->headerCellClass() ?>"><span id="elh_fans_AcaraID" class="fans_AcaraID"><?= $Page->AcaraID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
        <th class="<?= $Page->RadioID->headerCellClass() ?>"><span id="elh_fans_RadioID" class="fans_RadioID"><?= $Page->RadioID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
        <th class="<?= $Page->Keterangan->headerCellClass() ?>"><span id="elh_fans_Keterangan" class="fans_Keterangan"><?= $Page->Keterangan->caption() ?></span></th>
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
<?php if ($Page->FansID->Visible) { // FansID ?>
        <td<?= $Page->FansID->cellAttributes() ?>>
<span id="">
<span<?= $Page->FansID->viewAttributes() ?>>
<?= $Page->FansID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
        <td<?= $Page->Nama->cellAttributes() ?>>
<span id="">
<span<?= $Page->Nama->viewAttributes() ?>>
<?= $Page->Nama->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
        <td<?= $Page->Gender->cellAttributes() ?>>
<span id="">
<span<?= $Page->Gender->viewAttributes() ?>>
<?= $Page->Gender->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
        <td<?= $Page->NomorHP->cellAttributes() ?>>
<span id="">
<span<?= $Page->NomorHP->viewAttributes() ?>>
<?= $Page->NomorHP->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
        <td<?= $Page->TahunKelahiran->cellAttributes() ?>>
<span id="">
<span<?= $Page->TahunKelahiran->viewAttributes() ?>>
<?= $Page->TahunKelahiran->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
        <td<?= $Page->Kota->cellAttributes() ?>>
<span id="">
<span<?= $Page->Kota->viewAttributes() ?>>
<?= $Page->Kota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
        <td<?= $Page->Profesi->cellAttributes() ?>>
<span id="">
<span<?= $Page->Profesi->viewAttributes() ?>>
<?= $Page->Profesi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
        <td<?= $Page->Hobi->cellAttributes() ?>>
<span id="">
<span<?= $Page->Hobi->viewAttributes() ?>>
<?= $Page->Hobi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
        <td<?= $Page->AcaraID->cellAttributes() ?>>
<span id="">
<span<?= $Page->AcaraID->viewAttributes() ?>>
<?= $Page->AcaraID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
        <td<?= $Page->RadioID->cellAttributes() ?>>
<span id="">
<span<?= $Page->RadioID->viewAttributes() ?>>
<?= $Page->RadioID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
        <td<?= $Page->Keterangan->cellAttributes() ?>>
<span id="">
<span<?= $Page->Keterangan->viewAttributes() ?>>
<?= $Page->Keterangan->getViewValue() ?></span>
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
