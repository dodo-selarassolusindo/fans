<?php

namespace PHPMaker2024\prj_fans;

// Page object
$EmployeesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { employees: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var femployeesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("femployeesdelete")
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
<form name="femployeesdelete" id="femployeesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employees">
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
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <th class="<?= $Page->EmployeeID->headerCellClass() ?>"><span id="elh_employees_EmployeeID" class="employees_EmployeeID"><?= $Page->EmployeeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
        <th class="<?= $Page->LastName->headerCellClass() ?>"><span id="elh_employees_LastName" class="employees_LastName"><?= $Page->LastName->caption() ?></span></th>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
        <th class="<?= $Page->FirstName->headerCellClass() ?>"><span id="elh_employees_FirstName" class="employees_FirstName"><?= $Page->FirstName->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <th class="<?= $Page->_Title->headerCellClass() ?>"><span id="elh_employees__Title" class="employees__Title"><?= $Page->_Title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
        <th class="<?= $Page->TitleOfCourtesy->headerCellClass() ?>"><span id="elh_employees_TitleOfCourtesy" class="employees_TitleOfCourtesy"><?= $Page->TitleOfCourtesy->caption() ?></span></th>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
        <th class="<?= $Page->BirthDate->headerCellClass() ?>"><span id="elh_employees_BirthDate" class="employees_BirthDate"><?= $Page->BirthDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
        <th class="<?= $Page->HireDate->headerCellClass() ?>"><span id="elh_employees_HireDate" class="employees_HireDate"><?= $Page->HireDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
        <th class="<?= $Page->Address->headerCellClass() ?>"><span id="elh_employees_Address" class="employees_Address"><?= $Page->Address->caption() ?></span></th>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
        <th class="<?= $Page->City->headerCellClass() ?>"><span id="elh_employees_City" class="employees_City"><?= $Page->City->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
        <th class="<?= $Page->Region->headerCellClass() ?>"><span id="elh_employees_Region" class="employees_Region"><?= $Page->Region->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
        <th class="<?= $Page->PostalCode->headerCellClass() ?>"><span id="elh_employees_PostalCode" class="employees_PostalCode"><?= $Page->PostalCode->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
        <th class="<?= $Page->Country->headerCellClass() ?>"><span id="elh_employees_Country" class="employees_Country"><?= $Page->Country->caption() ?></span></th>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
        <th class="<?= $Page->HomePhone->headerCellClass() ?>"><span id="elh_employees_HomePhone" class="employees_HomePhone"><?= $Page->HomePhone->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
        <th class="<?= $Page->Extension->headerCellClass() ?>"><span id="elh_employees_Extension" class="employees_Extension"><?= $Page->Extension->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
        <th class="<?= $Page->Photo->headerCellClass() ?>"><span id="elh_employees_Photo" class="employees_Photo"><?= $Page->Photo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
        <th class="<?= $Page->ReportsTo->headerCellClass() ?>"><span id="elh_employees_ReportsTo" class="employees_ReportsTo"><?= $Page->ReportsTo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <th class="<?= $Page->_Username->headerCellClass() ?>"><span id="elh_employees__Username" class="employees__Username"><?= $Page->_Username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
        <th class="<?= $Page->_Password->headerCellClass() ?>"><span id="elh_employees__Password" class="employees__Password"><?= $Page->_Password->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <th class="<?= $Page->_Email->headerCellClass() ?>"><span id="elh_employees__Email" class="employees__Email"><?= $Page->_Email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <th class="<?= $Page->Activated->headerCellClass() ?>"><span id="elh_employees_Activated" class="employees_Activated"><?= $Page->Activated->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
        <th class="<?= $Page->_UserLevel->headerCellClass() ?>"><span id="elh_employees__UserLevel" class="employees__UserLevel"><?= $Page->_UserLevel->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Avatar->Visible) { // Avatar ?>
        <th class="<?= $Page->Avatar->headerCellClass() ?>"><span id="elh_employees_Avatar" class="employees_Avatar"><?= $Page->Avatar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ActiveStatus->Visible) { // ActiveStatus ?>
        <th class="<?= $Page->ActiveStatus->headerCellClass() ?>"><span id="elh_employees_ActiveStatus" class="employees_ActiveStatus"><?= $Page->ActiveStatus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->MessengerColor->Visible) { // MessengerColor ?>
        <th class="<?= $Page->MessengerColor->headerCellClass() ?>"><span id="elh_employees_MessengerColor" class="employees_MessengerColor"><?= $Page->MessengerColor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->CreatedAt->Visible) { // CreatedAt ?>
        <th class="<?= $Page->CreatedAt->headerCellClass() ?>"><span id="elh_employees_CreatedAt" class="employees_CreatedAt"><?= $Page->CreatedAt->caption() ?></span></th>
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
<?php if ($Page->EmployeeID->Visible) { // EmployeeID ?>
        <td<?= $Page->EmployeeID->cellAttributes() ?>>
<span id="">
<span<?= $Page->EmployeeID->viewAttributes() ?>>
<?= $Page->EmployeeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->LastName->Visible) { // LastName ?>
        <td<?= $Page->LastName->cellAttributes() ?>>
<span id="">
<span<?= $Page->LastName->viewAttributes() ?>>
<?= $Page->LastName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->FirstName->Visible) { // FirstName ?>
        <td<?= $Page->FirstName->cellAttributes() ?>>
<span id="">
<span<?= $Page->FirstName->viewAttributes() ?>>
<?= $Page->FirstName->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Title->Visible) { // Title ?>
        <td<?= $Page->_Title->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->TitleOfCourtesy->Visible) { // TitleOfCourtesy ?>
        <td<?= $Page->TitleOfCourtesy->cellAttributes() ?>>
<span id="">
<span<?= $Page->TitleOfCourtesy->viewAttributes() ?>>
<?= $Page->TitleOfCourtesy->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->BirthDate->Visible) { // BirthDate ?>
        <td<?= $Page->BirthDate->cellAttributes() ?>>
<span id="">
<span<?= $Page->BirthDate->viewAttributes() ?>>
<?= $Page->BirthDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->HireDate->Visible) { // HireDate ?>
        <td<?= $Page->HireDate->cellAttributes() ?>>
<span id="">
<span<?= $Page->HireDate->viewAttributes() ?>>
<?= $Page->HireDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
        <td<?= $Page->Address->cellAttributes() ?>>
<span id="">
<span<?= $Page->Address->viewAttributes() ?>>
<?= $Page->Address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
        <td<?= $Page->City->cellAttributes() ?>>
<span id="">
<span<?= $Page->City->viewAttributes() ?>>
<?= $Page->City->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
        <td<?= $Page->Region->cellAttributes() ?>>
<span id="">
<span<?= $Page->Region->viewAttributes() ?>>
<?= $Page->Region->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
        <td<?= $Page->PostalCode->cellAttributes() ?>>
<span id="">
<span<?= $Page->PostalCode->viewAttributes() ?>>
<?= $Page->PostalCode->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
        <td<?= $Page->Country->cellAttributes() ?>>
<span id="">
<span<?= $Page->Country->viewAttributes() ?>>
<?= $Page->Country->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->HomePhone->Visible) { // HomePhone ?>
        <td<?= $Page->HomePhone->cellAttributes() ?>>
<span id="">
<span<?= $Page->HomePhone->viewAttributes() ?>>
<?= $Page->HomePhone->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Extension->Visible) { // Extension ?>
        <td<?= $Page->Extension->cellAttributes() ?>>
<span id="">
<span<?= $Page->Extension->viewAttributes() ?>>
<?= $Page->Extension->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Photo->Visible) { // Photo ?>
        <td<?= $Page->Photo->cellAttributes() ?>>
<span id="">
<span<?= $Page->Photo->viewAttributes() ?>>
<?= $Page->Photo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ReportsTo->Visible) { // ReportsTo ?>
        <td<?= $Page->ReportsTo->cellAttributes() ?>>
<span id="">
<span<?= $Page->ReportsTo->viewAttributes() ?>>
<?= $Page->ReportsTo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Username->Visible) { // Username ?>
        <td<?= $Page->_Username->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Username->viewAttributes() ?>>
<?= $Page->_Username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Password->Visible) { // Password ?>
        <td<?= $Page->_Password->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Password->viewAttributes() ?>>
<?= $Page->_Password->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_Email->Visible) { // Email ?>
        <td<?= $Page->_Email->cellAttributes() ?>>
<span id="">
<span<?= $Page->_Email->viewAttributes() ?>>
<?= $Page->_Email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Activated->Visible) { // Activated ?>
        <td<?= $Page->Activated->cellAttributes() ?>>
<span id="">
<span<?= $Page->Activated->viewAttributes() ?>>
<?= $Page->Activated->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_UserLevel->Visible) { // UserLevel ?>
        <td<?= $Page->_UserLevel->cellAttributes() ?>>
<span id="">
<span<?= $Page->_UserLevel->viewAttributes() ?>>
<?= $Page->_UserLevel->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Avatar->Visible) { // Avatar ?>
        <td<?= $Page->Avatar->cellAttributes() ?>>
<span id="">
<span<?= $Page->Avatar->viewAttributes() ?>>
<?= $Page->Avatar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ActiveStatus->Visible) { // ActiveStatus ?>
        <td<?= $Page->ActiveStatus->cellAttributes() ?>>
<span id="">
<span<?= $Page->ActiveStatus->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->ActiveStatus->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->MessengerColor->Visible) { // MessengerColor ?>
        <td<?= $Page->MessengerColor->cellAttributes() ?>>
<span id="">
<span<?= $Page->MessengerColor->viewAttributes() ?>>
<?= $Page->MessengerColor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->CreatedAt->Visible) { // CreatedAt ?>
        <td<?= $Page->CreatedAt->cellAttributes() ?>>
<span id="">
<span<?= $Page->CreatedAt->viewAttributes() ?>>
<?= $Page->CreatedAt->getViewValue() ?></span>
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
