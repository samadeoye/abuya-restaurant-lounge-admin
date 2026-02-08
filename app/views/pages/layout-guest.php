<?php
$pageTitle = isset($pageTitle) ? $pageTitle : APP_NAME;

if (isset($_SESSION[SESSION_NAME]) && !in_array(strtolower($pageTitle), ['404']))
{
  blockOutToMainPage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Global css -->
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_VENDORS; ?>/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo DEF_PATH_ASSETS_CSS; ?>/style.css">
    <link rel="shortcut icon" href="<?php echo DEF_PATH_ASSETS_IMG; ?>/favicon.png" />

    <!-- Toast Alert -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Page specific css -->
    <?php
    if (isset($additionalCss) && count($additionalCss) > 0)
    {
        echo implode(PHP_EOL, $additionalCss);
    }
    ?>
</head>
<body>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <?php
            //main page content
            if (isset($pageContent))
            {
                echo $pageContent;
            }
            ?>
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

    <!-- Toast Alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
    
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