 <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- <div class="user-panel">
            <div class="pull-left image">
              <img onerror="this.onerror=null;this.src='<?php //echo base_url("assets/dist/img/default.png")?>';" src="<?php //echo base_url('images/profile_image').'/'.$this->userInfo->img_path ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php //echo ucwords($this->userInfo->fullName('f l')) ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div> -->
          <!-- search form -->
          
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php $CI =& get_instance(); echo $CI->setSideBar(); ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title_view ?>
            <small>Portal</small>
          </h1>
        </section>