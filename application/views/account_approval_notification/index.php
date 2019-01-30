<section class="content">
	<div class="row">
		<div class="col-lg-12">

			<div>

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">

			    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Activation</a></li>
			    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Deactivation</a></li>
			    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Modification</a></li>
			   
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content" style="padding:10px;background:#FFF">
			  <!-- ACTIVATION PANEL -->
			    <div role="tabpanel" class="tab-pane active" id="home">
			    	<table id="table_activation" class="table table-bordered">
			    		<thead>
			                <tr class="headings">
			                    <th></th>
			                    <th>Name </th>
			                    <th>Position </th>
			                    <th>Status </th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			    	</table>
			    </div>
			    <!-- !ACTIVATION PANEL -->
			    <!-- DEACTIVATION PANEL -->
			    <div role="tabpanel" class="tab-pane" id="profile">
			    	<table id="table_deactivation" class="table table-bordered">
			    		<thead>
			                <tr class="headings">
			                    <th></th>
			                    <th>Name </th>
			                    <th>Position </th>	
			                    <th>Status </th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			    	</table>
			    </div>
			    <!-- !DEACTIVATION PANEL -->
			    <!-- MODIFICATION PANEL -->
			    <div role="tabpanel" class="tab-pane" id="messages">
			    	<table id="table_modification" class="table table-bordered">
			    		<thead>
			                <tr class="headings">
			                    <th></th>
			                    <th>Name </th>
			                    <th>Position </th>	
			                    <th>Status </th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			    	</table>
			    </div>
			    <!-- !MODIFICATION PANEL -->
			    
			  </div>

			</div>

		</div>
		
	</div>
	
</section>

<!-- MODAL ACTIVATION -->
<div class="modal fade" id="set_remarks_modal_activation" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form id="form_submit_remarks_activation" method="post">
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" class="form-control" id="activate_pi_id" name="pi_id">
          </div>
          <div class="form-group">
            <textarea id="activate_remarks" name="remarks" style="border:none!important" placeholder="Remarks here . . ." required class="form-control" rows="5"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- !MODAL ACTIVATION -->

<!-- MODAL DEACTIVATION -->
<div class="modal fade" id="set_remarks_modal_deactivation" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?php echo base_url('account_approval_notification/submit_remarks_deactivation') ?>" id="form_submit_remarks_deactivation" method="post">
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" class="form-control" id="deactivate_pi_id" name="pi_id">
          </div>
          <div class="form-group">
            <textarea id="deactivate_remarks" name="remarks" style="border:none!important" placeholder="Remarks here . . ." required class="form-control" rows="5"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- !MODAL DEACTIVATION -->

<!-- MODAL MODIFICATION -->
<div class="modal fade" id="set_remarks_modal_modification" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?php echo base_url('account_approval_notification/submit_remarks_modification') ?>" id="form_submit_remarks_modification" method="post">
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" class="form-control" id="modify_pi_id" name="pi_id">
          </div>
          <div class="form-group">
            <textarea id="modify_remarks" name="remarks" style="border:none!important" placeholder="Remarks here . . ." required class="form-control" rows="5"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- !MODAL MODIFICATION -->

<style type="text/css">
		/*@import url('//cdn.datatables.net/1.10.2/css/jquery.dataTables.css');*/
		 td.details-control {
		    background: url('<?php echo base_url("assets/images/details_open.png") ?>') no-repeat center center;
		    cursor: pointer;
		}
		tr.shown td.details-control {
		    background: url('<?php echo base_url("assets/images/details_close.png") ?>') no-repeat center center;
		}
</style>
<?php include("js.php") ?>