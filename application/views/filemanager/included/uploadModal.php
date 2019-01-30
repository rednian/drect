<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      
        <div class="modal-header flat with-border">
          <button type="button" class="close" onclick="close_upload()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-upload"></i> Upload Files</h4>
        </div>
        <div class="modal-body">
          <form method="post" id="form-upload" action="php/addFiles.php" enctype="multipart/form-data">
            <div class="box box-solid flat" style="margin-bottom:10px">
              <div class="box-body flat">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input required type="text" class="form-control input-sm" name="name" placeholder="File name">
                    </div>
                  </div>
                  <!-- <div class="col-lg-6">
                    <div class="form-group">
                      <input onkeypress="return isNumber(event)" value="" type="text" class="form-control input-sm" name="version" placeholder="Version">
                    </div>
                  </div> -->
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      Description
                      <textarea name="description" rows="4" class="form-control input-sm"></textarea>
                    </div>
                  </div>
                </div>
                <!-- <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <select class="form-control input-sm" name="type">
                        <option disabled selected class="hide">Classification</option>
                        <option>+ Add Type</option>
                        <option value="Memo">Memo</option>
                        <option value="Request Form">Request Form</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <select class="form-control input-sm" name="div">
                        <option disabled selected class="hide">Division</option>
                        <option>+ Add Type</option>
                        <option value="1">Academic</option>
                        <option value="2">Administration</option>
                        <option value="3">External</option>
                      </select>
                    </div>
                  </div>
                </div> -->
                
                <!-- <div class="form-group">
                  <select class="form-control input-sm" name="dep">
                    <option disabled selected class="hide">Department</option>
                    <option>+ Add Type</option>
                    <option value="1">ITE</option>
                    <option value="2">BE</option>
                    <option value="3">EngTech Global Solutions Inc.</option>
                  </select>
                </div> -->
                <div class="form-group">
                  <input required name="file" type="file" id="file" class="form-control input-sm">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-sm btn-info flat ">Upload</button>
                </div>
              </div>
            </div>
          </form> 

          <div class="box box-solid flat" id="added_files">
            <div class="box-header with-border">
              <b>Uploaded Files</b>
            </div>
            <div class="box-body flat">
              
            </div>
            
          </div>

        </div>
        <div class="modal-footer flat">
          <button type="button" class="btn btn-default btn-sm flat" onclick="close_upload()">Close</button>
        </div>

    </div>
  </div>
</div>


