<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="ffansedit" id="ffansedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ffansedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffansedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["FansID", [fields.FansID.visible && fields.FansID.required ? ew.Validators.required(fields.FansID.caption) : null], fields.FansID.isInvalid],
            ["Nama", [fields.Nama.visible && fields.Nama.required ? ew.Validators.required(fields.Nama.caption) : null], fields.Nama.isInvalid],
            ["Gender", [fields.Gender.visible && fields.Gender.required ? ew.Validators.required(fields.Gender.caption) : null], fields.Gender.isInvalid],
            ["NomorHP", [fields.NomorHP.visible && fields.NomorHP.required ? ew.Validators.required(fields.NomorHP.caption) : null], fields.NomorHP.isInvalid],
            ["TahunKelahiran", [fields.TahunKelahiran.visible && fields.TahunKelahiran.required ? ew.Validators.required(fields.TahunKelahiran.caption) : null], fields.TahunKelahiran.isInvalid],
            ["Kota", [fields.Kota.visible && fields.Kota.required ? ew.Validators.required(fields.Kota.caption) : null], fields.Kota.isInvalid],
            ["Profesi", [fields.Profesi.visible && fields.Profesi.required ? ew.Validators.required(fields.Profesi.caption) : null], fields.Profesi.isInvalid],
            ["Hobi", [fields.Hobi.visible && fields.Hobi.required ? ew.Validators.required(fields.Hobi.caption) : null], fields.Hobi.isInvalid],
            ["AcaraID", [fields.AcaraID.visible && fields.AcaraID.required ? ew.Validators.required(fields.AcaraID.caption) : null], fields.AcaraID.isInvalid],
            ["RadioID", [fields.RadioID.visible && fields.RadioID.required ? ew.Validators.required(fields.RadioID.caption) : null], fields.RadioID.isInvalid],
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
            "Gender": <?= $Page->Gender->toClientList($Page) ?>,
            "Kota": <?= $Page->Kota->toClientList($Page) ?>,
            "AcaraID": <?= $Page->AcaraID->toClientList($Page) ?>,
            "RadioID": <?= $Page->RadioID->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="fans">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->FansID->Visible) { // FansID ?>
    <div id="r_FansID"<?= $Page->FansID->rowAttributes() ?>>
        <label id="elh_fans_FansID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->FansID->caption() ?><?= $Page->FansID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->FansID->cellAttributes() ?>>
<span id="el_fans_FansID">
<span<?= $Page->FansID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->FansID->getDisplayValue($Page->FansID->EditValue))) ?>"></span>
<input type="hidden" data-table="fans" data-field="x_FansID" data-hidden="1" name="x_FansID" id="x_FansID" value="<?= HtmlEncode($Page->FansID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
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
        <label id="elh_fans_Gender" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Gender->caption() ?><?= $Page->Gender->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Gender->cellAttributes() ?>>
<span id="el_fans_Gender">
<template id="tp_x_Gender">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="fans" data-field="x_Gender" name="x_Gender" id="x_Gender"<?= $Page->Gender->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_Gender" class="ew-item-list"></div>
<selection-list hidden
    id="x_Gender"
    name="x_Gender"
    value="<?= HtmlEncode($Page->Gender->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_Gender"
    data-target="dsl_x_Gender"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Gender->isInvalidClass() ?>"
    data-table="fans"
    data-field="x_Gender"
    data-value-separator="<?= $Page->Gender->displayValueSeparatorAttribute() ?>"
    <?= $Page->Gender->editAttributes() ?>></selection-list>
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
<input type="<?= $Page->NomorHP->getInputTextType() ?>" name="x_NomorHP" id="x_NomorHP" data-table="fans" data-field="x_NomorHP" value="<?= $Page->NomorHP->EditValue ?>" size="15" maxlength="255" placeholder="<?= HtmlEncode($Page->NomorHP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomorHP->formatPattern()) ?>"<?= $Page->NomorHP->editAttributes() ?> aria-describedby="x_NomorHP_help">
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
<input type="<?= $Page->TahunKelahiran->getInputTextType() ?>" name="x_TahunKelahiran" id="x_TahunKelahiran" data-table="fans" data-field="x_TahunKelahiran" value="<?= $Page->TahunKelahiran->EditValue ?>" size="4" maxlength="4" placeholder="<?= HtmlEncode($Page->TahunKelahiran->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TahunKelahiran->formatPattern()) ?>"<?= $Page->TahunKelahiran->editAttributes() ?> aria-describedby="x_TahunKelahiran_help">
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
    <select
        id="x_Kota"
        name="x_Kota"
        class="form-control ew-select<?= $Page->Kota->isInvalidClass() ?>"
        data-select2-id="ffansedit_x_Kota"
        data-table="fans"
        data-field="x_Kota"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->Kota->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->Kota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Kota->getPlaceHolder()) ?>"
        <?= $Page->Kota->editAttributes() ?>>
        <?= $Page->Kota->selectOptionListHtml("x_Kota") ?>
    </select>
    <?= $Page->Kota->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Kota->getErrorMessage() ?></div>
