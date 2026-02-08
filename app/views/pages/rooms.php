<?php
$pageTitle = 'Rooms';
$baseUrl = DEF_ROOT_PATH;

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
        <button class="btn btn-primary mb-2 mb-md-0 me-2" id="btnReloadRoomsTable">Reload</button>
    </div>
    <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
        <button type="button" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text" id="btnAddNewRoom"> <i class="mdi mdi-plus-circle"></i> Add Room</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="roomsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Short Description</th>
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

$roomModalId = 'largeModal';

$additionalCss[] = <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
EOQ;

$additionalJs[] = <<<EOQ
function editRoom(id)
{
    showModal('modals?modalFile=room&id='+id+'&action=updateroom&modalId={$roomModalId}', '{$roomModalId}');
}

function deleteRoom(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this room?',
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
                    'action': 'deleteroom'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('roomsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

function loadRoomsTable()
{
    //Destroy existing table to remove old data
    if ($.fn.dataTable.isDataTable('#roomsTable'))
    {
        $('#roomsTable').DataTable().clear().destroy();
    }

    //Reinitialize DataTable with updated parameters
    var roomsTableInit = $('#roomsTable').DataTable({
        processing: true,
        autoWidth: false,
        responsive: true,
        ajax: {
            url: 'actions?action=getrooms',
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
            { data: 'price' },
            { data: 'short_description' },
            { data: 'featured_image' },
            { data: 'cdate' },
            { data: 'mdate' },
            { data: 'edit' },
            { data: 'delete' },
        ],
        columnDefs: [
            {"orderable": false, "targets": [4, 7, 8]}
        ],
        pageLength: 50
    });
    return roomsTableInit;
}

EOQ;

$additionalJsOnLoad[] = <<<EOQ

var roomsTable = loadRoomsTable();

$('#btnReloadRoomsTable').click(function() {
    loadRoomsTable();
});

$('#btnAddNewRoom').click(function() {
    showModal('modals?modalFile=room&action=addroom&modalId={$roomModalId}', '{$roomModalId}');
});

EOQ;

$pathAssetsVendors = DEF_PATH_ASSETS_VENDORS;
$additionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
<script src="{$pathAssetsVendors}/ckeditor5/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
EOQ;

require_once DEF_PATH_PAGES . '/layout.php';
