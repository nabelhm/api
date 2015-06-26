@credit

Feature: Pick profile

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

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

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            }
        ]
        """


    Scenario: Picking the credit profile having balance
        Given the credit profile "u1" has a balance of 10 CUC

        And I send a GET request to "/credit/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 10
        }
        """

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 10
            }
        ]
        """