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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwstemplate
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
        'local_ws_get_user_access_per_period' => array(
                'classname'   => 'local_ws_get_user_access_external',
                'methodname'  => 'get_user_access_per_period',
                'classpath'   => 'local/get_user_access/externallib.php',
                'description' => 'Return access from user',
                'type'        => 'read',
		'services' => array('Acesss_Results')
        ),
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'Acesss_Results' => array(
                'functions' => array ('local_ws_get_user_access_per_period'),
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);

