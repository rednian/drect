	<audio id="chatAudio">
	    <source src="<?php echo base_url('assets/sounds/notificationSound.mp3') ?>" type="audio/mpeg">
	</audio>

	<audio id="notificationAudio">
	    <source src="<?php echo base_url('assets/sounds/charming_bell.mp3') ?>" type="audio/mpeg">
	</audio>

    <!-- DISABLE RIGHT CLICK -->
 	

 	<!-- NOTIFICATION JS -->
 	<script type="text/javascript">
 		<?php
		  	$CI =& get_instance();
		  	if($CI->uri->segment(1) != '' && $CI->uri->segment(1) != 'login'){
		?>

 		check_new_emails();
 		load_new_mymails_menu();
 		chatCounter();

 		var refresh_10 = setInterval(function(){
		  check_new_emails();
		  load_new_mymails_menu();
		  notify_rt_process();
		  notify_new_request_processed();
		  notify_done_denied_request();
		  chatCounter();	
		  <?php
		  	if($CI->userInfo->position_type == 1 || $CI->userInfo->position_type == 2 || $CI->userInfo->position_type == 3){
		  ?>
		  	notify_new_request();
		  <?php
		  	}
		  ?>

		},10000);
		 <?php
		  	}
		 ?>
 		// CHECK NEW EMAIL
 		function check_new_emails(){
		  $.ajax({
		    url: "<?php echo base_url('notification/check_new_emails') ?>",
		    dataType: "json",
		    success: function(data){
		      if(data['result'] == 1){
		        for(var i=0;i<data['info'].length;i++){
		            new PNotify({
		                    title: 'New Email',
		                    text: '<img onerror="imgError(this);" src="'+data['info'][i]['image']+'" class=\'img img-circle\' style=\'width:30px;height:30px\'>\
		                            &nbsp;&nbsp;<span style=\'font-size:15px\'>'+data['info'][i]['from']+' sent you email</span>\n\
		                            <br><a style="text-decoration:none" class="btn btn-xs btn-primary" href="'+data['info'][i]['link']+'"><i class="fa fa-chevron-right"></i> Read more</a>',
		                    type: 'info',
		                    icon: 'fa fa-envelope-o'
		            });
		            $('#notificationAudio')[0].play();
		        }
		      }
		      console.log(data);
		    },
		    error: function(){
		      
		    }
		   });
		}
		//LOAD NEW MY MAILS NOTIFICATION //
		function load_new_mymails_menu(){
		  
		  var lists = "";
		  var count = 0;
		  var msg;

		  $.ajax({
		    url: "<?php echo base_url('notification/load_unread_mails') ?>",
		    dataType: "json",
		    success: function(data){
		      if(data['result'] == 1){
		        for(var i=0;i<data['info'].length;i++){
		            lists += "<li>\
		                        <a href='"+data['info'][i]['link']+"'>\
		                          <div class=\"pull-left\">\
		                            <img onerror=\"imgError(this);\" style='width:40px;height:40px' src='"+data['info'][i]['image']+"' class=\"img-circle\" alt=\"User Image\"/>\
		                          </div>\
		                          <h4>\
		                            "+data['info'][i]['from']+"\
		                            <small><i class=\"fa fa-clock-o\"></i> "+data['info'][i]['time']+"</small>\
		                          </h4>\
		                          <p>"+data['info'][i]['content']+"</p>\
		                        </a>\
		                      </li>";
		            count++;
		        }

		        if(count == 1){
		          $("li.new_emails span.counter").addClass('label-danger');
		          msg = "You have "+count+" message";
		        }
		        else if(count > 1){
		          msg = "You have "+count+" messages";
		          $("li.new_emails span.counter").addClass('label-danger');
		        }
		        else{
		          msg = "You have "+count+" message";
		        }
		        $("li.new_emails a.dropdown-toggle").addClass("animated");
		        $("li.new_emails a.dropdown-toggle").addClass("shake");

		        $("li.new_emails ul.dropdown-menu ul.menu").html(lists);
		        $("li.new_emails span.counter").html(count);
		        $("li.new_emails ul.dropdown-menu li.header").html(msg);
		        $("div.box-summary-email h3").html(count);
		      }
		      
		    },
		    error: function(){
		      
		    }
		   });
		}
		// CHECK IF PROCESS IS ABOUT TO END OR OVERDUE ALREADY //
		function notify_rt_process(){
			$.ajax({
				url: "<?php echo base_url('notification/notify_overdue_process') ?>",
				dataType: "json",
				success: function(data){
					for(var i = 0; i < data.length; i++){
						if(data[i]['result'] == true){
							new PNotify({
			                    text: data[i]['message'],
			                    type: data[i]['type'],
			                    icon: 'fa fa-exclamation-circle'
			            	});
			            	$('#notificationAudio')[0].play();

			            	<?php if($this->uri->segment(1) == "request" || $this->uri->segment(1) == "task"){ ?> 
			            	if(data[i]['overdue'] == true){
			            		// get_process(data[i]['rt_id']);
			            		// disable_buttons_process("overdue");
			            		isPreviousProcessDone(data[i]['rt_id']);
			            	}
			            	<?php } ?>
						}
					}
				},
				error: function(){}
			});
		}
		// CHECK IF NEW REQUEST involves you as signatory //
		function notify_new_request_processed(){
			$.ajax({
				url: "<?php echo base_url('notification/check_new_request_as_sig') ?>",
				dataType: "json",
				success: function(data){
					for(var i = 0; i < data.length; i++){
						if(data[i]['result'] == true){
							new PNotify({
			                    text: data[i]['message'],
			                    type: data[i]['type'],
			                    icon: 'fa fa-exclamation-circle'
			            	});
			            	$('#notificationAudio')[0].play();
						}
					}
				},
				error: function(){}
			});
		}

		function notify_done_denied_request(){
			$.ajax({
				url: "<?php echo base_url('notification/check_done_request_steps') ?>",
				dataType: "json",
				success: function(data){
					for(var i = 0; i < data.length; i++){
						if(data[i]['result'] == true){
							new PNotify({
			                    text: data[i]['message'],
			                    type: data[i]['type'],
			                    icon: 'fa fa-exclamation-circle'
			            	});
			            	$('#notificationAudio')[0].play();

						}
					}
				},
				error: function(){}
			});
		}

		function notify_new_request(){
			$.ajax({
				url: "<?php echo base_url('notification/check_new_request') ?>",
				dataType: "json",
				success: function(data){
					if(data.length > 0){
						for(var i = 0; i < data.length; i++){
							if(data[i]['result'] == true){
								new PNotify({
				                    text: data[i]['message'],
				                    type: data[i]['type'],
				                    icon: 'fa fa-exclamation-circle'
				            	});
				            	$('#notificationAudio')[0].play();

							}
						}
					}
				},
				error: function(){}
			});
		}

		function show_alert(title, type, message){
			var icon;

			if(type == "success"){
				icon = 'fa fa-check';
			}
			else{
				icon = 'fa fa-times';
			}

			new PNotify({
				  title: title,
	              text: message,
	              type: type,
	              icon: icon
	      	});
		}

		function chatCounter(){
			
			$.ajax({
				url: "<?php echo base_url("chats/chatCounter"); ?>",
				type: "GET",
				dataType: "json",
				success: function(data){
					if(data['count'] != ""){
						$.each(data['user'], function( key, value){
							$(".chatUserCount[pi_id="+key+"]").html(value);
							console.log(key, value);
						});
					}
					else{

					}
					$("span.chat-counter").html(data['count']);
				},
				error: function(){
					console.log("ERROR COUNTER CHAT");
				}
			});
		}

		function add_notification_menu(){
			
		}

