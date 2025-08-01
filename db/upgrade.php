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
 * This file keeps track of upgrades to the pdfjsfolder module.
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod_pdfjsfolder
 * @copyright  2013 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute pdfjsfolder upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_pdfjsfolder_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2025061206) {

        // Define field autoimport to be added to publication.
        $table = new xmldb_table('pdfjsfolder');

        // Conditionally launch add field showfilechangeswarning.
        $field = new xmldb_field('showfilechangeswarning');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'showfilechangeswarning');
        if (!$dbman->field_exists($table, 'showfilechangeswarning')) {
            $dbman->add_field($table, $field);
        }

        // Assign savepoint reached.
        upgrade_mod_savepoint(true, 2025061206, 'pdfjsfolder');
    }

    // Final return of upgrade result (true, all went good) to Moodle.
    return true;
}
