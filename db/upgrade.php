<?php
// This file is part of Moodle - http://moodle.org/
//
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
 * An activity to interface with WebEx.
 *
 * @package    mod_webexactvity
 * @author     Eric Merrill <merrill@oakland.edu>
 * @copyright  2014 Oakland University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Runs the upgrade between versions.
 *
 * @param int      $oldversion Version we are starting from.
 * @return bool    True on success, false on failure.
 */
function xmldb_webexactivity_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Moodle v2.6.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2014013002) {
        echo "mod_webexactivity must be upgraded to at version 2014013002 before continuing.";
        return false;
    }

    if ($oldversion < 2014020401) {

        // Set the introformat to 1 (html).
        $DB->execute('UPDATE {webexactivity} SET introformat = 1');

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014020401, 'webexactivity');
    }

    if ($oldversion < 2014020403) {

        // Define field timemodified to be added to webexactivity_recording.
        $table = new xmldb_table('webexactivity_recording');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'visible');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014020403, 'webexactivity');
    }

    if ($oldversion < 2014020404) {

         // Define field deleted to be added to webexactivity_recording.
        $table = new xmldb_table('webexactivity_recording');
        $field = new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'visible');

        // Conditionally launch add field deleted.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014020404, 'webexactivity');
    }

    if ($oldversion < 2014021300) {

        // Define field endtime to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('endtime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'starttime');

        // Conditionally launch add field endtime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014021300, 'webexactivity');
    }

    if ($oldversion < 2014021301) {

        // Define field guestuserid to be dropped from webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('guestuserid');

        // Conditionally launch drop field guestuserid.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014021301, 'webexactivity');
    }

    if ($oldversion < 2014021302) {

        // Define field guestuserid to be dropped from webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('xml');

        // Conditionally launch drop field guestuserid.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014021302, 'webexactivity');
    }

    if ($oldversion < 2014021400) {

        // Define field duration to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('duration', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'endtime');

        // Conditionally launch add field duration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);

            $meetings = $DB->get_recordset('webexactivity');

            $update = new \stdClass();
            foreach ($meetings as $meeting) {
                if (!isset($meeting->duration)) {
                    $update->id = $meeting->id;
                    $update->duration = (($meeting->endtime - $meeting->starttime) / 60);
                    $update->endtime = null;

                    $DB->update_record('webexactivity', $update);
                }
            }

            $meetings->close();
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014021400, 'webexactivity');
    }

    if ($oldversion < 2014021800) {

        // Define field timemodified to be added to webexactivity_user.
        $table = new xmldb_table('webexactivity_user');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'password');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014021800, 'webexactivity');
    }

    if ($oldversion < 2014022400) {

        // Define field firstname to be added to webexactivity_user.
        $table = new xmldb_table('webexactivity_user');
        $field = new xmldb_field('firstname', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'password');

        // Conditionally launch add field firstname.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field lastname to be added to webexactivity_user.
        $field = new xmldb_field('lastname', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'firstname');

        // Conditionally launch add field lastname.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field email to be added to webexactivity_user.
        $field = new xmldb_field('email', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'lastname');

        // Conditionally launch add field email.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014022400, 'webexactivity');
    }

    if ($oldversion < 2014022402) {

        // Define field creatorwebexid to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('creatorwebexid', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'creatorwebexuser');

        // Conditionally launch add field creatorwebexid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $meetings = $DB->get_recordset('webexactivity');

        $update = new \stdClass();
        foreach ($meetings as $meeting) {
            $user = $DB->get_record('webexactivity_user', array('id' => $meeting->creatorwebexuser));
            $update->id = $meeting->id;
            if ($user) {
                $update->creatorwebexid = $user->webexid;
                $DB->update_record('webexactivity', $update);
            }
        }

        $meetings->close();

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014022402, 'webexactivity');
    }

    if ($oldversion < 2014022403) {

        // Define field creatorwebexuser to be dropped from webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('creatorwebexuser');

        // Conditionally launch drop field creatorwebexuser.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014022403, 'webexactivity');
    }

    if ($oldversion < 2014022500) {

        $webexusers = $DB->get_recordset('webexactivity_user');

        $update = new \stdClass();
        foreach ($webexusers as $webexuser) {
            $user = $DB->get_record('user', array('id' => $webexuser->moodleuserid));
            $update->id = $webexuser->id;
            if ($user) {
                $update->firstname = $user->firstname;
                $update->lastname = $user->lastname;
                $update->email = $user->email;
                $DB->update_record('webexactivity_user', $update);
            }
        }

        $webexusers->close();

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014022500, 'webexactivity');
    }

    return true;
}



