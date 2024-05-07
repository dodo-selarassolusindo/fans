<?php

namespace PHPMaker2024\prj_fans;

// Page object
$FansSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { fans: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var ffanssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("ffanssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["FansID", [ew.Validators.integer], fields.FansID.isInvalid],
            ["Nama", [], fields.Nama.isInvalid],
            ["Gender", [], fields.Gender.isInvalid],
            ["NomorHP", [], fields.NomorHP.isInvalid],
            ["TahunKelahiran", [], fields.TahunKelahiran.isInvalid],
            ["Kota", [], fields.Kota.isInvalid],
            ["Profesi", [], fields.Profesi.isInvalid],
            ["Hobi", [], fields.Hobi.isInvalid],
            ["AcaraID", [], fields.AcaraID.isInvalid],
            ["RadioID", [], fields.RadioID.isInvalid],
            ["Keterangan", [], fields.Keterangan.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

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
<?php if ($Page->IsModal) { ?>
    currentAdvancedSearchForm = form;
<?php } else { ?>
    currentForm = form;
<?php } ?>
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
<form name="ffanssearch" id="ffanssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="fans">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->FansID->Visible) { // FansID ?>
    <div id="r_FansID" class="row"<?= $Page->FansID->rowAttributes() ?>>
        <label for="x_FansID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_FansID"><?= $Page->FansID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_FansID" id="z_FansID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->FansID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_FansID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->FansID->getInputTextType() ?>" name="x_FansID" id="x_FansID" data-table="fans" data-field="x_FansID" value="<?= $Page->FansID->EditValue ?>" placeholder="<?= HtmlEncode($Page->FansID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->FansID->formatPattern()) ?>"<?= $Page->FansID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->FansID->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Nama->Visible) { // Nama ?>
    <div id="r_Nama" class="row"<?= $Page->Nama->rowAttributes() ?>>
        <label for="x_Nama" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Nama"><?= $Page->Nama->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Nama" id="z_Nama" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Nama->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Nama" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Nama->getInputTextType() ?>" name="x_Nama" id="x_Nama" data-table="fans" data-field="x_Nama" value="<?= $Page->Nama->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Nama->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nama->formatPattern()) ?>"<?= $Page->Nama->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Nama->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Gender->Visible) { // Gender ?>
    <div id="r_Gender" class="row"<?= $Page->Gender->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Gender"><?= $Page->Gender->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Gender" id="z_Gender" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Gender->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Gender" class="ew-search-field ew-search-field-single">
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
    value="<?= HtmlEncode($Page->Gender->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_Gender"
    data-target="dsl_x_Gender"
    data-repeatcolumn="5"
    class="form-control<?= $Page->Gender->isInvalidClass() ?>"
    data-table="fans"
    data-field="x_Gender"
    data-value-separator="<?= $Page->Gender->displayValueSeparatorAttribute() ?>"
    <?= $Page->Gender->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->Gender->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->NomorHP->Visible) { // NomorHP ?>
    <div id="r_NomorHP" class="row"<?= $Page->NomorHP->rowAttributes() ?>>
        <label for="x_NomorHP" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_NomorHP"><?= $Page->NomorHP->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_NomorHP" id="z_NomorHP" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->NomorHP->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_NomorHP" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->NomorHP->getInputTextType() ?>" name="x_NomorHP" id="x_NomorHP" data-table="fans" data-field="x_NomorHP" value="<?= $Page->NomorHP->EditValue ?>" size="15" maxlength="255" placeholder="<?= HtmlEncode($Page->NomorHP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomorHP->formatPattern()) ?>"<?= $Page->NomorHP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->NomorHP->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->TahunKelahiran->Visible) { // TahunKelahiran ?>
    <div id="r_TahunKelahiran" class="row"<?= $Page->TahunKelahiran->rowAttributes() ?>>
        <label for="x_TahunKelahiran" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_TahunKelahiran"><?= $Page->TahunKelahiran->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_TahunKelahiran" id="z_TahunKelahiran" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->TahunKelahiran->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_TahunKelahiran" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->TahunKelahiran->getInputTextType() ?>" name="x_TahunKelahiran" id="x_TahunKelahiran" data-table="fans" data-field="x_TahunKelahiran" value="<?= $Page->TahunKelahiran->EditValue ?>" size="4" maxlength="4" placeholder="<?= HtmlEncode($Page->TahunKelahiran->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TahunKelahiran->formatPattern()) ?>"<?= $Page->TahunKelahiran->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->TahunKelahiran->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Kota->Visible) { // Kota ?>
    <div id="r_Kota" class="row"<?= $Page->Kota->rowAttributes() ?>>
        <label for="x_Kota" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Kota"><?= $Page->Kota->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_Kota" id="z_Kota" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Kota->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Kota" class="ew-search-field ew-search-field-single">
    <select
        id="x_Kota"
        name="x_Kota"
        class="form-control ew-select<?= $Page->Kota->isInvalidClass() ?>"
        data-select2-id="ffanssearch_x_Kota"
        data-table="fans"
        data-field="x_Kota"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->Kota->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->Kota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Kota->getPlaceHolder()) ?>"
        <?= $Page->Kota->editAttributes() ?>>
        <?= $Page->Kota->selectOptionListHtml("x_Kota") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->Kota->getErrorMessage(false) ?></div>
<?= $Page->Kota->Lookup->getParamTag($Page, "p_x_Kota") ?>
<script>
loadjs.ready("ffanssearch", function() {
    var options = { name: "x_Kota", selectId: "ffanssearch_x_Kota" };
    if (ffanssearch.lists.Kota?.lookupOptions.length) {
        options.data = { id: "x_Kota", form: "ffanssearch" };
    } else {
        options.ajax = { id: "x_Kota", form: "ffanssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.Kota.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Profesi->Visible) { // Profesi ?>
    <div id="r_Profesi" class="row"<?= $Page->Profesi->rowAttributes() ?>>
        <label for="x_Profesi" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Profesi"><?= $Page->Profesi->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Profesi" id="z_Profesi" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Profesi->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Profesi" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Profesi->getInputTextType() ?>" name="x_Profesi" id="x_Profesi" data-table="fans" data-field="x_Profesi" value="<?= $Page->Profesi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Profesi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Profesi->formatPattern()) ?>"<?= $Page->Profesi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Profesi->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Hobi->Visible) { // Hobi ?>
    <div id="r_Hobi" class="row"<?= $Page->Hobi->rowAttributes() ?>>
        <label for="x_Hobi" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Hobi"><?= $Page->Hobi->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Hobi" id="z_Hobi" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Hobi->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Hobi" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Hobi->getInputTextType() ?>" name="x_Hobi" id="x_Hobi" data-table="fans" data-field="x_Hobi" value="<?= $Page->Hobi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Hobi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Hobi->formatPattern()) ?>"<?= $Page->Hobi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Hobi->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->AcaraID->Visible) { // AcaraID ?>
    <div id="r_AcaraID" class="row"<?= $Page->AcaraID->rowAttributes() ?>>
        <label for="x_AcaraID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_AcaraID"><?= $Page->AcaraID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_AcaraID" id="z_AcaraID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->AcaraID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_AcaraID" class="ew-search-field ew-search-field-single">
    <select
        id="x_AcaraID"
        name="x_AcaraID"
        class="form-control ew-select<?= $Page->AcaraID->isInvalidClass() ?>"
        data-select2-id="ffanssearch_x_AcaraID"
        data-table="fans"
        data-field="x_AcaraID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->AcaraID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->AcaraID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->AcaraID->getPlaceHolder()) ?>"
        <?= $Page->AcaraID->editAttributes() ?>>
        <?= $Page->AcaraID->selectOptionListHtml("x_AcaraID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->AcaraID->getErrorMessage(false) ?></div>
<?= $Page->AcaraID->Lookup->getParamTag($Page, "p_x_AcaraID") ?>
<script>
loadjs.ready("ffanssearch", function() {
    var options = { name: "x_AcaraID", selectId: "ffanssearch_x_AcaraID" };
    if (ffanssearch.lists.AcaraID?.lookupOptions.length) {
        options.data = { id: "x_AcaraID", form: "ffanssearch" };
    } else {
        options.ajax = { id: "x_AcaraID", form: "ffanssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.AcaraID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->RadioID->Visible) { // RadioID ?>
    <div id="r_RadioID" class="row"<?= $Page->RadioID->rowAttributes() ?>>
        <label for="x_RadioID" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_RadioID"><?= $Page->RadioID->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_RadioID" id="z_RadioID" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->RadioID->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_RadioID" class="ew-search-field ew-search-field-single">
    <select
        id="x_RadioID"
        name="x_RadioID"
        class="form-control ew-select<?= $Page->RadioID->isInvalidClass() ?>"
        data-select2-id="ffanssearch_x_RadioID"
        data-table="fans"
        data-field="x_RadioID"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->RadioID->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->RadioID->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->RadioID->getPlaceHolder()) ?>"
        <?= $Page->RadioID->editAttributes() ?>>
        <?= $Page->RadioID->selectOptionListHtml("x_RadioID") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->RadioID->getErrorMessage(false) ?></div>
<?= $Page->RadioID->Lookup->getParamTag($Page, "p_x_RadioID") ?>
<script>
loadjs.ready("ffanssearch", function() {
    var options = { name: "x_RadioID", selectId: "ffanssearch_x_RadioID" };
    if (ffanssearch.lists.RadioID?.lookupOptions.length) {
        options.data = { id: "x_RadioID", form: "ffanssearch" };
    } else {
        options.ajax = { id: "x_RadioID", form: "ffanssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.fans.fields.RadioID.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->Keterangan->Visible) { // Keterangan ?>
    <div id="r_Keterangan" class="row"<?= $Page->Keterangan->rowAttributes() ?>>
        <label for="x_Keterangan" class="<?= $Page->LeftColumnClass ?>"><span id="elh_fans_Keterangan"><?= $Page->Keterangan->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Keterangan" id="z_Keterangan" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->Keterangan->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_fans_Keterangan" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Keterangan->getInputTextType() ?>" name="x_Keterangan" id="x_Keterangan" data-table="fans" data-field="x_Keterangan" value="<?= $Page->Keterangan->EditValue ?>" maxlength="65535" placeholder="<?= HtmlEncode($Page->Keterangan->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Keterangan->formatPattern()) ?>"<?= $Page->Keterangan->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Keterangan->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffanssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffanssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="ffanssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
