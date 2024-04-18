Feature: Read a project
  In order to read a project
  I should be able to call a dedicated API endpoint to read a project

  Scenario: Try to read a project without any access token
    When I request "/api/project/01HTHWYWQN6ZQ0S9Z0A3XAWYS2" with method "GET" and Content-Type "application/json"
    Then The response status code should be "401"

  Scenario: Try to read a project with an expired access token
    Given I have an access token "018ecbca-387c-72a6-bc00-f2151671d083"
    When I request "/api/project/01HTHWYWQN6ZQ0S9Z0A3XAWYS2" with method "GET" and Content-Type "application/json"
    Then The response status code should be "401"

  Scenario: Read a project
    Given I have an access token "018eb331-5352-7b23-8790-65d5669e988a"
    When I request "/api/project/01HTHWYWQN6ZQ0S9Z0A3XAWYS2" with method "GET" and Content-Type "application/json"
    Then The response status code should be "200"
    And The JSON node "id" should not be null
    And The JSON node "name" should not be null
    And The JSON node "name" should be equal to "Test project"
