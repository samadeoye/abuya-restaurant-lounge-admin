<?php
use AbuyaAdmin\ProductCategory\ProductCategory;

$id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';
$action = trim($_REQUEST['action']);

$categoryTitle = $description = '';

$title = 'Add New Category';
$formId = 'addUpdateProductCategoryForm';
if ($action == 'updateproductcategory')
{
    $title = 'Update Category';

    $rs = ProductCategory::getProductCategory(
        $id
    );
    if ($rs)
    {
        $categoryTitle = $rs['title'];
        $description = $rs['description'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}

?>

<form class="pt-3" id="<?php echo $formId; ?>" method="post" action="inc/actions" onsubmit="return false;" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $title; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

        <div class="row">
            <div class="form-group col-md-12">
                <label>Title</label>
                <input type="text" class="form-control" name="title" id="title" value="<?php echo $categoryTitle; ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-md-12">
                <label>Description</label>
                <textarea type="text" class="form-control" name="description" id="description"><?php echo $description; ?></textarea>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = '<?php echo $formId; ?>';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    $('#'+formId+' #btnSubmit').click(function(){
        var title = $('#'+formId+' #title').val();
        
        if (title.length < 3 || title.length > 100)
        {
            throwError('Title is invalid!');
            return false;
        }
        else
        {
            var formData = new FormData(this.form);
            $.ajax({
                url: 'actions',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 1);
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess(data.msg);
                        closeModal(modalId, true);
                        reloadTable('productCategoriesTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });

});
</script>