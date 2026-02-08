<?php
use AbuyaAdmin\Product\Product;

$id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'largeModal';
$action = trim($_REQUEST['action']);

$productTitle = $type = $description = $price = $featuredImage = '';
$selectedProductCategories = [];

$title = 'Add New Product';
$formId = 'addUpdateProductForm';
if ($action == 'updateproduct')
{
    $title = 'Update Product';

    $rs = Product::getProduct(
        $id
    );
    if ($rs)
    {
        $productTitle = $rs['title'];
        $price = doTypeCastDouble($rs['price']);
        $type = $rs['type'];
        $description = $rs['description'];
        $featuredImage = $rs['featured_image'];
        $galleryImages = $rs['gallery_images'];
        $productCategories = $rs['productCategories'];

        $selectedProductCategories = [];
        if (!empty($productCategories))
        {
            foreach ($productCategories as $productCategory)
            {
                $selectedProductCategories[] = "{$productCategory['id']}";
            }
        }

        if (!empty($featuredImage))
        {
            $featuredImageUrl = isset($featuredImage['url']) ? $featuredImage['url'] : null;
            if (!empty($featuredImageUrl))
            {
                $featuredImage = <<<EOQ
<div class="pb-2">
    <a href="{$featuredImageUrl}" target="_blank">
        <img src="{$featuredImageUrl}" class="img-thumbnail" style="width:150px; height:120px; object-fit:cover;">
    </a>
</div>
EOQ;
            }
        }
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}

$arTypes = [
    'food' => 'Food',
    'drink' => 'Drink'
];
$typeOptions = '';
foreach ($arTypes as $typeId => $typeLabel)
{
    $selected = '';
    if ($typeId == $type)
    {
        $selected = ' selected';
    }
    $typeOptions .= <<<EOQ
    <option value="{$typeId}"{$selected}>{$typeLabel}</option>
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
                <input type="text" class="form-control" name="title" id="title" value="<?php echo $productTitle; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Price</label>
                <input type="text" class="form-control" name="price" id="price" value="<?php echo $price; ?>">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Type</label>
                <select class="form-select" name="type" id="type">
                    <?php echo $typeOptions; ?>
                </select>
            </div>
        </div>

        <div class="row" id="product_categories-wrapper">
            <div class="form-group col-md-12">
                <label for="product_categories">Product Category</label>
                <select class="form-select" multiple name="product_categories[]" id="product_categories">
                    <?php echo $productCategoryOptions; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" cols="30" rows="5"><?php echo $description;?></textarea>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Featured Image</label>
                <?php echo $featuredImage; ?>
                <input type="file" class="form-control" name="featured_image" id="featured_image" accept="image/*">
                <div id="featured_image_preview" class="mt-2"></div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <label>Gallery Images</label>
                
                <?php
                if (!empty($galleryImages))
                { ?>
                <div class="existing-gallery-images pb-2">
                    <div class="row">
                        <?php
                        foreach ($galleryImages as $index => $image)
                        {
                            $imageUrl = $image['url'] ?>
                            <div class="col-md-2 mb-2 gallery-image-item" data-image-index="<?php echo $index; ?>">
                                <div class="position-relative" style="display: inline-block; width: 100%;">
                                    <a href="<?php echo $imageUrl; ?>" target="_blank">
                                        <img src="<?php echo $imageUrl; ?>" class="img-thumbnail" style="width:100%; height:100px; object-fit:cover;">
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm remove-gallery-image" style="position: absolute; top: 5px; right: 5px; padding: 2px 8px; z-index: 10;" data-image-url="<?php echo $imageUrl; ?>" data-image-id="<?php echo $image['id']; ?>">
                                        x
                                    </button>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>
                </div>
                <input type="hidden" name="deleted_image_ids" id="deleted_image_ids" value="">
                <?php } ?>
                
                <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" accept="image/*" multiple>
                <small class="form-text text-muted">You can select multiple images. Hold Ctrl (Windows) or Cmd (Mac) to select multiple files.</small>
                
                <div id="gallery_preview" class="mt-3"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
    </div>
</form>


<style>
.gallery-image-item {
    position: relative;
}
.gallery-image-item .position-relative {
    display: inline-block;
    width: 100%;
}
.remove-gallery-image {
    padding: 2px 8px !important;
    font-size: 18px !important;
    line-height: 1 !important;
    z-index: 10 !important;
    cursor: pointer;
}
.remove-gallery-image:hover {
    background-color: #dc3545 !important;
}
</style>
<script>
var formId = '<?php echo $formId; ?>';
var modalId = '<?php echo $modalId; ?>';
var descriptionEditor;
var deletedGalleryImageIds = [];
$(document).ready(function() {

    new AutoNumeric('#price', {
        decimalPlaces: 2,
        digitGroupSeparator: '',
        decimalCharacter: '.',
    });

    ClassicEditor
    .create(document.querySelector('#description'), {
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'underline', '|',
            'undo', 'redo'
        ]
    })
    .then(editor => {
        descriptionEditor = editor;
    })
    .catch( err => {
        console.error(err.stack);
    } );

    $('#product_categories').select2({
        placeholder: "Select product categories",
        dropdownParent: $('#product_categories-wrapper'),
        width: '100%'
    });

    <?php
    if (!empty($selectedProductCategories))
    { ?>
        const selectedProductCategories = <?php echo json_encode($selectedProductCategories); ?>;
        $('#product_categories').val(selectedProductCategories).trigger('change');
        <?php
    }
    ?>

    //Featured image preview
    $('#featured_image').on('change', function(e) {
        var file = e.target.files[0];
        var preview = $('#featured_image_preview');
        preview.html('');
        
        if (file) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                preview.html(
                    '<div class="pb-2">' +
                        '<img src="' + e.target.result + '" class="img-thumbnail" style="width:150px; height:120px; object-fit:cover;">' +
                    '</div>'
                );
            }
            
            reader.readAsDataURL(file);
        }
    });

    //Gallery images preview
    $('#gallery_images').on('change', function(e) {
        var files = e.target.files;
        var preview = $('#gallery_preview');
        preview.html('');
        
        if (files.length > 0) {
            preview.append('<div class="row"></div>');
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.find('.row').append(
                        '<div class="col-md-2 mb-2">' +
                            '<img src="' + e.target.result + '" class="img-thumbnail" style="width:100%; height:100px; object-fit:cover;">' +
                        '</div>'
                    );
                }
                
                reader.readAsDataURL(file);
            }
        }
    });

    //Remove existing gallery image
    $('.remove-gallery-image').on('click', function() {
        var imageUrl = $(this).data('image-id');
        deletedGalleryImageIds.push(imageUrl);
        $(this).closest('.gallery-image-item').fadeOut(300, function() {
            $(this).remove();
        });
    });

    $('#'+formId+' #btnSubmit').click(function(){
        var title = $('#'+formId+' #title').val();
        var price = $('#'+formId+' #price').val();

        $('#description').val(descriptionEditor.getData());
        $('#deleted_image_ids').val(deletedGalleryImageIds.join(','));
        
        if (title.length < 3 || title.length > 100)
        {
            throwError('Title is invalid!');
            return false;
        }
        else if (price.length == 0)
        {
            throwError('Price is invalid!');
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
                        reloadTable('productsTable');
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