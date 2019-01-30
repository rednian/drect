<?php include("css.php") ?>

<section class="content">
	<div class="row">
		<div class="col-lg-12">
			
			<div>

			 
			  	<button onclick="$('#composeMail').modal('show')" class="btn btn-primary pull-right flat"><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Compose Mail</button>
			  
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li id="tab_inbox" role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-inbox"></i> Inbox&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			    <li id="tab_sent" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-send-o"></i> Sent&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			    <li id="tab_circulation" role="presentation"><a href="#circulation" aria-controls="circulation" role="tab" data-toggle="tab"><i class="fa fa-inbox"></i> Circulation&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			    <li id="tab_trash" role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-trash"></i> Trash&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content" style="padding:10px;background:#FFF">
			    <div role="tabpanel" class="tab-pane active" id="home">
			    	<h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Inbox</h1>
			    	<form method="post" action="<?php echo base_url('mail/delete_inbox') ?>" id="form_delete_inbox">
				    	<table class="table table-hover" style="width:100%" id="table-inbox">
				    		<thead>
				    			<tr>
					    			<th style="width:2%">
					    				<span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_inbox" type="checkbox" style="width:16px;height:16px"></span>
					    			</th>
					    			<th style="width:1%"></th>
					    			<th style="width:17%"></th>
					    			<th style="width:65%"></th>
					    			<th style="width:1%"></th>
					    			<th style="width:15%;text-align:right"><button id="btn_inbox_trash" type="submit" class="btn btn-default btn-xs flat"><i class="fa fa-trash"></i> Trash</button>
					    				<button onclick="load_inbox()" type="button" class="btn btn-default btn-xs flat"><i class="fa fa-refresh"></i> Refresh</button></th>
				    			</tr>
				    		</thead>
				    	</table>
			    	</form>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="messages">
			    	<h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Sent Messages</h1>
			    	<table class="table table-hover" id="table-sent">
			    		<thead>
			    			<tr>
				    			<th>
				    				<span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_sent" type="checkbox" style="width:16px;height:16px"></span>
				    			</th>
				    			<th style="text-align:right"><!-- <button class="btn btn-default btn-xs flat"><i class="fa fa-trash"></i> Trash</button> -->
				    				<button onclick="load_sent_emails()" class="btn btn-default btn-xs flat"><i class="fa fa-refresh"></i> Refresh</button></th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			
			    		</tbody>
			    	</table>
			    </div>

			    <div role="tabpanel" class="tab-pane" id="circulation">
			    	<h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">From Circulation</h1>
			    	<form method="post" action="">
				    	<table class="table table-hover" style="width:100%" id="table-circulation">
				    		<thead>
				    			<tr>
					    			<th style="width:2%">
					    				<span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_circulation" type="checkbox" style="width:16px;height:16px"></span>
					    			</th>
					    			<th style="width:1%"></th>
					    			<th style="width:17%"></th>
					    			<th style="width:65%"></th>
					    			<th style="width:1%"></th>
					    			<th style="width:15%;text-align:right">
					    				<button onclick="load_circulation()" type="button" class="btn btn-default btn-xs flat"><i class="fa fa-refresh"></i> Refresh</button></th>
				    			</tr>
				    		</thead>
				    	</table>
			    	</form>
			    </div>

			    <div role="tabpanel" class="tab-pane" id="settings">
			    	
			    	<h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Trash</h1>
			    	<form method="post" action="<?php echo base_url('mail/restore_mail') ?>" id="form_restore_inbox">
				    	<table class="table table-hover" style="width:100%" id="table-trash">
				    		<thead>
				    			<tr>
					    			<th style="width:2%">
					    				<span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_trash" type="checkbox" style="width:16px;height:16px"></span>
					    			</th>
					    			<th style="width:1%"></th>
					    			<th style="width:17%"></th>
					    			<th style="width:64%"></th>
					    			<th style="width:1%"></th>
					    			<th style="width:15%;text-align:right"><button id="btn_trash_restore" type="submit" class="btn btn-default btn-xs flat"><i class="fa fa-trash"></i> Restore</button>
				    			</tr>
				    		</thead>
				    	</table>
			    	</form>
			    
			    </div>
			  </div>

			</div>

		</div>
	</div>
</section>

<!-- MODAL WRITE EMAIL MODAL -->
<div class="modal fade" id="composeMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg pull-right">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">New Message</h4>
      </div>
      <form id="form_send_mail" method="post" action="<?php echo base_url('mail/insert_my_mail') ?>" enctype="multipart/form-data">
      <div class="modal-body">
          <div class="form-group">
            <select name="txtcontact[]" id="select-to" class="contacts" placeholder="Pick some people..."></select>

            </select>
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
            <input name="mail_subject" required type="text" class="form-control input-sm" placeholder="Subject">
          </div>
          <div class="form-group">
            <textarea name="mail_content" id="txtmessage" class="textarea" placeholder="Message" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
          </div>
          <div class="form-group">
              <label for="exampleInputEmail1">Attachments</label>
              <input data-allowed-file-extensions='["csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' id="input-2" name="rta_attachment[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
          </div>
      </div>
      <div class="modal-footer flat" style="background:#f3f3f3">
        <button type="button" class="btn btn-primary btn-sm flat" data-dismiss="modal" onclick="form_reset()">Close</button>
        <button type="reset" id="btn_reset_form" class="btn btn-danger btn-sm flat hide">Reset</button>
        <button type="submit" name="btn-send" class="btn btn-primary btn-sm flat"><i class="fa fa-check"></i> Send</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include("js.php") ?>
<script type="text/javascript">

	$(document).ready(function(){

		$("form#form_delete_inbox").on("submit", function(e){
			e.preventDefault();

			if($('input:checkbox:checked.checkboxmail_inbox').length > 0){
				$.ajax({
		          url: "<?php echo base_url('mail/delete_inbox') ?>",
		          type: "POST",            
		          data: new FormData(this),
		          dataType: "json",
		          contentType: false,      
		          cache: false,            
		          processData:false,       
		          success: function(data)  
		          {
		          		load_inbox();
		          		load_inbox_trash();

		            	new PNotify({
	                          title: 'Success',
	                          text: data['msg'],
	                          type: 'success',
	                          icon: 'fa fa-check'
	                  });
		          },
		          error:function(data){
		           	new PNotify({
	                          title: 'Error',
	                          text: 'Function Error',
	                          type: 'error',
	                          icon: 'fa fa-times'
	                    });
		          }
		       	});

			}
			else{

			}
			
		});

		$("form#form_restore_inbox").on("submit", function(e){
			e.preventDefault();

			if($('input:checkbox:checked.checkboxmail_trash').length > 0){
				$.ajax({
		          url: "<?php echo base_url('mail/restore_mail') ?>",
		          type: "POST",            
		          data: new FormData(this),
		          dataType: "json",
		          contentType: false,      
		          cache: false,            
		          processData:false,       
		          success: function(data)  
		          {
		          		load_inbox();
		          		load_inbox_trash();

		            	new PNotify({
	                          title: 'Success',
	                          text: data['msg'],
	                          type: 'success',
	                          icon: 'fa fa-check'
	                  });
		          },
		          error:function(data){
		           	new PNotify({
	                          title: 'Error',
	                          text: 'Function Error',
	                          type: 'error',
	                          icon: 'fa fa-times'
	                    });
		          }
		       	});

			}
			else{

			}
			
		});

	});
</script>