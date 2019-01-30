<div class="modal fade" id="confirmDeleteAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="">
        <div class="modal-header flat with-border">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Confirm Delete</h4>
        </div>
        <div class="modal-body message">
          Are you sure you want to delete?
        </div>
        <div class="modal-footer flat">
          <button type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="delete_file()" name= class="btn btn-success btn-sm flat">Ok</button>
        </div>
      </form> 
    </div>
  </div>
</div>

