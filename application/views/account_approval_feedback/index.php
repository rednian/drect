<section class="content">
	<div class="row">
		<div class="col-lg-12">

			<div>

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">

			    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Activation</a></li>
			    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Deactivation</a></li>
			    <!-- <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Modification</a></li> -->
			   
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
			                    <th>Department </th>
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
			                    <th>Department </th>
			                    <th>Status </th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			    	</table>
			    </div>
			    <!-- !DEACTIVATION PANEL -->
			    <!-- MODIFICATION PANEL -->
			  <!--   <div role="tabpanel" class="tab-pane" id="messages">
			    	
			    </div> -->
			    <!-- !MODIFICATION PANEL -->
			    
			  </div>

			</div>

		</div>
		
	</div>
	
</section>

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