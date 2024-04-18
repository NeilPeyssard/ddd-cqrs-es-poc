Feature: Exchange an authorization code for an access token
  In order to access the API
  As an anonymous user
  I should be able to exchange an authorization code for an access token

  Scenario: Exchange an authorization code for an access token
    Given I am an anonymous user
    When I request "/token" with method "POST" and Content-Type "application/json" and body:
    """
    {
        "public_key": "018eb331-5352-7b23-8790-65d5669e989b",
        "secret_key": "018eb331-5352-7b23-8790-65d566b3e8c6",
        "auth_code": "018ecbca-387c-72a6-bc00-f2151671d083"
    }
    """
    Then The response status code should be "200"
    And I should receive a valid JSON response
    And The JSON node "access_token" should not be null
    And The JSON node "expires_at" should not be null
