<section class="content">
	<div class="row">
		
		<div class="col-lg-12">
			
			<form method="post" id="form_submit_request" enctype="multipart/form-data" action="<?php echo base_url("task/insert_request") ?>">

			<div class="form-group">
				<button type="submit" class="btn btn-success flat"><i class="fa fa-save"></i> Send Task</button>
				<button type="reset" class="btn btn-danger flat"><i class="fa fa-times"></i> Cancel</button>
				<a href="<?php echo base_url('task') ?>" class="btn btn-primary flat pull-right"><i class="fa fa-table"></i> Back to list</a>
			</div>

			<div class="box box-solid">
				<div class="box-body">
					
						<div class="row">
							<div class="col-md-7">
								<div class="form-group hide">
									<label>Reference No.</label>
									<input type="text" value="<?php echo $gen_rt_id ?>" class="form-control" readonly name="rt_ref_no">
								</div>
								<div class="form-group">
									<label>Task Type</label>
									<select required onchange="get_form($(this).val())" class="form-control" name="procedure_id">

										<option class="hide"></option>

										<?php foreach ($procedure as $key => $value): ?>
											
											<option value="<?php echo $value->procedure_id ?>"><?php echo $value->procedure ?></option>

										<?php endforeach ?>

									</select>
								</div>
								<div class="form-group">
									<label>Task Title</label>
									<input required placeholder="Enter title here . . ." type="text" class="form-control" name="rt_title">
								</div>
								<div class="form-group">
									<label>Attach Files</label>
									<input data-allowed-file-extensions='["csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' id="input-2" name="rta_attachment[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
								</div>
							</div>
							
						</div>
						<div class="form-group">
							<textarea id="rt_content" name="rt_content"></textarea>
						</div>
					
				</div>
			</div>

			</form>

		</div>
	</div>
</section>

<script type="text/javascript">

	$(function(){

		tinymce.init({
		  selector: '#rt_content',
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

	});

	function get_form(procedure_id){
		$.ajax({
			url: "<?php echo base_url('task/get_form_procedure') ?>",
			data: {procedure_id : procedure_id},
			type: "GET",
			dataType:"html",
			success: function(data){

				tinymce.get("rt_content").setContent(data);
			},
			error: function(){
			}
		});
	}

	$(document).ready(function(){

		$("#form_submit_request").on("submit", function(e){

			e.preventDefault();
			tinyMCE.triggerSave();

			$.ajax({
              url: "<?php echo base_url('task/insert_request') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                if(data['result'] == 1){ 

                  $("button[type=reset]").trigger("click");
               	  gen_ref_no();
               	  
                  new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                  });

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

	function gen_ref_no(){
		$.ajax({
			url: "<?php echo base_url('request/new_generate_ref_no') ?>",
			dataType: "html",
			success: function(data){
				$("input[type=text][name=rt_ref_no]").val(data);
			},
			error: function(){

			}
		});
	}
</script>