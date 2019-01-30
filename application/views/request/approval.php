<style type="text/css">
	table#tblReferenceNo tbody td{
		padding:0px!important;
		padding-bottom:5px!important;
	}
	.requestItem{
		padding:4px;background:#FFF;width:100%;cursor: pointer;
	}
	.requestItem img{
		width:50px;height:50px;float:left;
		border-radius: 2px
	}
	.requestItem div.refInfo{
		margin-left:10px;float:left;
	}

	.requestItem div.refInfo div.refNo{
		font-size:12px
	}
	.requestItem div.refInfo div.refName{
		font-size:12px
	}
	.requestItem div.refInfo div.refDate{
		font-size:12px
	}
	/*.requestItem:hover{
		background:#c0d9e7;
	}	*/
	ul.pagination{
		margin-top:0px!important;
		float:right!important;
		margin-bottom: 0px
	}

	.timeline>li{
		margin-right:0px;
		margin-bottom: 5px;
	}
	.timeline li i{
		display: none!important;
	}
	.timeline:before {
	    background: none;
	}
	.timeline>li>.timeline-item {
	    margin-left:0px;
	}
	#viewReferenceNoContainer{
		box-shadow: none;
	}
	#tblReferenceNo_filter{
		display: none;
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-solid">
				<div class="box-body" style="padding-top:0px">
					
					<div class="row">
						<div class="col-lg-3">
							<div id="btnCountContainer"></div>
							<button onclick="loadReferenceNumbers('request')" class="btn btn-primary btn-sm flat" style="width:100%;margin-bottom:15px">Show All</button>
							<!-- <div class="form-group">
								Select type:
								<select id="selectType" class="form-control">
									<option value="request">Request</option>
									<option value="task">Task</option>
								</select>
							</div> -->
						</div>
						<div class="col-lg-5">
							<div class="form-group">
								Enter Reference Number:
								<div class="input-group">
									<span class="input-group-addon">
										<i class="fa fa-search"></i>
									</span>
									<input style="text-transform:uppercase" onkeyup="searchReferenceNumber($(this).val())" placeholder="Search reference No." type="text" class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<b>Reference No.</b>
							<table id="tblReferenceNo" class="table">
								<thead class="hide">
									<tr>
										<td></td>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-lg-9">
							<!-- REQUEST CONTENT -->
							<div class="box box-primary" id="viewReferenceNoContainer">
								<h2>SELECT REFERENCE NUMBER FROM LIST . . .</h2>
							</div>
							<!-- END REQUEST CONTENT -->

						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- MODal -->
