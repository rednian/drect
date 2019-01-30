<style type="text/css">

@font-face {
    font-family: lane;
    src: url('../BootstrapPlugin/fonts/Lane/LANENAR_.ttf');
}
@font-face {
    font-family: ubunto-mono;
    src: url('../BootstrapPlugin/fonts/ubuntu-mono/UbuntuMono-R.ttf');
}
@font-face {
    font-family: arcon;
    src: url('../BootstrapPlugin/fonts/arcon/Arcon-Regular.otf');
}
@font-face {
    font-family: bonveno;
    src: url('../BootstrapPlugin/fonts/BonvenoCF/BonvenoCF-Light.otf');
}
@font-face {
    font-family: questrial;
    src: url('../BootstrapPlugin/fonts/questrial/Questrial-Regular.otf');
}
/*body{
    font-family: Arial !important;
}
*/
@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}

.btn-info:hover{
  background: #FFF;
  color:#333;
}

.text-left{
  text-align: left;
}
.text-right{
  text-align: right;
}
.text-center{
  text-align: center;
}
.main-header .sidebar-toggle {
  color:#333 !important;
}
.main-header .sidebar-toggle:hover  {
  background: #dfdfdf !important
}

/*custimized design*/
.no-radius{
  border-radius: 0px;
}

.content-wrapper{
  background-color: #f9f9f9;
}

table td{
  font-size:12px;
}

/* CHAT BOX */
.control-sidebar{
  background: transparent;
}

@media (max-width: 767px) {

  .chat-contacts-container{
    height:100%;
    background:#FFF;
    width:100%;
    padding-top:100px;
    overflow: auto;
  }

}
@media (min-width: 767px) {
  .chat-contacts-container{
  height:100%;
  background:#FFF;
  width:100%;
  padding-top: 50px;
  z-index: 2;
  /*overflow: auto;*/
}
}
.chat-contacts-header{
  padding:6px;
  background: #f3f3f3;
  font-size:13px;
  color:#000;
}
.chat-contacts-body{
  height:100%;
  background:#FFF;
  padding:0x 0px 0px 0px;
  overflow: auto;
}
.chat-contacts-body .contacts-list>li{
  border-bottom:1px solid #f3f3f3;
  padding:5px;
}

.chat-contacts-body .contacts-list>li:hover{
  background:#F3f3f3;
}
.contacts-list-img {
  width: 30px;
  height: 30px;
}
.chat-contacts-body .contacts-list .contacts-list-info{
  color:#000;
  font-size:12px;
}
.chat-contacts-body .contacts-list .contacts-list-info .contacts-list-msg{
  color:#999;
  font-size:11px;
}
.chat-contacts-body::-webkit-scrollbar {
    height: 5px;
    width:7px;
}
.chat-contacts-body::-webkit-scrollbar-thumb {
    background: #CCC;
    height: 100px;
    width: 100px;
    border-radius: 5px;
}

.chat-messages-container{
  height:63%;
  background:#f3f3f3;
  width:100%;
  margin-top: 72px;
}

.chat-messages-container .chat-messages-header{
  padding:6px;
  background: #313D42;
  font-size:13px;
  color:#FFF;
}

.chat-messages-body{
  height:60%;
  background:#FFF;
  padding:5px 5px 10px 5px;
  overflow: auto;
}
.chat-messages-body .direct-chat-msg .direct-chat-text{
  font-size:10px;
}

.direct-chat-timestamp{
  font-size:9px;
}

.chat-messages-body::-webkit-scrollbar {
    height: 5px;
    width:7px;
}
.chat-messages-body::-webkit-scrollbar-thumb {
    background: #CCC;
    height: 100px;
    width: 100px;
    border-radius: 5px;
}
.chat-messages-footer{
  height:20%;
  background: #f3f3f3;
  padding: 3px;
}
.chat-textarea::-webkit-scrollbar {
    height: 5px;
    width:7px;
}
.chat-textarea::-webkit-scrollbar-thumb {
    background: #CCC;
    height: 100px;
    width: 100px;
    border-radius: 5px;
}

.skin-blue::-webkit-scrollbar {
    height: 5px;
    width:7px;
}
.skin-blue::-webkit-scrollbar-thumb {
    background: #CCC;
    height: 100px;
    width: 100px;
    border-radius: 5px;
}

