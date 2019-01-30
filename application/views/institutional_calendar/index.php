<style type="text/css">
	.fc th {
	    background: rgba(60, 141, 188, 0.83) !important;
	    font-weight:normal !important;
	}
	.fc button{
		border-radius: 0px!important;
	}

	.fc-content {
	   padding-top: 3px!important;
	   padding-bottom: 3px!important;
	   border-radius: 0px!important;
	   padding-left:3px;
	}

  /*.fc-today {
    background: rgba(152, 205, 236, 0.39)!important;
  }*/
  
  #calendar_list_container{
    min-height: 100px;
    max-height: 300px;
    overflow: auto;
    margin-top:5px;
  }
  #dep_calendar_list_container{
    min-height: 100px;
    max-height: 300px;
    overflow: auto;
    margin-top:5px;
  }

  .list_calendars{
    width:100%;background:#f3f3f3;padding:7px;margin-bottom:2px;padding-left:10px;cursor:pointer;
  }
  .list_calendars:hover{
    background:#CCC;

  }
  .list_calendars .option:hover{
    color: #FFF
  }

  #table-list-events tbody tr td{
    border-top: none!important;
  }

  #table-list-events tbody tr:hover{
    background:rgba(140, 195, 226, 0.70)!important;
   
  }

  /* SEARCH EVENT */
  .searched_container{
    border:1px solid #CCC;
    border-top:none;
    width:100%;
    border-bottom:none;
    position:absolute;
    background:red;
    z-index:100;
    height:auto;
    max-height:250px;
    overflow:auto;
  }
  .searched_result{
    width:100%;padding:5px;background:#FFFFFF;
    cursor: pointer;
    border-bottom:1px solid #CCC;
  }
  .searched_result:hover{
    background:#E9F7FD;
  }

</style>
<section class="content">

	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<a style="width:100%" href="<?php echo base_url('institution_calendar/create_calendar') ?>" class="btn btn-primary flat"><i class="fa fa-calendar"></i><sup><i class="fa fa-plus"></i></sup> Create New Calendar</a>
			</div>
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">List of Calendars</h3>
				</div>
				<div class="box-body" style="padding:2px 2px 0px 2px">
                  <div>
                    <button id="btn_view_all_calendar" onclick="view_all_calendar()" class="btn btn-primary btn-sm btn-block flat">View All</button>
                  </div>
                  <div id="calendar_list_container">
                    <div style="width:100%;background:#CCC;padding:5px;margin-bottom:2px">
                      No calendar available
                    </div>
                  </div>
        </div>
			</div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Department Calendars</h3>
        </div>
        <div class="box-body" style="padding:2px 2px 0px 2px">

                  <div id="dep_calendar_list_container">
                    <div style="width:100%;background:#CCC;padding:5px;margin-bottom:2px">
                      No calendar available
                    </div>
                  </div>
        </div>
      </div>
		</div>
		<div class="col-md-9">

			<div class="row">
				<div class="col-md-7">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-search"></i>
							</span>
							<input onkeyup="search_event($(this).val())" type="text" id="txtsearchevent" type="text" class="form-control" placeholder="Search event here">
						</div>
            <div style="width:100%;position:relative;background:#CCC;">
              <div class="searched_container">
                
              </div>
            </div>
					</div>
				</div>
			</div>
			
			
			<div class="box box-solid">
        <div class="box-header">
          <b id="calendar_name_title" class="box-title">All Events</b>
        </div>
				<div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
        </div><!-- /.box-body -->
			</div>
		</div>
	</div>
</section>

<!-- EVENT LISTS -->

<div class="modal fade" id="modal_list_events" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <table id="table-list-events" class="table table-hover" style="width:100%">
          <thead class="hide">
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- MODAL CONFIRM DELETE CALENDAR -->

<div class="modal fade" id="confirm_delete_calendar_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want delete calendar and its events?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="delete_calendar()" id="modal-btn-delete-calendar" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- MODAL CONFIRM DELETE EVENT -->
<div class="modal fade" id="confirm_delete_event_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want delete event?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="delete_event()" id="modal-btn-delete-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

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

<div class="list-group" id="contextMenu" style="display:none;z-index:1000;width:200px">
  <a style="cursor:pointer" onclick="create_event(content_menu_selected_id)" class="list-group-item"><i class="fa fa-edit"></i> Create event on this calendar</a>
  <a style="cursor:pointer" onclick="get_events()" class="list-group-item"><i class="fa fa-file-o"></i> List of events on this calendar</a>
  <a style="cursor:pointer" onclick="confirm_delete_calendar()" class="list-group-item"><i class="fa fa-times"></i> Delete calendar</a>
  <!-- <a style="cursor:pointer" class="list-group-item"><i class="fa fa-info-circle"></i> Description</a> -->
