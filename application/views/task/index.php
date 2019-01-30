<?php $count = $countPending['pending'] ?>
<style type="text/css">
  .div_hide{
    visibility: hidden;
  }
  #note{
    color: #666666;
    font-size: 12px;
    margin: 0 auto;
    padding: 4px;
    text-align: center;
    text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.3);
    width: 400px;
  }
</style>
<section class="content">
  
  <div class="row">
    <div class="col-lg-12">
      
      <div>
        <a id="sample_right" href="<?php echo base_url("task/create") ?>" class="btn btn-primary pull-right flat"><i class="fa fa-edit"></i> Create Task</a>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

          <li role="presentation" id="tab_initial" class="active"><a href="#initial" aria-controls="initial" role="tab" data-toggle="tab"> Initial Process <span style="background:rgb(99, 169, 210)" class="badge">0</span></a></li>
          <li role="presentation" id="tab_ongoing"><a href="#ongoing" aria-controls="ongoing" role="tab" data-toggle="tab"> Ongoing Process <span style="background:rgb(99, 169, 210)" class="badge">0</span></a></li>
          <!-- <li role="presentation" id="tab_receive"><a href="#receive" aria-controls="receive" role="tab" data-toggle="tab"> <i class="fa fa-edit"></i> Receive Task <span style="background:rgb(99, 169, 210);visibility:hidden" class="badge">0</span></a></li> -->
          
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" style="padding:20px;background:#FFF">

          <div role="tabpanel" class="tab-pane active" id="initial">
            
            <table id="table-initial" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Reference No.</th>
                  <th>Task</th>
                  <th>Task Type</th>
                  <th>Date Requested</th>
                  <th>Status</th>
                </tr>
              </thead>
            </table>

          </div>

          <div role="tabpanel" class="tab-pane" id="ongoing">
            
          <table id="table-ongoing" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Reference No.</th>
                  <th>Task</th>
                  <th>Task Type</th>
                  <th>Date Requested</th>
                  <th>Status</th>
                </tr>
              </thead>
            </table>
          </div>

          <div role="tabpanel" class="tab-pane" id="receive">
            
            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <form id="form_receive_request" method="post">
                    <div class="input-group">
                      <input name="rt_ref_no" style="text-transform:uppercase" type="text" class="form-control" placeholder="Enter reference number here . . .">
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                      </div>
                    </div>
                  </form>
                </div>

                <div id="remarks_container" class="div_hide">

                  <div class="clearfix" style="width:100%;background:#f3f3f3;padding:10px">
                    <button onclick="$('#request_details_modal').modal('show')" type="button" class="btn btn-sm btn-primary flat">Show Details</button>
                  </div>

                  <form method="post" id="form_process_decline" action="<?php echo base_url('task/process_denied') ?>">
                    <div class="form-group">
                      <input type="hidden" name="ref_no_receive" class="form-control" id="ref_no_receive">
                    </div>
                   <div class="form-group">
                    <textarea style="height:300px" name="textarea_receive_remarks" class="form-control" id="textarea_receive_remarks"></textarea>
                   </div>
                   <div class="form-group">
                     <button id="btn_process" class="btn btn-success flat" type="button"><i class="fa fa-check"></i> Process</button>
                     <button id="btn_decline" class="btn btn-danger flat" type="button"><i class="fa fa-times"></i> Decline</button>
                     
                   </div>
                  </form>
                </div>
               
              </div>
              <div class="col-md-5">
                <div id="process_details" class="box box-solid hide" style="background:#f3f3f3">
                  <div class="box-header">

                    <div id="countdown" class="hide"></div>

                    <p id="note" class="text-center"></p>
                  </div>
                  <div class="box-body" id="process_container"></div>
                </div>
              </div>
            </div>

          </div>


        </div>

      </div>

    </div>  
  </div>

</section>


<!-- CONTEXT MENU -->
<style type="text/css">

  .list-group-item {
      background-color: #333;
      color:#fff!important;
      font-size:12px;
      border:none!important;
      padding-top: 7px!important;
      padding-bottom: 7px!important;
  }
  .list-group-item:hover{
      background-color: #CCC !important;
      color:#000 !important;
  }

</style>

