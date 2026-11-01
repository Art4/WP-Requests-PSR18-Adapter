Feature: Response bodies
  The client hands out the decoded response body and fails in a controlled way
  if the server sends a broken response.

  Scenario: A chunked response is decoded
    When I send a "GET" request to "/chunked"
    Then the response status is 200
    And the response body is "Symfony is awesome!"

  Scenario: A gzip encoded response is decoded
    Given the request has the header "Accept-Encoding" with the value "gzip"
    When I send a "GET" request to "/"
    Then the response status is 200
    And the server received the path "/"

  Scenario Outline: A broken response neither crashes nor hangs the client
    Given the client has a timeout of 2 seconds
    When I send a "GET" request to "<path>"
    Then the client either returns a response or throws a client exception
    And the client answers within 3 seconds

    Examples:
      | path            |
      | /length-broken  |
      | /chunked-broken |
      | /gzip-broken    |
