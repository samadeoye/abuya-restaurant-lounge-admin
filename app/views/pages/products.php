<?php
$pageTitle = 'Products';
$baseUrl = DEF_ROOT_PATH;

$arTypes = [
    'food' => 'Food',
    'drink' => 'Drink',
];
$productTypeOptions = '';
foreach ($arTypes as $typeId => $typeLabel)
{
    $productTypeOptions .= <<<EOQ
    <option value="{$typeId}">{$typeLabel}</option>
EOQ;
}

$rows = AbuyaAdmin\ProductCategory\ProductCategory::getProductCategories();
$productCategoryOptions = '';
if (!empty($rows))
{
    foreach ($rows as $row)
    {
        $productCategoryOptions .= <<<EOQ
<option value="{$row['id']}">{$row['title']}</option>
EOQ;
    }
}

$pageContent = <<<EOQ

<div class="page-header">
    <h3 class="page-title"> {$pageTitle} </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{$baseUrl}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">{$pageTitle}</li>
        </ol>
    </nav>
</div>

<div class="page-header flex-wrap">
    <div class="header-left">
        <button class="btn btn-primary mb-2 mb-md-0 me-2" id="btnReloadProductsTable">Reload</button>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <button type="button" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text" id="btnAddNewProduct"> <i class="mdi mdi-plus-circle"></i> Add Product</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="row mt-3 mx-3">
                <div class="form-group col-md-4">
                    <select id="filterProductTypeId" name="filterProductTypeId" class="form-select">
                        <option value="">Select Type</option>
                        {$productTypeOptions}
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <select id="filterProductCategoryId" name="filterProductCategoryId" class="form-select">
                        <option value="">Select Category</option>
                        {$productCategoryOptions}
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <button class="btn btn-primary" id="btnFilterProductsTable">Filter</button>
                    <button class="btn btn-secondary" id="btnClearProductsTableFilter">Clear</button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="productsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Type</th>
                                <th>Product Category</th>
                                <th>Featured Image</th>
                                <th>Date Created</th>
                                <th>Date Modified</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

EOQ;

$productModalId = 'largeModal';

$additionalJs[] = <<<EOQ
function editProduct(id)
{
    showModal('modals?modalFile=product&id='+id+'&action=updateproduct&modalId={$productModalId}', '{$productModalId}');
}

function deleteProduct(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this product?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: 'actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                    'action': 'deleteproduct'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('productsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

var filterProductTypeId = '';
var filterProductCategoryId = '';
function getProductsTableParams()
{
    filterProductTypeId = $('#filterProductTypeId').val();
    filterProductCategoryId = $('#filterProductCategoryId').val();
}

function loadProductsTable()
{
    //Destroy existing table to remove old data
    if ($.fn.dataTable.isDataTable('#productsTable'))
    {
        $('#productsTable').DataTable().clear().destroy();
    }

    //Reinitialize DataTable with updated parameters
    var productsTableInit = $('#productsTable').DataTable({
        processing: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: 'actions?action=getproducts',
            data: {
                productTypeId: filterProductTypeId,
                productCategoryId: filterProductCategoryId,
            },
            dataSrc: function (data) {
                if (data.status === false || data.data == undefined)
                {
                    throwError(data.msg);
                    return [];
                }
                return data.data;
            },
            error: function () {
                throwError(data.msg);
            }
        },
        columns: [
            { data: 'sn' },
            { data: 'title' },
            { data: 'price' },
            { data: 'type' },
            { data: 'productCategory' },
            { data: 'featured_image' },
            { data: 'cdate' },
            { data: 'mdate' },
            { data: 'edit' },
            { data: 'delete' },
        ],
        columnDefs: [
            {"orderable": false, "targets": [5, 8, 9]}
        ],
        pageLength: 50
    });
    return productsTableInit;
}

EOQ;

$additionalJsOnLoad[] = <<<EOQ

var productsTable = loadProductsTable();

$('#btnReloadProductsTable').click(function() {
    getProductsTableParams();
    loadProductsTable();
});

$('#btnAddNewProduct').click(function() {
    showModal('modals?modalFile=product&action=addproduct&modalId={$productModalId}', '{$productModalId}');
});

$('#btnFilterProductsTable').on('click', function() {
    getProductsTableParams();
    loadProductsTable();
});

$('#btnClearProductsTableFilter').on('click', function() {
    filterProductTypeId = '';
    filterProductCategoryId = '';
    $('#filterProductTypeId').val(filterProductTypeId);
    $('#filterProductCategoryId').val(filterProductCategoryId);
});

EOQ;

$pathAssetsVendors = DEF_PATH_ASSETS_VENDORS;
$additionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
<script src="{$pathAssetsVendors}/ckeditor5/ckeditor.js"></script>
EOQ;

require_once DEF_PATH_PAGES . '/layout.php';
