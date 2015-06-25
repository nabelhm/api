@credit

Feature: Pick profile

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "user1@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Picking the credit profile recently created
        When I send a GET request to "/credit/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 0
        }
        """

    Scenario: Picking the credit profile having balance
        When the credit profile "u1" has a balance of 10 CUC

        And I send a GET request to "/credit/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 10
        }
        """