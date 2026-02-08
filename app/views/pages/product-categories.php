<?php
$pageTitle = 'Product Categories';
$baseUrl = DEF_ROOT_PATH;

$additionalCss[] = <<<EOQ
EOQ;

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
        <button class="btn btn-primary mb-2 mb-md-0 me-2" id="btnReloadCategoriesTable">Reload</button>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <button type="button" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text" id="btnAddNewCategory"> <i class="mdi mdi-plus-circle"></i> Add Category</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="categoriesTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Title</th>
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

$productCategoryModalId = 'defaultModal';

$additionalJs[] = <<<EOQ
function editProductCategory(id)
{
    showModal('modals?modalFile=productcategory&id='+id+'&action=updateproductcategory&modalId={$productCategoryModalId}', '{$productCategoryModalId}');
}

function deleteProductCategory(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this category?',
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
                    'action': 'deletecategory'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('categoriesTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

function loadCategoriesTable()
{
    //Destroy existing table to remove old data
    if ($.fn.dataTable.isDataTable('#categoriesTable'))
    {
        $('#categoriesTable').DataTable().clear().destroy();
    }

    //Reinitialize DataTable with updated parameters
    var categoriesTableInit = $('#categoriesTable').DataTable({
        processing: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: 'actions?action=getcategories',
            data: {},
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
            { data: 'cdate' },
            { data: 'mdate' },
            { data: 'edit' },
            { data: 'delete' },
        ],
        columnDefs: [
            {"orderable": false, "targets": [4, 5]}
        ],
        pageLength: 50
    });
    return categoriesTableInit;
}

EOQ;

$additionalJsOnLoad[] = <<<EOQ

var categoriesTable = loadCategoriesTable();

$('#btnReloadCategoriesTable').click(function() {
    loadCategoriesTable();
});

$('#btnAddNewCategory').click(function() {
    showModal('modals?modalFile=productcategory&action=addproductcategory&modalId={$productCategoryModalId}', '{$productCategoryModalId}');
});

EOQ;

$pathAssetsVendors = DEF_PATH_ASSETS_VENDORS;
$additionalJsScripts[] = <<<EOQ
EOQ;

require_once DEF_PATH_PAGES . '/layout.php';