<div id="modalOverdueRemarks" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modalDeniedRemarks" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalAddAttachment" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-file-o"></i> Add Attachment</h4>
      </div>
      <div class="modal-body">

      	<div>

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
			   <li role="presentation" class="active"><a href="#captureTab" aria-controls="captureTab" role="tab" data-toggle="tab">Capture Image</a></li>
			   <li role="presentation"><a href="#attachTab" aria-controls="attachTab" role="tab" data-toggle="tab">Attach File</a></li>
			</ul>

			  <!-- Tab panes -->
			<div class="tab-content" style="padding:10px">
			    <div role="tabpanel" class="tab-pane active" id="captureTab">
			    	
			    	<div class="row">
			        	<div class="col-md-8">
			        		<video style="background:#f3f3f3" id="video" width="640" height="480" autoplay></video>
					        <canvas id="canvas" width="640" height="480" class="hide"></canvas>
					        <br>
					        <button id="snap" class="btn btn-info btn-sm flat"><i class="fa fa-camera"></i> Capture</button>
					        <button id="snapCancel" onclick="resetImages()" class="btn btn-danger btn-sm flat hide"><i class="fa fa-times"></i> Reset</button>
					        <button id="snapAttach" class="btn btn-success btn-sm flat hide"><i class="fa fa-times"></i> Attach</button>
			        	</div>
			        	<div class="col-md-3 col-md-offset-1" id="snapImagesContainer">
			        		
			        	</div>
			        </div>
			    </div>

			    <div role="tabpanel" class="tab-pane" id="attachTab">
			    	<div class="form-group">
			    		<?php echo form_open_multipart("request/uploadRtpAttachments",["id"=>"formUploadRtpAttachments"]); ?>
							<label>Attach Files</label>
							<input type="hidden" id="rtpIDAtt" name="rtpIDAtt">
							<input id="rtp_att" name="rtp_att[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
							<!-- data-allowed-file-extensions='["zip", "rar", "csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' -->
						<?php echo form_close(); ?>
					</div>
			    </div>
			</div>
		</div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalAttachmentEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-file-o"></i> Add Attachment</h4>
      </div>
      <div class="modal-body">

      	<div>

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
			   <li role="presentation" class="active"><a href="#captureTabEdit" aria-controls="captureTabEdit" role="tab" data-toggle="tab">Capture Image</a></li>
			   <li role="presentation"><a href="#attachTabEdit" aria-controls="attachTabEdit" role="tab" data-toggle="tab">Attach File</a></li>
			</ul>

			  <!-- Tab panes -->
			<div class="tab-content" style="padding:10px">
			    <div role="tabpanel" class="tab-pane active" id="captureTabEdit">
			    	
			    	<div class="row">
			        	<div class="col-md-8">
			        		<video style="background:#f3f3f3" id="videoEdit" width="640" height="480" autoplay></video>
					        <canvas id="canvasEdit" width="640" height="480" class="hide"></canvas>
					        <br>
					        <button id="snapEdit" class="btn btn-info btn-sm flat"><i class="fa fa-camera"></i> Capture</button>
					        <button id="snapCancelEdit" onclick="resetImagesEdit()" class="btn btn-danger btn-sm flat hide"><i class="fa fa-times"></i> Reset</button>
					        <button id="snapAttachEdit" class="btn btn-success btn-sm flat hide"><i class="fa fa-times"></i> Attach</button>
			        	</div>
			        	<div class="col-md-3 col-md-offset-1" id="snapImagesContainerEdit">
			        		
			        	</div>
			        </div>
			    </div>

			    <div role="tabpanel" class="tab-pane" id="attachTabEdit">
			    	<div class="form-group">
			    		<?php echo form_open_multipart("request/uploadRtpAttachments",["id"=>"formUploadRtpAttachmentsEdit"]); ?>
							<label>Attach Files</label>
							<input type="hidden" id="rtpIDAttEdit" name="rtpIDAttEdit">
							<input id="rtp_attEdit" name="rtp_attEdit[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
							<!-- data-allowed-file-extensions='["zip", "rar", "csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' -->
							<button id="btnResetAttachmentEdit" class="hide" type="reset">REset</button>
						<?php echo form_close(); ?>
					</div>
			    </div>
			</div>
		</div>
        
      </div>
      <div class="modal-footer">
      	<button onclick="updateProcessAttachment()" type="button" class="btn btn-success flat">Update Attachments</button>
        <button type="button" class="btn btn-default flat" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- Modal -->
