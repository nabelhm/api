@credit
@access
Feature: Checking access

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "a1",
                "username": "admin1@server.local",
                "password": "pass1",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "id": "sms_reseller",
                "username": "sms_reseller@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            },
            {
                "id": "sms_journalist",
                "username": "sms_journalist@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_JOURNALIST"]
            },
            {
                "id": "card_reseller",
                "username": "card_reseller@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            }
        ]
        """

        And I set header "content-type" with value "application/json"

    Scenario: Accessing page pick profile
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/credit/me/pick-profile"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/pick-profile"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/pick-profile"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/pick-profile"

        Then the response code should be 403

    Scenario: Accessing page collect balance operation
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/credit/me/profile/balance/collect-operations"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/profile/balance/collect-operations"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/profile/balance/collect-operations"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/credit/me/profile/balance/collect-operations"

        Then the response code should be 403
