<style type="text/css">

.view_email_container{
	width:100%;position:relative;background:#FFF;min-height:500px;max-height:700px;overflow:auto;padding:20px;padding-top:5px;
}
	/* accordion panel-group */
.panel-group {
    margin-bottom: 5px;
}
.panel-group .panel {
   border:none;
   -webkit-box-shadow: none;
    box-shadow: none;
    border-radius: 0px;
    border-bottom:1px solid #f3f3f3;
}
.panel-default>.panel-heading {
    background-color: #FFF;
    padding-left:0px;
    cursor: pointer;
}
.panel-group .panel-heading+.panel-collapse>.panel-body {
    border-top: none;
    padding-left:50px;
    padding-top:0px;
    text-align:justify;
}

.selectize-control.contacts .selectize-input > div {
			padding: 1px 10px;
			font-size: 13px;
			font-weight: normal;
			-webkit-font-smoothing: auto;
			color: #f7fbff;
			text-shadow: 0 1px 0 rgba(8,32,65,0.2);
			background: #2183f5;
			background: -moz-linear-gradient(top, #2183f5 0%, #1d77f3 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2183f5), color-stop(100%,#1d77f3));
			background: -webkit-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: -o-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: -ms-linear-gradient(top,  #2183f5 0%,#1d77f3 100%);
			background: linear-gradient(to bottom,  #2183f5 0%,#1d77f3 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2183f5', endColorstr='#1d77f3',GradientType=0 );
			border: 1px solid #0f65d2;
			/*-webkit-border-radius: 999px;
			-moz-border-radius: 999px;
			border-radius: 999px;*/
			-webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
			-moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15);
			box-shadow: 0 1px 1px rgba(0,0,0,0.15);
		}
		.selectize-control.contacts .selectize-input > div.active {
			background: #0059c7;
			background: -moz-linear-gradient(top, #0059c7 0%, #0051c1 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#0059c7), color-stop(100%,#0051c1));
			background: -webkit-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: -o-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: -ms-linear-gradient(top,  #0059c7 0%,#0051c1 100%);
			background: linear-gradient(to bottom,  #0059c7 0%,#0051c1 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0059c7', endColorstr='#0051c1',GradientType=0 );
			border-color: #0051c1;
		}
		.selectize-control.contacts .selectize-input > div .email {
			opacity: 0.8;
		}
		.selectize-control.contacts .selectize-input > div .name + .email {
			margin-left: 5px;
		}
		.selectize-control.contacts .selectize-input > div .email:before {
			content: '<';
		}
		.selectize-control.contacts .selectize-input > div .email:after {
			content: '>';
		}
		.selectize-control.contacts .selectize-dropdown .caption {
			font-size: 12px;
			display: block;
			color: #a0a0a0;
		}

		.selectize-dropdown-content span.label{
			color:#333 !important;
		}
		.selectize-dropdown-content span.caption{
			display: none !important;
		}

		.selectize-input.items span.name+span.email{
			display: none !important;
		}

		.view_email_container{
			width:100%;position:relative;background:#FFF;min-height:500px;max-height:700px;overflow:auto;padding:20px;padding-top:5px;
		}
</style>
<script type="text/javascript">
	$(".content-header small").html("<a href=\"<?php echo base_url('mail_circulate') ?>\" class='btn btn-xs btn-primary flat'><i class='fa fa-arrow-left'></i> Back to Mail</a>");
</script>

<section class="content">
	
	<div class="view_email_container">
		
		<div class="row">
			<div class="col-lg-10">
					<?php $count = 1; ?>
					<div class="panel-group" id="accordion_<?php echo $count; ?>" role="tablist" aria-multiselectable="true">

					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="heading_<?php echo $count; ?>" data-toggle="collapse" data-parent="#accordion_<?php echo $count; ?>" href="#collapse_<?php echo $count; ?>" aria-expanded="true" aria-controls="collapseOne">

					      <h4 class="panel-title clearfix">
					      	<span class="pull-right"><?php echo $date." (".$ellapse." ago)" ?></span>
					      	<img onerror="imgError(this)" src="<?php echo base_url('images/profile_image')."/".$img_path ?>" class="img pull-left" style="width:40px;height:40px;margin-right:10px">
					        <span style="font-weight:bold">
					          <?php echo $from ?>
					        </span>
					        <div id="content_summary_<?php echo $count ?>" style="color:#666;width:50%;font-size:14px;padding-top:4px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden"><?php echo "to ".$to; ?></div>
					      </h4>
					    </div>
					    <div id="collapse_<?php echo $count; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_<?php echo $count; ?>">
					      <div class="panel-body">

					      	<?php echo $content; ?>

					      	<?php if (strlen($attachments) > 0): ?>
					      	
					      	<div class="attachment clearfix" style="margin-top:20px;margin-bottom:20px">
					      		<p><b><i class="fa fa-paperclip"></i> Attachments:</b></p>
					      		<?php echo $attachments ?>
					      	</div>

					      	<?php endif ?>

					      </div>
					    </div>
					  </div>
					  
					</div>

			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-10 clearfix">
				
				<div class="pull-left">
					<img onerror="imgError(this)" src="<?php echo base_url('images/profile_image')."/".$this->userInfo->img_path ?>" class="img" style="width:40px;height:40px;margin-right:10px;margin-bottom:5px">
				</div>
				<div style="padding-left:50px;">

					<form id="form_dispatch" method="post" action="<?php echo base_url('mail_circulate/forward'); ?>" enctype="multipart/form-data">

						<div class="form-group">
							<input type="hidden" name="mail_id" value="<?php echo $mail_id; ?>">
				            <select name="txtcontact[]" id="select-to" class="contacts" placeholder="Pick some people..."></select>
				             <script>
        // <select id="select-to"></select>

	        var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
	                          '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

	        var formatName = function(item) {
	          return $.trim((item.first_name || '') + ' ' + (item.last_name || ''));
	        };

	        $('#select-to').selectize({
	          persist: false,
	          maxItems: null,
	          valueField: 'email',
	          labelField: 'name',
	          searchField: ['first_name', 'last_name', 'email'],
	          sortField: [
	            {field: 'first_name', direction: 'asc'},
	            {field: 'last_name', direction: 'asc'}
	          ],
	          options: <?php echo $contacts ?>,
	          render: {
	            item: function(item, escape) {
	              var name = formatName(item);
	              return '<div>' +
	                (name ? '<span class="name">' + escape(name) + '</span>' : '') +
	                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
	              '</div>';
	            },
	            option: function(item, escape) {
	              var name = formatName(item);
	              var label = name || item.email;
	              var caption = name ? item.email : null;
	              return '<div>' +
	                '<span class="label">' + escape(label) + '</span>' +
	                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
	              '</div>';
	            }
	          },
	          createFilter: function(input) {
	            var regexpA = new RegExp('^' + REGEX_EMAIL + '$', 'i');
	            var regexpB = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
	            return regexpA.test(input) || regexpB.test(input);
	          },
	          create: function(input) {
	            if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
	              return {email: input};
	            }
	            var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
	            if (match) {
	              var name       = $.trim(match[1]);
	              var pos_space  = name.indexOf(' ');
	              var first_name = name.substring(0, pos_space);
	              var last_name  = name.substring(pos_space + 1);

	              return {
	                email: match[2],
	                first_name: first_name,
	                last_name: last_name
	              };
	            }
	            alert('Invalid email address.');
	            return false;
	          }
	        });

	        </script>

          				</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success flat"><i class="fa fa-send"></i> Forward</button>
						</div>
					</form>

				</div>
				
			</div>
		</div>
		
	</div>

</section>


<script type="text/javascript">
	// DISPATCH

	$(document).ready(function(){

		$("form#form_dispatch").on("submit", function(e){

			e.preventDefault();

			$.LoadingOverlay("show", {
			    image       : "<?php echo base_url('assets/images/sending.gif') ?>",
			    fontawesome : ""
			});

			$.ajax({
	          url: "<?php echo base_url('mail_circulate/forward') ?>",
	          type: "POST",            
	          data: new FormData(this),
	          dataType: "json",
	          contentType: false,      
	          cache: false,            
	          processData:false,       
	          success: function(data)  
	          {

	          	$.LoadingOverlay("hide");

	            if(data['result'] == 1){ 

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
	          	$.LoadingOverlay("hide");

	           	new PNotify({
                          title: 'Error',
                          text: 'Function Error',
                          type: 'error',
                          icon: 'fa fa-times'
                    });
	          }
	       	});

		});
	});
</script>