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
    And "page" content:
      | title   | body          | path      |
      | Welcome | Hello world!  | /hello    |
    When I am logged in as "Test user"
    And I visit "/hello"
    Then I should see "Hello world!"
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
    Given "page" content:
      | title   | body          | path      |
      | Welcome | Hello world!  | /hello    |
    When I visit "/hello"
    Then I should not see a "a.toolbar-icon-voi-admin-admin" element

  Scenario: Anonymous contributors cannot edit data
    Given "page" content:
      | title   | body          | path      |
      | Welcome | Hello world!  | /hello    |
    When I visit "/hello"
    Then I should not see a "nav.tabs-primary" element

    Scenario: Anonymous contributors cannot add data 2
      When I go to "/node/add"
      Then I should see "ACCESS DENIED"

  Scenario: Database available and download links visible
    Given "document" content:
      | title         | field_article_title | field_artice_contents |
      | Hello  world! | Hello               | Hello world!          |
    When I re-index "documents_voi_lib_unb_ca" and wait 10
    When I visit "/en/search?search_api_fulltext=world%21"
    Then I should see a "div.views-row" element
    And I should see "Add to data download"
