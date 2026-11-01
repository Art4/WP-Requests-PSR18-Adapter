Feature: Redirects
  The client follows redirects by default and keeps or changes the request
  method as HTTP defines it.

  Scenario: A redirect is followed
    When I send a "GET" request to "/302"
    Then the response status is 200
    And the server received the method "GET"

  Scenario: A redirect is not followed if the option is disabled
    Given the client does not follow redirects
    When I send a "GET" request to "/302"
    Then the response status is 302
    And the response header "Location" is "http://localhost:8057/"

  # Requests only changes the method after a 303.
  @known-issue @issue-41
  Scenario: A POST request becomes a GET request after a 302
    Given the request has the header "Content-Type" with the value "application/x-www-form-urlencoded"
    And the request has the body "foo=bar"
    When I send a "POST" request to "/302"
    Then the response status is 200
    And the server received the method "GET"

  Scenario: A POST request stays a POST request after a 307
    Given the request has the header "Content-Type" with the value "application/json"
    And the request has the body:
      """
      {"foo":"bar"}
      """
    When I send a "POST" request to "/307"
    Then the response status is 200
    And the server received the method "POST"

  Scenario: A relative redirect is resolved against the request URL
    When I send a "GET" request to "/302/relative"
    Then the response status is 200
    And the server received the path "/"

  # Requests does not resolve a Location header that starts with //.
  @known-issue @issue-41
  Scenario: A schema-relative redirect is resolved against the request URL
    When I send a "GET" request to "/301/invalid"
    Then the response status is 200
    And the server received the path "/?foo=bar"

  Scenario: Exceeding the redirect limit is a client error
    Given the client follows at most 0 redirects
    When I send a "GET" request to "/302"
    Then a request exception is thrown
