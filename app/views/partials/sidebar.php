<?php
$baseUrl = DEF_ROOT_PATH;
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
                <div class="nav-profile-image">
                    <img src="<?php echo DEF_PATH_ASSETS_IMG; ?>/faces/dash_avatar.png" alt="<?php echo $arUser['name']; ?> img">
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex ms-0 mb-3 flex-column">
                    <span class="fw-semibold mb-1 mt-2 text-center"><?php echo $arUser['name']; ?></span>
                    <span class="text-secondary icon-sm text-center"><?php echo stringToTitle($arUser['role']); ?></span>
                </div>
            </a>
        </li>

        <li class="nav-item pt-3">
            <a class="nav-link d-block" href="<?php echo $baseUrl; ?>">
                <img class="sidebar-brand-logo logoImg" src="<?php echo DEF_PATH_ASSETS_IMG; ?>/logo.png" alt="<?php echo APP_NAME; ?> logo">
                <img class="sidebar-brand-logomini logoImg" src="<?php echo DEF_PATH_ASSETS_IMG; ?>/logo.png" alt="<?php echo APP_NAME; ?> logo">
            </a>
        </li>
        
        <li class="nav-item <?php echo $arCurrentPage['dashboard'];?>">
            <a class="nav-link" href="<?php echo $baseUrl; ?>">
                <i class="mdi mdi-compass-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <!-- <li class="nav-item <?php echo $arCurrentPage['products'];?>">
            <a class="nav-link" href="<?php echo $baseUrl; ?>/products">
                <i class="mdi mdi-pasta menu-icon"></i>
                <span class="menu-title">Products</span>
            </a>
        </li> -->

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products">
                <i class="mdi mdi-pasta menu-icon"></i>
                    <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php echo $arCurrentPage['product-categories'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/product-categories">Categories</a></li>
                <li class="nav-item <?php echo $arCurrentPage['products'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/products">Products</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#rooms" aria-expanded="false" aria-controls="rooms">
                <i class="mdi mdi-bed menu-icon"></i>
                    <span class="menu-title">Rooms</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="rooms">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php echo $arCurrentPage['facilities'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/facilities">Facilities</a></li>
                <li class="nav-item <?php echo $arCurrentPage['rooms'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/rooms">Rooms</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <i class="mdi mdi-cog menu-icon"></i>
                    <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php echo $arCurrentPage['general-settings'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/general-settings">General Settings</a></li>
                <li class="nav-item <?php echo $arCurrentPage['ecommerce-settings'];?>"> <a class="nav-link" href="<?php echo $baseUrl; ?>/ecommerce-settings">E-commerce Settings</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $arCurrentPage['profile'];?>">
            <a class="nav-link" href="<?php echo $baseUrl; ?>/profile">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <!-- <a class="nav-link" href="<?php echo $baseUrl; ?>/logout"> -->
            <a class="nav-link" id="btnLogoutSidebar" href="javascript:;">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>