<div class="list-group" id="contextMenu" style="display:none;z-index:1000;width:150px">
  <a href="#" onclick="confirm_cancel()" class="list-group-item"><i class="fa fa-times"></i> Cancel Task</a>
  <a href="#" onclick="view(content_menu_selected_id)" class="list-group-item"><i class="fa fa-file-o"></i> View</a>
  <a href="#" onclick="show_remarks(content_menu_selected_id)" class="list-group-item"><i class="fa fa-comment-o"></i> Show Remarks</a>
</div>

<!-- CONFIRM CANCEL REQUEST MODAL -->

<div class="modal fade" id="confirm_cancel_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to cancel task?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="cancel_request(content_menu_selected_id)" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- SHOW REMARKS MODAL -->
<div class="modal fade" id="show_remarks_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remarks from Jovanni Bayawa</h4>
      </div>
      <div class="modal-body clearfix">
        
        <img src="<?php echo base_url("assets/dist/img/default.png")?>" class="img pull-left img-thumbnail" style="width:100px;height:100px;margin-right:10px;margin-bottom:10px">
        <p>
          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- MODAL SEND REASON -->
<div class="modal fade" id="give_reason_modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <form method="post" id="form_submit_reason_overdue">
        <div class="modal-body clearfix">
          <p>It seems the process has been overdue. Kindly give us the reason why.</p>
          <div class="form-group">
            <textarea id="text_overdue_reason" required class="form-control" rows="6"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- REQUESTS DETAILS -->
