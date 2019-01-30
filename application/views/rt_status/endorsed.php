<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">STATISTICS</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
                	
          <div class="row">
            <div class="col-md-6">
              <div class="chart" id="sales-chart"></div>
            </div>
          </div>

        	<div class="row"  style="margin-top:30px">
        		<div class="col-md-3">
        			<div class="form-group">
           		<select id="selectFilter" class="form-control input-sm">
           			<option selected class="hide" value="">Filter by:</option>
           			<option value="">All</option>
           			<option value="approved">Approved</option>
           			<option value="pending">Pending</option>
           			<option value="overdue">Ovedue</option>
           			<option value="denied">Denied</option>
                <option value="undone">Undone</option>
           		</select>
           	</div>
        		</div>
        	</div>
        	<div class="row">
        		<div class="col-md-12">
        			<table id="tblReports" class="table table-bordered table-hover" style="width:100%">
        				<thead>
        					<tr>
        						<th>Ref. NO.</th>
                    <th>Requestor</th>
        						<th>Title</th>
        						<th>Request</th>
                    <th>Date</th>
                    <th>Status</th>
        					</tr>
        				</thead>
        				<tbody></tbody>
        			</table>
        		</div>
        	</div>
        </div><!-- /.box-body -->
      </div>
		</div>
	</div>
</section>

<script type="text/javascript">

  function getStatistics(){
    $.ajax({
      url: "<?php echo base_url('rt_status_endorsed/getStatistics') ?>",
      dataType: "JSON",
      success: function(data){
        new Morris.Donut({
          element: 'sales-chart',
          resize: true,
          colors: ["#CCC", "#f56954", "#00a65a", '#ffbf00', "#11a0d2"],
          data: data,
          hideHover: 'auto'
        });
      },
      error: function(){

      }
    });
  }

	$(function(){
    getStatistics();
    var tblReports = $('#tblReports').DataTable( {
        ajax: "<?php echo base_url('rt_status_endorsed/setStatisticsTable') ?>",
        dom: 'Bfrtip',
        buttons: [
            {
                title: 'Request & Task Status',
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
              title: 'Request & Task Status',
              extend: 'csv',
              text: 'CSV',
              exportOptions: {
                  modifier: {
                      page: 'current'
                  },
                  columns: ':visible'
              }
            },
            {
              extend: 'colvis',
              columns: [1,2,3,4,5]
            }
        ]
    });

    $('#selectFilter').on("change", function(){
      var stat = $(this).val();
      tblReports
        .column(5)
        .search(stat)
        .draw();
    });
  });
</script>
 