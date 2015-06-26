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
                "role": "ROLE_INFO_SMS_RESELLER"
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
            "invitation": "12-34-56",
            "username": "info_sms_reseller@gmail.com",
            "password": "pass"
        }
        """

        And I send a POST request to "/user/register-account" with body:
        """
        {
            "invitation": "12-34-56",
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