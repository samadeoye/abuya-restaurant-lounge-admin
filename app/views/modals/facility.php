<?php
use AbuyaAdmin\Facility\Facility;

$id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
$modalId = isset($_REQUEST['modalId']) ? trim($_REQUEST['modalId']) : 'defaultModal';
$action = trim($_REQUEST['action']);

$facilityTitle = $icon = $iconClass = '';

$title = 'Add New Facility';
$formId = 'addUpdateFacilityForm';
if ($action == 'updatefacility')
{
    $title = 'Update Facility';

    $rs = Facility::getFacility(
        $id
    );
    if ($rs)
    {
        $facilityTitle = $rs['title'];
        $iconClass = $rs['icon'];
        if (!empty($iconClass))
        {
            $icon = <<<EOQ
<div>
    <i class="{$iconClass}" style="font-size:25px;"></i>
</div>
EOQ;
        }
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
                <input type="text" class="form-control" name="title" id="title" value="<?php echo $facilityTitle; ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label>Icon</label>
                <?php echo $icon; ?>
                <input type="text" class="form-control" name="icon" id="icon" readonly>
            </div>
            <div id="icon-grid" class="d-flex flex-wrap border p-2" style="max-height:300px; overflow-y:auto;">
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
var currentIcon = '<?php echo $iconClass; ?>';
$(document).ready(function() {

    fetch('<?php echo DEF_PATH_ASSETS_JS; ?>/icons.json')
    .then(response => response.json())
    .then(icons => {
        const grid = document.getElementById('icon-grid');
        icons.forEach(icon => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-light m-1';
        btn.style.fontSize = '24px';
        btn.title = icon.label;
        btn.innerHTML = `<i class="${icon.name}"></i>`;
        btn.dataset.icon = `${icon.name}`;
        
        //Add border-primary if this is the current icon
        if (currentIcon && btn.dataset.icon === currentIcon) {
            btn.classList.add('border-primary');
            document.getElementById('icon').value = currentIcon;
        }
        
        btn.addEventListener('click', () => {
            document.getElementById('icon').value = btn.dataset.icon;
            //Optional: highlight selected
            grid.querySelectorAll('button').forEach(b => b.classList.remove('border-primary'));
            btn.classList.add('border-primary');
        });
            grid.appendChild(btn);
        });
    });

    $('#'+formId+' #btnSubmit').click(function(){
        var title = $('#'+formId+' #title').val();
        var icon = $('#'+formId+' #icon').val();
        
        if (title.length < 3 || title.length > 100)
        {
            throwError('Title is invalid!');
            return false;
        }
        else if (icon.length < 5)
        {
            throwError('Icon is invalid!');
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
                        reloadTable('facilitiesTable');
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