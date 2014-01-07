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
 * @package   mod_webexactvity
 * @copyright Eric Merrill (merrill@oakland.edu)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die;

function xmldb_webexactivity_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Moodle v2.6.0 release upgrade line.
    // Put any upgrade step following this.
    if ($oldversion < 2013111100) {
        // Define table webexactivity_users to be created.
        $table = new xmldb_table('webexactivity_users');

        // Adding fields to table webexactivity_users.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('moodleuserid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('username', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('password', XMLDB_TYPE_CHAR, '255', null, null, null, null);

        // Adding keys to table webexactivity_users.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for webexactivity_users.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define field meetingkey to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('meetingkey', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'introformat');

        // Conditionally launch add field meetingkey.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hostlink to be added to webexactivity.
        $field = new xmldb_field('hostlink', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'meetingkey');

        // Conditionally launch add field hostlink.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field attendeelink to be added to webexactivity.
        $field = new xmldb_field('attendeelink', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'hostlink');

        // Conditionally launch add field attendeelink.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field starttime to be added to webexactivity.
        $field = new xmldb_field('starttime', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'attendeelink');

        // Conditionally launch add field starttime.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field length to be added to webexactivity.
        $field = new xmldb_field('length', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'starttime');

        // Conditionally launch add field length.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2013111100, 'webexactivity');
    }

    if ($oldversion < 2013111200) {

        // Define field type to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('type', XMLDB_TYPE_INTEGER, '2', null, null, null, null, 'introformat');

        // Conditionally launch add field type.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2013111200, 'webexactivity');
    }

    if ($oldversion < 2013111202) {
        // Define field webexid to be added to webexactivity_users.
        $table = new xmldb_table('webexactivity_users');
        $field = new xmldb_field('webexid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'moodleuserid');

        // Conditionally launch add field webexid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2013111202, 'webexactivity');
    }

    if ($oldversion < 2013111203) {

        // Rename field webexid on table webexactivity_users to NEWNAMEGOESHERE.
        $table = new xmldb_table('webexactivity_users');
        $field = new xmldb_field('webexid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'username');

        // Launch rename field webexid.
        $dbman->rename_field($table, $field, 'webexuserid');

        // Rename field webexuserid on table webexactivity_users to NEWNAMEGOESHERE.
        $field = new xmldb_field('username', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'password');

        // Launch rename field webexuserid.
        $dbman->rename_field($table, $field, 'webexid');

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2013111203, 'webexactivity');
    }

    if ($oldversion < 2013121700) {
        // Define field guesttoken to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('guesttoken', XMLDB_TYPE_CHAR, '128', null, null, null, null, 'meetingkey');

        // Conditionally launch add field guesttoken.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hosts to be added to webexactivity.
        $field = new xmldb_field('hosts', XMLDB_TYPE_TEXT, null, null, null, null, null, 'length');

        // Conditionally launch add field hosts.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field xml to be added to webexactivity.
        $field = new xmldb_field('xml', XMLDB_TYPE_TEXT, null, null, null, null, null, 'hosts');

        // Conditionally launch add field xml.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field hostlink to be dropped from webexactivity.
        $field = new xmldb_field('hostlink');

        // Conditionally launch drop field hostlink.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field attendeelink to be dropped from webexactivity.
        $field = new xmldb_field('attendeelink');

        // Conditionally launch drop field attendeelink.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2013121700, 'webexactivity');
    }

    if ($oldversion < 2014010601) {

        // Define table webexactivity_recording to be created.
        $table = new xmldb_table('webexactivity_recording');

        // Adding fields to table webexactivity_recording.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('webexid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('meetingkey', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('recordingid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('hostid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('streamurl', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('fileurl', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('duration', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table webexactivity_recording.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for webexactivity_recording.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014010601, 'webexactivity');
    }

    if ($oldversion < 2014010602) {

        // Define field id to be added to webexactivity_recording.
        $table = new xmldb_table('webexactivity_recording');
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014010602, 'webexactivity');
    }

    if ($oldversion < 2014010603) {

        // Rename field duration on table webexactivity to NEWNAMEGOESHERE.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('length', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'starttime');

        // Launch rename field duration.
        $dbman->rename_field($table, $field, 'duration');

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014010603, 'webexactivity');
    }

    if ($oldversion < 2014010605) {

        // Define field id to be added to webexactivity.
        $table = new xmldb_table('webexactivity');
        $field = new xmldb_field('laststatuscheck', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Webex Activity savepoint reached.
        upgrade_mod_savepoint(true, 2014010605, 'webexactivity');
    }

    return true;
}