<div class="modal fade" id="modalGallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-paperclip"></i> Attachments</h4>
      </div>
      <div class="modal-body clearfix"></div>
      <div class="modal-footer">
      	<button class="btn btn-primary flat pull-left hide" type="" id="addMoreAtt">Add more attachments</button>
        <button type="button" class="btn btn-default flat" data-dismiss="modal">Close</button>
        <a id="btnDownloadZip" type="button" class="btn btn-primary flat"><i class="fa fa-download"></i> Download All</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalViewAttachment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      	<img class='img img-responsive img-thumbnail' style='width:100%'>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPendingTask" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pending Task <span class="badge">4</span></h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	//SNAPSHOT
	var snapImg = [];
	var snapImgEdit = [];
	var localstream;
	var localstream2;
	var countAtt = 0;
	countPendingTask();

	$(document).ready(function(){
		// Grab elements, create settings, etc.
		var video = document.getElementById('video');
		// Get access to the camera!
		
		$('#modalAddAttachment').on('shown.bs.modal', function (e) {
		  // do something...
			if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			    // Not adding `{ audio: true }` since we only want video now
			    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
			        video.src = window.URL.createObjectURL(stream);
			        video.play();
			        localstream = stream;
			    });
			}
		});

		$('#modalAddAttachment').on('hidden.bs.modal', function (e) {
		   localstream.getVideoTracks()[0].stop();
		   if(snapImg.length > 0){
		   	 countAtt += snapImg.length;
		   	 $("button#btnAttachment").html("<i class='fa fa-file-o'></i> Attachment <span class=\"badge\">"+countAtt+"</span>");
		   }
		   else{
		   	 $("button#btnAttachment").html("<i class='fa fa-file-o'></i> Attachment");
		   }
		});

		$('a[data-toggle="tab"][aria-controls="captureTab"]').on('shown.bs.tab', function (e) {
		  	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			    // Not adding `{ audio: true }` since we only want video now
			    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
			        video.src = window.URL.createObjectURL(stream);
			        video.play();
			        localstream = stream;
			    });
			}
		});

		$('a[data-toggle="tab"][aria-controls="captureTab"]').on('hidden.bs.tab', function (e) {
		  localstream.getVideoTracks()[0].stop();
		});

		// Elements for taking the snapshot
		var canvas = document.getElementById('canvas');
		var context = canvas.getContext('2d');

		// Trigger photo take
		document.getElementById("snap").addEventListener("click", function() {
			
			context.drawImage(video, 0, 0, 640, 480);
			$("#snapCancel").removeClass('hide');
			$("#snapAttach").removeClass('hide');
			$("#canvas").removeClass('hide');
			$("#canvas").fadeIn();
			
			$("#video").addClass('hide');
			$("#snap").addClass('hide');
		});

		document.getElementById("snapAttach").addEventListener("click", function() {
			convertCanvasToImage(canvas);
			resetImages();
		});

		$('#rtp_att').on('change', function(event) {

		    var inp = document.getElementById('rtp_att');
		    
			for (var i = 0; i < inp.files.length; ++i) {
			  countAtt++;
			}

			if(countAtt > 0){
		   	 	$("button#btnAttachment").html("<i class='fa fa-file-o'></i> Attachment <span class=\"badge\">"+countAtt+"</span>");
		   	}
		   	else{
		   	 $("button#btnAttachment").html("<i class='fa fa-file-o'></i> Attachment");
		   	}
		});

		$("form#formUploadRtpAttachments").on("submit", function(e){
			e.preventDefault();
			$.ajax({
			    url: "<?php echo base_url('request/uploadRtpAttachments') ?>",
			    type: "POST",            
			    data: new FormData(this),
			    dataType: "json",
			    contentType: false,      
			    cache: false,            
			    processData:false,       
			    success: function(data)  
			    {
			     
			    },
			    error:function(data){
			      
			    }
		    });
		});
	});

	function convertCanvasToImage(canvas) {
		var image = new Image();
		image.src = canvas.toDataURL("image/png");
		
		var img = "<div class='box box-solid imgAttachment' style='margin-bottom:4px'>\
						<div class='box-body' style='background:#F3f3f3;padding-top:0px'>\
							<button onclick=\"removeImage($(this))\" type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>\
							<img src='"+image.src+"' class='img img-responsive'>\
						</div>\
					</div>"
		
		$("#snapImagesContainer").prepend(img);
		snapImg.push(image.src);
	}

	function removeImage(b){
		var src = b.siblings("img").attr("src");
		var index = snapImg.indexOf(src);

		if (index > -1) {
	       snapImg.splice(index, 1);
	    }

	    b.closest("div.imgAttachment").remove();
	}

	function saveImage64(name, canvas) {
		var img = new Image();
		dataURL = canvas.toDataURL("images/png");
		
		$.ajax({
			url: "<?php echo base_url('rt_approval/saveImageFolder') ?>",
			data: {imgBase64: dataURL, imgFileName : name},
			type: "POST",
			dataType: "html",
			success: function(data){
				getImages();
			},
			error: function(){
			}
		});
	}

	function resetImages(){
		$("#snapCancel").addClass('hide');
		$("#snapAttach").addClass('hide');
		$("#canvas").addClass('hide');
		
		$("#video").removeClass('hide');
		$("#snap").removeClass('hide');
	}

	function resetAttachments(){
		snapImg = [];
		$("#snapImagesContainer").html("");
	}
	function resetAttachmentsEdit(){
		snapImgEdit = [];
		$("#snapImagesContainerEdit").html("");
	}

	function getAttachments(rtp_id, step, pi_id){
		$.ajax({
			url: "<?php echo base_url('rt_approval/getAttachments') ?>",
			data: {rtp_id : rtp_id, pi_id : pi_id},
			type: "GET",
			dataType: "json",
			success: function(data){

				if(<?php echo $this->userInfo->pi_id ?> == pi_id){
					$("#modalGallery .modal-footer button#addMoreAtt").removeClass("hide");
					$("#modalGallery .modal-footer button#addMoreAtt").attr("onclick", "showEditAttachmentModal('"+data[1]+"',"+rtp_id+");$('#modalGallery').modal('hide');");
				}
				else{
					$("#modalGallery .modal-footer button#addMoreAtt").addClass("hide");
				}

				$("#modalGallery .modal-body").html(data[0]);
				$("#modalGallery .modal-header h4.modal-title").html("<span class='fa fa-paperclip'></span> <b>"+step+"</b> Attachments");
				$("#modalGallery a#btnDownloadZip").attr("href", "<?php echo base_url('rt_approval/downloadAsZip/"+rtp_id+"') ?>");
				$("#modalGallery").modal("show");


			},
			error: function(){

			}
		});
	}

	function viewAttachment(img){
		var path = img.attr('src');
		var name = img.attr('path');

		$("#modalViewAttachment .modal-body img").attr("src", path);
		$("#modalViewAttachment .modal-header h4.modal-title").html(name);
		$("#modalViewAttachment").modal("show");
	}

	function deleteRtpAtt(rtpa_id, path){
		bootbox.confirm("Are you sure you want delete the attachment?", function(result) {
		  	if(result == true){
			  	$.ajax({
			  		url: "<?php echo base_url('rt_approval/deleteAttachment') ?>",
			  		data: {rtpa_id : rtpa_id, path : path},
			  		type: "GET",
			  		dataType: "json",
			  		success: function(data){
			  			if(data.result == true){

			  				$("#modalGallery .modal-body div.rtp_att#rtpa_"+rtpa_id).fadeOut(1000);

			  				new PNotify({
		                          title: 'Success',
		                          text: 'Attachment deleted successfully.',
		                          type: 'success',
		                          icon: 'fa fa-check'
		                    });

			  			}
			  			else{

			  			}
			  		},
			  		error: function(){

			  		}
			  	});	
		  	}
		}); 
	}