<div class="modal fade" id="request_details_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="request_information clearfix" style="width:100%;background:#f3f3f3;padding:10px">
          
        </div>
      </div>
      <div class="modal-body clearfix">
        <h3>Title Here <small>Ref No Here</small></h3>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

        <div class="att_container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript">

    var content_menu_selected_id = 0;
    var tbl_initial;
    var tbl_ongoing;
    load_initial_request();

  $(function(){

        $("#textarea_receive_remarks").wysihtml5();
    
  });

  $(document).ready(function () {

        tbl_initial = $('#table-initial').dataTable({
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

        tbl_ongoing = $('#table-ongoing').dataTable({
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

    
    });
    
    function load_initial_request(){
        $.ajax({
            url: "<?php echo base_url('task/load_list_initial') ?>",
            dataType: "JSON",
            success: function(data){

                tbl_initial.fnClearTable();

                  for(var i = 0; i < data.length; i++) { 

                    var color = "";
                    if(data[i]['status'] == 'denied'){
                        color = "label-danger";
                    }
                    else if(data[i]['status'] == 'pending'){
                        color = "label-warning";
                    }
                    else if(data[i]['status'] == 'cancelled'){
                        color = "label-default";
                    }
                    else if(data[i]['status'] == 'endorsed'){
                        color = "label-success";
                    }

                    tbl_initial.fnAddData([ 
                                          i+1,
                                          "<a onmousedown=\"HideMenu('contextMenu');\" onmouseup=\"HideMenu('contextMenu');\" oncontextmenu=\"ShowMenu('contextMenu',event, "+data[i]['rt_id']+")\" class='item_initial' onclick=\"view("+data[i]['rt_id']+")\" href=\"#\"><b>"+data[i]['ref_no']+"</b></a>", 
                                          data[i]['title'], 
                                          data[i]['type'],
                                           data[i]['date'],
                                          "<span class='label "+color+"'>"+data[i]['status']+"</span>" 
                                          ]); 

                    $("#tab_initial span.badge").html(i+1);
                  } // End For
                  
            },
            error: function(){

            }
        });
    }

    update_list();
    load_list_processing();
    
    function update_list(){

        $.ajax({
            url: "<?php echo base_url('task/load_list_processing') ?>",
            dataType: "JSON",
            success: function(data){

                  for(var i = 0; i < data.length; i++) { 

                    update_check_status(data[i]['rt_id']);

                  } // End For
                  
            },
            error: function(){

            }
        });

    }

    function load_list_processing(){
        $.ajax({
            url: "<?php echo base_url('task/load_list_processing') ?>",
            dataType: "JSON",
            success: function(data){

                tbl_ongoing.fnClearTable();

                  for(var i = 0; i < data.length; i++) { 

                    var color = "";
                    if(data[i]['status'] == 'denied'){
                        color = "label-danger";
                    }
                    else if(data[i]['status'] == 'pending'){
                        color = "label-warning";
                    }
                    else if(data[i]['status'] == 'overdue' || data[i]['status'] == 'cancelled'){
                        color = "label-default";
                    }
                    else if(data[i]['status'] == 'done'){
                        color = "label-success";
                    }
                    else if(data[i]['status'] == 'processing'){
                        color = "label-info";
                    }

                    tbl_ongoing.fnAddData([ 
                                          i+1,
                                          "<a onmousedown=\"HideMenu('contextMenu');\" onmouseup=\"HideMenu('contextMenu');\" oncontextmenu=\"ShowMenu('contextMenu',event, "+data[i]['rt_id']+")\" class='item_initial' onclick=\"view("+data[i]['rt_id']+")\" href=\"#\"><b>"+data[i]['ref_no']+"</b></a>", 
                                          data[i]['title'], 
                                          data[i]['type'],
                                           data[i]['date'],
                                          "<span class='label "+color+"'>"+data[i]['status']+"</span>" 
                                          ]); 

                    $("#tab_ongoing span.badge").html(i+1);
                  } // End For
                  
            },
            error: function(){

            }
        });
    }

    function view(id){
        location.href = "<?php echo base_url('task/view') ?>/"+id;
    }

     function ShowMenu(control, e, id) {
        var posx = e.clientX +window.pageXOffset +'px'; //Left Position of Mouse Pointer
        var posy = e.clientY + window.pageYOffset + 'px'; //Top Position of Mouse Pointer
        document.getElementById(control).style.position = 'absolute';
        document.getElementById(control).style.display = 'inline';
        document.getElementById(control).style.left = posx;
        document.getElementById(control).style.top = posy;      

        content_menu_selected_id = id;

    }
    function HideMenu(control) {
        document.getElementById(control).style.display = 'none'; 
    }
    
    function clickOnBody(event){
        HideMenu('contextMenu');
    }
    document.body.addEventListener('mousedown', clickOnBody, false);
    document.body.addEventListener('touchstart', clickOnBody, false);
  
    function clickOnMenu(event){
        event.stopPropagation();
    }
    var menu = document.getElementById('contextMenu');
    menu.addEventListener('mousedown', clickOnMenu, false);
    menu.addEventListener('mouseup', clickOnMenu, false);
    menu.addEventListener('touchstart', clickOnMenu, false);
    

    function confirm_cancel(){
      HideMenu('contextMenu');
      $("#confirm_cancel_modal").modal('show');
    }

    function cancel_request(rt_id){
      $.ajax({
        url: "<?php echo base_url('task/cancel_request') ?>",
        data: {rt_id : rt_id},
        type: "get",
        dataType: "JSON",
        success: function(data){
          if(data['result'] == 1){ 
            load_initial_request();
           $("#confirm_cancel_modal").modal('hide');
            new PNotify({
                    title: 'Success',
                    text: data['msg'],
                    type: 'success',
                    icon: 'fa fa-check'
            });

          }
          else if(data['result'] == 0){
            load_initial_request();
            $("#confirm_cancel_modal").modal('hide');
              new PNotify({
                    title: 'Error',
                    text: data['msg'],
                    type: 'error',
                    icon: 'fa fa-times'
              });
              
          }
        },
        error: function(){

        }
      });
    }

    function show_remarks(rt_id){
      
      $.ajax({
        url: "<?php echo base_url('task/get_remarks') ?>",
        data: {rt_id : rt_id},
        type: "GET",
        dataType: "json",
        success: function(data){
          if(data["result"] == 1){

             HideMenu('contextMenu');
             
              $("#show_remarks_modal .modal-body").html(data['content']);
              $("#show_remarks_modal .modal-header h4").html("Remarks from "+data['from']);
              $("#show_remarks_modal").modal('show');

             
          }
          else if(data['result'] == 0){

              HideMenu('contextMenu');

              new PNotify({
                    title: 'Error',
                    text: 'Remarks not available',
                    type: 'error',
                    icon: 'fa fa-times'
              });
          }
          
        },
        error: function(){

        }
      });
      // $("#show_remarks_modal").modal('show');
    }

    $(document).ready(function(){

      $("#form_receive_request").on("submit", function(e){
        e.preventDefault();

        $.ajax({
          url: "<?php echo base_url('task/sample') ?>",
          type: "POST",            
          data: new FormData(this),
          dataType: "json",
          contentType: false,      
          cache: false,            
          processData:false,       
          success: function(data)  
          {
            if(data['result'] == 1){ 
              $("#process_details").removeClass("hide");
              $("#note").removeClass("hide");
              $("#remarks_container").removeClass("div_hide");
              $("#ref_no_receive").val(data['ref_no']);

              update_check_status(data['rt_id']);
              get_process(data['rt_id']);
              disable_buttons_process(data['status']);
              get_details_request(data['rt_details']);
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

    function get_details_request(data){
      var r_info = "<img onerror=\"imgError(this)\" src='"+data['img']+"' class=\"img img-circle pull-left\" style=\"width:50px;height:50px\">\
          <div class=\"pull-left\" style=\"padding-left:20px\">\
            <span style=\"font-weight:bold;margin-right:15px\">from: "+data['name']+"</span> <small style=\"color:#888\"><i class=\"fa fa-clock-o\"></i>\ "+data['date']+"</small><br>\
            <small style=\"color:#888\">"+data['position']+"</small>\
          </div>";

      $(".request_information").html(r_info);
      $("#request_details_modal .modal-body h3").html(data['title']+" <br><small>ref. no: <b>"+data['ref_no']+"</b></small>");
      $("#request_details_modal .modal-body p").html(data['content']);

      if(data['att'].length > 0){
         $("#request_details_modal .modal-body .att_container").html("<hr>"+data['att']);
      }
     
      
    }

    var receive_status;
    function disable_buttons_process(stat){
      receive_status = stat;
      if(stat == "overdue"){
        $("#btn_process").removeClass("hide");
        $("#btn_decline").removeClass("hide");
      }
      else if(stat == "done"){
        $("#btn_process").addClass("hide");
        $("#btn_decline").addClass("hide");
      }
      else if(stat == "denied"){
        $("#btn_process").addClass("hide");
        $("#btn_decline").addClass("hide");
      }
    }

    function get_process(rt_id){
        $.ajax({
          url: "<?php echo base_url('task/get_request_process') ?>",
          data: {rt_id : rt_id},
          dataType: "html",
          success: function(data){
            $("#process_container").html(data);
          },
          error: function(){

          }
        });
      }


// function show_timer(rt_id, date_end, type, duration){
//   $('#countdown').html("");
//   // alert(date_end+" "+type+" "+duration);
//   var note = $('#note'),
//     ts = new Date(2010, 2, 16),
//     newYear = true;
//     var opr = 0;

//   if((new Date()) > ts){
//     // The new year is here! Count towards something else.
//     // Notice the *1000 at the end - time must be in milliseconds
//     if(type == "day"){

//       var date2 = new Date(moment().format('MMMM DD, YYYY H:mm:ss'));
//       var date1 = new Date(date_end);
//       var timeDiff = Math.abs(date2.getTime() - date1.getTime());
//       var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

//       if(diffDays == 0){
//         opr = duration*24*60*60*1000;
//       }else{
//         opr = 0;
//       }

//     }
//     if(type == "hour"){

//       var date2 = new Date(moment().format('MMMM DD, YYYY H:mm:ss'));
//       var date1 = new Date(date_end);
//       var diff = (Math.abs(date2.getTime() - date1.getTime())) / 3600000;
      

//       if(diff == 0){
//         opr = duration*60*60*1000;
//       }
//       else if(diff < duration){
//         opr = 0;
//       }
      
//     }
//     if(type == "minute"){
//       var date2 = new Date(moment().format('MMMM DD, YYYY H:mm:ss'));
//       var date1 = new Date(date_end);
//       var diffMs = (date2 - date1);
//       var diffMins = ((diffMs % 86400000) % 3600000) / 60000; // minutes
      
//       if(diffMins == 0){
//         opr = duration*60*1000;
//       }
//       else{
//         opr = 0;
//       }
//     }

//     ts = (new Date(date_end)).getTime() + opr;
//     newYear = false;
//   }
    
//   $('#countdown').countdown({
//     timestamp : ts,
//     callback  : function(days, hours, minutes, seconds){
      
//       var message = "";
      
//       message += days + " day" + ( days==1 ? '':'s' ) + ", ";
//       message += hours + " hour" + ( hours==1 ? '':'s' ) + ", ";
//       message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " and ";
//       message += seconds + " second" + ( seconds==1 ? '':'s' ) + " <br />";
      
//       if(newYear){
//         message += "left until the new year!";
//       }
//       else {
//         message += "left to end process!";
//       }
      
//       if(days == 0 && hours == 0 && minutes == 0 && seconds == 0){
//         update_process_overdue(rt_id);
//         disable_buttons_process("overdue");
//         get_process(rt_id);
//       }
//       note.html(message);
//     }
//   });
  
// }

function update_process_overdue(rt_id){
  $.ajax({
    url: "<?php echo base_url('task/update_overdue') ?>",
    data: {rt_id : rt_id},
    type: "GET",
    dataType: "json",
    success: function(data){

    },
    error: function(){

    }
  });
}

function update_check_status(rt_id){
  $.ajax({
    url: "<?php echo base_url('task/update_check_status') ?>",
    data: {rt_id : rt_id},
    type: "GET",
    dataType: "html",
    success: function(data){
      console.log(data);
    },
    error: function(){
      // console.log(rt_id);
    }
  });
}

$(document).ready(function(){
  var stat = "";
  $("form#form_process_decline").on("submit", function(e){
      var reason = $("#text_overdue_reason").val().trim();
      e.preventDefault();
      $.ajax({
        url: "<?php echo base_url('task/process_denied') ?>",
        data: $(this).serialize()+"&status="+stat+"&reason="+reason,
        type: "post",
        dataType: "json",
        success: function(data){
          if(data['result'] == 1){

            $('#countdown').countdown('stop');
            $('#countdown').html("");
            
            $("#note").countdown('stop');
            $("#note").addClass("hide");

            get_process(data['rt_id']);
            disable_buttons_process(stat);
            $("#give_reason_modal").modal("hide");
            $("#text_overdue_reason").val("");
            new PNotify({
                    title: 'Success',
                    text: data['msg'],
                    type: 'success',
                    icon: 'fa fa-check'
            });
          }
          else if(data['result'] == 0){
              $("#give_reason_modal").modal("hide");
              $("#text_overdue_reason").val("");
              new PNotify({
                    title: 'Error',
                    text: data['msg'],
                    type: 'error',
                    icon: 'fa fa-times'
              });
              
          }
        },
        error: function(){
          $("#give_reason_modal").modal("hide");
          $("#text_overdue_reason").val("");
        }
      });
  });

 $("button#btn_process").click(function(e){
     stat = "done";
     if(receive_status == "overdue"){
      $("#give_reason_modal").modal("show");
     }
     else{
        $("form#form_process_decline").submit();
     }
  });

  $("button#btn_decline").click(function(e){
     stat = "denied";
     var remarks = $("#textarea_receive_remarks").val();

      
      remarks = remarks.replace(/&nbsp;/g,'');
      remarks = remarks.replace(/ /g,'');
      
     if(remarks.trim() == "<p></p>" || remarks.trim() == ""){
        new PNotify({
                    title: 'Error',
                    text: "Please give us remarks why decline.",
                    type: 'error',
                    icon: 'fa fa-times'
              });
     }
     else{
        if(receive_status == "overdue"){
          $("#give_reason_modal").modal("show");
        }
        else{
          $("form#form_process_decline").submit();
        }
     }
     
  });

  $("button#btn_send_reason").click(function(e){
     stat = "overdue";
     $("form#form_process_decline").submit();
  });
});

$(document).ready(function(){
  $("#form_submit_reason_overdue").on("submit", function(e){
    e.preventDefault();

    var reason = $("#text_overdue_reason").val();

    if(reason.trim().length == 0){
      new PNotify({
                    title: 'Error',
                    text: "Please give us your reason.",
                    type: 'error',
                    icon: 'fa fa-times'
              });
    }
    else{
      $("form#form_process_decline").submit();
    }
    
  });
});

</script>