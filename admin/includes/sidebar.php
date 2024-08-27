<!-- Make side bar active -->
<?php
    $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>

                <a class="nav-link <?=  $page == 'index.php' ? 'active':''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard 
                </a>
                <a class="nav-link <?=  $page == 'order-create.php' ? 'active':''; ?>" href="order-create.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                    Purchase a Product
                </a>
                <a class="nav-link <?=  $page == 'orders.php' ? 'active':''; ?>" href="orders.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    View Purchase Products
                </a>

                <div class="sb-sidenav-menu-heading">Interface</div>

                <a class="nav-link 
                    <?=  ($page == 'categories-create.php') || ($page == 'categories.php') ? 'collapse active':'collapsed'; ?>"
                    href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse 
                    <?=  ($page == 'categories-create.php') || ($page == 'categories.php') ? 'show':''; ?>"
                    id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=  $page == 'categories-create.php' ? 'active':''; ?>" href="categories-create.php">Create Category</a>
                        <a class="nav-link <?=  $page == 'categories.php' ? 'active':''; ?>" href="categories.php">View Categories</a>
                    </nav>
                </div>
                
                <a class="nav-link <?=  ($page == 'products-create.php') || ($page == 'products.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseProduct" aria-expanded="false" aria-controls="collapseProduct">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Products
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse <?=  ($page == 'products-create.php') || ($page == 'products.php') ? 'collapse active':'collapsed'; ?>" id="collapseProduct" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=  $page == 'products-create.php' ? 'active':''; ?>" href="products-create.php">Create Products</a>
                        <a class="nav-link <?=  $page == 'products.php' ? 'active':''; ?>" href="products.php">View Products</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Manage Users</div>
               
                <a class="nav-link <?=  ($page == 'customers-create.php') || ($page == 'customers.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseCustomer" 
                    aria-expanded="false" aria-controls="collapseCustomer">

                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Customer
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?=  ($page == 'customers-create.php') || ($page == 'customers.php') ? 'collapse active':'collapsed'; ?>" id="collapseCustomer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=  $page == 'customers-create.php' ? 'active':''; ?>" href="customers-create.php">Add Customer</a>
                        <a class="nav-link <?=  $page == 'customers.php' ? 'active':''; ?>" href="customers.php">View Customers</a>
                    </nav>
                </div>

                <a class="nav-link <?=  ($page == 'admins-create.php') || ($page == 'admins.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseAdmins" 
                    aria-expanded="false" aria-controls="collapseAdmins">

                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Admins/Staff
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?=  ($page == 'admins-create.php') || ($page == 'admins.php') ? 'collapse active':'collapsed'; ?>" id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=  $page == 'admins-create.php' ? 'active':''; ?>" href="admins-create.php">Add Admin</a>
                        <a class="nav-link <?=  $page == 'admins.php' ? 'active':''; ?>" href="admins.php">View Admins</a>
                    </nav>
                </div>

                <!-- New Generate Sales Report Section -->
                <div class="sb-sidenav-menu-heading">Reports</div>
                
                <a class="nav-link <?=  ($page == 'my-report.php') || ($page == 'users-report.php') ? 'collapse active':'collapsed'; ?>" href="#" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Generate Report
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?=  ($page == 'my-report.php') || ($page == 'users-report.php') ? 'collapse active':'collapsed'; ?>" id="collapseReport" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=  $page == 'my-report.php' ? 'active':''; ?>" href="my-report.php">My Report</a>
                        <a class="nav-link <?=  $page == 'users-report.php' ? 'active':''; ?>" href="users-report.php">Users Report</a>
                    </nav>
                </div>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>
