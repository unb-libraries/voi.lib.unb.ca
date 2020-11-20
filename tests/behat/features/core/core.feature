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
    And I should not see a "a.toolbar-icon-voi-admin-admin" element

  Scenario: Logged in page editors can add only pages
    Given I am logged in as a user with the "page_editor" role
    When I visit "/en/node/add"
    Then I should see "Basic page"
    And I should not see "Document"

  Scenario: Logged in record editors can add only documents
    Given I am logged in as a user with the "record_editor" role
    When I visit "/en/node/add"
    Then I should not see "Basic page"
    And I should see "Document"

  Scenario: Anonymous contributors cannot add data
    When I visit "/"
    Then I should not see a "a.toolbar-icon-voi-admin-admin" element

  Scenario: Anonymous contributors cannot add data 2
    When I go to "/node/add"
    Then I should see "ACCESS DENIED"

  Scenario: Site should support English and French
    When I visit "/en/what-is-text-analysis"
    Then I should see "WHAT IS COMPUTER-ASSISTED TEXTUAL ANALYSIS?"
    And I should see "Français"
    When I click "Français"
    Then I should see "QU'EST-CE QUE L'ANALYSE DE DONNÉES TEXTUELLES?"

  Scenario: Database available and download links visible
    When I visit "/en/search"
    Then I should see a "div.views-row" element
    And I should see "Add to data download"
