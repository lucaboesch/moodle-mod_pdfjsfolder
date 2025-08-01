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
 * Define all the restore steps that will be used by the restore_pdfjsfolder_activity_task.
 *
 * @package    mod_pdfjsfolder
 * @copyright  2013 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one pdfjsfolder activity.
 */
class restore_pdfjsfolder_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines the backup structure.
     *
     * @return array
     */
    protected function define_structure() {
        $paths = [];
        $paths[] = new restore_path_element('pdfjsfolder',
                                            '/activity/pdfjsfolder');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Restore pdfjsfolder.
     *
     * @param  stdClass $data
     */
    protected function process_pdfjsfolder(stdClass $data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the pdfjsfolder record.
        $newitemid = $DB->insert_record('pdfjsfolder', $data);

        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Adds related files after restore.
     */
    protected function after_execute() {
        // Add related files, no need to match by itemname (just
        // internally handled context).
        $this->add_related_files('mod_pdfjsfolder', 'intro', null);
        $this->add_related_files('mod_pdfjsfolder', 'pdfs', null);
    }
}
