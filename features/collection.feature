Feature:
  In order to use Postman with API Platform
  as a developer

  Scenario: Generates a Postman collection with 1 folder and 5 requests
    When I generate the Postman collection
    Then I should have 1 folder in Postman collection
    And I should have 5 requests in Postman collection
