<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php echo form_open_multipart('PolicyController/add', ["id"=>'frmAddPolicy']) ?>
			<div class="box box-solid">
				<div class="box-header with-border">
					<a type="button" href="<?php echo base_url('policy') ?>" class="btn btn-default pull-right flat" style="margin-left:10px">Back to List</a> <button type="submit" class="btn btn-success pull-right flat"><i class="fa fa-save"></i> Save Policy</button>
					<button class="hide" type="reset">reset</button>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Policy No.</label>
								<input autocomplete="off" type="text" class="form-control" name="policy_no">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Policy Title</label>
								<input autocomplete="off" type="text" class="form-control" name="policy_title">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Attachment</label>
								<input id="rtp_att" name="path[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
			                	<label style="cursor:pointer;-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on" onselectstart="return false;" onmousedown="return false;">
			                      <input id="check_add_form" type="checkbox" class="js-switch" />&nbsp;&nbsp;&nbsp;<span>Add Content</span>
			                  </label>
			                </div>
							<div class="form-group hide" id="text-area-container">
			    				<textarea name="policy_content" id="text_form_procedure"></textarea>
			    			</div>
						</div>
					</div>

				</div>
			</div>
			<?php echo form_close() ?>
		</div>
	</div>
</section>

<script type="text/javascript">
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

	$(document).ready(function(){
		$("#frmAddPolicy").on("submit", function(e){

			e.preventDefault();
			tinyMCE.triggerSave();

			$.ajax({
              url: "<?php echo base_url('PolicyController/add') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {

                if(data['result'] == true){ 
                	$("#frmAddPolicy button[type=reset]").trigger("click");
	                  new PNotify({
	                          title: 'Success',
	                          text: "Policy has been saved successfully.",
	                          type: 'success',
	                          icon: 'fa fa-check'
	                  });

                }
                else if(data['result'] == false){

                    new PNotify({
                          title: 'Error',
                          text: 'Policy not saved.',
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
</script>