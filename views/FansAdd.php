<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ffansadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffansadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["Nama", [fields.Nama.visible && fields.Nama.required ? ew.Validators.required(fields.Nama.caption) : null], fields.Nama.isInvalid],
            ["Gender", [fields.Gender.visible && fields.Gender.required ? ew.Validators.required(fields.Gender.caption) : null, ew.Validators.integer], fields.Gender.isInvalid],
            ["NomorHP", [fields.NomorHP.visible && fields.NomorHP.required ? ew.Validators.required(fields.NomorHP.caption) : null], fields.NomorHP.isInvalid],
            ["TahunKelahiran", [fields.TahunKelahiran.visible && fields.TahunKelahiran.required ? ew.Validators.required(fields.TahunKelahiran.caption) : null], fields.TahunKelahiran.isInvalid],
            ["Kota", [fields.Kota.visible && fields.Kota.required ? ew.Validators.required(fields.Kota.caption) : null, ew.Validators.integer], fields.Kota.isInvalid],
            ["Profesi", [fields.Profesi.visible && fields.Profesi.required ? ew.Validators.required(fields.Profesi.caption) : null], fields.Profesi.isInvalid],
            ["Hobi", [fields.Hobi.visible && fields.Hobi.required ? ew.Validators.required(fields.Hobi.caption) : null], fields.Hobi.isInvalid],
            ["AcaraID", [fields.AcaraID.visible && fields.AcaraID.required ? ew.Validators.required(fields.AcaraID.caption) : null, ew.Validators.integer], fields.AcaraID.isInvalid],
            ["RadioID", [fields.RadioID.visible && fields.RadioID.required ? ew.Validators.required(fields.RadioID.caption) : null, ew.Validators.integer], fields.RadioID.isInvalid],
            ["Keterangan", [fields.Keterangan.visible && fields.Keterangan.required ? ew.Validators.required(fields.Keterangan.caption) : null], fields.Keterangan.isInvalid]
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ffansadd" id="ffansadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="fans">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->Nama->Visible) { // Nama ?>
    <div id="r_Nama"<?= $Page->Nama->rowAttributes() ?>>
        <label id="elh_fans_Nama" for="x_Nama" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Nama->caption() ?><?= $Page->Nama->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Nama->cellAttributes() ?>>
<span id="el_fans_Nama">
<input type="<?= $Page->Nama->getInputTextType() ?>" name="x_Nama" id="x_Nama" data-table="fans" data-field="x_Nama" value="<?= $Page->Nama->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Nama->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nama->formatPattern()) ?>"<?= $Page->Nama->editAttributes() ?> aria-describedby="x_Nama_help">
<?= $Page->Nama->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Nama->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
    <div id="r_Gender"<?= $Page->Gender->rowAttributes() ?>>
        <label id="elh_fans_Gender" for="x_Gender" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Gender->caption() ?><?= $Page->Gender->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Gender->cellAttributes() ?>>
<span id="el_fans_Gender">
<input type="<?= $Page->Gender->getInputTextType() ?>" name="x_Gender" id="x_Gender" data-table="fans" data-field="x_Gender" value="<?= $Page->Gender->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Gender->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Gender->formatPattern()) ?>"<?= $Page->Gender->editAttributes() ?> aria-describedby="x_Gender_help">
<?= $Page->Gender->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Gender->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
    <div id="r_NomorHP"<?= $Page->NomorHP->rowAttributes() ?>>
        <label id="elh_fans_NomorHP" for="x_NomorHP" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NomorHP->caption() ?><?= $Page->NomorHP->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NomorHP->cellAttributes() ?>>
<span id="el_fans_NomorHP">
<input type="<?= $Page->NomorHP->getInputTextType() ?>" name="x_NomorHP" id="x_NomorHP" data-table="fans" data-field="x_NomorHP" value="<?= $Page->NomorHP->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->NomorHP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomorHP->formatPattern()) ?>"<?= $Page->NomorHP->editAttributes() ?> aria-describedby="x_NomorHP_help">
<?= $Page->NomorHP->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NomorHP->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
    <div id="r_TahunKelahiran"<?= $Page->TahunKelahiran->rowAttributes() ?>>
        <label id="elh_fans_TahunKelahiran" for="x_TahunKelahiran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->TahunKelahiran->caption() ?><?= $Page->TahunKelahiran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->TahunKelahiran->cellAttributes() ?>>
<span id="el_fans_TahunKelahiran">
<input type="<?= $Page->TahunKelahiran->getInputTextType() ?>" name="x_TahunKelahiran" id="x_TahunKelahiran" data-table="fans" data-field="x_TahunKelahiran" value="<?= $Page->TahunKelahiran->EditValue ?>" size="30" maxlength="4" placeholder="<?= HtmlEncode($Page->TahunKelahiran->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TahunKelahiran->formatPattern()) ?>"<?= $Page->TahunKelahiran->editAttributes() ?> aria-describedby="x_TahunKelahiran_help">
<?= $Page->TahunKelahiran->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->TahunKelahiran->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
    <div id="r_Kota"<?= $Page->Kota->rowAttributes() ?>>
        <label id="elh_fans_Kota" for="x_Kota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Kota->caption() ?><?= $Page->Kota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Kota->cellAttributes() ?>>
<span id="el_fans_Kota">
<input type="<?= $Page->Kota->getInputTextType() ?>" name="x_Kota" id="x_Kota" data-table="fans" data-field="x_Kota" value="<?= $Page->Kota->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->Kota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Kota->formatPattern()) ?>"<?= $Page->Kota->editAttributes() ?> aria-describedby="x_Kota_help">
<?= $Page->Kota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Kota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
    <div id="r_Profesi"<?= $Page->Profesi->rowAttributes() ?>>
        <label id="elh_fans_Profesi" for="x_Profesi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Profesi->caption() ?><?= $Page->Profesi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Profesi->cellAttributes() ?>>
<span id="el_fans_Profesi">
<input type="<?= $Page->Profesi->getInputTextType() ?>" name="x_Profesi" id="x_Profesi" data-table="fans" data-field="x_Profesi" value="<?= $Page->Profesi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Profesi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Profesi->formatPattern()) ?>"<?= $Page->Profesi->editAttributes() ?> aria-describedby="x_Profesi_help">
<?= $Page->Profesi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Profesi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
    <div id="r_Hobi"<?= $Page->Hobi->rowAttributes() ?>>
        <label id="elh_fans_Hobi" for="x_Hobi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Hobi->caption() ?><?= $Page->Hobi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Hobi->cellAttributes() ?>>
<span id="el_fans_Hobi">
<input type="<?= $Page->Hobi->getInputTextType() ?>" name="x_Hobi" id="x_Hobi" data-table="fans" data-field="x_Hobi" value="<?= $Page->Hobi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Hobi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Hobi->formatPattern()) ?>"<?= $Page->Hobi->editAttributes() ?> aria-describedby="x_Hobi_help">
<?= $Page->Hobi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Hobi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
    <div id="r_AcaraID"<?= $Page->AcaraID->rowAttributes() ?>>
        <label id="elh_fans_AcaraID" for="x_AcaraID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->AcaraID->caption() ?><?= $Page->AcaraID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->AcaraID->cellAttributes() ?>>
<span id="el_fans_AcaraID">
<input type="<?= $Page->AcaraID->getInputTextType() ?>" name="x_AcaraID" id="x_AcaraID" data-table="fans" data-field="x_AcaraID" value="<?= $Page->AcaraID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->AcaraID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->AcaraID->formatPattern()) ?>"<?= $Page->AcaraID->editAttributes() ?> aria-describedby="x_AcaraID_help">
<?= $Page->AcaraID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->AcaraID->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
    <div id="r_RadioID"<?= $Page->RadioID->rowAttributes() ?>>
        <label id="elh_fans_RadioID" for="x_RadioID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->RadioID->caption() ?><?= $Page->RadioID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->RadioID->cellAttributes() ?>>
<span id="el_fans_RadioID">
<input type="<?= $Page->RadioID->getInputTextType() ?>" name="x_RadioID" id="x_RadioID" data-table="fans" data-field="x_RadioID" value="<?= $Page->RadioID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->RadioID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->RadioID->formatPattern()) ?>"<?= $Page->RadioID->editAttributes() ?> aria-describedby="x_RadioID_help">
<?= $Page->RadioID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->RadioID->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
    <div id="r_Keterangan"<?= $Page->Keterangan->rowAttributes() ?>>
        <label id="elh_fans_Keterangan" for="x_Keterangan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Keterangan->caption() ?><?= $Page->Keterangan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Keterangan->cellAttributes() ?>>
<span id="el_fans_Keterangan">
<input type="<?= $Page->Keterangan->getInputTextType() ?>" name="x_Keterangan" id="x_Keterangan" data-table="fans" data-field="x_Keterangan" value="<?= $Page->Keterangan->EditValue ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->Keterangan->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Keterangan->formatPattern()) ?>"<?= $Page->Keterangan->editAttributes() ?> aria-describedby="x_Keterangan_help">
<?= $Page->Keterangan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Keterangan->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffansadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffansadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
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