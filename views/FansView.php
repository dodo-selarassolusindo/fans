<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansView = &$Page;
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
<form name="ffansview" id="ffansview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ffansview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffansview")
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
<input type="hidden" name="t" value="fans">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->FansID->Visible) { // FansID ?>
    <tr id="r_FansID"<?= $Page->FansID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_FansID"><?= $Page->FansID->caption() ?></span></td>
        <td data-name="FansID"<?= $Page->FansID->cellAttributes() ?>>
<span id="el_fans_FansID">
<span<?= $Page->FansID->viewAttributes() ?>>
<?= $Page->FansID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
    <tr id="r_Nama"<?= $Page->Nama->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Nama"><?= $Page->Nama->caption() ?></span></td>
        <td data-name="Nama"<?= $Page->Nama->cellAttributes() ?>>
<span id="el_fans_Nama">
<span<?= $Page->Nama->viewAttributes() ?>>
<?= $Page->Nama->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
    <tr id="r_Gender"<?= $Page->Gender->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Gender"><?= $Page->Gender->caption() ?></span></td>
        <td data-name="Gender"<?= $Page->Gender->cellAttributes() ?>>
<span id="el_fans_Gender">
<span<?= $Page->Gender->viewAttributes() ?>>
<?= $Page->Gender->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
    <tr id="r_NomorHP"<?= $Page->NomorHP->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_NomorHP"><?= $Page->NomorHP->caption() ?></span></td>
        <td data-name="NomorHP"<?= $Page->NomorHP->cellAttributes() ?>>
<span id="el_fans_NomorHP">
<span<?= $Page->NomorHP->viewAttributes() ?>>
<?= $Page->NomorHP->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
    <tr id="r_TahunKelahiran"<?= $Page->TahunKelahiran->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_TahunKelahiran"><?= $Page->TahunKelahiran->caption() ?></span></td>
        <td data-name="TahunKelahiran"<?= $Page->TahunKelahiran->cellAttributes() ?>>
<span id="el_fans_TahunKelahiran">
<span<?= $Page->TahunKelahiran->viewAttributes() ?>>
<?= $Page->TahunKelahiran->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
    <tr id="r_Kota"<?= $Page->Kota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Kota"><?= $Page->Kota->caption() ?></span></td>
        <td data-name="Kota"<?= $Page->Kota->cellAttributes() ?>>
<span id="el_fans_Kota">
<span<?= $Page->Kota->viewAttributes() ?>>
<?= $Page->Kota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
    <tr id="r_Profesi"<?= $Page->Profesi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Profesi"><?= $Page->Profesi->caption() ?></span></td>
        <td data-name="Profesi"<?= $Page->Profesi->cellAttributes() ?>>
<span id="el_fans_Profesi">
<span<?= $Page->Profesi->viewAttributes() ?>>
<?= $Page->Profesi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
    <tr id="r_Hobi"<?= $Page->Hobi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Hobi"><?= $Page->Hobi->caption() ?></span></td>
        <td data-name="Hobi"<?= $Page->Hobi->cellAttributes() ?>>
<span id="el_fans_Hobi">
<span<?= $Page->Hobi->viewAttributes() ?>>
<?= $Page->Hobi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
    <tr id="r_AcaraID"<?= $Page->AcaraID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_AcaraID"><?= $Page->AcaraID->caption() ?></span></td>
        <td data-name="AcaraID"<?= $Page->AcaraID->cellAttributes() ?>>
<span id="el_fans_AcaraID">
<span<?= $Page->AcaraID->viewAttributes() ?>>
<?= $Page->AcaraID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
    <tr id="r_RadioID"<?= $Page->RadioID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_RadioID"><?= $Page->RadioID->caption() ?></span></td>
        <td data-name="RadioID"<?= $Page->RadioID->cellAttributes() ?>>
<span id="el_fans_RadioID">
<span<?= $Page->RadioID->viewAttributes() ?>>
<?= $Page->RadioID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
    <tr id="r_Keterangan"<?= $Page->Keterangan->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_fans_Keterangan"><?= $Page->Keterangan->caption() ?></span></td>
        <td data-name="Keterangan"<?= $Page->Keterangan->cellAttributes() ?>>
<span id="el_fans_Keterangan">
<span<?= $Page->Keterangan->viewAttributes() ?>>
<?= $Page->Keterangan->getViewValue() ?></span>
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
