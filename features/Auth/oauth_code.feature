Feature: Create an authorization code
  In order to allow a client to access my data
  As an authenticated user only
  I should be able to authorize the client to access my data, and create an authorization code

  Scenario: Try to load the authorization page as an anonymous user
    Given I am an anonymous user
    When I request "/authorize?publicKey=018eb331-5352-7b23-8790-65d5669e989b&redirectUrl=https://foobar.com/authorize&grantType=auth_code" with method "GET"
    Then The response status code should be "302"
    And I should be redirect to uri "/login"

  Scenario: Load the authorization page with a wrong client ID
    Given I am an authenticated user with id "01HTHWYWQN6ZQ0S9Z0A3XAWYV4"
    When I request "/authorize?publicKey=some-wrong-client-id&redirectUrl=https://foobar.com/authorize&grantType=auth_code" with method "GET"
    Then The response status code should be "404"

  Scenario: Load the authorization page with a non existing redirect URL
    Given I am an authenticated user with id "01HTHWYWQN6ZQ0S9Z0A3XAWYV4"
    When I request "/authorize?publicKey=018eb331-5352-7b23-8790-65d5669e989b&redirectUrl=https://bad-domain.com/authorize&grantType=auth_code" with method "GET"
    Then The response status code should be "400"

  Scenario: Load the authorization page with a non authorized grant type
    Given I am an authenticated user with id "01HTHWYWQN6ZQ0S9Z0A3XAWYV4"
    When I request "/authorize?publicKey=018eb331-5352-7b23-8790-65d5669e989b&redirectUrl=https://foobar.com/authorize&grantType=client_credentials" with method "GET"
    Then The response status code should be "400"

  Scenario: Load the authorization page
    Given I am an authenticated user with id "01HTHWYWQN6ZQ0S9Z0A3XAWYV4"
    When I request "/authorize?publicKey=018eb331-5352-7b23-8790-65d5669e989b&redirectUrl=https://foobar.com/authorize&grantType=auth_code" with method "GET"
    Then The response status code should be "200"
    And I should see a "button" with label "Authorize client"

  Scenario: Create an authorization code
    Given I am an authenticated user with id "01HTHWYWQN6ZQ0S9Z0A3XAWYV4"
    When I request "/authorize?publicKey=018eb331-5352-7b23-8790-65d5669e989b&redirectUrl=https://foobar.com/authorize&grantType=auth_code" with method "POST" and Content-Type "application/x-www-form-urlencoded" and body:
    """
    {
      "authorize": []
    }
    """
    Then The response status code should be "302"
    And I should be redirect to an url which match "^https:\/\/foobar.com\/authorize\?auth_code=(.+)$"
