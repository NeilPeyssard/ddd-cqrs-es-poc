Feature: Create a project
  In order to create a project
  I should be able to call a dedicated API endpoint to create a project

  Scenario: Try to create a project without any access token
    When I request "/api/project" with method "POST" and Content-Type "application/json" and body:
    """
    {
        "name": "My awesome project"
    }
    """
    Then The response status code should be "401"

  Scenario: Try to create a project with an expired access token
    Given I have an access token "018ecbca-387c-72a6-bc00-f2151671d083"
    When I request "/api/project" with method "POST" and Content-Type "application/json" and body:
    """
    {
        "name": "My awesome project"
    }
    """
    Then The response status code should be "401"

  Scenario: Create a project
    Given I have an access token "018eb331-5352-7b23-8790-65d5669e988a"
    When I request "/api/project" with method "POST" and Content-Type "application/json" and body:
    """
    {
        "name": "My awesome project"
    }
    """
    Then The response status code should be "201"
    And The JSON node "id" should not be null
