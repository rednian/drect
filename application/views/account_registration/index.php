<style type="text/css">
    .info-list{
        padding-left:0px;
    }
    .info-list li{
        margin-bottom:5px;
        margin-right:0px;
    }
</style>

<section class="content">
  <div class="row">
    <div class="col-lg-3 hide" id="profile_info">
        <div class="box box-solid">
          <div class="box-header with-border">
            <b><i class="fa fa-user"></i> User Information</b>
            <a onclick="close_user_info_box()" class="close"><i class="fa fa-times"></i></a>
          </div>
          <div class="box-body">
            Select from list to view . . .
          </div>
        </div>
    </div>
    <div class="col-lg-12" id="col_list_users">
      <div class="box box-solid">
        <div class="box-header with-border">
            
              <a href="<?php echo base_url('user_account/create'); ?>" class="btn btn-default flat"><i class="fa fa-user-plus"></i> Create New User</a>

        </div>
        <div class="box-body">
            
          <table id="table-userss" class="table table-hover table-bordered">
             <thead>
                <tr class="headings">
                    <th>#</th>
                    <th>Name </th>
                    <th>Position </th>
                    <th>Status </th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    $c = 1;
                    foreach($lists as $key => $value){
                      $stat = "warning";
                      if($value->status == "active"){
                        $stat = "success";
                      }
                      elseif($value->status == "disapproved"){
                        $stat = "danger";
                      }
                      elseif($value->status == "inactive"){
                        $stat = "default";
                      }
                      echo "<tr>
                              <td>".$c."</td>
                              <td style='font-weight:bold'><a data-toggle=\"collapse\" data-target=\"#democontent\" href='#' onclick=\"view_user({$value->pi_id})\">".strtoupper($value->fullName('f l'))."</a></td>
                              <td>{$value->position}</td>
                              
                              <td><span class='label label-{$stat}'>{$value->status}</span></td>
                          </tr>

                          ";
                          $c++;
                    } 

                ?>

            </tbody>
          </table>
    
        </div>
      </div>
    </div>
  </div>
</section><!-- /.content -->

<!-- SET REMARKS MODAL -->
<div class="modal fade" id="set_remarks_modal" tabindex="-1" role="dialog">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button  onclick="reset_form_deactivation()" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">WHY DEACTIVATE ?</h4>
      </div>
      <form id="form-deactivate-user" method="post" action="<?php echo base_url('user_account/deactivate_account') ?>">
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" class="form-control" id="deactivate_pi_id" name="pi_id">
          </div>
          <div class="form-group">
            <textarea id="deactivate_reason" name="reason" style="border:none!important" placeholder="Please give your reason . . ." required class="form-control" rows="5"></textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <button onclick="reset_form_deactivation()" type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // SET REMARK MODAL -->

<script type="text/javascript">

    var asInitVals = new Array();
   
            $(document).ready(function () {
                var oTable = $('#table-userss').dataTable({
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Account List</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }
                });
                
            });

    function close_user_info_box(){
      $("#profile_info").addClass("hide");
      $("#col_list_users").removeClass("col-lg-9");
      $("#col_list_users").addClass("col-lg-12");
    }

    function view_user(pi_id){

      $("#profile_info").removeClass("hide");
      $("#profile_info").fadeIn(5000);
      $("#col_list_users").removeClass("col-lg-12");
      $("#col_list_users").addClass("col-lg-9");

      $.ajax({
        url: "<?php echo base_url('user_account/view') ?>",
        data: {pi_id : pi_id},
        type: "GET",
        dataType: "json",
        success: function(data){

          var disabled = "";

          if(data['status'] == "pending"){
            disabled = "hide";
          }

          var info = "<div class=\"form-group\">\
                          <img onerror=\"this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>';\" src='<?php echo base_url('images/profile_image') ?>/"+data['img_path']+"' style='width:100%' class=\"img img-thumbnail\">\
                      </div>\
                      <div class=\"form-group\">\
                          <h3>"+data['name']+"</h3>\
                          <ul class=\"info-list\" style=\"list-style: none;font-size:12px\">\
                              <li class=\"info-list-position\"><i class='fa fa-briefcase'></i> <span>"+data['position']+"</span></li>\
                              <li class=\"info-list-birthdate\"><i class='fa fa-birthday-cake'></i> <span>"+data['birthdate']+"</span></li>\
                              <li class=\"info-list-contact_no\"><i class='fa fa-phone'></i> <span>"+data['contact_no']+"</span></li>\
                              <li class=\"info-list-life_span\"><i class='fa fa-clock-o'></i> Expires: <span>"+data['life_span']+"</span></li>\
                          </ul>";

              if(data['status'] == "active"){

                info +=  "<button onclick=\"set_remarks('"+data['pi_id']+"')\"type=\"button\" class=\"btn btn-danger btn-sm flat\"><i class=\"fa fa-times\"></i> Deactivate Profile</button> <a href=\"<?php echo base_url('user_account/edit/"+Base64.encode(data[\'pi_id\'])+"'); ?>\" type=\"button\" class=\"btn btn-primary btn-sm flat "+disabled+"\"><i class=\"fa fa-times\"></i> Edit Profile</a>\
                      </div>";
              }
                         
          $("#profile_info .box-body").html(info);
        },
        error: function(){

        }
      });
    }

    function set_remarks(pi_id){
      $("#set_remarks_modal #deactivate_pi_id").val(pi_id);
      $("#set_remarks_modal").modal('show');
    }
    
    $(document).ready(function(){

      $("#form-deactivate-user").on("submit", function(e){
        e.preventDefault();

        $.ajax({
              url: "<?php echo base_url('user_account/deactivate_account') ?>",
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

                  reset_form_deactivation();
                  $("#set_remarks_modal").modal('hide');
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

    function reset_form_deactivation(){
      $("#set_remarks_modal #deactivate_pi_id").val("");
      $("#set_remarks_modal #deactivate_reason").val("");
    }

  
</script>
     
      