<?= $Page->Kota->Lookup->getParamTag($Page, "p_x_Kota") ?>
<script>
loadjs.ready("ffansedit", function() {
    var options = { name: "x_Kota", selectId: "ffansedit_x_Kota" };
    if (ffansedit.lists.Kota?.lookupOptions.length) {
        options.data = { id: "x_Kota", form: "ffansedit" };
    } else {
        options.ajax = { id: "x_Kota", form: "ffansedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.Kota.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
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
    <select
        id="x_AcaraID"
        name="x_AcaraID"
        class="form-control ew-select<?= $Page->AcaraID->isInvalidClass() ?>"
        data-select2-id="ffansedit_x_AcaraID"
        data-table="fans"
        data-field="x_AcaraID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->AcaraID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->AcaraID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->AcaraID->getPlaceHolder()) ?>"
        <?= $Page->AcaraID->editAttributes() ?>>
        <?= $Page->AcaraID->selectOptionListHtml("x_AcaraID") ?>
    </select>
    <?= $Page->AcaraID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->AcaraID->getErrorMessage() ?></div>
<?= $Page->AcaraID->Lookup->getParamTag($Page, "p_x_AcaraID") ?>
<script>
loadjs.ready("ffansedit", function() {
    var options = { name: "x_AcaraID", selectId: "ffansedit_x_AcaraID" };
    if (ffansedit.lists.AcaraID?.lookupOptions.length) {
        options.data = { id: "x_AcaraID", form: "ffansedit" };
    } else {
        options.ajax = { id: "x_AcaraID", form: "ffansedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.AcaraID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
    <div id="r_RadioID"<?= $Page->RadioID->rowAttributes() ?>>
        <label id="elh_fans_RadioID" for="x_RadioID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->RadioID->caption() ?><?= $Page->RadioID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->RadioID->cellAttributes() ?>>
<span id="el_fans_RadioID">
    <select
        id="x_RadioID"
        name="x_RadioID"
        class="form-control ew-select<?= $Page->RadioID->isInvalidClass() ?>"
        data-select2-id="ffansedit_x_RadioID"
        data-table="fans"
        data-field="x_RadioID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->RadioID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->RadioID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->RadioID->getPlaceHolder()) ?>"
        <?= $Page->RadioID->editAttributes() ?>>
        <?= $Page->RadioID->selectOptionListHtml("x_RadioID") ?>
    </select>
    <?= $Page->RadioID->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->RadioID->getErrorMessage() ?></div>
<?= $Page->RadioID->Lookup->getParamTag($Page, "p_x_RadioID") ?>
<script>
loadjs.ready("ffansedit", function() {
    var options = { name: "x_RadioID", selectId: "ffansedit_x_RadioID" };
    if (ffansedit.lists.RadioID?.lookupOptions.length) {
        options.data = { id: "x_RadioID", form: "ffansedit" };
    } else {
        options.ajax = { id: "x_RadioID", form: "ffansedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.RadioID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
    <div id="r_Keterangan"<?= $Page->Keterangan->rowAttributes() ?>>
        <label id="elh_fans_Keterangan" for="x_Keterangan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Keterangan->caption() ?><?= $Page->Keterangan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Keterangan->cellAttributes() ?>>
<span id="el_fans_Keterangan">
<textarea data-table="fans" data-field="x_Keterangan" name="x_Keterangan" id="x_Keterangan" cols="35" rows="2" placeholder="<?= HtmlEncode($Page->Keterangan->getPlaceHolder()) ?>"<?= $Page->Keterangan->editAttributes() ?> aria-describedby="x_Keterangan_help"><?= $Page->Keterangan->EditValue ?></textarea>
<?= $Page->Keterangan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Keterangan->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffansedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffansedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("fans");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
