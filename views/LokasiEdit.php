<?php

namespace PHPMaker2024\prj_fans;

// Page object
$LokasiEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="flokasiedit" id="flokasiedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { lokasi: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var flokasiedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("flokasiedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["LokasiID", [fields.LokasiID.visible && fields.LokasiID.required ? ew.Validators.required(fields.LokasiID.caption) : null], fields.LokasiID.isInvalid],
            ["Nama", [fields.Nama.visible && fields.Nama.required ? ew.Validators.required(fields.Nama.caption) : null], fields.Nama.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
        })
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="lokasi">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->LokasiID->Visible) { // LokasiID ?>
    <div id="r_LokasiID"<?= $Page->LokasiID->rowAttributes() ?>>
        <label id="elh_lokasi_LokasiID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->LokasiID->caption() ?><?= $Page->LokasiID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->LokasiID->cellAttributes() ?>>
<span id="el_lokasi_LokasiID">
<span<?= $Page->LokasiID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->LokasiID->getDisplayValue($Page->LokasiID->EditValue))) ?>"></span>
<input type="hidden" data-table="lokasi" data-field="x_LokasiID" data-hidden="1" name="x_LokasiID" id="x_LokasiID" value="<?= HtmlEncode($Page->LokasiID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
    <div id="r_Nama"<?= $Page->Nama->rowAttributes() ?>>
        <label id="elh_lokasi_Nama" for="x_Nama" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nama->caption() ?><?= $Page->Nama->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nama->cellAttributes() ?>>
<span id="el_lokasi_Nama">
<input type="<?= $Page->Nama->getInputTextType() ?>" name="x_Nama" id="x_Nama" data-table="lokasi" data-field="x_Nama" value="<?= $Page->Nama->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Nama->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nama->formatPattern()) ?>"<?= $Page->Nama->editAttributes() ?> aria-describedby="x_Nama_help">
<?= $Page->Nama->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Nama->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="flokasiedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="flokasiedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("lokasi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
