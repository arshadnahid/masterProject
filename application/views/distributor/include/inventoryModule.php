<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse          ace-save-state">
    <script type="text/javascript">
        try{ace.settings.loadState('sidebar')}catch(e){}
    </script>

    <div class="sidebar-shortcuts" id="sidebar-shortcuts">

        <a href="<?php echo site_url('DistributorDashboard'); ?>">
            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div></a>
    </div><!-- /.sidebar-shortcuts -->
    <?php
    $admin_status = strtolower($this->session->userdata('status'));
    $admin_id = strtolower($this->session->userdata('admin_id'));
    $distributor_id = strtolower($this->session->userdata('dis_id'));

    $this->db->where('active', '1');
    $this->db->where('parent_id', '1002');
    $this->db->order_by('orderBy', 'DESC');
    $navs = $this->db->from('navigation')->get()->result_array();
    
    ?>
    <ul class="nav nav-list">
        <?php
        foreach ($navs as $each_menu):
            $sub_menu = $this->Common_model->get_data_list_by_main_menu_id($each_menu['navigation_id'], $admin_id);
            //echo $this->db->last_query();die;
            ?>
            <li class="active open hover">
                <?php
                if ($each_menu['navigation_id'] == 63):
                    ?>
                    <a href="http://sflcl.com/baseithr/" target="_blank">
                        <i class="menu-icon <?php echo $each_menu['icon']; ?>"></i>
                        <span class="menu-text">
                            <?php echo $each_menu['label']; ?>
                        </span>
                    </a>
                <?php else: ?>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon <?php echo $each_menu['icon']; ?>"></i>
                        <span class="menu-text">
                            <?php echo $each_menu['label']; ?>
                        </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                <?php endif; ?>
                <b class="arrow"></b>
                <?php if (!empty($sub_menu)): ?>
                    <ul class="submenu">
                        <?php
                        foreach ($sub_menu as $each_submenu):
                            if (!empty($each_submenu)):
                                $single_submenu = $this->Common_model->get_single_data_by_single_column('navigation', 'navigation_id', $each_submenu['navigation_id']);
                                $icon = $single_submenu->icon;
                                $url = $single_submenu->url;
                                $link = site_url($url);
                                $label = $single_submenu->label;
                                ?>
                                <li class="active open hover">
                                    <a href="<?php echo $link; ?>">
                                        <i class="menu-icon fa fa-share"></i>&nbsp;
                                        <?php echo $label; ?>

                                    </a>
                                </li>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        <li  class="active open hover">
            <a href="<?php echo site_url('moduleDashboard'); ?>">
                <i class=" menu-icon fa fa-archive"></i>
                <span class="menu-text">
                    Module
                </span>
            </a>
        </li>
    </ul><!-- /.nav-list -->
</div>