<?php include('css.php') ?>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            
            <div class="col-lg-12">

                <div class="form-group pull-right">
                  <button onclick='load_archive_files()' type='button btn-default btn-sm'><i class='fa fa-refresh'></i> Refresh</button>
                  <button onclick="archive_files()" type='button btn-default btn-sm'><i class='fa fa-archive'></i> Archive all Files</button>
                </div>
                  
                <table class="table table-hover table-striped" id="tbl_archive">
                  <thead>
                    <tr>
                    <th></th>
                      <th>Name</th>
                      <th>Version</th>
                      <th>Type</th>
                      <th>Size</th>
                      <th>Date Modified</th>
                      <th>Remarks</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
            </div>
           
          </div>

        </section><!-- /.content -->

  <script type="text/javascript">
    
    var tblarchive = $("#tbl_archive").dataTable({
                                                  "bPaginate": true,
                                                  "bLengthChange": false,
                                                  "bFilter": true,
                                                  "bSort": true,
                                                  "bInfo": true,
                                                  "bAutoWidth": true,
                                                  "iDisplayLength": 10,
                                                    "oLanguage": 
                                 {
                                      "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-chevron-right"></i>',
                                          "sPrevious": '<i class="fa fa-chevron-left"></i>'
                                      }
                                  },
                                  "dom": '<"toolbar"><"archive">lfrtip'
                                                  });

load_archive_files();

function load_archive_files(){
  $.ajax({
    url: "<?php echo base_url('external_functions/archive/load_archive_files.php') ?>",
    dataType: "json",
    success: function(data){
      console.log(data);
      tblarchive.fnClearTable();
      for(var i = 0; i < data.length; i++) { 

        tblarchive.fnAddData([ 
                              i+1,
                              data[i]['af_name'], 
                              data[i]['af_version'], 
                              data[i]['af_type'], 
                              data[i]['af_size'], 
                              data[i]['af_date_modified'],
                              data[i]['af_remark'],
                              data[i]['action']
                              ]); 
      } // End For
    },
    error: function(){

    }
  });
}


function archive_files(){
  $.ajax({
    url: "<?php echo base_url('external_functions/archive/archive_files.php') ?>",
    dataType: "html",
    success: function(data){

    },
    error: function(){

    }
  });

  window.open('<?php echo base_url("FILES/archive_files.zip") ?>');

}

// $(document).ready(function() {
//     var refreshbut = "<button onclick='load_archive_files()' type='button btn-default btn-sm'><i class='fa fa-refresh'></i> Refresh</button>";
//     $("div.toolbar").html(refreshbut);

//     var archivebut = "<button onclick=\"archive_files();\" type='button btn-default btn-sm'><i class='fa fa-archive'></i> Archive all Files</button>";
//     $("div.archive").html(archivebut);
// } );
  </script>