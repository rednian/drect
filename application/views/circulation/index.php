<style type="text/css">
	table thead tr th{
		background:#f3f3f3;
		color:#000;
	}

	input[type=checkbox] {
    	margin: 0px;
    	margin-bottom:0px;
	}
  .unread{
    background: #FFF8E5;
  }
</style>
<section class="content">
	
	<div class="row">

		<div class="col-lg-12">
			
			<div>

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li id="tab_inbox" role="presentation" class="active"><a href="#inbox" aria-controls="inbox" role="tab" data-toggle="tab"><i class="fa fa-inbox"></i> Inbox&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			    <li id="tab_sent" role="presentation"><a href="#sent" aria-controls="sent" role="tab" data-toggle="tab"><i class="fa fa-send"></i> Sent&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			    <li id="tab_trash" role="presentation"><a href="#trash" aria-controls="trash" role="tab" data-toggle="tab"><i class="fa fa-trash"></i> Trash&nbsp;&nbsp;&nbsp;<span class="badge">0</span></a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content" style="padding:10px;background:#FFF">

			  	<!-- INBOX DISPATCH -->
			    <div role="tabpanel" class="tab-pane active" id="inbox">
			    	<h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Inbox</h1>
			    	<form method="post" id="form_delete_inbox">
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
			    <!-- END INBOX DISPATCH -->

			    <div role="tabpanel" class="tab-pane" id="sent">
              <h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Sent</h1>
            <form method="post" id="form_delete_inbox">
              <table class="table table-hover" style="width:100%" id="table-sent">
                <thead>
                  <tr>
                    <th style="width:2%">
                      <span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_sent" type="checkbox" style="width:16px;height:16px"></span>
                    </th>
                    <th style="width:1%"></th>
                    <th style="width:17%"></th>
                    <th style="width:65%"></th>
                    <th style="width:1%"></th>
                    <th style="width:15%;text-align:right">
                      <button onclick="load_sent()" type="button" class="btn btn-default btn-xs flat"><i class="fa fa-refresh"></i> Refresh</button></th>
                  </tr>
                </thead>
              </table>
            </form>   
          </div>

			    <div role="tabpanel" class="tab-pane" id="trash">
            <h1 style="font-weight:bold;font-size:24px;margin-top:0px;margin-bottom:20px">Trash</h1>
            <form method="post" id="form_restore_inbox">
              <table class="table table-hover" style="width:100%" id="table-trash">
                <thead>
                  <tr>
                    <th style="width:2%">
                      <span data-toggle="tooltip" data-placement="bottom" title="Select all" style='font-weight:normal'><input id="option_select_all_trash" type="checkbox" style="width:16px;height:16px"></span>
                    </th>
                    <th style="width:1%"></th>
                    <th style="width:17%"></th>
                    <th style="width:65%"></th>
                    <th style="width:1%"></th>
                    <th style="width:15%;text-align:right"><button id="btn_inbox_trash" type="submit" class="btn btn-default btn-xs flat"><i class="fa fa-trash"></i> Restore</button>
                      </th>
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

<script type="text/javascript">

  // load_inbox();

  var retrieveEmails = setInterval(getMails, 10000);

  function getMails(){
    clearInterval(retrieveEmails);
    $.ajax({
      url: "<?php echo base_url('mail_circulate/retrieve_emails') ?>",
      dataType: "json",
      success: function(data){
        retrieveEmails = setInterval(getMails, 30000);
      },
      error: function(){
        retrieveEmails = setInterval(getMails, 30000);
      }
    });
  }

  var tbl_inbox = $('#table-inbox').DataTable({
    "ajax": "<?php echo base_url('mail_circulate/load_inbox') ?>",
    "createdRow": function ( row, data, index ) {
                    
                      if(data[6] == "no"){
                        $('td', row).addClass('unread');
                      }
                  },
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
            "bSort": false,
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Inbox</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
  });

  setInterval( function () {
    tbl_inbox.ajax.reload(null, false);
    console.log("inbox reload");
  }, 5000 );

  var tbl_sent = $('#table-sent').dataTable({
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
            "bSort": false,
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Inbox</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
        });

  var tbl_trash = $('#table-trash').dataTable({
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
            "bSort": false,
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Inbox</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
        });

