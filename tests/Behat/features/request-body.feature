Feature: Request bodies
  The client sends the body of a PSR-7 request unchanged, whatever the
  content type is.

  Scenario Outline: A JSON body reaches the server unchanged
    Given the request has the header "Content-Type" with the value "application/json"
    And the request has the body:
      """
      {"hello":"world","n":42}
      """
    When I send a "<method>" request to "/echo"
    Then the response status is 200
    And the server received the method "<method>"
    And the server received the header "Content-Type" with the value "application/json"
    And the server received the body:
      """
      {"hello":"world","n":42}
      """

    Examples:
      | method |
      | POST   |
      | PUT    |
      | PATCH  |
      | DELETE |

  Scenario: Repeated parameters in a URL-encoded body are preserved
    Given the request has the header "Content-Type" with the value "application/x-www-form-urlencoded"
    And the request has the body "text=foo&text=bar"
    When I send a "POST" request to "/echo"
    Then the response status is 200
    And the server received the body "text=foo&text=bar"

  Scenario: A multiline body keeps its line breaks
    Given the request has the header "Content-Type" with the value "text/plain"
    And the request has the body:
      """
      first line
      second line
      """
    When I send a "POST" request to "/echo"
    Then the response status is 200
    And the server received the body:
      """
      first line
      second line
      """

  Scenario Outline: A request without a body sends no body
    When I send a "<method>" request to "/echo"
    Then the response status is 200
    And the server received no body

    Examples:
      | method |
      | GET    |
      | POST   |
      | PUT    |
      | PATCH  |
      | DELETE |

  Scenario Outline: Length headers of an empty body do not make the server wait
    Given the request has the header "<header>" with the value "<value>"
    When I send a "<method>" request to "/echo"
    Then the response status is 200
    And the server received no body

    Examples:
      | method | header            | value   |
      | GET    | Content-Length    | 10      |
      | POST   | Content-Length    | 10      |
      | POST   | Transfer-Encoding | chunked |

  # HttpClient::sendRequest() treats the body [] as empty.
  @known-issue @issue-42
  Scenario Outline: A body that is a JSON array is sent
    Given the request has the header "Content-Type" with the value "application/json"
    And the request has the body "[]"
    When I send a "<method>" request to "/echo"
    Then the response status is 200
    And the server received the body "[]"

    Examples:
      | method |
      | POST   |
      | PUT    |
      | PATCH  |

  # Regression test for #28, which failed with a TypeError in http_build_query().
  # Whether the body [] reaches the server is decided in #42.
  Scenario Outline: A JSON array on a method without body semantics does not fail
    Given the request has the body "[]"
    When I send a "<method>" request to "/echo"
    Then the response status is 200

    Examples:
      | method |
      | GET    |
      | DELETE |

  Scenario Outline: A body on a method without body semantics does not hang
    Given the request has the body "arbitrary-body-bytes"
    When I send a "<method>" request to "/echo"
    Then the response status is 200
    And the server received the body "arbitrary-body-bytes"

    Examples:
      | method |
      | GET    |
      | DELETE |

  Scenario: A body on a HEAD request does not hang
    Given the request has the header "Content-Type" with the value "application/octet-stream"
    And the request has the body "arbitrary-body-bytes"
    When I send a "HEAD" request to "/head"
    Then the response status is 200
    And the response has no body
