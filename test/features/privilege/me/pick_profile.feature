@privilege

Feature: Pick profile

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles":[
                    "ROLE_RECHARGE_CARD_RESELLER",
                    "ROLE_INFO_SMS_JOURNALIST"
                ]
            }
        ]
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Picking the privilege profile
        When I send a GET request to "/privilege/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "roles":[
                "ROLE_RECHARGE_CARD_RESELLER",
                "ROLE_INFO_SMS_JOURNALIST"
            ]
        }
        """
