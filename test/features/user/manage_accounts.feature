@user

Feature: Manage accounts

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

        And I am authenticating as "admin1@server.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Creating an user account
        When I send a POST request to "/user/create-account" with body:
        """
        {
            "username": "user1@muchacuba.local",
            "password": "pass1",
            "roles":[
                "ROLE_RECHARGE_CARD_RESELLER",
                "ROLE_INFO_SMS_JOURNALIST",
                "ROLE_INFO_SMS_RESELLER"
            ]
        }
        """
        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "@string@",
                "email": "admin1@server.local",
                "mobile": "",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "uniqueness": "@string@",
                "email": "user1@muchacuba.local",
                "mobile": "",
                "roles":[
                    "ROLE_RECHARGE_CARD_RESELLER",
                    "ROLE_INFO_SMS_JOURNALIST",
                    "ROLE_INFO_SMS_RESELLER"
                ]
            }
        ]
        """

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I send a GET request to "/role/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "@string@",
            "roles":[
                "ROLE_RECHARGE_CARD_RESELLER",
                "ROLE_INFO_SMS_JOURNALIST",
                "ROLE_INFO_SMS_RESELLER"
            ]
        }
        """

        And I send a GET request to "/credit/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "@string@",
            "balance": 0,
            "debt": 0
        }
        """

        And I send a GET request to "/info-sms/pick-profile"

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "@string@",
            "balance": 0
        }
        """

    Scenario: Creating an user account with an empty password
        When I send a POST request to "/user/create-account" with body:
        """
        {
            "username": "user1@muchacuba.local",
            "password": "",
            "roles":[
                    "ROLE_RECHARGE_CARD_RESELLER",
                    "ROLE_INFO_SMS_JOURNALIST"
                    ]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER_EMPTY_PASSWORD"
        }
        """

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "@string@",
                "email": "admin1@server.local",
                "mobile": "",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

    Scenario: Creating an user account with an invalid username
        When I send a POST request to "/user/create-account" with body:
        """
        {
            "username": "user1.muchacuba.local",
            "password": "pass1",
            "roles":[
                    "ROLE_RECHARGE_CARD_RESELLER",
                    "ROLE_INFO_SMS_JOURNALIST"
                    ]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER_INVALID_USERNAME"
        }
        """

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "@string@",
                "email": "admin1@server.local",
                "mobile": "",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

    Scenario: Collecting user accounts
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
                "id": "a1",
                "username": "user1@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            }
        ]
        """

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "a1",
                "email": "admin1@server.local",
                "mobile": "",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "uniqueness": "a1",
                "email": "user1@muchacuba.local",
                "mobile": "",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            }
        ]
        """
