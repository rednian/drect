<script type="text/javascript">

      $('.carousel').carousel({
        interval: 10000
      });

      var selected_status;
      var selected_year;
      var selected_type;

      // request_summary();
      // countPendingTask();

      // refresh_bar = setInterval(function(){
      //     if(selected_status != ""){
      //      show_graph(selected_status, selected_year, selected_type);
      //     }
      // },10000);

      show_graph("pending", $("#rt_year").val(), $("#rt_type").val());

      function show_graph(status, year, type){

        selected_status = status;
        selected_year = year;
        selected_type = type;

        $.ajax({
          url: "<?php echo base_url('dashboard/get_graph_data') ?>",
          data: {status : selected_status, year : selected_year, type : selected_type},
          type: "GET",
          dataType: "json",
          success: function(data){
        
          var bar_dataPending = {data : data['bar'], color: data['color']};

           $.plot("#bar-chart", [bar_dataPending], {
              grid: {
                borderWidth: 1,
                borderColor: "#f3f3f3",
                tickColor: "#f3f3f3"
              },
              series: { 
                bars: {
                  show: true,
                  barWidth: 0.5,
                  align: "center",
                  
                }
              },
              xaxis: {
                mode: "categories",
                tickLength: 0
              },
              yaxis:
              {
                  min:0
              }
           });

          },
          error: function(){

          }
        });
      }

function request_summary(){
  $.ajax({
    url: "<?php echo base_url("dashboard/count_requests") ?>",
    type: "GET",
    dataType: "json",
    success: function(data){
      $(".box-summary.box-summary-endorsed-request .inner h3").html(data['endorsed']);
      $(".box-summary.box-summary-denied-request .inner h3").html(data['denied']);

      console.log(data);
    },
    error: function(){

    }
  });
}

get_schedules();
function get_schedules(){
  $.ajax({
    url: "<?php echo base_url('dashboard/get_schedule') ?>",
    dataType: "html",
    success: function(data){
      $(".carousel-inner").html(data);
    },
    error: function(){

    }
  });
}

var tableEndorseRT;
var rtType = $("#selectRTType").val();

loadReferenceNumbers(rtType);

$(document).ready(function(){
  tableEndorseRT = $("#tableEndorseRT").dataTable({
                      "bInfo":false,
                      "pageLength": 3,
                      "bLengthChange":false,
                      "bSort":false,
                      "bFilter":false,
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

  $("#selectRTType").on("change", function(){
    loadReferenceNumbers($(this).val());
  });
});

function loadReferenceNumbers(type){
    $.ajax({
      url: "<?php echo base_url('request/getReferenceNumbers') ?>",
      data: {type : type},
      type: "GET",
      dataType: "json",
      success: function(data){
        tableEndorseRT.fnClearTable();
        $.each(data, function(key, value){

          // var href = "#view?id="+value.rt_id;
          var href = "<?php echo base_url('task#view?id="+value.rt_id+"') ?>";

          var newRow = tableEndorseRT.fnAddData([
              "<a href='"+href+"'><img style=\"width:40px;height:40px\" class=\"pull-left\" onerror=\"imgError(this)\" src=\"<?php echo base_url('images/profile_image/"+value.img_path+"') ?>\">\
                            <div class=\"pull-left\" style=\"padding-left:10px\">\
                              <span>"+value.firstname+" "+value.lastname+"</span><br>\
                              Ref No. <b style=\"font-size:13px\">"+value.rt_ref_no+"</b>\
                            </div>\
                            <div class='pull-right'><span style='font-size:12px'>"+value.rt_date_time+"</span><br>"+value.processStatus+"</div></a>"
            ]);

          var oSettings = tableEndorseRT.fnSettings();
                    var nTr = oSettings.aoData[ newRow[0] ].nTr;
                    $(nTr).attr("id", value['rt_id']);
        });
        console.log(data);
      },
      error: function(){

      }
    });
  }

 </script>