Feature: Status codes
  A PSR-18 client returns a response for every well-formed HTTP response,
  including 4xx and 5xx status codes.

  Scenario: A successful request returns the response
    When I send a "GET" request to "/json"
    Then the response status is 200
    And the response header "Content-Type" is "application/json"

  Scenario Outline: An error status code is no exception
    When I send a "GET" request to "/custom?status=<status>"
    Then the response status is <status>

    Examples:
      | status |
      | 400    |
      | 403    |
      | 404    |
      | 500    |
      | 503    |

  Scenario: A 404 response keeps its headers
    When I send a "GET" request to "/404"
    Then the response status is 404
    And the response header "Content-Type" is "application/json"

  # Requests handles an interim response with the fsockopen transport as the final response.
  @known-issue-fsockopen @issue-41
  Scenario: An informational response is skipped
    When I send a "GET" request to "/103"
    Then the response status is 200
    And the response body is "Here the body"

  Scenario: A HEAD request has no response body
    When I send a "HEAD" request to "/head"
    Then the response status is 200
    And the response header "X-Request-Vars" reports the method "HEAD"
    And the response has no body
