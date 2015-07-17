@info_sms
@current
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
            },
            {
                "id": "u2",
                "username": "info_sms_reseller1@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And the info sms profile "u2" has a balance of 1000 sms

        And I set header "content-type" with value "application/json"

    Scenario: Picking the info sms profile recently created
        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 0
        }
        """

    Scenario: Picking the info sms profile having balance
        Given I am authenticating as "info_sms_reseller1@muchacuba.local" with "pass" password

        And I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u2",
            "balance": 1000
        }
        """