</div>

<div class="list-group" id="contextMenuDept" style="display:none;z-index:1000;width:200px">
  <a style="cursor:pointer" onclick="get_dep_events()" class="list-group-item"><i class="fa fa-file-o"></i> List of events on this calendar</a>
  <!-- <a style="cursor:pointer" class="list-group-item"><i class="fa fa-info-circle"></i> Description</a> -->
</div>

<!-- END CONTEXT MENU -->

<script>

load_calendars();
load_dep_calendars();

  function load_calendars(){
    var display = "";
    $.ajax({
      url: "<?php echo base_url('institution_calendar/load_calendars') ?>",
      dataType: "json",
      success: function(data){
        if(data.length > 0){
          for(var i=0; i<data.length;i++){
          display += "<div onclick=\"change_calendar('"+data[i]['calendar_id']+"', '"+data[i]['calendar_name']+"')\" title='Display only this calendar' class='btn btn-default flat list_calendars' style='text-align:left'>\
                        <i class='fa fa-calendar'></i> "+data[i]['calendar_name']+"\
                        <span class='pull-right option' onmousedown=\"HideMenu('contextMenu');\" onmouseup=\"HideMenu('contextMenu');\" onclick=\"ShowMenu('contextMenu',event, '"+data[i]['calendar_id']+"')\" title='Options' style='cursor:pointer'><i class='fa fa-bars'></i></span>\
                      </div>";
          }
          $("#calendar_list_container").html(display);
        }
        else{
          var no_cal = "<div style=\"width:100%;background:#CCC;padding:5px;margin-bottom:2px\">\
                          No institutional calendar available\
                        </div>";
          $("#calendar_list_container").html(no_cal);
        }
      },
      error: function(data){

      }
    });
  } 

  function load_dep_calendars(){
    var display = "";
    $.ajax({
      url: "<?php echo base_url('institution_calendar/load_department_calendars') ?>",
      dataType: "json",
      success: function(data){
        if(data.length > 0){
          for(var i=0; i<data.length;i++){
          display += "<div onclick=\"change_dep_calendar('"+data[i]['dep_id']+"', '"+data[i]['department']+"')\" title='Display only this calendar' class='btn btn-default flat list_calendars' style='text-align:left'>\
                        <i class='fa fa-calendar'></i> "+data[i]['department']+"\
                        <span class='pull-right option' onmousedown=\"HideMenu('contextMenu');\" onmouseup=\"HideMenu('contextMenu');\" onclick=\"ShowMenu('contextMenuDept',event, '"+data[i]['dep_id']+"')\" title='Options' style='cursor:pointer'><i class='fa fa-bars'></i></span>\
                      </div>";
          }
          $("#dep_calendar_list_container").html(display);
        }
        else{
          var no_cal = "<div style=\"width:100%;background:#CCC;padding:5px;margin-bottom:2px\">\
                          No department calendar available\
                        </div>";
          $("#dep_calendar_list_container").html(no_cal);
        }
      },
      error: function(data){

      }
    });
  } 

      $(function () {

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
          },
          //Random default events
          events: "<?php echo base_url('institution_calendar/load_events_in_calendar') ?>",

          eventClick:  function(event, jsEvent, view) {    
            
          },
          dayClick: function(date, jsEvent, view) {
            // sample("contextMenu", event);
            var now = new moment();
            var time = now.format("h:mm A");
            var dateS = moment(date);
            var formateddate = dateS.format("MM/DD/YYYY");
            var selectedDate = formateddate+" "+time+" - "+formateddate+" "+time;

            location.href="<?php echo base_url('institution_calendar/create_event').'/'?>"+Base64.encode(selectedDate);
          }
       
        });

      });

    // ************************** CONTEXT MENU ********************************//

    var content_menu_selected_id;

    function sample(control, e) {
        var posx = e.clientX +window.pageXOffset +'px'; //Left Position of Mouse Pointer
        var posy = e.clientY + window.pageYOffset + 'px'; //Top Position of Mouse Pointer
        document.getElementById(control).style.position = 'absolute';
        document.getElementById(control).style.display = 'inline';
        document.getElementById(control).style.left = posx;
        document.getElementById(control).style.top = posy;
        
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
        document.getElementById("contextMenuDept").style.display = 'none'; 
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

    var menu2 = document.getElementById('contextMenuDept');
    menu2.addEventListener('mousedown', clickOnMenu, false);
    menu2.addEventListener('mouseup', clickOnMenu, false);
    menu2.addEventListener('touchstart', clickOnMenu, false);

    // ************************* END CONTEXT MENU ******************************** //

    function create_event(calendar_id){
      location.href="<?php echo base_url('institution_calendar/create_event').'/'?>"+Base64.encode(calendar_id);
    }
    function view_event(event_id){
      location.href="<?php echo base_url('institution_calendar/view_event').'/'?>"+Base64.encode(event_id);
    }
    function view_dep_event(event_id){
      location.href="<?php echo base_url('institution_calendar/view_dep_event').'/'?>"+Base64.encode(event_id);
    }
    function edit_event(event_id){
      location.href="<?php echo base_url('institution_calendar/edit_event').'/'?>"+Base64.encode(event_id);
    }

    var event_list = $("#table-list-events").dataTable({
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bFilter": false,
                            "bSort": false,
                            "bInfo": false,
                            "bAutoWidth": true,
                            "iDisplayLength": 5,
                            "oLanguage": 
                                 {
                                      "oPaginate": 
                                        {
                                            "sNext": '<i class="fa fa-angle-right"></i>',
                                            "sPrevious": '<i class="fa fa-angle-left"></i>',
                                            "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                            "sLast": '<i class="fa fa-angle-double-right"></i>'
                                        },
                                        "sEmptyTable":     "No event available in the calendar"
                                }
                            
                          });

    function get_events(){
      $.ajax({
        url: "<?php echo base_url('institution_calendar/get_events') ?>",
        data: {calendar_id : content_menu_selected_id},
        type: "GET",
        dataType: "json",
        success: function(data){
          
          HideMenu('contextMenu');
          
          event_list.fnClearTable();

          for(var i = 0;i<data['list'].length;i++){
              event_list.fnAddData([
                                  "<b style='font-size:15px;'><a href='#'>"+data['list'][i]['title']+"</a></b><br><span style='color:#555'><i class='fa fa-calendar'></i> "+data['list'][i]['date']+" | "+data['list'][i]['time']+"</span><br><span style='color:#555'><i class='fa fa-map-marker'></i> "+data['list'][i]['place']+"</span>",
                                  "<span class='pull-right'><a href='#' onclick=\"view_event('"+data['list'][i]['event_id']+"')\"><i class='fa fa-eye'></i> View</a> | <a href='#' onclick=\"edit_event('"+data['list'][i]['event_id']+"')\"><i class='fa fa-edit'></i> Edit</a> | <a href='#' onclick=\"confirm_delete_event("+data['list'][i]['event_id']+")\"><i class='fa fa-remove'></i> Remove</a></span>"
                                  ]); 
          }

          $("#modal_list_events .modal-title").html("<b><i class='fa fa-calendar'></i> "+data['calendar']+"</b>&nbsp;&nbsp;&nbsp;&nbsp;<a class='btn btn-primary btn-xs flat' onclick=\"create_event('"+data['calendar_id']+"')\">Add new event</a>");
          $("#modal_list_events").modal('show');
        },
        error: function(){

        }
      });
    }

    function get_dep_events(){
      $.ajax({
        url: "<?php echo base_url('institution_calendar/get_dep_events') ?>",
        data: {dep_id : content_menu_selected_id},
        type: "GET",
        dataType: "json",
        success: function(data){
          
          HideMenu('contextMenu');
          
          event_list.fnClearTable();

          for(var i = 0;i<data['list'].length;i++){
              event_list.fnAddData([
                                  "<b style='font-size:15px;'><a href='#'>"+data['list'][i]['title']+"</a></b><br><span style='color:#555'><i class='fa fa-calendar'></i> "+data['list'][i]['date']+" | "+data['list'][i]['time']+"</span><br><span style='color:#555'><i class='fa fa-map-marker'></i> "+data['list'][i]['place']+"</span>",
                                  "<span class='pull-right'><a href='#' onclick=\"view_dep_event('"+data['list'][i]['event_id']+"')\"><i class='fa fa-eye'></i> View</a></span>"
                                  ]); 
          }

          $("#modal_list_events .modal-title").html("<b><i class='fa fa-calendar'></i> "+data['calendar']+"</b>");
          $("#modal_list_events").modal('show');
        },
        error: function(){

        }
      });
    }

    function confirm_delete_calendar(){
       HideMenu('contextMenu');
      $("#confirm_delete_calendar_modal").modal('show');
    }

    function delete_calendar(){
      $.ajax({
        url: "<?php echo base_url('institution_calendar/delete_calendar') ?>",
        data: {calendar_id : content_menu_selected_id},
        type: "GET",
        dataType: "json",
        success: function(data){
            if(data['result'] == 1){ 
              $('#calendar').fullCalendar('refetchEvents');
              $("#confirm_delete_calendar_modal").modal('hide');
              $("#btn_view_all_calendar").trigger("click");
              load_calendars();
              load_dep_calendars();
              new PNotify({
                      title: 'Success',
                      text: data['msg'],
                      type: 'success',
                      icon: 'fa fa-check'
              });

              $("#btn-reset").trigger("click");
              txtdescription.setValue("");
            }
            else if(data['result'] == 0){
                $("#confirm_delete_calendar_modal").modal('hide');
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

    var del_event_id;

    function confirm_delete_event(event_id){
      del_event_id = event_id;
      $("#confirm_delete_event_modal").modal('show');
    }

    function delete_event(){
      $.ajax({
        url: "<?php echo base_url('institution_calendar/delete_event') ?>",
        data: {event_id : del_event_id},
        type: "GET",
        dataType: "json",
        success: function(data){
            if(data['result'] == 1){ 
              $('#calendar').fullCalendar('refetchEvents');
              $("#confirm_delete_event_modal").modal('hide');
              get_events();
              new PNotify({
                      title: 'Success',
                      text: data['msg'],
                      type: 'success',
                      icon: 'fa fa-check'
              });
            }
            else if(data['result'] == 0){
                $("#confirm_delete_event_modal").modal('hide');
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

    var prev_cal;
    var prev_dep_cal;
    
    function change_calendar(calendar_id, calendar_name){
      $("#calendar_name_title").html(calendar_name);
      $("#calendar").fullCalendar('removeEvents');
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar') ?>");
       $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_dep_events_in_calendar').'/' ?>"+prev_dep_cal);
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar').'/' ?>"+prev_cal);
      $('#calendar').fullCalendar('addEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar').'/' ?>"+calendar_id); 
      prev_cal = calendar_id;
    }
    function change_dep_calendar(dep_id, calendar_name){
      $("#calendar_name_title").html(calendar_name);
      $("#calendar").fullCalendar('removeEvents');
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar') ?>"); 
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar').'/' ?>"+prev_cal);
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_dep_events_in_calendar').'/' ?>"+prev_dep_cal);
      $('#calendar').fullCalendar('addEventSource',"<?php echo base_url('institution_calendar/load_dep_events_in_calendar').'/' ?>"+dep_id); 
      prev_dep_cal = dep_id;
    }
    function view_all_calendar(){
      $("#calendar_name_title").html("All Events");
      $("#calendar").fullCalendar('removeEvents');
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar') ?>");
       $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_dep_events_in_calendar').'/' ?>"+prev_dep_cal);
      $('#calendar').fullCalendar('removeEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar').'/' ?>"+prev_cal);
      $('#calendar').fullCalendar('addEventSource',"<?php echo base_url('institution_calendar/load_events_in_calendar') ?>");
    }

    // SEARCH EVENTS //

    function search_event(key){

      if(key.length == 0 || key.trim() == ""){
        $(".searched_container").hide();
        $(".searched_container").html("");
      }
      else{
        $.ajax({
          url: "<?php echo base_url('institution_calendar/search_event') ?>",
          data: {search : key},
          type: "GET",
          dataType: "html",
          success: function(data){
            $(".searched_container").show();
            $(".searched_container").html(data);
          },
          error: function(){

          }
        });
      } 
    }

    $(document).on('click', function () {
        $(".searched_container").hide();
        $(".searched_container").html("");
        $("#txtsearchevent").val("");
    });

    function stop_event(e){
      var evnt = e || window.event;
      evnt.stopPropagation();
    }

    function reset_search(){
      $(".searched_container").hide();
        $(".searched_container").html("");
      $("#txtsearchevent").val("");
    }

    $('input#txtsearchevent').on('click', function (event) {
        event.stopPropagation();
    });

    function jump_to_date(date){
      $('#calendar').fullCalendar('gotoDate', date);
    }
</script>

