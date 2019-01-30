     </div><!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <img src="<?php echo base_url('assets/images/engtech1.png') ?>">
        </div>
        <strong>Copyright &copy; <?php echo date('Y') ?> <a href="#">ACLC College of Butuan</a>.</strong> All rights reserved.
      </footer>

      <style type="text/css">
         
        #tab_chat.nav.nav-tabs li.active a{
          border: none;
          background:#CCC;
          color:#000;
        }
        #tab_chat.nav.nav-tabs li a{
          border: none;
        }

        #chat_content{
          padding:0px;
        }

       .contact_item{
          padding:5px;
          cursor: pointer;
          border-bottom:1px solid #f3f3f3;
        }
       .contact_item:hover{
          background:#f3f3f3;
        }
        .contact_item img.contact_img{
          width: 40px;height:40px;
        }
        .contact_item div.contact_info{
          padding:0px 3px 0px 5px
        }
        .contact_name{
          font-weight:bold;
          color:#000;
        }
        .contact_position{
          font-size:13px;
          color:#333;
        }
        .has-msg{
          background: #000!important;
        }

      </style>
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark" style="background:#fff">
        <!-- Create the tabs -->
        <ul id="tab_chat" class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li class="active"><a href="#chat_contacts_pane" data-toggle="tab"><i class="fa fa-user"></i> Contacts</a></li>
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-search"></i> Search</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" id="chat_content">
          <!-- Home tab content -->
          <div class="tab-pane active no-padding" id="chat_contacts_pane">

            <!-- Contacts item -->
           <!-- CONTACTS HERE -->

          </div><!-- /.tab-pane -->
         
          <!-- Settings tab content -->
          <div class="tab-pane" id="control-sidebar-settings-tab">
            <!-- SEARCH -->
            <div style="padding:3px">
              <div class="form-group" style="margin-bottom:0px">
                <div class="input-group input-group-sm">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input onkeyup="search_contact($(this).val())" type="text" class="form-control" placeholder="Search contact here">
                </div>
              </div>
            </div>
            <!-- !SEARCH -->
            <!-- SEARCH RESULTS -->
            <div id="search_results"></div>
            <!-- !SEARCH RESULTS -->
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg" style="background:#FFF"></div>
    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <!-- MODAL CHAT BOX -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm pull-right">
        <div class="modal-content">
          <div class="modal-header clearfix">
            <button onclick="chatbox_open=false;scrolled=false" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <img src="" style="width:50px;height:50px;" class="img img-circle img-thumbnail pull-left">
            <div class="pull-left" style="padding-left:10px">
              <b style="font-size:15px">Chris Olivo</b><br>
              <span class="header_position">Programmer</span>
            </div>
          </div>
          <div class="modal-body no-padding direct-chat direct-chat-primary" id="modal_body_chat">
            
            <div id="convo_container" class="direct-chat-messages" style="height:450px;"></div>

          </div>
          <div class="modal-footer" style="padding:10px">
            <form id="form_chat" method="post" action="<?php echo base_url('chats/send_message') ?>">
              <div class="form-group" style="margin-bottom:0px">
                <div class="input-group">
                  <input type="hidden" name="chat_to">
                  <input autocomplete="off" id="txtchatcontent" name="chat_content" type="text" class="form-control" placeholder="Message here . . .">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-send"></i></button>
                  </span>
                </div>
              </div>
            </form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- !MODAL CHAT BOX -->

    <!-- CHATS JAVASCRIPT -->
    <script type="text/javascript">

      var chatbox_open = false;
      var refreshChat;
      var convo_id;

      load_contacts();
      var refreshChatContacts = setInterval(load_contacts, 5000);

      function get_last_message(id){
        $.ajax({
          url: "<?php echo base_url('chats/check_last_message') ?>",
          data: {pi_id : id},
          type: "GET",
          dataType: "html",
          success: function(data){
            if(data != 0){
              $("#chat_modal .direct-chat-messages").append(data);
            }
            $(".direct-chat-msg .direct-chat-img").on("error", function(){
                $( this ).attr( "src", "<?php echo base_url('images/profile_image/default.png')?>" );
            });

          },
          error: function(){

          }
        });
      }

      function load_contacts(){
        clearInterval(refreshChatContacts);
        $.ajax({
          url: "<?php echo base_url('chats/load_contacts') ?>",
          dataType: "html",
          success: function(data){
            $("#chat_contacts_pane").html(data);
            
            refreshChatContacts = setInterval(load_contacts, 5000);
          },
          error: function(data){
            refreshChatContacts = setInterval(load_contacts, 5000);
          }
        });
      }

      function show_conversation(pi_id){
         $("#convo_container").LoadingOverlay("show", {
                              image       : "<?php echo base_url('assets/images/sending.gif') ?>",
                              fontawesome : ""
                          });
        $.ajax({
          url: "<?php echo base_url('chats/get_convo') ?>",
          data: {pi_id : pi_id},
          type: "GET",
          dataType: "html",
          success: function(data){
            $(".chatUserCount").html("");
            chatCounter();
            $("#chat_modal .direct-chat-messages").html(data);
          },
          error: function(){

          }
        });
      }

      function get_contact_info(pi_id){

        $.ajax({
          url: "<?php echo base_url('chats/get_contact_info') ?>",
          data: {pi_id : pi_id},
          type: "GET",
          dataType: "json",
          success: function(data){

            chatbox_open = true;

            $("#chat_modal .modal-header img").attr("src", data['img_path']);
            $("#chat_modal .modal-header b").html(data['name']);
            $("#chat_modal .modal-header span.header_position").html(data['position']);

            $("#chat_modal .modal-header img").on("error", function(){
                $( this ).attr( "src", "<?php echo base_url('images/profile_image/default.png')?>" );
            });

            $("#chat_modal .modal-footer input[name=chat_to]").val(pi_id);
            show_conversation(data['pi_id']);
            convo_id = data['pi_id'];

            $("#chat_modal").modal("show");

            $(".direct-chat-msg .direct-chat-img").on("error", function(){
                $( this ).attr( "src", "<?php echo base_url('images/profile_image/default.png')?>" );
            });



          },
          error: function(){

          }
        });
      }

      $(document).ready(function(){

        // var message = $("#txtchatcontent").val();
        // // message = message.trim();

        $("#form_chat").on("submit", function(e){
          e.preventDefault();

          // if(message.length > 0){

            $.ajax({
              url: "<?php echo base_url('chats/send_message') ?>",
              data: $(this).serialize(),
              type: "POST",
              dataType: "json",
              success: function(){
                $("input[name=chat_content]").val("");
              },
              error: function(){

              }
            });
          // }

        });
      }); 

      // CHECK MESSAGE EVERY SEC 
      // if(chatbox_open == true){
      refreshChat = setInterval(test, 1000);
      // }
      function test(){
        if(chatbox_open == true){
          get_last_message(convo_id);
          updateScroll();
        }
      }

      var scrolled = false;

      function updateScroll(){
          if(!scrolled){
              var element = document.getElementById("convo_container");
              element.scrollTop = element.scrollHeight;
          }
      }

      $("#convo_container").on('scroll', function(){
          scrolled=true;
      });


      function search_contact(key){
        $.ajax({
          url: "<?php echo base_url('chats/search_contact') ?>",
          data: {search : key},
          type: "GET",
          dataType: "html",
          success: function(data){

            $("#search_results").html(data);

            $(".contact_img").on("error", function(){
                $( this ).attr( "src", "<?php echo base_url('images/profile_image/default.png')?>" );
            });
          },
          error: function(data){

          }
        });
      }


</script>