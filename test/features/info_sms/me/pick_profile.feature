@info_sms

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

        And the info sms profile "u1" has a balance of 1000 sms

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Picking the profile
        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 1000
        }
        """


