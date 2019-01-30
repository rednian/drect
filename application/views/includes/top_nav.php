  <body class="hold-transition skin-blue sidebar-mini fixed">

    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url('dashboard') ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini" style="padding-top:3px"><img class="center-block img-responsive" src="<?php echo base_url("assets/images/drect_sm_icon.fw.png") ?>" style="width:80%;height:40px"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img class="center-block img-responsive pull-left" src="<?php echo base_url("assets/images/DRECT.fw.png") ?>" style="width:200px;height:50px"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label counter">
                    
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"></li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu"></ul>
                  </li>
                  <li class="footer"><a href="<?php echo base_url('task') ?>">View all</a></li>
                </ul>
              </li>
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu new_emails">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label counter"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">No message available</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      
                    </ul>

                  </li>
                  <li class="footer"><a href="<?php echo base_url('mail'); ?>">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <!-- <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-danger"></span>
                </a>
                <ul class="dropdown-menu">
                  
                  <li>
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li> -->
              <!-- Tasks: style can be found in dropdown.less -->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img onerror="this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>';" src="<?php echo base_url('images/profile_image').'/'.$this->userInfo->img_path ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo ucwords($this->userInfo->fullName('f l')) ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img onerror="this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>';" src="<?php echo base_url('images/profile_image').'/'.$this->userInfo->img_path ?>" class="img-circle" alt="User Image">
                    <p>
                     <?php echo ucwords($this->userInfo->fullName('f l')) ?>
                      <small><?php echo ucwords($this->userInfo->position) ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo base_url('settings'); ?>" class="btn btn-default btn-flat"><i class="fa fa-gear"></i> Settings</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url('dashboard/logout'); ?>" class="btn btn-default btn-flat"><i class="fa fa-power-off"></i> Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-comments-o"></i> <span class="label label-danger chat-counter"></span></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>