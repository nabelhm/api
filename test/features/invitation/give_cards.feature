@invitation
@current
Feature: Give Cards

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
                "username": "info_sms_reseller@server.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And I am authenticating as "admin@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Giving invitations
        When I send a POST request to "/invitation/give-cards" with body:
        """
        {
            "uniqueness": "u1",
            "amount": "2",
            "role": "ROLE_INFO_SMS_JOURNALIST"
        }
        """
        Then the response code should be 200

        And the response should contain json:
        """
        [

        ]
        """
        When I send a GET request to "/invitation/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "cards": [
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

        And the system should have the following invitation cards:
        """
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
        """

        And the system should have the following invitation assigned cards:
        """
        [
            {
                "uniqueness": "u1",
                "card": "@string@"
            },
            {
                "uniqueness": "u1",
                "card": "@string@"
            }
        ]
        """

    Scenario: Giving invitations to a different user
        When I send a POST request to "/invitation/give-cards" with body:
        """
        {
            "uniqueness": "u2",
            "amount": "2",
            "role": "ROLE_INFO_SMS_JOURNALIST"
        }
        """
        Then the response code should be 200

        And the response should contain json:
        """
        [

        ]
        """

        When I send a GET request to "/invitation/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "cards": []
        }
        """

        And I am authenticating as "info_sms_reseller@server.local" with "pass" password

        When I send a GET request to "/invitation/me/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u2",
            "cards": [
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

        And the system should have the following invitation cards:
        """
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
        """
        And the system should have the following invitation assigned cards:
        """
        [
            {
                "uniqueness": "u2",
                "card": "@string@"
            },
            {
                "uniqueness": "u2",
                "card": "@string@"
            }
        ]
        """

    Scenario: Giving invitations with an invalid amount
        When I send a POST request to "/invitation/give-cards" with body:
        """
        {
            "uniqueness": "a1",
            "amount": "a",
            "role": "ROLE_INFO_SMS_JOURNALIST"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INVITATION.PROFILE.INVALID_AMOUNT"
        }
        """
