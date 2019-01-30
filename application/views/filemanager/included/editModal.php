<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-properties">
    <div class="modal-content">
      
        <div class="modal-header flat with-border">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h5 id="title"><i class='fa fa-pencil fa-1x'></i>&nbsp;&nbsp;&nbsp;&nbsp;Edit File</h5>
        </div>

        <form method="post" id="form_save_file_changes" action="php/save_file_changes.php">

          <div class="modal-body" style='font-size:12px'>
            <div class="form-group">
              Form name:
              <input required type="text" id="txtfilename" name="filename" class="form-control input-sm">
              <input required type="hidden" id="txtf_id" name="f_id" class="form-control input-sm">
            </div>
            <div class="form-group">
              Description:
              <textarea required id="txtdescription" class="form-control input-sm" name="description"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-sm flat">Save Changes</button>
          </div>

        </form>

    </div>
  </div>
</div>


