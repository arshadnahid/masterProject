<ul class="page-sidebar-menu visible-sm visible-xs  page-header-fixed" data-keep-expanded="false"
    data-auto-scroll="true" data-slide-speed="200">
    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
    <!-- DOC: This is mobile version of the horizontal menu. The desktop version is defined(duplicated) in the header above -->
    <li class="sidebar-search-wrapper">
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->

        <!-- END RESPONSIVE QUICK SEARCH FORM -->
    </li>

    <li class="nav-item  ">
        <a href="<?php echo site_url($this->project .'/moduleDashboard'); ?>" class="nav-link nav-toggle <?php if (!empty($page_type) && $page_type == 'dashboard') echo 'active'; ?>">

            <span class="title"><?php echo get_phrase('Dashboard') ?></span>

        </a>

    </li>
    <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">

            <span class="title">Inventory</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">

            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Operation</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    <?php
                    $limit = 6;
                    //sales report
                    $sub_menu = $this->Common_model->get_data_list_by_main_menu_id(8, $admin_id, $limit, $statr = 0);
                    foreach ($sub_menu as $each_menu):
                        $url = $each_menu->url;
                        $link = $url;
                        $label = $each_menu->label;
                        ?>
                        <li class="nav-item ">
                            <a href="<?php echo site_url($this->project .'/'.$link); ?>">   <?php echo get_phrase($label); ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="<?php echo site_url($this->project .'/'.'purchases_lpg_add'); ?>">   <?php echo get_phrase('purchases_lpg_add'); ?></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Setup</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Report</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>
    <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">

            <span class="title">Sales</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">

            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Operation</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Setup</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Report</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>
    <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">

            <span class="title">Accounts</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">

            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Operation</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Setup</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Report</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>
    <li class="nav-item  ">
        <a href="javascript:;" class="nav-link nav-toggle">

            <span class="title">Configuration</span>
            <span class="arrow"></span>
        </a>
        <ul class="sub-menu">

            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Operation</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Setup</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <span class="title">Import</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Purchases Voucher</a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Supplier Payment </a>
                    </li>
                    <li class="nav-item ">
                        <a href="#" class="nav-link "> Pending Cheque </a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>

</ul>