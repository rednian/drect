<section class="content">
          
          <div class="row">
            <div class="col-lg-7">
              <div class="box box-solid">
                <div class="box-header with-border" >
                  <h3 class="box-title" style="font-size:13px"><b><i class="fa fa-user"></i> Account Information</b></h3>
                </div>
                <div class="box-body" style="background:#f3f3f3">
                   <div class="row">
                     <div class="col-lg-4">
                       <div class="box box-solid">
                        <div class="box-header" style="background:#CCC;font-size:12px;padding:3px">
                          <i class="fa fa-file-image-o"></i> Change Profile Picture
                        </div>
                        <div class="box-body">
                        <form action="<?php echo base_url('settings/change_profile_picture') ?>" id="form_change_profile_image" method="post" enctype="multipart/form-data">
                          <img src="<?php echo base_url('images/profile_image/'.$this->userInfo->img_path); ?>" class="img img-thumbnail center-block view_image" style="width:150px;height:155px">
                          <div class="btn btn-default btn-file btn-xs flat center-block" style="margin-top:5px;width:150px">
	                          <i class="fa fa-folder-o"></i> Browse for photo
	                          <input onchange="getimagefile();$('#form_change_profile_image').submit()" type="file" id="image_path" name="image_file"/>
	                      </div>
                        </form>
                        </div>
                      </div>
                     </div>
                     <div class="col-lg-8">
                       <div class="box box-solid">
                        <div class="box-header" style="background:#CCC;font-size:12px;padding:3px">
                          <i class="fa fa-lock"></i> Account Settings
                        </div>
                        <div class="box-body">
                            <form id="form_change_password" method="post" action="<?php echo base_url('settings/change_password') ?>">
                              <div class="form-group">
                                Old password
                                <div class="form-group">
                                <div class="input-group input-group-sm">
                                  <span class="input-group-addon" id="sizing-addon3"><i class="fa fa-lock"></i></span>
                                  <input required type="password" id="password" name="password" class="form-control" placeholder="Enter new password" aria-describedby="sizing-addon3">
                                  <span class="input-group-btn">
                                    <button id="btn_show_password"class="btn btn-default" type="button" style="border-radius:0px"><i class="fa fa-eye"></i></button>
                                  </span>
                                </div>
                                </div>
                              </div>

                              <div class="form-group">
                                New password
                                <div class="form-group">
                                <input required type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" aria-describedby="sizing-addon3">
                                </div>
                              </div>

                              <div class="form-group">
                                Confirm password
                                <div class="form-group">
                                <input required type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter new password" aria-describedby="sizing-addon3">
                                </div>
                              </div>
                              <div class="form-group">
                              	<button name="btn_change_password" type="submit" class="btn btn-sm btn-success flat"><i class="fa fa-save"></i> Save Changes</button>
                              </div>
                            </form>
                        </div>
                      </div>
                     </div>
                   </div> 
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7">
              <div class="box box-solid">
                <div class="box-header with-border" >
                  <h3 class="box-title" style="font-size:13px"><b><i class="fa fa-clock-o"></i> Idle Settings</b></h3>
                </div>
                <div class="box-body" style="background:#f3f3f3">
                  <!-- <div class="form-group">
                    Option
                    <select id="set_option" name="set_option" class="form-control input-sm">
                      <option value="Auto Logout">Auto Logout</option>
                      <option value="Auto Lock">Auto Lock</option>
                      <option value="Not set" class="hide">Not set</option>
                    </select>
                  </div> -->
                  <form method="post" id="form_change_idle">
	                  <div class="form-group">
	                    Wait:&nbsp;&nbsp;&nbsp;&nbsp;<input autocomplete="off" required style="padding-left:5px" id="set_time_duration" name="set_time_duration" type="text"> minutes to automatic logout
	                  </div>
	                  <div class="form-group">
			            <button type="submit" class="btn btn-sm btn-success flat"><i class="fa fa-save"></i> Save Changes</button>
			          </div>
		          </form>
                </div>
              </div>
              </div>
          </div>
          
         
        </section>

        <?php include("js.php") ?>