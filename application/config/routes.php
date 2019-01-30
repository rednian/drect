<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = "login";
$route['404_override'] =  "error/error_404";

// custom routes //
$route['task/endorsement/(:num)'] = 'request_task_approval/view/$1';

// mail routes //
$route['mail/personal']        = 'mail';
$route['mail/personal/(:any)'] = 'mail/view/$1';

$route['mail/circulation']        = 'mail_circulate';
$route['mail/circulation/(:any)'] = 'mail_circulate/view/$1';

$route['mail/dispatch']        = 'mail_dispatch';
$route['mail/dispatch/(:any)'] = 'mail_dispatch/view/$1';

// approval
$route['approval/calendar']        = 'calendar_notification';
$route['approval/calendar/(:any)'] = 'calendar_notification/view_event/$1';

$route['approval/procedure']        = 'pwi_notification';
$route['approval/procedure/(:any)'] = 'pwi_notification/view_procedure/$1';

$route['approval/account'] = 'account_approval_notification';

// feedback
$route['feedback/account'] = 'account_approval_feedback';

// calendar
$route['calendar/institution']                     = 'institution_calendar';
$route['calendar/institution/create']              = 'institution_calendar/create_calendar';
$route['calendar/institution/event/create/(:any)'] = 'institution_calendar/create_event/$1';
$route['calendar/institution/event/(:any)/edit']   = 'institution_calendar/edit_event/$1';
$route['calendar/institution/event/(:any)']        = 'institution_calendar/view_event/$1';

$route['calendar/personal']                     = 'personal_calendar';
$route['calendar/personal/create']              = 'personal_calendar/create_calendar';
$route['calendar/personal/event/(:any)']        = 'personal_calendar/view_event/$1';
$route['calendar/personal/event/create/(:any)'] = 'personal_calendar/create_event/$1';
$route['calendar/personal/event/(:any)/edit']   = 'personal_calendar/edit_event/$1';

// archives
$route['archive/procedure'] = 'pwi_repository';
$route['archive/deleted']   = 'archive_files';

// security
$route['security/account']             = 'user_account';
$route['security/account/create']      = 'user_account/create';
$route['security/account/(:any)/edit'] = 'user_account/edit/$1';

$route['security/procedure'] = 'procedure';
$route['security/email']     = 'add_email_domain';

// settings
$route['settings/account']      = 'settings';
$route['settings/organization'] = 'org_settings';

// reports
$route['report/rt/endorsed'] = 'rt_status_endorsed';
$route['report/rt/overdue']  = 'rt_status_overdue';
$route['report/rt/pending']  = 'rt_status_pending';
$route['report/rt/deny']     = 'rt_status_deny';
$route['report/log_history'] = 'loghistory';

$route['policy'] = 'PolicyController';
$route['policy/new'] = 'PolicyController/create';
$route['policy/(:num)/edit'] = 'PolicyController/editPolicy/$1';

$route['rt_statistics'] = 'rt_status_endorsed';
/* End of file routes.php */
/* Location: ./application/config/routes.php */