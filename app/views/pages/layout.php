<?php
$pageTitle = isset($pageTitle) ? $pageTitle : APP_NAME;

if (!isset($_SESSION[SESSION_NAME]))
{
  blockOutToLoginPage();
}
$arCurrentPage = getCurrentPageAdmin($pageTitle);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $pageTitle . ' - ' . APP_NAME; ?></title>
    
    <!-- Global css -->
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/css/vendor.bundle.base.css">
    <!-- <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/font-awesome/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/style.css">
    <link rel="shortcut icon" href="<?php echo DEF_PATH_ASSETS_IMG; ?>/favicon.png" />

    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/select2-bootstrap-theme/select2-bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/icons/bootstrap-icons.scss">

    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/icons/custom-icons/css/custom-icons.css">

    <!-- Toast Alert -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.4/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />

    <!-- Page specific css -->
    <?php
    if (isset($additionalCss) && count($additionalCss) > 0)
    {
        echo implode(PHP_EOL, $additionalCss);
    }
    ?>
</head>
<body>

    <?php
    require_once DEF_PATH_MODALS . '/modals.php';
    ?>

    <div class="container-scroller">
        <?php
        //sidebar
        require_once DEF_PATH_SIDEBAR; ?>

        <div class="container-fluid page-body-wrapper">
            <?php
            //navbar
            require_once DEF_PATH_NAVBAR; ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <?php
                    //main page content
                    if (isset($pageContent))
                    {
                        echo $pageContent;
                    }
                    ?>
                </div>

                <?php
                //footer
                require_once DEF_PATH_FOOTER;
                ?>
            </div>
        </div>
    </div>
    <!-- container-scroller -->

    <script>
    <?php
    if (isset($additionalJs) && count($additionalJs) > 0)
    {
        echo implode("\n", $additionalJs);
        echo "\n";
    }
    ?>
    </script>

    <!-- Global js -->
    <script src="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/js/vendor.bundle.base.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/off-canvas.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/misc.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/custom.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/settings.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/todolist.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/hoverable-collapse.js"></script>

    <script src="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/select2/select2.min.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/file-upload.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/typeahead.js"></script>
    <script src="<?php echo DEF_PATH_ASSETS_JS; ?>/select2.js"></script>

    <!-- Toast Alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

    <!-- Core DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>

    <!-- DataTables Bootstrap 5 integration -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/2.3.4/dataTables.bootstrap5.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script> -->
    
    <!-- Page specific js -->
    <?php
    if (isset($additionalJsScripts) && count($additionalJsScripts) > 0)
    {
        echo implode(PHP_EOL, $additionalJsScripts);
    }
    ?>

    <script>
    $(document).ready(function() {

        $(document).ajaxStart(function () {
            $.blockUI({
                message: `
                    <div id="globalLoader">
                    <div class="spinner-border text-light" role="status"></div>
                    <div class="mt-2 text-white">Processing...</div>
                    </div>
                `,
                css: {
                    border: 'none',
                    backgroundColor: 'transparent',
                    padding: 0
                },
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.5,
                    cursor: 'wait'
                },
                baseZ: 2000
            });

            $('.blockUI.blockMsg.blockPage').css({
                top: 0,
                left: 0,
                width: '100vw',
                height: '100vh',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'none'
            });
        });

        $(document).ajaxStop(function () {
            $.unblockUI();
        });

        $('#btnLogoutSidebar').on('click', function()
        {
            doOpenLogoutModal();
        });

        <?php
        if (isset($additionalJsOnLoad) && count($additionalJsOnLoad) > 0)
        {
            echo implode("\n", $additionalJsOnLoad);
        }
        ?>
    });
    </script>

</body>
</html>