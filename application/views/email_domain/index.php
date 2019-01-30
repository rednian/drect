<section class="content">
          
      <div class="row">
                    <div class="col-md-5">
                      <form method="post" id="add_email_form" action="php/add_email_domain.php">
                        <div class="box box-solid flat">
                          <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-envelope-o"></i><sup><i class="fa fa-plus"></i></sup> Add Email</h3>
                          </div>
                          <div class="box-body">
                              <input type="hidden" name="type" value="communication">
                              <div class="form-group">
                                <input name="display_name" id="display_name" required type="text" class="form-control" placeholder="Sender name">
                                <input name="email_id" id="email_id" type="hidden" class="form-control" placeholder="Sender name">
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon">@</span>
                                  <input name="email" id="email" required placeholder="Email address" type="email" class="form-control">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                  <input id="email_password" name="email_password" required placeholder="Password" type="password" class="form-control">
                                  <span class="input-group-btn">
                                    <button id="show_password" type="button" class="btn btn-default"><i class="fa fa-eye"></i></button>
                                  </span>
                                </div>
                              </div>
                            </form>
                          </div>
                          <div class="box-footer">
                            <button type="reset" id="btn_reset" class="btn btn-defaut flat">Reset</button>
                            <button type="submit" class="btn btn-primary flat">Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="col-md-7">
                      <div class="box box-solid flat">
                        <div class="box-header with-border">
                          <h3 class="box-title">
                            <i class="fa fa-table"></i> List
                          </h3>
                        </div>
                        <div class="box-body">
                          <table id="tbl_official_email" class="table table-hover table-striped">
                            <thead>
                              <tr>
                                <th></th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email address</th>
                                <th>Password</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            
                          </table>
                        </div>
                      </div>
                    </div>
                </div>

        </section>

        <div class="modal fade" id="confirm_delete_email_domain" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" action="">
              <div class="modal-header flat with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Delete</h4>
              </div>
              <div class="modal-body message">
                
              </div>
              <div class="modal-footer flat">
                <button type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="delete_domain()" id="" name= class="btn btn-success btn-sm flat">Ok</button>
              </div>
            </form> 
          </div>
        </div>
      </div>

      <div class="modal fade" id="confirm_change_email_domain" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" action="">
              <div class="modal-header flat with-border">
                <button type="button" class="close" onclick="load_official_emails()" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmation</h4>
              </div>
              <div class="modal-body message">
                Are you sure you want to change email domain?
              </div>
              <div class="modal-footer flat">
                <button onclick="load_official_emails()" type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="update_status()" id="" name= class="btn btn-success btn-sm flat">Ok</button>
              </div>
            </form> 
          </div>
        </div>
      </div>

      <?php include("js.php") ?>