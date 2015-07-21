@invitation

Feature: Pick profile

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "a1",
                "username": "admin1@server.local",
                "password": "pass1",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

        And the invitation card profile "a1" has assigned 2 cards of "ROLE_INFO_SMS_JOURNALIST"

        And I am authenticating as "admin1@server.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Picking the invitation card profile
        When I send a GET request to "/invitation/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "a1",
            "cards":
                [
                    {
                        "code": "@string@",
                        "role": "ROLE_INFO_SMS_JOURNALIST",
                        "consumed": false
                    },
                    {
                        "code": "@string@",
                        "role": "ROLE_INFO_SMS_JOURNALIST",
                        "consumed": false
                    }
                ]
        }
        """