//--------------------------------------------- END OF ATTACHMENT JS -------------------------------------------------------

	$(function(){
		var url = window.location.href;
		url = url.split("=");

		if(url.length > 1){
			isPreviousProcessDone(url[1]);
		}
		
	});
	
	var tblReferenceNo;
	var pubPreviousProcess;
	var openedRT = {open : false, rt_id : 0};

	// REAL TIME UPDATE 
	var checkUpdateRefNo = setInterval(function(){

		if(openedRT['open'] == true){
			getRequestDetails(openedRT.rt_id, openedRT.result, openedRT.stat, openedRT.rtp_id);
			console.log("viewed: "+openedRT['rt_id']);
		}
		else{
			console.log("nothing viewed");
		}
	},5000);


	$(document).ready(function(){
		tblReferenceNo = $("#tblReferenceNo").dataTable({
			"bLengthChange": false,
			"bSort":false,
			"pageLength": 5,
			"bFilter":true,
			"bInfo":false,
			"pagingType" : "simple",
			"oLanguage": {
		                        "sSearch": "<i class='fa fa-search'></i> ",
		                        "oPaginate": 
		                                      {
		                                          "sNext": '<i class="fa fa-chevron-right"></i>',
		                                          "sPrevious": '<i class="fa fa-chevron-left"></i>',
		                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
		                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
		                                      }
		                    }
		});
	});

	var pubRTtype = $("#selectType").val();
	loadReferenceNumbers(pubRTtype);

	$(document).ready(function(){
		$("#selectType").on("change", function(){
			loadReferenceNumbers($(this).val());
		});
	});

	function loadReferenceNumbers(type){
		$.ajax({
			url: "<?php echo base_url('request/getReferenceNumbers') ?>",
			data: {type : 'request'},
			type: "GET",
			dataType: "json",
			success: function(data){
				tblReferenceNo.fnClearTable();
				$.each(data, function(key, value){

					var href = "#view?id="+value.rt_id;
					var newRow = tblReferenceNo.fnAddData([
							"<a href='"+href+"'><div onclick=\"isPreviousProcessDone("+value.rt_id+")\" class=\"requestItem clearfix\">\
												<img onerror=\"imgError(this)\" src=\"<?php echo base_url('images/profile_image/"+value.img_path+"') ?>\">\
												<div class=\"refInfo\">\
													<div class=\"refNo\"><small>Ref No.</small> <b>"+value.rt_ref_no+"</b></div>\
													<div class=\"refName\">"+value.firstname+" "+value.lastname+"</div>\
													<div class='refDate'>"+value.rt_date_time+"</div>\
												</div>\
												<div class='pull-right'>"+value.processStatus+"</div>\
											</div></a>"
						]);

					var oSettings = tblReferenceNo.fnSettings();
                    var nTr = oSettings.aoData[ newRow[0] ].nTr;
                    $(nTr).attr("id", value['rt_id']);
				});
				console.log(data);
			},
			error: function(){

			}
		});
	}

	$(document).ready(function(){

		$('#tblReferenceNo tbody').on('click', 'tr', function () {
            $("table#tblReferenceNo tbody tr td .requestItem").css({"background":"#FFF"});
            $("table#tblReferenceNo tbody tr").removeClass("activeRow");

            $(this).closest('tr').addClass("activeRow");
            $("#tblReferenceNo tbody tr.activeRow td .requestItem").css({"background":"#CCC"});
        });
    });

	function viewReferenceNumber(rt_id, result1){
		if(result1[0] == true){
			if(result1[1] == "pending"){
				bootbox.confirm("Are you sure you received already the document? The process will start after this confirmation.", function(result) {
				  	if(result == true){
					  	updateToProcessing(result1[2], rt_id);
					  	update_check_status(rt_id);
						getRequestDetails(rt_id, true, result1[1], result1[2]);
				  	}
				}); 

			}
			else{
				update_check_status(rt_id);
				getRequestDetails(rt_id, true, result1[1], result1[2]);
			}
		}
		else{
			update_check_status(rt_id);
			getRequestDetails(rt_id, false, result1[1], result1[2]);
			
		}
	}

	function getRequestDetails(rt_id, result, stat, rtp_id){
		
		openedRT = {open : true, rt_id : rt_id, result : result, stat : stat, rtp_id : rtp_id};

		$.ajax({
			url: "<?php echo base_url('request/viewReferenceNumber') ?>",
			data: {rt_id : rt_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				var str = "";
				$.each(data, function(key, value){
					str += "<div class=\"box-header clearfix\" style=\"background:#CCC\">\
									<div class=\"clearfix pull-left\">\
										<img onerror=\"imgError(this)\" class=\"pull-left\" src=\"<?php echo base_url('images/profile_image/"+value.img_path+"') ?>\" style=\"width:30px;height:30px\">\
										<div class=\"pull-left\" style=\"margin-left:10px\">\
											<span>"+value.firstname+" "+value.lastname+" <small>("+value.position+")</small></span><br>\
										</div>\
									</div>\
									<div class=\"pull-right\">";
										if(result == true){
											str += "<button id='btnAttachment' onclick=\"$('#modalAddAttachment').modal('show');$('input#rtpIDAtt').val("+rtp_id+");\" class=\"btn btn-sm btn-default flat\"><i class='fa fa-file-o'></i> Attachment";

											if(countAtt > 0){
											   	 str += " <span class=\"badge\">"+countAtt+"</span>";
											}
											else{
												str += "";
											}
											  
											str +="</button> <button onclick=\"confirmProcessDenied("+rt_id+",'done', "+rtp_id+", '"+stat+"')\" id='btnProcess' class=\"btn btn-success btn-sm flat\"><i class=\"fa fa-check\"></i> Process</button>\
												<button onclick=\"confirmProcessDenied("+rt_id+", 'denied', "+rtp_id+", '"+stat+"')\" id='btnDecline' class=\"btn btn-danger btn-sm flat\"><i class=\"fa fa-times\"></i> Decline</button>";
										}
										
							str +=	"</div>\
								</div>\
								<div class=\"box-body\" style=\"background:#F3f3f3\">\
									<div class=\"row\">\
										<div class=\"col-lg-9\">\
											<h2 style=\"font-weight:bold;margin-top:5px;margin-bottom:0px\">"+value.rt_title+"</h2>\
											<small>REFERENCE NO. "+value.rt_ref_no+"</small>\
											<span class=\"pull-right\">"+value.rt_date_time+"</span>\
											<div style=\"margin-top:10px;background:#FFF;padding:5px;font-size:10px;border:1px solid #CCC\">"+value.rt_content+"</div>";
										
										if(value.attachments != ""){
											str += "<br><span style=\"font-weight:bold\">Attachments</span><br>";
											str += value.attachments;
										}

					str +=	"</div>\
								<div class=\"col-lg-3\" style='font-size:10px!important;padding-left:0px!important;padding-right:0px!important'><span style=\"font-size:14px;font-weight:bold\">Procedure</span>"+value.process+"</div>\
							</div>\
						</div>";

				});
				
				$("#viewReferenceNoContainer").html(str);
				$("#viewReferenceNoContainer table").css({"width":"100%"});
				$("#viewReferenceNoContainer table td").css({"border":"1px solid #333"});
				
			},
			error: function(){

			}
		});
	}

	function update_check_status(rt_id){
	  $.ajax({
	    url: "<?php echo base_url('request/update_check_status') ?>",
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

	function searchReferenceNumber(val){
		tblReferenceNo.fnFilter(val);
	}

	function isPreviousProcessDone(rt_id){
		$.ajax({
			url: "<?php echo base_url('request/isPreviousProcessDone') ?>",
			data: {rt_id : rt_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				viewReferenceNumber(rt_id, data);
			},
			error: function(){
				console.log("error");
			}
		});
	}

	function updateToProcessing(rtp_id, rt_id){
		$.ajax({
			url: "<?php echo base_url('request/updateToProcessing') ?>",
			data: {rtp_id : rtp_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data.result == true){
					
				}
			},
			error: function(){

			}
		});
	}

	function confirmProcessDenied(rt_id, type, rtp_id, stat){

		if(type == "done"){
			if(stat == "overdue"){
				bootbox.dialog(
					{
		                title: "Confirmation",
		                message: '<div class="row">  ' +
		                    '<div class="col-md-12"> ' +
		                    'It seems the process has been overdue, kindly give us your reason why.' +
		                    '<textarea rows=\'5\' class=\'form-control\' id=\'remarks\'></textarea>' +
		                    '</div>  </div>',
		                buttons: {
		                    success: {
		                        label: "Submit",
		                        className: "btn-success",
		                        callback: function () {

		                            var reason = $('#remarks').val();
		                            var remarks = "";

		                            if(reason == ""){
		                            	new PNotify({
						                    title: 'Information',
						                    text: "Please give us remarks why overdue.",
						                    type: 'error',
						                    icon: 'fa fa-exclamation'
						              	});
		                            }
		                            else{
		                            	processRequest(rt_id, rtp_id, type, remarks, reason);
		                            }
		                        }
		                    }
		                }
		            }
		        );
			}
			else{
				bootbox.confirm("Are you sure you want process request?", function(result) {
				  	if(result == true){
					  	var remarks = "";
		                var reason = "";

		                processRequest(rt_id, rtp_id, type, remarks, reason);
				  	}
				}); 
			}
		}
		else{
			bootbox.dialog(
				{
	                title: "Confirmation",
	                message: '<div class="row">  ' +
	                    '<div class="col-md-12"> ' +
	                    'Are you sure you want to deny the request? Why?' +
	                    '<textarea rows=\'5\' class=\'form-control\' id=\'remarks\'></textarea>' +
	                    '</div>  </div>',
	                buttons: {
	                    success: {
	                        label: "Submit",
	                        className: "btn-success",
	                        callback: function () {
	                            var remarks = $('#remarks').val();
	                            var reason = "";

	                            if(remarks == ""){
	                            	new PNotify({
					                    title: 'Information',
					                    text: "Please give us remarks why decline.",
					                    type: 'error',
					                    icon: 'fa fa-exclamation'
					              	});
	                            }
	                            else{
	                            	processRequest(rt_id, rtp_id, type, remarks, reason);
	                            }
	                        }
	                    }
	                }
	            }
	        );
		}
	}

	function processRequest(rt_id, rtp_id, type, remarks, reason){
		$.LoadingOverlay("show", {
		    image       : "<?php echo base_url('assets/images/loading123.gif') ?>",
		    fontawesome : ""
		});
		$.ajax({
			url: "<?php echo base_url('request/changeProcessStatus'); ?>",
			data: {rtp_id : rtp_id, type : type, remarks : remarks, reason : reason, rt_id : rt_id, att : snapImg},
			type: "POST",
			dataType: "json",
			success: function(data){
				
				$.LoadingOverlay("hide");
				
				if(data.result == true){

					var inp = document.getElementById('rtp_att');

					if(inp.files.length > 0 ){
						$("form#formUploadRtpAttachments").submit();
					}

					update_check_status(rt_id);
					getRequestDetails(rt_id, false, type, rtp_id);
					loadReferenceNumbers(pubRTtype);
					countPendingTask();
					resetAttachments();
				}
			},
			error: function(){
				$.LoadingOverlay("hide");
			}
		});
	}

	function countPendingTask(){
		$.ajax({
			url: "<?php echo base_url('request/countPendingTask') ?>",
			dataType: "json",
			success: function(data){
				if(data[0] > 0){
					var i = "<button onclick=\"loadPendingTask()\" style='margin-bottom:5px;width:100%' class=\"btn btn-warning btn-sm flat\" type=\"button\">\
							  Pending Task <span class=\"badge\">"+data[0]+"</span>\
							</button>"
					$("#btnCountContainer").html(i);
					$("#modalPendingTask .badge").html(data[0]);
				}
				else{
					$("#btnCountContainer").html("");
				}
				console.log(data[1]);
			},
			error: function(){

			}
		});
	}

	function loadPendingTask(){
		$.ajax({
			url: "<?php echo base_url('request/showPendingtask') ?>",
			type: "GET",
			dataType: "json",
			success: function(data){
				tblReferenceNo.fnClearTable();
				$.each(data, function(key, value){

					var href = "#view?id="+value.rt_id;
					var newRow = tblReferenceNo.fnAddData([
							"<a href='"+href+"'><div onclick=\"isPreviousProcessDone("+value.rt_id+")\" class=\"requestItem clearfix\">\
												<img onerror=\"imgError(this)\" src=\"<?php echo base_url('images/profile_image/"+value.img_path+"') ?>\">\
												<div class=\"refInfo\">\
													<div class=\"refNo\"><small>Ref No.</small> <b>"+value.rt_ref_no+"</b></div>\
													<div class=\"refName\">"+value.firstname+" "+value.lastname+"</div>\
													<div class='refDate'>"+value.rt_date_time+"</div>\
												</div>\
												<div class='pull-right'>"+value.processStatus+"</div>\
											</div></a>"
						]);

					var oSettings = tblReferenceNo.fnSettings();
                    var nTr = oSettings.aoData[ newRow[0] ].nTr;
                    $(nTr).attr("id", value['rt_id']);
				});
				console.log(data);
			},
			error: function(){

			}
		});
	}


	// EDIT ATTACHMENTS //
	$(document).ready(function(){
		// Grab elements, create settings, etc.
		var video = document.getElementById('videoEdit');
		// Get access to the camera!
		
		$('#modalAttachmentEdit').on('shown.bs.modal', function (e) {
		  // do something...
			if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			    // Not adding `{ audio: true }` since we only want video now
			    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
			        video.src = window.URL.createObjectURL(stream);
			        video.play();
			        localstream2 = stream;
			    });
			}
		});

		$('#modalAttachmentEdit').on('hidden.bs.modal', function (e) {
		   localstream2.getVideoTracks()[0].stop();
		});

		$('a[data-toggle="tab"][aria-controls="captureTabEdit"]').on('shown.bs.tab', function (e) {
		  	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			    // Not adding `{ audio: true }` since we only want video now
			    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
			        video.src = window.URL.createObjectURL(stream);
			        video.play();
			        localstream2 = stream;
			    });
			}
		});

		$('a[data-toggle="tab"][aria-controls="captureTabEdit"]').on('hidden.bs.tab', function (e) {
		  localstream2.getVideoTracks()[0].stop();
		});

		// Elements for taking the snapshot
		var canvas = document.getElementById('canvasEdit');
		var context = canvas.getContext('2d');

		// Trigger photo take
		document.getElementById("snapEdit").addEventListener("click", function() {
			
			context.drawImage(video, 0, 0, 640, 480);
			
			$("#snapCancelEdit").removeClass('hide');
			$("#snapAttachEdit").removeClass('hide');
			$("#canvasEdit").removeClass('hide');
			$("#canvasEdit").fadeIn();
			
			$("#videoEdit").addClass('hide');
			$("#snapEdit").addClass('hide');
		});

		document.getElementById("snapAttachEdit").addEventListener("click", function() {
			convertCanvasToImageEdit(canvas);
			resetImagesEdit();

			console.log(snapImgEdit);
		});

		$("form#formUploadRtpAttachmentsEdit").on("submit", function(e){
			e.preventDefault();
			$.ajax({
			    url: "<?php echo base_url('request/uploadRtpAttachmentsEdit') ?>",
			    type: "POST",            
			    data: new FormData(this),
			    dataType: "json",
			    contentType: false,      
			    cache: false,            
			    processData:false,       
			    success: function(data)  
			    {
			     	console.log(data);
			    },
			    error:function(data){
			      
			    }
		    });
		});
	});

	function uploadSnapEditedImages(){
		var rtp_id = $("#modalAttachmentEdit input#rtpIDAttEdit").val();
		$.ajax({
			url: "<?php echo base_url('request/editSnappedImages'); ?>",
			data: {att : snapImgEdit, rtp_id : rtp_id},
			type: "POST",
			dataType: "json",
			success: function(data){

			},
			error: function(){
			}
		});
	}

	function convertCanvasToImageEdit(canvas) {
		var image = new Image();
		image.src = canvas.toDataURL("image/png");
		
		var img = "<div class='box box-solid imgAttachment' style='margin-bottom:4px'>\
						<div class='box-body' style='background:#F3f3f3;padding-top:0px'>\
							<button onclick=\"removeImageEdit($(this))\" type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>\
							<img src='"+image.src+"' class='img img-responsive'>\
						</div>\
					</div>"
		
		$("#snapImagesContainerEdit").prepend(img);
		snapImgEdit.push(image.src);
	}
	function resetImagesEdit(){
		$("#snapCancelEdit").addClass('hide');
		$("#snapAttachEdit").addClass('hide');
		$("#canvasEdit").addClass('hide');
		
		$("#videoEdit").removeClass('hide');
		$("#snapEdit").removeClass('hide');
	}
	function removeImageEdit(b){
		var src = b.siblings("img").attr("src");
		var index = snapImgEdit.indexOf(src);

		if (index > -1) {
	       snapImgEdit.splice(index, 1);
	    }

	    b.closest("div.imgAttachment").remove();
	    console.log(snapImgEdit);
	}

	function showEditAttachmentModal(process, rtp_id){
		$("#modalAttachmentEdit input#rtpIDAttEdit").val(rtp_id);
		$('#modalAttachmentEdit .modal-header h4.modal-title').html("<i class=\"fa fa-file-o\"></i> Add Attachment in <b>"+process+"</b>");
		$('#modalAttachmentEdit').modal('show');
	}

	function updateProcessAttachment(){
		var inp = document.getElementById('rtp_attEdit');
		if(snapImgEdit.length > 0 || inp.files.length > 0 ){
			
			uploadSnapEditedImages();
			$("form#formUploadRtpAttachmentsEdit").submit();
			
			resetAttachmentsEdit();
			$('#modalAttachmentEdit').modal('hide');
			$("button#btnResetAttachmentEdit").trigger("click");
			
			new PNotify({
                  title: 'Success',
                  text: 'Attachment updated successfully.',
                  type: 'success',
                  icon: 'fa fa-check'
            });

		}
		else{
			alert("no attachments");
		}
	}

</script>
