
<?php
// require_once('php/db.php');
// require_once('php/file.php');

?>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-lg-12">
            
              <div class="box box-solid flat">
                <div class="box-header with-border" style="background:#f3f3f3;">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="btn-group">
                        <button onclick="show_create_folder_panel()" class="btn btn-default no-radius btn-sm" title="New Folder">
                        <i class="fa fa-folder"><sup><i class="fa fa-plus"></i></sup></i>
                        </button>
                        <button onclick="$('#uploadModal').modal('show')"class="btn btn-default no-radius btn-sm" title="Upload">
                          <i class="fa fa-upload"></i>
                        </button>
                      </div>
                      <div class="btn-group hide">
                        <button onclick="select_function('copy')" id="btncopy" type="button" class="btn btn-default no-radius btn-sm" title="Copy">
                          <i class="fa fa-copy"></i>
                        </button>
                        <button onclick="select_function('cut')" id="btncut" class="btn btn-default no-radius btn-sm hide" title="Cut">
                          <i class="fa fa-cut"></i>
                        </button>
                        <button onclick="action()" id="btnpaste" class="btn btn-default no-radius btn-sm" title="Paste">
                          <i class="fa fa-paste"></i>
                        </button>
                      </div>
                      <button onclick="confirm_delete()" id="btndelete" class="btn btn-default no-radius btn-sm" title="Delete">
                        <i class="fa fa-trash-o"></i>
                      </button>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group" style="margin-bottom:0px">
                        <div class="input-group input-group-sm">
                          <span class="input-group-addon no-radius"><i class="fa fa-search"></i></span>
                          <input onkeyup="search($(this).val())" type="text" id="txtsearch" class="form-control" placeholder="Search" style="border-right:none">
                          <span class="input-group-addon no-radius"><i onclick="load_after_create();" style="cursor:pointer" class="fa fa-times" id="cancel_search"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <div class="box-body" style="padding-bottom:0px">
                  <div class="row">
                   
                    <div class="col-lg-12">
                      
                      <?php include("included/createFolderPanel.php"); ?>


                      <div class="box box-solid flat">
                        <div class="box-body no-padding" id="box-content" style="height:377px;overflow:auto">
                          <table class="table table-striped table-hover" id="table-files">
                            <thead>
                              <tr>
                                <td></td>
                                <td>Name</td>
                                <td>Version</td>
                                <td>Date modified</td>
                                <td>Size</td>
                                <td>Type</td>
                                <td style='text-align:right'>Action</td>
                              </tr>
                            </thead>
                            <form id='form_table_folder_files' method='POST'>
                            <tbody>
                              
                            </tbody>
                            </form>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer" style="padding:4px;background:#f3f3f3;color:#333;font-size:13px;">
                  <span id="path" style='font-weight:bold' class='text-primary'></span>
                </div>
              </div>
            
            </div>
          </div>
          

        </section><!-- /.content -->

      <?php include("included/uploadModal.php"); ?>
      <?php include("included/infoModal.php"); ?>
      <?php include("included/editModal.php"); ?>
      <?php include("included/confirmDeleteFile.php"); ?>
    