function update_star(mail_id){
  $.ajax({
    url: "<?php echo base_url('mail_circulate/make_important') ?>",
    data: {star : mail_id},
    type: "GET",
    dataType: "html",
    success: function(data){

    },
    error: function(){

    }
  });
  
}

$('#option_select_all_inbox').change(function() {
        $('.checkboxmail_inbox').prop("checked", this.checked);
});

$('#option_select_all_trash').change(function() {
        $('.checkboxmail_trash').prop("checked", this.checked);
});

$('#option_select_all_sent').change(function() {
        $('.checkboxmail_sent').prop("checked", this.checked);
});

$('.checkboxmail_inbox').on("change", function(){

    if($('input:checkbox:checked.checkboxmail_inbox').length === $("input:checkbox.checkboxmail_inbox").length)
    {
        $('#option_select_all_inbox').prop("checked",true);
        // alert("all checkbox were selected");
    }
    else
    {
        // alert("some checkbox were not selected");
        $('#option_select_all_inbox').prop("checked",false);
    }

});

$(document).ready(function(){

    $("form#form_delete_inbox").on("submit", function(e){
      e.preventDefault();

      if($('input:checkbox:checked.checkboxmail_inbox').length > 0){
        $.ajax({
              url: "<?php echo base_url('mail_circulate/delete_inbox') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                  load_inbox();
                  load_trash();
                  // load_inbox_trash();
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

load_trash();
function load_trash(){
  $.ajax({
    url: "<?php echo base_url('mail_circulate/load_trash') ?>",
    dataType: "json",
    success: function(data){  

        tbl_trash.fnClearTable();
        var count = 0;
        // LOOP FOR MY MAIL SENT
        for(var i=0;i<data.length;i++){

          tbl_trash.fnAddData([
                              data[i]['checkbox'],
                              data[i]['star'],
                              data[i]['from'],
                              data[i]['subject'],
                              data[i]['attachment'],
                              "<span class='pull-right'>"+data[i]['date']+"</span>",
                            ]);
          count++;
           
        }

        $("#tab_trash .badge").html(count);
        //Handle starring for glyphicon and font awesome
        $(".mailbox-star").click(function (e) {
          e.preventDefault();
          //detect type
          var $this = $(this).find("i");
          var glyph = $this.hasClass("glyphicon");
          var fa = $this.hasClass("fa");          

          //Switch states
          if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
          }

          if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
          }
        });
    },
    error: function(){

    }
  }); 
}

// function load_trash(){
//   $.ajax({
//     url: "<?php echo base_url('mail_circulate/load_sent') ?>",
//     dataType: "json",
//     success: function(data){  

//         tbl_sent.fnClearTable();
//         var count = 0;
//         // LOOP FOR MY MAIL SENT
//         for(var i=0;i<data.length;i++){

//           tbl_sent.fnAddData([
//                               data[i]['checkbox'],
//                               data[i]['star'],
//                               data[i]['from'],
//                               data[i]['subject'],
//                               data[i]['attachment'],
//                               "<span class='pull-right'>"+data[i]['date']+"</span>",
//                             ]);
//           count++;
           
//         }

//         $("#tab_sent .badge").html(count);
//         //Handle starring for glyphicon and font awesome
//         $(".mailbox-star").click(function (e) {
//           e.preventDefault();
//           //detect type
//           var $this = $(this).find("i");
//           var glyph = $this.hasClass("glyphicon");
//           var fa = $this.hasClass("fa");          

//           //Switch states
//           if (glyph) {
//             $this.toggleClass("glyphicon-star");
//             $this.toggleClass("glyphicon-star-empty");
//           }

//           if (fa) {
//             $this.toggleClass("fa-star");
//             $this.toggleClass("fa-star-o");
//           }
//         });
//     },
//     error: function(){

//     }
//   }); 
// }

$(document).ready(function(){

    $("form#form_restore_inbox").on("submit", function(e){
      e.preventDefault();

      if($('input:checkbox:checked.checkboxmail_trash').length > 0){
        $.ajax({
              url: "<?php echo base_url('mail_circulate/restore_inbox') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                  load_inbox();
                  load_trash();
                  // load_inbox_trash();
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