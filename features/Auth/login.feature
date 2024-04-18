Feature: Login user
  In order to authenticate a user
  As an anonymous user
  I should be able to login

  Scenario: Load the login page
    Given I am an anonymous user
    When I request "/login" with method "GET"
    Then The response status code should be "200"
    And I should see an "input" with attributes:
    | attr | value     |
    | name | _username |
    | type | text      |
    And I should see an "input" with attributes:
    | attr | value     |
    | name | _password |
    | type | password  |

  Scenario: Submit the login form
    Given I am an anonymous user
    When I request "/login" with method "POST" and Content-Type "application/x-www-form-urlencoded" and body:
    """
    {
      "_username": "j.doe@gmail.com",
      "_password": "secret"
    }
    """
    Then The response status code should be "302"
