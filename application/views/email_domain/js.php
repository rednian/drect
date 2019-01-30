<script type="text/javascript">
  //SAVE OFFICIAL EMAIL DOMAIN //
$(document).ready(function(e){
  $('#add_email_form').on('submit',function(e){
    e.preventDefault();
    $.ajax({
      url: "<?php echo base_url('external_functions/email/add_email_domain.php') ?>",
      type: "POST",            
      data: new FormData(this),
      dataType: "json",
      contentType: false,      
      cache: false,            
      processData:false,       
      success: function(data)  
      {
        if(data['result'] == true){
          
          $("#btn_reset").trigger("click");
          load_official_emails();

          new PNotify({
                          title: 'Success',
                          text: "Email Saved",
                          type: 'success',
                          icon: 'fa fa-check'
                  });

        }
        else if(data['result'] == false){
          
          new PNotify({
                          title: 'Error',
                          text: "Email not saved",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });

        }
        else if(data['result'] == 'updated'){
          
          $("#btn_reset").trigger("click");
          load_official_emails();

          new PNotify({
                          title: 'Success',
                          text: "Email Updated",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
        }
        else if(data['result'] == 'not updated'){
          new PNotify({
                          title: 'Error',
                          text: "Email not updated",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });
        }
        else if(data['result'] == 'exist'){
           new PNotify({
                          title: 'Error',
                          text: "Email already exist",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });
        }
      },
      error:function(data){
        showErrorMessage("<i class='fa fa-times'></i>&nbsp;&nbsp;Function ERROR");
      }
    });
  });

  $('#add_email_rt_form').on('submit',function(e){
    e.preventDefault();
    $.ajax({
      url: "<?php echo base_url('external_functions/email/add_email_domain.php') ?>",
      type: "POST",            
      data: new FormData(this),
      dataType: "json",
      contentType: false,      
      cache: false,            
      processData:false,       
      success: function(data)  
      {
        if(data['result'] == true){
          
          $("#btn_reset").trigger("click");
          load_official_emails();

          new PNotify({
                          title: 'Success',
                          text: "Email Saved",
                          type: 'success',
                          icon: 'fa fa-check'
                  });

        }
        else if(data['result'] == false){
          
          new PNotify({
                          title: 'Error',
                          text: "Email not saved",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });

        }
        else if(data['result'] == 'updated'){
          
          $("#btn_reset").trigger("click");
          load_official_emails();

          new PNotify({
                          title: 'Success',
                          text: "Email Updated",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
        }
        else if(data['result'] == 'not updated'){
          new PNotify({
                          title: 'Error',
                          text: "Email not updated",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });
        }
        else if(data['result'] == 'exist'){
           new PNotify({
                          title: 'Error',
                          text: "Email already exist",
                          type: 'Error',
                          icon: 'fa fa-times'
                  });
        }
      },
      error:function(data){
        showErrorMessage("<i class='fa fa-times'></i>&nbsp;&nbsp;Function ERROR");
      }
    });
  });

});

$('button#show_password').mousedown(function(){
  $("input#email_password").attr("type","text");
});

$('button#show_password').mouseup(function(){
  $("input#email_password").attr("type","password");
});


var tbl_official_email = $('#tbl_official_email').dataTable({
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
                    "aoColumns": [
                    { "sClass": "details-control" },
                    null,
                    null,
                    null,
                    null,
                    null
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Official Email Domain</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }});
  
load_official_emails();

function load_official_emails(){
  var check = "";
  $.ajax({
    url: "<?php echo base_url('external_functions/email/load_email_domain.php') ?>",
    dataType: "json",
    success: function(data){
      tbl_official_email.fnClearTable();
      for(var i = 0; i < data.length; i++) { 

        if(data[i]['status'] == 'active'){
          check = "checked";
        }
        else{
          check = "";
        }

        tbl_official_email.fnAddData([ 
                              "<input "+check+" value='"+data[i]['email_id']+"'name=\"radio_email\" type=\"radio\" class=\"radio_email\" style=\"width:15px;height:15px\">", 
                              i + 1, 
                              data[i]['display_name'], 
                              data[i]['email'], 
                              "[password]",
                              "<a onclick=\"edit_domain("+data[i]['email_id']+")\" href=\"#\"><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"confirmDelete("+data[i]['email_id']+",'"+data[i]['email']+"')\" href=\"#\"><i class='fa fa-trash'></i></a>"
                              ]); 
      } // End For
      $('input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      }).on('ifChanged', function(e) {
          // // Get the field name
          var isChecked = e.currentTarget.checked;

          if (isChecked == true) {
            confirm_update_status($(this).val());
          }
      });
    },
    error: function(){

    }
  });
}

$(document).ready(function(){
  $('input[type=radio]').on('ifChecked', function(event){
    alert("ok");
  });
});

var confirmID;

function confirm_update_status(id){
   $("#confirm_change_email_domain").modal('show');
   confirmID = id;
}
function update_status(){
  $.ajax({
    url: "<?php echo base_url('external_functions/email/update_status_email_domain.php') ?>",
    data: {update : confirmID},
    dataType: "html",
    success: function(data){
      if(data == 'updated'){
       
        load_official_emails();
        $("#confirm_change_email_domain").modal('hide');
        new PNotify({
                          title: 'Success',
                          text: "Updated successfully",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
      }
      else if(data == 'not updated'){
        $("#confirm_change_email_domain").modal('hide');
        new PNotify({
                          title: 'Error',
                          text: "Error Update",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
      }
       
    },
    error: function(){
      $("#confirm_change_email_domain").modal('hide');
      new PNotify({
                          title: 'Error',
                          text: "Function error",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
    }
  });
}

var selected_email_id = "";
function confirmDelete(email_id,email){
  $("#confirm_delete_email_domain .message").html("Are you sure you want delete <b>"+email+"</b> ?");
   $("#confirm_delete_email_domain").modal('show');
   selected_email_id = email_id;
}

function delete_domain(){
   $.ajax({
    url:"<?php echo base_url('external_functions/email/delete_email_domain.php') ?>",
    data: {del : selected_email_id},
    dataType: "html",
    success: function(data){
      if(data == 'deleted'){
        
        load_official_emails();
        $("#confirm_delete_email_domain").modal('hide');
        selected_email_id = "";

         new PNotify({
                          title: 'Success',
                          text: "Deleted successfully",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
      }
      else if(data == 'not deleted'){
        $("#confirm_delete_email_domain").modal('hide');
        new PNotify({
                          title: 'Error',
                          text: "Error delete",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
      }
       
    },
    error: function(){
      $("#confirm_delete_email_domain").modal('hide');
      new PNotify({
                          title: 'Error',
                          text: "Function error",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
    }
  });
}

function edit_domain(email_id){
  $.ajax({
    url: "<?php echo base_url('external_functions/email/edit_email_domain.php') ?>",
    data: {edit : email_id},
    dataType: 'json',
    success: function(data){
      $.each( data, function( key, value ) {
        $('#'+key).val(value);
      });
    },
    error: function(){

    }
  })
  // alert(email_id);
}
</script>
