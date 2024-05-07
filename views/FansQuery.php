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
        .setQueryBuilderLists({
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
<form name="ffanssearch" id="ffanssearch" class="<?= $Page->FormClassName ?>" action="<?= HtmlEncode(GetUrl("fanslist")) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="fans">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<input type="hidden" name="rules" value="<?= HtmlEncode($Page->getSessionRules()) ?>">
<template id="tpx_fans_FansID" class="fanssearch"><span id="el_fans_FansID" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->FansID->getInputTextType() ?>" name="x_FansID" id="x_FansID" data-table="fans" data-field="x_FansID" value="<?= $Page->FansID->EditValue ?>" placeholder="<?= HtmlEncode($Page->FansID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->FansID->formatPattern()) ?>"<?= $Page->FansID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->FansID->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_Nama" class="fanssearch"><span id="el_fans_Nama" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Nama->getInputTextType() ?>" name="x_Nama" id="x_Nama" data-table="fans" data-field="x_Nama" value="<?= $Page->Nama->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Nama->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Nama->formatPattern()) ?>"<?= $Page->Nama->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Nama->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_Gender" class="fanssearch"><span id="el_fans_Gender" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_fans_NomorHP" class="fanssearch"><span id="el_fans_NomorHP" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->NomorHP->getInputTextType() ?>" name="x_NomorHP" id="x_NomorHP" data-table="fans" data-field="x_NomorHP" value="<?= $Page->NomorHP->EditValue ?>" size="15" maxlength="255" placeholder="<?= HtmlEncode($Page->NomorHP->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->NomorHP->formatPattern()) ?>"<?= $Page->NomorHP->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->NomorHP->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_TahunKelahiran" class="fanssearch"><span id="el_fans_TahunKelahiran" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->TahunKelahiran->getInputTextType() ?>" name="x_TahunKelahiran" id="x_TahunKelahiran" data-table="fans" data-field="x_TahunKelahiran" value="<?= $Page->TahunKelahiran->EditValue ?>" size="4" maxlength="4" placeholder="<?= HtmlEncode($Page->TahunKelahiran->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->TahunKelahiran->formatPattern()) ?>"<?= $Page->TahunKelahiran->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->TahunKelahiran->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_Kota" class="fanssearch"><span id="el_fans_Kota" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_fans_Profesi" class="fanssearch"><span id="el_fans_Profesi" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Profesi->getInputTextType() ?>" name="x_Profesi" id="x_Profesi" data-table="fans" data-field="x_Profesi" value="<?= $Page->Profesi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Profesi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Profesi->formatPattern()) ?>"<?= $Page->Profesi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Profesi->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_Hobi" class="fanssearch"><span id="el_fans_Hobi" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Hobi->getInputTextType() ?>" name="x_Hobi" id="x_Hobi" data-table="fans" data-field="x_Hobi" value="<?= $Page->Hobi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->Hobi->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Hobi->formatPattern()) ?>"<?= $Page->Hobi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Hobi->getErrorMessage(false) ?></div>
</span></template>
<template id="tpx_fans_AcaraID" class="fanssearch"><span id="el_fans_AcaraID" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_fans_RadioID" class="fanssearch"><span id="el_fans_RadioID" class="ew-search-field ew-search-field-single">
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
</span></template>
<template id="tpx_fans_Keterangan" class="fanssearch"><span id="el_fans_Keterangan" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->Keterangan->getInputTextType() ?>" name="x_Keterangan" id="x_Keterangan" data-table="fans" data-field="x_Keterangan" value="<?= $Page->Keterangan->EditValue ?>" maxlength="65535" placeholder="<?= HtmlEncode($Page->Keterangan->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Keterangan->formatPattern()) ?>"<?= $Page->Keterangan->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Keterangan->getErrorMessage(false) ?></div>
</span></template>
<div id="fans_query_builder" class="query-builder mb-3"></div>
<div class="btn-group mb-3 query-btn-group"></div>
<button type="button" id="btn-view-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("View", true)) ?>"><i class="fa-solid fa-eye ew-icon"></i></button>
<button type="button" id="btn-clear-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("Clear", true)) ?>"><i class="fa-solid fa-xmark ew-icon"></i></button>
<script>
// Filter builder
loadjs.ready(["wrapper", "head"], () => {
    let filters = [
            {
                id: "FansID",
                type: "integer",
                label: currentTable.fields.FansID.caption,
                operators: currentTable.fields.FansID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.FansID.validators),
                data: {
                    format: currentTable.fields.FansID.clientFormatPattern
                }
            },
            {
                id: "Nama",
                type: "string",
                label: currentTable.fields.Nama.caption,
                operators: currentTable.fields.Nama.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Nama.validators),
                data: {
                    format: currentTable.fields.Nama.clientFormatPattern
                }
            },
            {
                id: "Gender",
                type: "integer",
                label: currentTable.fields.Gender.caption,
                operators: currentTable.fields.Gender.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Gender.validators),
                data: {
                    format: currentTable.fields.Gender.clientFormatPattern
                }
            },
            {
                id: "NomorHP",
                type: "string",
                label: currentTable.fields.NomorHP.caption,
                operators: currentTable.fields.NomorHP.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.NomorHP.validators),
                data: {
                    format: currentTable.fields.NomorHP.clientFormatPattern
                }
            },
            {
                id: "TahunKelahiran",
                type: "string",
                label: currentTable.fields.TahunKelahiran.caption,
                operators: currentTable.fields.TahunKelahiran.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.TahunKelahiran.validators),
                data: {
                    format: currentTable.fields.TahunKelahiran.clientFormatPattern
                }
            },
            {
                id: "Kota",
                type: "integer",
                label: currentTable.fields.Kota.caption,
                operators: currentTable.fields.Kota.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Kota.validators),
                data: {
                    format: currentTable.fields.Kota.clientFormatPattern
                }
            },
            {
                id: "Profesi",
                type: "string",
                label: currentTable.fields.Profesi.caption,
                operators: currentTable.fields.Profesi.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Profesi.validators),
                data: {
                    format: currentTable.fields.Profesi.clientFormatPattern
                }
            },
            {
                id: "Hobi",
                type: "string",
                label: currentTable.fields.Hobi.caption,
                operators: currentTable.fields.Hobi.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Hobi.validators),
                data: {
                    format: currentTable.fields.Hobi.clientFormatPattern
                }
            },
            {
                id: "AcaraID",
                type: "integer",
                label: currentTable.fields.AcaraID.caption,
                operators: currentTable.fields.AcaraID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.AcaraID.validators),
                data: {
                    format: currentTable.fields.AcaraID.clientFormatPattern
                }
            },
            {
                id: "RadioID",
                type: "integer",
                label: currentTable.fields.RadioID.caption,
                operators: currentTable.fields.RadioID.clientSearchOperators,
                default_operator: "equal",
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.RadioID.validators),
                data: {
                    format: currentTable.fields.RadioID.clientFormatPattern
                }
            },
            {
                id: "Keterangan",
                type: "string",
                label: currentTable.fields.Keterangan.caption,
                operators: currentTable.fields.Keterangan.clientSearchOperators,
                default_operator: "contains",
                input: ew.getQueryBuilderFilterInput(),
                value_separator: ew.IN_OPERATOR_VALUE_SEPARATOR,
                validation: ew.getQueryBuilderFilterValidation(ffanssearch.fields.Keterangan.validators),
                data: {
                    format: currentTable.fields.Keterangan.clientFormatPattern
                }
            },
        ],
        $ = jQuery,
        $qb = $("#fans_query_builder"),
        args = {},
        rules = ew.parseJson($("#ffanssearch input[name=rules]").val()),
        queryBuilderOptions = Object.assign({}, ew.queryBuilderOptions),
        allowViewRules = queryBuilderOptions.allowViewRules,
        allowClearRules = queryBuilderOptions.allowClearRules,
        hasRules = group => Array.isArray(group?.rules) && group.rules.length > 0,
        getRules = () => $qb.queryBuilder("getRules", { skip_empty: true }),
        getSql = () => $qb.queryBuilder("getSQL", false, false, rules)?.sql;
    delete queryBuilderOptions.allowViewRules;
    delete queryBuilderOptions.allowClearRules;
    args.options = ew.deepAssign({
        plugins: Object.assign({}, ew.queryBuilderPlugins),
        lang: ew.language.phrase("querybuilderjs"),
        select_placeholder: ew.language.phrase("PleaseSelect"),
        inputs_separator: `<div class="d-inline-flex ms-2 me-2">${ew.language.phrase("AND")}</div>`, // For "between"
        filters,
        rules
    }, queryBuilderOptions);
    $qb.trigger("querybuilder", [args]);
    $qb.queryBuilder(args.options).on("rulesChanged.queryBuilder", () => {
        let rules = getRules();
        !ew.DEBUG || console.log(rules, getSql());
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").toggleClass("disabled", !rules);
    }).on("afterCreateRuleInput.queryBuilder", function(e, rule) {
        let select = rule.$el.find(".rule-value-container").find("selection-list, select")[0];
        if (select) { // Selection list
            let id = select.dataset.field.replace("^x_", ""),
                form = ew.forms.get(select);
            form.updateList(select, undefined, undefined, true); // Update immediately
        }
    });
    $("#ffanssearch").on("beforesubmit", function () {
        this.rules.value = JSON.stringify(getRules());
    });
    $("#btn-reset").toggleClass("d-none", false).on("click", () => {
        hasRules(rules) ? $qb.queryBuilder("setRules", rules) : $qb.queryBuilder("reset");
        return false;
    });
    $("#btn-action").toggleClass("d-none", false);
    if (allowClearRules) {
        $("#btn-clear-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => $qb.queryBuilder("reset"));
    }
    if (allowViewRules) {
        $("#btn-view-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => {
            let rules = getRules();
            if (hasRules(rules)) {
                let sql = getSql();
                ew.alert(sql ? '<pre class="text-start fs-6">' + sql + '</pre>' : '', "dark");
                !ew.DEBUG || console.log(rules, sql);
            } else {
                ew.alert(ew.language.phrase("EmptyLabel"));
            }
        });
    }
    $(".query-btn-group").toggleClass(".mb-3", $(".query-btn-group").find(".btn:not(.d-none)").length);
    if (hasRules(rules)) { // Enable buttons if rules exist initially
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").removeClass("disabled");
    }
});
</script>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn d-none disabled" name="btn-action" id="btn-action" type="submit" form="ffanssearch" formaction="<?= HtmlEncode(GetUrl("fanslist")) ?>" data-ajax="<?= $Page->UseAjaxActions ? "true" : "false" ?>"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffanssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn d-none disabled" name="btn-reset" id="btn-reset" type="button" form="ffanssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
