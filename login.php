<?php
/**
 * @author Tim St.Clair - tim.stclair@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package auth/opensesame
 * @version 1.0
 *
 * Logs on the user specified by id (precreated by webservice) and opens the first course found (expecting only one) at its first activity
 *
 * 2016-03-10  Created
**/

global $CFG, $USER, $SESSION, $DB;

require('../../config.php');
require_once($CFG->dirroot . '/auth/opensesame/classes/encryption.php');

$ENCRYPTION_KEY = get_config('auth/opensesame', 'encryptionkey');
$HMAC_SALT = get_config('auth/opensesame', 'hmacsalt');

if (!isset($ENCRYPTION_KEY) || !isset($HMAC_SALT)) {
	throw new Exception("OpenSesame: Sorry, this plugin has not yet been configured. Please contact the Moodle administrator for details.");
}

if (!isset($_GET['data'])) { // probably should use required_param() ...
	throw new Exception("OpenSesame: Sorry, the correct parameter was not supplied.");
}

$encdata = hex2bin($_GET['data']);
$enclib = new OpenSesame_Encryption($ENCRYPTION_KEY, $HMAC_SALT);
$rawdata = $enclib->decrypt($encdata);

if (false === $rawdata) {
	throw new Exception("OpenSesame: Sorry, the parameter format was not correct.");
}
parse_str($rawdata);

/* now we have access to */
// $activity
// $homeurl
// $auth_id

if (!isset($activity)) { $activity = 1; }
if (!isset($homeurl)) { $homeurl = ""; } else { $SESSION->coursehome = $homeurl; }
if (!isset($auth_id)) { throw new Exception("OpenSesame: Fatal error, cannot continue."); }

if ($DB->record_exists('user', array('id'=>$auth_id))) { // update manually created user that has the same username but doesn't yet have the right idnumber
	$user = get_complete_user_data('id', $auth_id);

	$SESSION->wantsurl = $CFG->wwwroot.'/';
	$course = $DB->get_records_sql("
			select
			e.courseid from {enrol} e inner join {user_enrolments} u
			on e.id = u.enrolid
			where e.enrol = 'manual'
			and e.status = 0
			and u.userid = ?
			order by e.timecreated desc
		", array($auth_id), 0, 1);

	if (!empty($course)) {
		$course = array_pop($course);
		$courseId = $course->courseid;

		// ensure completion record records timestarted
		require_once($CFG->dirroot.'/completion/completion_completion.php');
		$cc = array(
		    'course'    => $courseId,
		    'userid'    => $auth_id
		);
		$ccompletion = new completion_completion($cc);
		$ccompletion->mark_inprogress(); // no param should set it to time()

		if ($courseId > 0) {
			$SESSION->wantsurl = new moodle_url('/course/view.php', array('id'=>$courseId));
		}
		if ($activity > 0) {
			$mod = $DB->get_records_sql('select cm.id, m.name from {course_sections} cs
					inner join {course_modules} cm on cs.course = cm.course
					inner join {modules} m on cm.module = m.id
					where cs.course = ? and cs.visible = 1 and cm.visible = 1 order by cs.sequence', array($courseId), $activity - 1, 1); // and cs.section > 0
			if (!empty($mod)) {
				$mod = array_pop($mod);
				$SESSION->wantsurl = new moodle_url("/mod/$mod->name/view.php", array("id" => $mod->id));
			}
		}
	}
	$authplugin = get_auth_plugin('opensesame');
	if ($authplugin->user_login($user->username, $user->password)) {
		$user->loggedin = true;
		$user->site     = $CFG->wwwroot;
		complete_user_login($user); // now performs \core\event\user_loggedin event
	}

	redirect($SESSION->wantsurl);
} else {
	throw new Exception("OpenSesame: Sorry, the appropriate record was not found.");
}
