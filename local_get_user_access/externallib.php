<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
/*require(__DIR__.'config.php');*/

/*defined('MOODLE_INTERNAL') || die();*/

class local_ws_get_user_access_external extends external_api {
  
    public static function get_user_access_per_period_parameters() {
        return new external_function_parameters(
                array('userid' => new external_value(PARAM_INT, 'Please inform the user id'),                      
					  'startdate' => new external_value(PARAM_TEXT, 'Please inform the start date dd/mm/yyyy hh:mi:ss'),
				      'finaldate' => new external_value(PARAM_TEXT, 'Please inform the final date dd/mm/yyyy'),         
				)
        );
    }

    public static function get_user_access_per_period($userid,$startdate,$finaldate) {
        global $USER;
        global $DB;
        global $x;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::get_user_access_per_period_parameters(),
                array('userid' => $userid, 'startdate' => $startdate, 'finaldate' => $finaldate)
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        $sql = "SELECT u.id, l.action, FROM_UNIXTIME(max(l.timecreated),'%d%m%Y %h:%i:%s') as last_access FROM   mdl_logstore_standard_log l, mdl_user u WHERE  u.id=l.userid and  l.eventname = '\\core\\event\\user_loggedin' " .
		"	AND FROM_UNIXTIME(l.timecreated, '%Y-%m-%d') BETWEEN STR_TO_DATE('".$startdate."','%d%m%Y %h:%i:%s') AND STR_TO_DATE('".$finaldate."','%d%m%Y %h:%i:%s') and u.id=".$userid."  group by u.username, l.action;";

        $results = $DB->get_records_sql($sql);
        return $results;
    }



 /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_access_per_period_returns() {

        return new external_multiple_structure(
                new external_single_structure(
                array(
                    'userid' => new external_value(PARAM_INT, 'id'),                    
                    'action' => new external_value(PARAM_TEXT,'action'),                    
                    'last_access' => new external_value(PARAM_TEXT,'last_access'),
                    )
        )
        );
    }


}