@media (min-width: 768px) {
  #modal-chat .modal-sm {
    width:330px;
    float:right;
  }
}

.direct-chat-text {
  padding:5px;
  font-size:12px;
}

/* index */
.login-page{
  background:#FFF;
}
.login-page .login-box .login-box-body #login-button{
  padding:10px;
}

/* document type */

.docx{
  color:#3c8dbc;
}
.ppt{
  color:#d14424;
}
.xlsx{
  color:#1f6e43;
}
.pdf{
  color:#d70a0a;
}

/* header */

.skin-blue .main-header .navbar {
  background: #f9f9f9;
}
.skin-blue .main-header .navbar li a{
  color:#333 !important;
}
.skin-blue .main-header .logo{
  background: #222D32;
}

.skin-blue .main-header .logo:hover{
  background: #1E282C;
}

/* navigation */
.sidebar-menu .treeview-menu>li>a {
  font-size: 13px;
  padding: 5px 5px 5px 10px;
}



/*flat box */
.flat{
  border:none !important;
  -webkit-box-shadow:none !important;
  box-shadow: none !important;
}

/* label */

.label-black{
  background-color: #000 !important;
}

.label-blood{
  background-color: #9a1111 !important;
}



.skin-blue .main-header li.user-header {
  background-color: #f3f3f3;
}
.skin-blue .main-header li.user-header p {
  color:#000 !important;
}


/* nav-stacked */

.nav-stacked>li.active>a, .nav-stacked>li.active>a:hover {
  border-top: 0;
  /*border-left-color: #222D32;*/
}

.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
  color: #000;
  background-color: #f3f3f3;
}

.alert.alert-success{
  background-color: rgba(51,204,102,0.8) !important;
}

.alert.alert-danger{
  background-color: rgba(255,51,51,0.8) !important;
}

.alert.alert-warning{
  background-color: rgba(255,204,51,0.8) !important;
}
.text-stroke{
  -webkit-text-stroke-width: 1px;
  -webkit-text-stroke-color: #000;
}

/* font-awesome */

.fa-1x{
  font-size:17px;
}

.modal-properties{
  width:400px;
}

table.dataTable thead th.sorting:after,
table.dataTable thead th.sorting_asc:after,
table.dataTable thead th.sorting_desc:after {
    position: absolute;
    top: 0px;
    right: 8px;
    display: block;
    font-family: FontAwesome;
}
table.dataTable thead th.sorting:after {
    content: "\f0dc";
    color: #ddd;
    font-size: 0.8em;
    padding-top: 0.12em;
}
table.dataTable thead th.sorting_asc:after {
    content: "\f0de";
}
table.dataTable thead th.sorting_desc:after {
    content: "\f0dd";
}

/* OFFICIAL EMAIL DOMAIN */

table#tbl_official_email thead tr th{
  background: #f3f3f3;
}


/* ----------------------------- EVENTS ------------------------------*/

.media .profile_thumb {
    border: 1px solid;
    width: 50px;
    height: 50px;
    margin: 5px 10px 5px 0;
    border-radius: 50%;
    padding: 9px 12px;
}

.media .profile_thumb i {
    font-size: 30px;
}

.media .date {
    background: #00C0EF;
    width: 52px;
    margin-right: 10px;
    border-radius: 10px;
    padding: 5px;
}

.media .date .month {
    margin: 0;
    text-align: center;
    color: #fff;
    font-size:16px;
}

.media .date .day {
    text-align: center;
    color: #fff;
    font-size: 27px;
    margin: 0;
    line-height: 27px;
    font-weight: bold;
}

.event .media-body a.title {
    font-weight: bold;
    font-size:15px;
}

.event .media-body p {
    margin-top: 5px;
    margin-bottom: 5px;
    font-size:12px;
    text-align: justify;
}
.event .media-body a.read_more {
    font-size:12px;
}
article.media {
    width: 100%;
}

  table thead tr th{
  background-color: #6ea0be;
  color:#FFF;
  padding:5px !important;
}

.paginate_button a{
  border:none !important;
  /*background: none !important;*/
}

.toolbar {
    float: left;
   padding-right: 20px;
}

.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
   /* border-top: 1px solid #f4f4f4;*/
    border-top: none;
}

table tbody tr td:first-child{
  width:1%
}
</style>