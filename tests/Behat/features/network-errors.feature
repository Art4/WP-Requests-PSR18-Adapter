# The @known-issue scenarios fail, because Requests reports these failures with
# its generic exception, not with a Transport exception.
Feature: Network errors
  A PSR-18 client throws a NetworkExceptionInterface if the request cannot be
  sent because of a network failure.

  @known-issue @issue-41
  Scenario: The host name cannot be resolved
    When I send a "GET" request to "http://does.not.exist.invalid/"
    Then a network exception is thrown

  @known-issue @issue-41
  Scenario: The connection is refused
    When I send a "GET" request to "http://127.0.0.1:1/"
    Then a network exception is thrown

  @known-issue @issue-41
  Scenario: A redirect leads to a host name that cannot be resolved
    When I send a "GET" request to "/301/bad-tld"
    Then a network exception is thrown

  Scenario: A URL with an unsupported scheme is no valid request
    When I send a "GET" request to "ftp://127.0.0.1/"
    Then a request exception is thrown

  Scenario: A URL without a host is no valid request
    When I send a "GET" request to "http://"
    Then a request exception is thrown
