Feature: Timeouts
  A timeout is a network failure, so a PSR-18 client throws a
  NetworkExceptionInterface.

  Scenario: A response below the timeout is returned
    Given the client has a timeout of 2 seconds
    When I send a "GET" request to "/timeout-header"
    Then the response status is 200

  # Requests reports these failures with its generic exception, not with a Transport exception.
  @known-issue @issue-41
  Scenario: Waiting too long for the response headers is a network failure
    Given the client has a timeout of 0.5 seconds
    When I send a "GET" request to "/timeout-long"
    Then a network exception is thrown

  # Also, the fsockopen transport only limits idle time, so an endless response never ends.
  @known-issue @known-issue-fsockopen @issue-41
  Scenario: Waiting too long for the response body is a network failure
    Given the client has a timeout of 1 seconds
    When I send a "GET" request to "/max-duration"
    Then a network exception is thrown

  # Also, Requests raises a timeout below one second to one second for cURL.
  @known-issue @known-issue-curl @issue-41
  Scenario: A timeout below one second is respected
    Given the client has a timeout of 0.1 seconds
    When I send a "GET" request to "/timeout-header"
    Then a network exception is thrown
