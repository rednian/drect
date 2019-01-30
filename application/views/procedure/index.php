<section class="content">
	
	<div class="row">
		
		<div class="col-lg-12">
			<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li id="tab_create" role="presentation" class="active"><a href="#create" aria-controls="create" role="tab" data-toggle="tab"><i class="fa fa-edit"></i> Create Procedure</a></li>
			    <li id="tab_list" role="presentation"><a href="#list" aria-controls="list" role="tab" data-toggle="tab"><i class="fa fa-list-ol"></i> Procedure List</a></li>
			    <li role="presentation"><a href="#approval" aria-controls="approval" role="tab" data-toggle="tab"><i class="fa fa-check"></i> Procedure for Approval</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content" style="padding:20px;background:#FFF">
			    <div role="tabpanel" class="tab-pane active" id="create">
			    	<form id="form_create_procedure" method="post" action="<?php echo base_url("procedure/update"); ?>">
			    	<div class="row">

			    		<div class="col-lg-7">

			    			<div class="form-group">
			    				<button class="btn btn-success flat" type="submit"><i class="fa fa-save"></i> Save Procedure</button>
			    				<button id="btn-reset" class="btn btn-danger flat" type="reset"><i class="fa fa-times"></i> Cancel</button>
			    			</div>

			    			<div class="form-group hide">
			    				<b>Procedure ID</b>
			    				<div class="form-group">
				    				<input required name="procedure_id" type="text" class="form-control">
				    			</div>
			    			</div>
                <div class="form-group">
                  <b>Policy</b>
                  <div class="form-group">
                    <select class="form-control" name="policy_id">
                      <option selected class="hide" value="">Select Policy</option>
                      <?php
                        foreach($policy as $key => $value){
                      ?>
                          <option value="<?php echo $value->policy_id ?>"><?php echo $value->policy_title ?></option>
                      <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>

			    			<div class="form-group">
			    				<b>Procedure Title</b>
			    				<div class="form-group">
				    				<input required name="procedure" type="text" class="form-control">
				    			</div>
			    			</div>

                
			    			
			    			<label>Please add step . . .</label>
			    			<div id="steps_container"></div>

			    			<div class="form-group pull-right">
			    				<button data-template="textbox" id="btn-add-step" type="button" class="btn btn-primary btn-sm flat"><i class="fa fa-plus"></i> Add step</button>
			    			</div>
			    			

			    		</div>

              <div class="col-lg-5">
                <div class="box box-solid hide" id="desc_container" style="background: #f3f3f3">
                  <div class="box-body">
                    <div class="form-group">
                      <label>Description of changes</label>
                      <div class="form-group">
                        <textarea required name="edit_description" class="form-control" rows=7></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

			    		<div class="col-lg-12">
                <div class="form-group">
                	<label style="cursor:pointer;-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on" onselectstart="return false;" onmousedown="return false;">
                      <input id="check_add_form" type="checkbox" class="js-switch" />&nbsp;&nbsp;&nbsp;<span>Requires form</span>
                  </label>
                </div>
			    			<div class="form-group hide" id="text-area-container">
			    				<textarea name="procedure_form" id="text_form_procedure"></textarea>
			    			</div>
			    		</div>
			    	</div>

			    	</form>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="list">
			    	<table id="table-list-procedure" class="table table-hover table-bordered">
			             <thead>
			                <tr class="headings">
			                    <th>#</th>
			                    <th>Title </th>
			                    <th>Version </th>
			                    <th>Date Created </th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			        </table>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="approval">

			    	<table id="table-list-approval" class="table table-hover table-bordered">
			             <thead>
			                <tr class="headings">
			                    <th>#</th>
			                    <th>Title </th>
			                    <th>Version </th>
			                    <th>Date Modified </th>
			                    <th>Status </th>
                          <th>Description</th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			          </table>

			    </div>
			  </div>

			</div>

		</div>
	</div>
</section><!-- /.content -->

<!-- MODAL WHY DELETE -->
<div class="modal fade" id="comfirm_delete_procedure" tabindex="-1" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">WHY DELETE PROCEDURE ?</h4>
      </div>
      <form id="form-submit-request" method="post" action="<?php echo base_url('procedure/request_delete') ?>">

      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" class="form-control" id="delete_procedure_id" name="delete_procedure_id">
          </div>
          <div class="form-group">
            <textarea id="delete_reason" name="delete_reason" style="border:none!important" placeholder="Please give your reason . . ." required class="form-control" rows="5"></textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- MODAL REMARKS -->
<div class="modal fade" id="modalRemarks" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Don del Rosario's Remarks</h4>
      </div>
      <div class="modal-body">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

	// alert($("#steps_container").html());

	var count_step = 0;
	var procedure_for_edit = 0;
	var listapprovalTable;
	var listprocedureTable;
	$(document).ready(function () {
        listapprovalTable = $('#table-list-approval').dataTable({
            "oLanguage": {
                "sSearch": "<i class='fa fa-search'></i> ",
                "oPaginate": 
                              {
                                  "sNext": '<i class="fa fa-angle-right"></i>',
                                  "sPrevious": '<i class="fa fa-angle-left"></i>',
                                  "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                  "sLast": '<i class="fa fa-angle-double-right"></i>'
                              }
            },
            "bLengthChange": false,
            "bSort": true,
            "aoColumnDefs": [
                {
                    'bSortable': false,
                    'aTargets': [0]
                } //disables sorting for column one
            ],
            'iDisplayLength': 10,
            "sPaginationType": "full_numbers",
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                "aButtons": [
                                {
                                    "sExtends": "copy",
                                    "sButtonText": "<i class='fa fa-copy'></i> Copy"
                                },
                                {
                                    "sExtends": "csv",
                                    "sButtonText": "<i class='fa fa-file-excel-o'></i> Save to CSV"
                                },
                                {
                                    "sExtends": "xls",
                                    "sButtonText": "<i class='fa fa-file-excel-o'></i> Generate Excel"
                                },           
                                {
                                  "sExtends": "print",
                                  "sButtonText": "<i class='fa fa-print'></i> Print",
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Procedure List for approval</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
        });

			listprocedureTable = $('#table-list-procedure').dataTable({
            "oLanguage": {
                "sSearch": "<i class='fa fa-search'></i> ",
                "oPaginate": 
                              {
                                  "sNext": '<i class="fa fa-angle-right"></i>',
                                  "sPrevious": '<i class="fa fa-angle-left"></i>',
                                  "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                  "sLast": '<i class="fa fa-angle-double-right"></i>'
                              }
            },
            "bLengthChange": false,
            "bSort": true,
            "aoColumnDefs": [
                {
                    'bSortable': false,
                    'aTargets': [0]
                } //disables sorting for column one
            ],
            'iDisplayLength': 10,
            "sPaginationType": "full_numbers",
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                "aButtons": [
                                {
                                    "sExtends": "copy",
                                    "sButtonText": "<i class='fa fa-copy'></i> Copy"
                                },
                                {
                                    "sExtends": "csv",
                                    "sButtonText": "<i class='fa fa-file-excel-o'></i> Save to CSV"
                                },
                                {
                                    "sExtends": "xls",
                                    "sButtonText": "<i class='fa fa-file-excel-o'></i> Generate Excel"
                                },           
                                {
                                  "sExtends": "print",
                                  "sButtonText": "<i class='fa fa-print'></i> Print",
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Procedure List</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                },
                                
                            ]
            }
        });
        
    });


	$(document).ready(function(){

	  $("button#btn-add-step").click(function(e){
			$.ajax({
				url: "<?php echo base_url('procedure/add_step') ?>",
				dataType: "html",
				data:{step_id : count_step},
				success: function(data){

					$("#steps_container").append(data);
					count_step++;
				},
				error: function(){

				}
			});
	  });

	  $("button[type=reset]").click(function(e){

	  	$("#check_add_form").attr("checked", false);
  		$('#text-area-container').addClass("hide");
  		$('#form_create_procedure').bootstrapValidator('resetForm', true);  
  		$('#form_create_procedure').bootstrapValidator('disableSubmitButtons', false);
  		$("#steps_container").html("");
  		tinymce.get("text_form_procedure").setContent('');
  		procedure_for_edit = 0;
      $("#desc_container").addClass("hide");

	  });

      $('#form_create_procedure').bootstrapValidator({
          message: 'This value is not valid', 
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',  
            invalid: 'glyphicon glyphicon-remove',
            validating: 'fa fa-refresh fa-spin',
          },
          fields: {
            procedure: { 
              message: 'The procedure title is not valid',
              validators: {
                notEmpty: {
                  message: 'The procedure title is required and can\'t be empty'
                }
              }
            },
            policy_id: { 
              message: 'The Policy is not valid',
              validators: {
                notEmpty: {
                  message: 'The policy is required and can\'t be empty'
                }
              }
            },
            edit_description:{
              message: 'The must specify the the changes',
              validators: {
                notEmpty: {
                  message: 'This field is required and can\'t be empty'
                }
              }
            },
            'step[]': { 
              message: 'The step is not valid',
              validators: {
                notEmpty: {
                  message: 'The step is required and can\'t be empty'
                }
              }
            },
            'procedure_signatory[]': {
              validators: {
                notEmpty: {
                  message: 'The signatory is required and can\'t be empty'
                }
              }
            },
            'duration[]':{
                validators: {
                    notEmpty: {
                      message: 'This field is required and can\'t be empty'
                    },
                    regexp: {
                      regexp: /^[0-9]+$/,
                      message: 'Invalid input'
                    }
                }
            },
            'duration_type[]':{
                validators: {
                    notEmpty: {
                      message: 'This field is required and can\'t be empty'
                    }
                }
            }
            
          }
        })  .on('success.form.bv', function(e) {
          
          e.preventDefault();

          var $form     = $(e.target);
          tinyMCE.triggerSave();

          if($("#steps_container").html().trim() != ""){

          	var url = "";
          	if(procedure_for_edit == 0){
          		url = "<?php echo base_url('procedure/insert') ?>";
          	}else if(procedure_for_edit != 0){
          		url = "<?php echo base_url('procedure/update') ?>";
          	}
            
          	$.ajax({
              url: url,
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                if(data['result'] == 1){ 

                  reset_form();

                  $('input[type=checkbox]#check_add_form').prop("checked", false);

                  $("#btn-reset").trigger("click");
               
                  new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                  });

                  load_list_approval("approved");
                  $("#desc_container").addClass("hide");

                }
                else if(data['result'] == 0){
                    new PNotify({
                          title: 'Error',
                          text: data['msg'],
                          type: 'error',
                          icon: 'fa fa-times'
                    });
                    $('#form_create_procedure').bootstrapValidator('disableSubmitButtons', true);
                }
              },
              error:function(data){
                
              }
            });

          }
          else if($("#steps_container").html().trim() == ""){
          	new PNotify({
                          title: 'Error',
                          text: 'Unable to save procedure. Please add step.',
                          type: 'error',
                          icon: 'fa fa-times'
                    });
          }
          

      });


    });    
	
	function reset_form(){
		$("#steps_container").html("");
	}

	$(document).ready(function(){
		$('input[type=checkbox]#check_add_form').on("change", function(){
		    if (this.checked) {
		        $('#text-area-container').removeClass("hide");
		    }
		    else{
		    	$('#text-area-container').addClass("hide");
		    	tinymce.get("text_form_procedure").setContent('');
		    }
		});
	});

	load_list_approval("approved");
	load_list_procedure("approved");

	function load_list_approval(stat){
		$.ajax({
			url: "<?php echo base_url('procedure/load_list_for_approval') ?>",
			data: {status : stat},
			dataType: "json",
			type: "GET",
			success: function(data){

				    listapprovalTable.fnClearTable();

			      for(var i = 0; i < data.length; i++) { 

			      	var color = "";
              var statBut = "";
			      	if(data[i]['status'] == 'disapproved'){
			      		color = "label-danger";
                statBut = "<span class='label "+color+"'>"+data[i]['status']+"</span> <button onclick=\"showRemarks('"+data[i]['type']+"','"+data[i]['remark_id']+"')\" title='Remarks' class='btn btn-default btn-xs'><i class='fa fa-comment-o'></i> Show Remarks</button>";
			      	}
			      	else if(data[i]['status'] == 'pending'){
			      		color = "label-warning";
                statBut = "<span class='label "+color+"'>"+data[i]['status']+"</span>";
			      	}

			        listapprovalTable.fnAddData([ 
										                i+1,
			                              "<b>"+data[i]['title']+"</b>", 
			                              data[i]['version'], 
			                              data[i]['date_created'],
			                              statBut,
                                    data[i]['desc'],
			                              data[i]['action']
			                              ]); 

			      } // End For
			},
			error: function(){

			}
		});
	}

  function showRemarks(type, id){
    $.ajax({
      url: "<?php echo base_url('procedure/showRemarks') ?>",
      data: {type : type, procedure_id : id},
      type: "GET",
      dataType: "json",
      success: function(data){

        $('#modalRemarks div.modal-header h4.modal-title').html(data.from+"'s Remarks");
        $('#modalRemarks div.modal-body p').html(data.remarks);

        $('#modalRemarks').modal("show");

        console.log(data);
      },
      error: function(){
        console.log("error");
      }
    });
    
  }

	function delete_procedure(id, type){
		$.ajax({
			url: "<?php echo base_url('procedure/delete_procedure') ?>",
			data: {procedure_id : id, type : type},
			dataType: "json",
			type: "GET",
			success: function(data){

				 new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                 });

                 load_list_approval("approved");
			},
			error: function(){

			}
		});
	}

  function confirm_delete_procedure(proc_id){

    $("#comfirm_delete_procedure #delete_procedure_id").val(proc_id);
    $("#comfirm_delete_procedure").modal('show');

  }

  $(document).ready(function(e){
    $('#form-submit-request').bootstrapValidator({
          message: 'This value is not valid', 
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',  
            invalid: 'glyphicon glyphicon-remove',
            validating: 'fa fa-refresh fa-spin',
          },
          fields: {
            procedure: { 
              message: 'what is your reason?',
              validators: {
                notEmpty: {
                  message: 'This field is required and can\'t be empty'
                }
              }
            }
          }
        })  .on('success.form.bv', function(e) {
          
          e.preventDefault();

          var $form     = $(e.target);

          $.ajax({
              url: "<?php echo base_url('procedure/request_delete') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                if(data['result'] == 1){ 

                  new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                  });

                  $('#form-submit-request').bootstrapValidator('resetForm', true);  
                  $('#form-submit-request').bootstrapValidator('disableSubmitButtons', false);

                  load_list_approval("approved");

                  $("#comfirm_delete_procedure #delete_reason").val('');
                  $("#comfirm_delete_procedure").modal('hide');

                }
                else if(data['result'] == 0){

                    new PNotify({
                          title: 'Error',
                          text: data['msg'],
                          type: 'error',
                          icon: 'fa fa-times'
                    });

                }
              },
              error:function(data){
                
              }
          });

      });
  });

	function load_list_procedure(stat){
		$.ajax({
			url: "<?php echo base_url('procedure/load_list') ?>",
			data: {status : stat},
			dataType: "json",
			type: "GET",
			success: function(data){
				  listprocedureTable.fnClearTable();

			      for(var i = 0; i < data.length; i++) { 

			        listprocedureTable.fnAddData([ 
										  i+1,
                      "<b>"+data[i]['title']+"</b>", 
                      data[i]['version'], 
                      data[i]['date_created'],
                      "<button title='Delete' onclick=\"confirm_delete_procedure("+data[i]['procedure_id']+")\" class='btn btn-danger btn-xs flat'><i class='fa fa-times'></i> Delete</button> <button onclick=\"edit_procedure("+data[i]['procedure_id']+");$('#desc_container').removeClass('hide');\" title='Edit' class='btn btn-info btn-xs flat'><i class='fa fa-edit'></i> Edit</button>"
                      ]); 

			      } // End For
			},
			error: function(){

			}
		});
	}

	function edit_procedure(procedure_id){

		$("ul.nav.nav-tabs li").removeClass("active");
		$("div.tab-content div.tab-pane").removeClass("active");

		$("ul.nav.nav-tabs li#tab_create").addClass("active");
		$("div.tab-content div.tab-pane#create").addClass("active");

		$('#text-area-container').addClass("hide");
		$('#form_create_procedure').bootstrapValidator('resetForm', true);  
		$('#form_create_procedure').bootstrapValidator('disableSubmitButtons', false);

		$.ajax({
			url: "<?php echo base_url('procedure/edit') ?>",
			data: {procedure_id : procedure_id},
			type: "GET",
			dataType : "json",
			success: function(data){
				console.log(data);
				$("#check_add_form").prop("checked", true);
				$('#text-area-container').removeClass("hide");
				tinymce.get("text_form_procedure").setContent(data['prod_details']['form']);
				$("input[name=procedure][type=text]").val(data['prod_details']['title']);
				$("input[name=procedure_id][type=text]").val(data['prod_details']['procedure_id']);
        $("select[name=policy_id]").val(data['prod_details']['policy_id']);
				$("#steps_container").html(data['step']);

				procedure_for_edit = data['prod_details']['procedure_id'];
				count_step = data['count_step'];
			},
			error: function(){

			}
		});


	}

	$(function(){

		tinymce.init({
		  selector: '#text_form_procedure',
		  height: 500,
		  theme: 'modern',
		  plugins: [
		    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		    'searchreplace wordcount visualblocks visualchars code fullscreen',
		    'insertdatetime media nonbreaking save table contextmenu directionality',
		    'emoticons template paste textcolor colorpicker textpattern imagetools'
		  ],
		  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		  toolbar2: 'print preview media | forecolor backcolor emoticons',
		  image_advtab: true,
		  templates: [
		    { title: 'Test template 1', content: 'Test 1' },
		    { title: 'Test template 2', content: 'Test 2' }
		  ],
		  content_css: [
		    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
		    '//www.tinymce.com/css/codepen.min.css'
		  ]
		});
		/** ******  switchery  *********************** **/
		if ($(".js-switch")[0]) {
		    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
		    elems.forEach(function (html) {
		        var switchery = new Switchery(html, {
		            color: '#3c8dbc'
		        });
		    });
		}
		/** ******  /switcher  *********************** **/
	});


</script>