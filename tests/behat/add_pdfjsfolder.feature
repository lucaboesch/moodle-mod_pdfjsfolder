@mod @mod_pdfjsfolder @_file_upload
Feature: Add a pdfjsfolder activity
  In order to let the users use the pdfjsfolder in a course
  As a teacher
  I need to add a pdfjsfolder to a moodle course

  @javascript
  Scenario: Add a pdfjsfolder to a course
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a pdfjsfolder activity to course "Course 1" section "2" and I fill the form with:
      | Name        | Test PDF.js folder                            |
      | Description | Test PDF.js folder description                |
      | PDFs        | mod/pdfjsfolder/tests/fixtures/submission.pdf |
    And I upload "mod/pdfjsfolder/tests/fixtures/submission.pdf" file to "PDFs" filemanager
    And I click on "Save and return to course" "button"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Test PDF.js folder"
    Then I should see "Test PDF.js folder"
    And I should see "submission.pdf"