<?php
  	if($CI->uri->segment(1) != '' && $CI->uri->segment(1) != 'login'){
?>
// CHECK SMS - - - - - - - 
	var smsTimeReq = setInterval(checkSMS, 1000);
  
	  function checkSMS(){
	    clearInterval(smsTimeReq);
	    $.ajax({
	      url: "<?php echo base_url('request/checkSmsProcess') ?>",
	      type: "GET",
	      dataType: "json",
	      success: function(data){
	        console.log(data);
	        smsTimeReq = setInterval(checkSMS, 1000);
	      },
	      error: function(){
	        smsTimeReq = setInterval(checkSMS, 1000);
	      }
	    });
	  }

<?php } ?>

<?php
  	if($CI->uri->segment(1) != '' && $CI->uri->segment(1) != 'login'){
?>
// IDLE TIMEOUT
<?php if($this->userInfo->idle_settings != "" || !is_null($this->userInfo->idle_settings) || $this->userInfo->idle_settings > 0){ ?>	

var IDLE_TIMEOUT = 60 * <?php echo $this->userInfo->idle_settings ?>; //seconds
var _idleSecondsCounter = 0;

document.onclick = function() {
    _idleSecondsCounter = 0;
};
document.onmousemove = function() {
    _idleSecondsCounter = 0;
};
document.onkeypress = function() {
    _idleSecondsCounter = 0;
};

window.setInterval(CheckIdleTime, 1000);
<?php } ?>
<?php } ?>

