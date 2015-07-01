@user
@access
Feature: Checking access

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "admin@server.local",
                "password": "pass",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "id": "u2",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            },
            {
                "id": "u3",
                "username": "info_sms_journalist@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_JOURNALIST"]
            },
            {
                "id": "u4",
                "username": "recharge_card_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_RESELL_CARD_RESELLER"]
            }
        ]
        """

        And I set header "content-type" with value "application/json"

    Scenario: Accessing page register account
        When I send a GET request to "/user/register-account"

        Then the response code should not be 403

    Scenario: Accessing page collect accounts
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a GET request to "/user/collect-accounts"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/user/collect-accounts"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a GET request to "/user/collect-accounts"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/user/collect-accounts"

        Then the response code should be 403
