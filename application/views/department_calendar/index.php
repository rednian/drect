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
				<a style="width:100%" href="<?php echo base_url('department_calendar/create_event') ?>" class="btn btn-primary flat"><i class="fa fa-calendar"></i><sup><i class="fa fa-plus"></i></sup> Create New Event</a>
			</div>
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">List of Events</h3>
				</div>
				<div class="box-body" style="padding:2px 2px 0px 2px">
                  <div>
                    <div class="form-group">
                      <b>Select Month</b>
                      <input id="select_month" onchange="jump_to_date($(this).val())" type="month" class="form-control">
                    </div>
                  </div>
                  <div id="calendar_list_container">
                    <div style="width:100%;background:#CCC;padding:5px;margin-bottom:2px">
                      No event available
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
        <div class="col-md-5">
          <div class="form-group pull-right">
            <a href="<?php echo base_url('department_calendar/event_list') ?>" class="btn btn-primary flat"><i class="fa fa-table"></i> All Events List</a>
          </div>
        </div>
			</div>
			
			
			<div class="box box-solid">
				<div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
        </div><!-- /.box-body -->
			</div>
		</div>
	</div>
</section>

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
  <a onclick="view_event(content_menu_selected_id)" style="cursor:pointer" class="list-group-item"><i class="fa fa-eye"></i> View event</a>
  <a onclick="confirm_delete_event(content_menu_selected_id)" style="cursor:pointer" class="list-group-item"><i class="fa fa-times"></i> Delete event</a>
  <!-- <a onclick="edit_event(content_menu_selected_id)" style="cursor:pointer" class="list-group-item"><i class="fa fa-edit"></i> Edit event</a> -->
</div>

<!-- END CONTEXT MENU -->

<script>

load_events();

  function load_events(){
    var display = "";
    $.ajax({
      url: "<?php echo base_url('department_calendar/load_events') ?>",
      data: {status : 'approved'},
      dataType: "json",
      success: function(data){
        if(data.length > 0){
          for(var i=0; i<data.length;i++){
          display += "<div onclick=\"jump_to_date('"+data[i]['from_date']+"')\" class='btn btn-default flat list_calendars' style='text-align:left'>\
                        <i class='fa fa-calendar'></i> "+data[i]['event_title']+"\
                        <span class='pull-right option' onmousedown=\"HideMenu('contextMenu');\" onmouseup=\"HideMenu('contextMenu');\" onclick=\"ShowMenu('contextMenu',event, '"+data[i]['event_id']+"')\" title='Options' style='cursor:pointer'><i class='fa fa-bars'></i></span>\
                      </div>";
          }
          $("#calendar_list_container").html(display);
        }
        else{
          var no_cal = "<div style=\"width:100%;background:#CCC;padding:5px;margin-bottom:2px\">\
                          No event available\
                        </div>";
          $("#calendar_list_container").html(no_cal);
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
        events: "<?php echo base_url('department_calendar/load_events_in_calendar') ?>",

        eventClick:  function(event, jsEvent, view) {    
          
        },
        dayClick: function(date, jsEvent, view) {
          // sample("contextMenu", event);
          var now = new moment();
          var time = now.format("h:mm A");
          var dateS = moment(date);
          var formateddate = dateS.format("MM/DD/YYYY");
          var selectedDate = formateddate+" "+time+" - "+formateddate+" "+time;

          location.href="<?php echo base_url('department_calendar/create_event').'/'?>"+Base64.encode(selectedDate);
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

    // ************************* END CONTEXT MENU ******************************** //

    function view_event(event_id){
      location.href="<?php echo base_url('department_calendar/view_event').'/'?>"+Base64.encode(event_id);
    }
    function edit_event(event_id){
      location.href="<?php echo base_url('department_calendar/edit_event').'/'?>"+Base64.encode(event_id);
    }

    var del_event_id;

    function confirm_delete_event(event_id){
      del_event_id = event_id;
      $("#confirm_delete_event_modal").modal('show');
      HideMenu('contextMenu');
    }

    function delete_event(){
      $.ajax({
        url: "<?php echo base_url('department_calendar/delete_event') ?>",
        data: {event_id : del_event_id},
        type: "GET",
        dataType: "json",
        success: function(data){
            if(data['result'] == 1){ 
              $('#calendar').fullCalendar('refetchEvents');
              $("#confirm_delete_event_modal").modal('hide');
              load_events();
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
    // SEARCH EVENTS //

    function search_event(key){
      if(key.length == 0 || key.trim() == ""){
        $(".searched_container").hide();
        $(".searched_container").html("");
      }
      else{
        $.ajax({
          url: "<?php echo base_url('department_calendar/search_event') ?>",
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

