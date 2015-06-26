@info_sms

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

    Scenario: Picking the info sms profile recently created
        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 0
        }
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            }
        ]
        """

    Scenario: Picking the info sms profile having balance
        Given the info sms profile "u1" has a balance of 1000 sms

        And I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 1000
        }
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 1000
            }
        ]
        """



