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
 * Pdfjsfolder restore task that provides all the settings and steps to
 * perform one complete restore of the activity.
 *
 * @package    mod_pdfjsfolder
 * @copyright  2013 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
             '/mod/pdfjsfolder/backup/moodle2/restore_pdfjsfolder_stepslib.php');

/**
 * Pdfjsfolder restore class
 *
 * @package    mod_pdfjsfolder
 * @copyright  2013 Jonas Nockert <jonasnockert@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_pdfjsfolder_activity_task extends restore_activity_task {
    /**
     * Define (add) particular settings this activity can have.
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define (add) particular steps this activity can have.
     */
    protected function define_my_steps() {
        $this->add_step(
            new restore_pdfjsfolder_activity_structure_step(
                'pdfjsfolder_structure',
                'pdfjsfolder.xml'));
    }

    /**
     * Define the contents in the activity that must be processed
     * by the link decoder.
     *
     * @return array
     */
    public static function define_decode_contents() {
        $contents = [];
        $contents[] = new restore_decode_content('pdfjsfolder',
                                                 ['intro'],
                                                 'pdfjsfolder');
        return $contents;
    }

    /**
     * Define the decoding rules for links belonging to the activity
     * to be executed by the link decoder.
     *
     * @return array
     */
    public static function define_decode_rules() {
        $rules = [];
        $rules[] = new restore_decode_rule('PDFJSFOLDERVIEWBYID',
                                           '/mod/pdfjsfolder/view.php?id=$1',
                                           'course_module');
        $rules[] = new restore_decode_rule('PDFJSFOLDERINDEX',
                                           '/mod/pdfjsfolder/index.php?id=$1',
                                           'course');
        return $rules;
    }

    /**
     * Define the restore log rules that will be applied by the restore_logs_processor when restoring pdfjsfolder logs.
     * {@link https://github.com/moodle/moodle/blob/main/backup/util/helper/restore_logs_processor.class.php}
     *
     * It must return one array of restore_log_rule objects.
     *
     * @return array
     */
    public static function define_restore_log_rules() {
        $rules = [];
        $rules[] = new restore_log_rule('pdfjsfolder',
                                        'add',
                                        'view.php?id={course_module}',
                                        '{pdfjsfolder}');
        $rules[] = new restore_log_rule('pdfjsfolder',
                                        'update',
                                        'view.php?id={course_module}',
                                        '{pdfjsfolder}');
        $rules[] = new restore_log_rule('pdfjsfolder',
                                        'view',
                                        'view.php?id={course_module}',
                                        '{pdfjsfolder}');
        return $rules;
    }

    /**
     * Define the restore log rules that will be applied by the when restoring course logs.
     * {@link https://github.com/moodle/moodle/blob/main/backup/util/helper/restore_logs_processor.class.php}
     * It must return one array of restore_log_rule objects.
     *
     * Note this rules are applied when restoring course logs by the
     * restore final task, but are defined here at activity level.
     * All them are rules not linked to any module instance (cmid = 0).
     *
     * @return array
     */
    public static function define_restore_log_rules_for_course() {
        $rules = [];
        $rules[] = new restore_log_rule('pdfjsfolder',
                                        'view all',
                                        'index.php?id={course}',
                                        null);
        return $rules;
    }
}