function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
        oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        document.location.href = "<?php echo base_url('dashboard/logout') ?>";
    }
}

// COUNT PENDING TASK NOTIFICATION
<?php
  	if($CI->uri->segment(1) != '' && $CI->uri->segment(1) != 'login'){
?>

var contPendTask = setInterval(countPendingTask, 1000);
var pendingtask = setInterval(pendingTaskNotification, 1000);

function countPendingTask(){

	clearInterval(contPendTask);
	$.ajax({
	  url: "<?php echo base_url('dashboard/countPendingTask'); ?>",
	  dataType: "JSON",
	  success: function(data){
	    
	    var totalCount = 0;
	    
	    if(data.count > 0){
	      	totalCount += data.count;
	    }
	    if(data.countHead > 0){
	    	totalCount += data.countHead;
	    }
	    else{
	      	$("li.dropdown.notifications-menu ul.dropdown-menu li.header").html("No notification available.");
	    }

	    if(totalCount > 0){
	    	$("li.dropdown.notifications-menu a.dropdown-toggle span.label.counter").addClass("label-danger");
	    	$("li.dropdown.notifications-menu a.dropdown-toggle span.label.counter").html(totalCount);
	    }
	    else if(totalCount < 1){
	    	$("li.dropdown.notifications-menu a.dropdown-toggle span.label.counter").removeClass("label-danger");
	    	$("li.dropdown.notifications-menu a.dropdown-toggle span.label.counter").html("");
	    }

	    $(".small-box.box-summary-pending-request div.inner h3").html(totalCount);
	    $("li.dropdown.notifications-menu ul.dropdown-menu li.header").html("You have "+totalCount+" notification/s.");

	    contPendTask = setInterval(countPendingTask, 1000);
	  },
	  error: function(){
	    contPendTask = setInterval(countPendingTask, 1000);
	  }
	});
}

function pendingTaskNotification(){
	clearInterval(pendingtask);
	$.ajax({
		url: "<?php echo base_url('dashboard/getPendingTaskNotification') ?>",
		dataType: "JSON",
		success: function(data){
			$("li.dropdown.notifications-menu ul.dropdown-menu li ul.menu").html("");
			$.each(data[0], function(key, value){
				
				var not = "<li>\
	                        <a href='<?php echo base_url('task#view?id="+value.rt_id+"') ?>' class=\"clearfix\">\
	                          <img src='"+value.img+"' style='width:50px;height:50px;margin-right:5px' class=\"pull-left\">\
	                          <span class=\"text-bold pull-left\">"+value.process+"</span><br>\
	                          <span>"+value.msg+"</span><br>\
	                          <small style='color: #999'>"+value['time']+"</small>\
	                        </a>\
	                      </li>";

				$("li.dropdown.notifications-menu ul.dropdown-menu li ul.menu").prepend(not);
			});

			$.each(data[1], function(key, value){
				
				var not = "<li>\
	                        <a href='"+value.href+"' class=\"clearfix\">\
	                          <img src='"+value.img+"' style='width:50px;height:50px;margin-right:5px' class=\"pull-left\">\
	                          <span class=\"text-bold pull-left\">"+value.from+"</span><br>\
	                          <span>"+value.msg+"</span><br>\
	                          <small style='color: #999'>"+value['time']+"</small>\
	                        </a>\
	                      </li>";

				$("li.dropdown.notifications-menu ul.dropdown-menu li ul.menu").prepend(not);
			});

			pendingtask = setInterval(pendingTaskNotification, 1000);
		},
		error: function(){
			pendingtask = setInterval(pendingTaskNotification, 1000);
		}
	});
}

<?php } ?>

</script>

  </body>
</html>
