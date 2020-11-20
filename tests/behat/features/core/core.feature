@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
  Scenario: Visit as an anonymous user
    When I visit "/user"
    Then I should see "UNB/STU patrons must sign in using Secure Services Login"

  Scenario: Log in as a user created during this scenario
    Given users:
      | name      | status |
      | Test user |      1 |
    When I am logged in as "Test user"
    And I visit "/en/publications-and-conferences"
    Then I should see "Discours fondateurs en Acadie"
    And I should not see a ".primary-tabs-title" element

  Scenario: Logged in page editors can add data
    Given I am logged in as a user with the "page_editor" role
    When I visit "/en/node/add"
    Then I should see "Basic page"
    And I should not see "Document"

  Scenario: Logged in record editors can add documents
    Given I am logged in as a user with the "record_editor" role
    When I visit "/en/node/add"
    Then I should not see "Basic page"
    And I should see "Document"

  Scenario: Anonymous contributors cannot add data
    When I visit "/"
    Then I should not see a "a.toolbar-icon-voi-admin-admin" element
