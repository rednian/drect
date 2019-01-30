<style type="text/css">
  ul.pagination{
    margin-top:0px!important;
    float:right!important;
    margin-bottom: 0px
  }
</style>
        <!-- Main content -->
        <section class="content">
          <a href="" onclick="window.open('http://www.w3schools.com/jsref/met_win_open.asp', '', 'width=500,height=500,resizable=no')"></a>
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>0</h3>
                  <p>New Email</p>
                </div>
                <div class="icon">
                  <i class="ion ion-email-unread"></i>
                </div>
                <a href="<?php echo base_url('mail') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green box-summary box-summary-endorsed-request">
                <div class="inner">
                  <h3>0</h3>
                  <p>Endorsed Request/Task</p>
                </div>
                <div class="icon">
                  <i class="ion ion-document-text"></i>
                </div>
                <a href="<?php echo base_url('request') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow box-summary box-summary-pending-request">
                <div class="inner">
                  <h3>0<?php //echo $countPending['count']; ?></h3>
                  <p>Pending Request/Task</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-stopwatch"></i>
                </div>
                <a href="<?php echo base_url('task') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red box-summary box-summary-denied-request">
                <div class="inner">
                  <h3>0</h3>
                  <p>Denied Request/Task</p>
                </div>
                <div class="icon">
                  <i class="ion ion-alert-circled"></i>
                </div>
                <a href="<?php echo base_url('request') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->  
              <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                  <li><a onclick="show_graph('denied', selected_year, selected_type)" style="border-right:2px" href="#revenue-chart" data-toggle="tab">Denied</a></li>
                  <li><a onclick="show_graph('cancelled', selected_year, selected_type)" style="border-right:2px" href="#revenue-chart" data-toggle="tab">Cancelled</a></li>
                  <li><a onclick="show_graph('endorsed', selected_year, selected_type)" style="border-right:2px" href="#revenue-chart" data-toggle="tab">Endorsed</a></li>
                  <li class="active"><a onclick="show_graph('pending', selected_year, selected_type)" style="border-right:2px" href="#revenue-chart" data-toggle="tab">Pending</a></li>
                  <li class="pull-left header" style="font-size:18px"><i class="fa fa-bar-chart  "></i> Requests and Task Statistics</li>
                </ul>
                <div class="tab-content">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative;">
                    <div class="form-group">
                      Select year&nbsp;&nbsp;<select onchange="show_graph(selected_status, $(this).val(), selected_type)" name="rt_year" id="rt_year">
                        <?php for($x = date('Y'); $x >= 2013; $x--){ ?>
                          <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                        <?php } ?>
                      </select>
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      Select type&nbsp;&nbsp;<select onchange="show_graph(selected_status, selected_year, $(this).val())" name="rt_type" id="rt_type">
                        <option value="all">All</option>
                        <option value="request">Request</option>
                        <option value="task">Task</option>
                      </select>
                    </div>
                    <div id="bar-chart" style="height: 300px;"></div>
                  </div>
                </div>
              </div><!-- /.nav-tabs-custom -->

              <!-- Chat box -->
              <!-- /.box (chat box) -->

            </section><!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              <div class="box box-primary">
                <div class="box-header with-border">
                  <!-- tools box -->
                  <div class="pull-right box-tools">
                    
                    <button class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /. tools -->

                  <i class="fa fa-file-o"></i>
                  <h3 class="box-title">
                    Endorsed Request And Task For Approval
                  </h3>
                </div>
                <div class="box-body">
                    
                    Select type <select id="selectRTType">
                      <option value="request">Request</option>
                      <option value="task">Task</option>
                    </select>
                    <!--  &nbsp;&nbsp;&nbsp;Select status <select id="selectRTStatus">
                      <option value="">all</option>
                      <option value="done">done</option>
                      <option value="pending">pending</option>
                      <option value="denied">denied</option>
                      <option value="overdue">overdue</option>
                      <option value="processing">processing</option>
                    </select> -->
                    <table id="tableEndorseRT" class="table table-hover">
                      <thead class="hide">
                        <tr>
                          <td></td>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>

                </div><!-- /.box-body-->
                
              </div>

              <!-- Map box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <!-- tools box -->
                  <div class="pull-right box-tools">
                    
                    <button class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                  </div><!-- /. tools -->

                  <i class="fa fa-calendar"></i>
                  <h3 class="box-title">
                    Featured Events
                  </h3>
                </div>
                <div class="box-body">
                  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <!-- <ol class="carousel-indicators">
                      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                      <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                      <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
 -->
  <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">

                        
                      
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left text-primary" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right text-primary" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>

                </div><!-- /.box-body-->
                
              </div>
              <!-- /.box -->
            </section><!-- right col -->
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
 

 <?php include("js.php") ?>