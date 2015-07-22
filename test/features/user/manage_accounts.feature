@user

Feature: Manage accounts

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "admin@server.local",
                "password": "pass",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

        And the system has the following invitation cards:
        """
        [
            {
                "code": "12-34-56",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":false
            },
            {
                "code": "12-34-57",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":true
            }
        ]
        """

        And I set header "content-type" with value "application/json"

    Scenario: Register an user account
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
            "username": "info_sms_reseller@gmail.com",
            "password": "pass"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And I am authenticating as "admin@server.local" with "pass" password

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "mobile": "",
                "email": "admin@server.local",
                "roles": [
                    "ROLE_ADMIN"
                ]
            },
            {
                "uniqueness": "@string@",
                "mobile": "",
                "email": "info_sms_reseller@gmail.com",
                "roles": [
                    "ROLE_INFO_SMS_RESELLER"
                ]
            }
        ]
        """

        And the system should have the following invitation cards:
        """
        [
            {
                "code": "12-34-56",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":true
            },
            {
                "code": "12-34-57",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":true
            }
        ]
        """

        And the system should have the following uniquenesses:
        """
        [
            {
                "id": "u1"
            },
            {
                "id": "@string@"
            }
        ]
        """

        And the system should have the following mobile profiles:
        """
        [
        ]
        """

        And the system should have the following internet profiles:
        """
        [
            {
                "uniqueness": "u1",
                "email": "admin@server.local"
            },
            {
                "uniqueness": "@string@",
                "email": "info_sms_reseller@gmail.com"
            }
        ]
        """

        And the system should have the following assigned roles:
        """
        [
            {
                "uniqueness": "u1",
                "role": "ROLE_ADMIN"
            },
            {
                "uniqueness": "@string@",
                "role": "ROLE_INFO_SMS_RESELLER"
            }
        ]
        """

        And the system should have the following authentication profiles:
        """
        [
            {
                "uniqueness": "u1",
                "salt": "@string@",
                "hash": "@string@"
            },
            {
                "uniqueness": "@string@",
                "salt": "@string@",
                "hash": "@string@"
            }
        ]
        """

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            },
            {
                "uniqueness": "@string@",
                "balance": 0
            }
        ]
        """

        And the system should have the following recharge card profiles:
        """
        [
            {
                "uniqueness": "u1",
                "debt": 0,
                "cards": []
            },
            {
                "uniqueness": "@string@",
                "debt": 0,
                "cards": []
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            },
            {
                "uniqueness": "@string@",
                "balance": 0
            }
        ]
        """

    Scenario: Register an user account with a mobile number
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
            "username": "5312345678",
            "password": "pass"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And I am authenticating as "admin@server.local" with "pass" password

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "mobile": "",
                "email": "admin@server.local",
                "roles": [
                    "ROLE_ADMIN"
                ]
            },
            {
                "uniqueness": "@string@",
                "mobile": "+5312345678",
                "email": "",
                "roles": [
                    "ROLE_INFO_SMS_RESELLER"
                ]
            }
        ]
        """

        And the system should have the following invitation cards:
        """
        [
            {
                "code": "12-34-56",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":true
            },
            {
                "code": "12-34-57",
                "role": "ROLE_INFO_SMS_RESELLER",
                "consumed":true
            }
        ]
        """

        And the system should have the following uniquenesses:
        """
        [
            {
                "id": "u1"
            },
            {
                "id": "@string@"
            }
        ]
        """

        And the system should have the following mobile profiles:
        """
        [
            {
                "uniqueness": "@string@",
                "number": "+5312345678"
            }
        ]
        """

        And the system should have the following internet profiles:
        """
        [
            {
                "uniqueness": "u1",
                "email": "admin@server.local"
            }
        ]
        """

        And the system should have the following assigned roles:
        """
        [
            {
                "uniqueness": "u1",
                "role": "ROLE_ADMIN"
            },
            {
                "uniqueness": "@string@",
                "role": "ROLE_INFO_SMS_RESELLER"
            }
        ]
        """

        And the system should have the following authentication profiles:
        """
        [
            {
                "uniqueness": "u1",
                "salt": "@string@",
                "hash": "@string@"
            },
            {
                "uniqueness": "@string@",
                "salt": "@string@",
                "hash": "@string@"
            }
        ]
        """

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            },
            {
                "uniqueness": "@string@",
                "balance": 0
            }
        ]
        """

        And the system should have the following recharge card profiles:
        """
        [
            {
                "uniqueness": "u1",
                "debt": 0,
                "cards": []
            },
            {
                "uniqueness": "@string@",
                "debt": 0,
                "cards": []
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            },
            {
                "uniqueness": "@string@",
                "balance": 0
            }
        ]
        """

    Scenario: Register an user account with a non existent invitation
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-55",
            "username": "info_sms_reseller@gmail.com",
            "password": "pass"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER.ACCOUNT.NON_EXISTENT_INVITATION"
        }
        """

    Scenario: Register an user account with a consumed invitation
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-57",
            "username": "info_sms_reseller@gmail.com",
            "password": "pass"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER.ACCOUNT.ALREADY_CONSUMED_INVITATION"
        }
        """

    Scenario: Register an user account with an empty password
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
            "username": "info_sms_reseller@gmail.com",
            "password": ""
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER.ACCOUNT.EMPTY_PASSWORD"
        }
        """

    Scenario: Register an user account with an invalid username
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
            "username": "sms_reseller.gmail.com",
            "password": "pass"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER.ACCOUNT.INVALID_USERNAME"
        }
        """

    Scenario: Register an user account with an existent username
        When I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
            "username": "admin@server.local",
            "password": "pass"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "USER.ACCOUNT.EXISTENT_USERNAME"
        }
        """

    Scenario: Collecting user accounts
        Given I am authenticating as "admin@server.local" with "pass" password

        And I send a GET request to "/user/collect-accounts"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "mobile": "",
                "email": "admin@server.local",
                "roles":
                    [
                        "ROLE_ADMIN"
                    ]
            }
        ]
        """