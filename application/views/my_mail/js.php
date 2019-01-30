<script type="text/javascript">
	// WYSIWYG
	var txtmessage = $("#txtmessage").wysihtml5();
	// TABLE INBOX
	var tbl_inbox = $('#table-inbox').dataTable({
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

var tbl_circulation = $('#table-circulation').dataTable({
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Sent Messages</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
        });

	function form_reset(){

		var $select = $('#select-to').selectize();
		 var control = $select[0].selectize;
		 control.clear();

	      $("button[type=reset]").trigger("click");
	}

	$(document).ready(function(){

		$("form#form_send_mail").on("submit", function(e){
			e.preventDefault();
			$.ajax({
	          url: "<?php echo base_url('mail/insert_my_mail') ?>",
	          type: "POST",            
	          data: new FormData(this),
	          dataType: "json",
	          contentType: false,      
	          cache: false,            
	          processData:false,       
	          success: function(data)  
	          {
	            if(data['result'] == 1){ 

               load_sent_emails();
	             form_reset();

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
  
  load_sent_emails();

  function load_sent_emails(){
    $.ajax({
      url: "<?php echo base_url('mail/load_sent_emails') ?>",
      dataType: "json",
      success: function(data){

        tbl_sent.fnClearTable();

        var count = 0;
        // LOOP FOR MY MAIL SENT
        for(var i=0;i<data[0].length;i++){
          tbl_sent.fnAddData([
                              data[0][i]['checkbox']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[0][i]['to']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[0][i]['subject'],
                              "<span class='pull-right'>"+data[0][i]['attachment']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[0][i]['date']+"</span>"
                            ]);
          count++;
        }
        // LOOP FOR DISPATCH SENT
        for(var i=0;i<data[1].length;i++){
          tbl_sent.fnAddData([
                              data[1][i]['checkbox']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[1][i]['to']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[1][i]['subject'],
                              "<span class='pull-right'>"+data[1][i]['attachment']+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+data[1][i]['date']+"</span>"
                            ]); 
          count++;
        }

        $("#tab_sent .badge").html(count);
        
      },
      error: function(data){

      }

    });
  }

// SELECT ALL SENT MAIL
$('#option_select_all_sent').change(function() {
        $('.checkboxmail_sent').prop("checked", this.checked);
});

$('.checkboxmail_sent').change(function(){

    if($('input:checkbox:checked.checkboxmail_sent').length === $("input:checkbox.checkboxmail_sent").length)
    {
        $('#option_select_all_sent').prop("checked",true);
    }
    else
    {
        $('#option_select_all_sent').prop("checked",false);
    }

});

// SELECT ALL INBOX
$('#option_select_all_inbox').change(function() {
        $('.checkboxmail_inbox').prop("checked", this.checked);
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


// SELECT ALL INBOX
$('#option_select_all_trash').change(function() {
        $('.checkboxmail_trash').prop("checked", this.checked);
});

$('.checkboxmail_trash').on("change", function(){

    if($('input:checkbox:checked.checkboxmail_trash').length === $("input:checkbox.checkboxmail_trash").length)
    {
        $('#option_select_all_trash').prop("checked",true);
        // alert("all checkbox were selected");
    }
    else
    {
        // alert("some checkbox were not selected");
        $('#option_select_all_trash').prop("checked",false);
    }

});


// LOAD INBOX
load_inbox();

function load_inbox(){
  $.ajax({
    url: "<?php echo base_url('mail/load_inbox') ?>",
    dataType: "json",
    success: function(data){  

       tbl_inbox.fnClearTable();
        var count = 0;
        // LOOP FOR MY MAIL SENT
        for(var i=0;i<data.length;i++){

          tbl_inbox.fnAddData([
                              data[i]['checkbox'],
                              data[i]['star'],
                              data[i]['from'],
                              data[i]['subject'],
                              data[i]['attachment'],
                              "<span class='pull-right'>"+data[i]['date']+"</span>",
                            ]);
          count++;
          if(data[i]['read_status'] == 'no'){
            $('table#table-inbox tbody tr:last-child td').css('background','#FFF8E5');
          }

           // $('table#table-inbox tbody tr:last-child td:nth-child(4)').css({'white-space':'nowrap','overflow':'hidden','text-overflow':'ellipsis'});
           
        }

        $("#tab_inbox .badge").html(count);
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

load_inbox_trash();

function load_inbox_trash(){
  $.ajax({
    url: "<?php echo base_url('mail/load_inbox_trash') ?>",
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

function update_star(sent_id){
  $.ajax({
    url: "<?php echo base_url('mail/make_important') ?>",
    data: {star : sent_id},
    type: "GET",
    dataType: "html",
    success: function(data){

    },
    error: function(){

    }
  });
  
}

load_circulation();

function load_circulation(){
  $.ajax({
    url: "<?php echo base_url('mail/load_circulation') ?>",
    dataType: "json",
    success: function(data){  

       tbl_circulation.fnClearTable();
        var count = 0;
        // LOOP FOR MY MAIL SENT
        for(var i=0;i<data.length;i++){

          tbl_circulation.fnAddData([
                              data[i]['checkbox'],
                              data[i]['star'],
                              data[i]['from'],
                              data[i]['subject'],
                              data[i]['attachment'],
                              "<span class='pull-right'>"+data[i]['date']+"</span>",
                            ]);
          count++;
          if(data[i]['read_status'] == 'no'){
            $('table#table-circulation tbody tr:last-child td').css('background','#FFF8E5');
          }

           // $('table#table-inbox tbody tr:last-child td:nth-child(4)').css({'white-space':'nowrap','overflow':'hidden','text-overflow':'ellipsis'});
           
        }

        $("#tab_circulation .badge").html(count);
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

</script>