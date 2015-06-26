@recharge_card

Feature: Liquidate debt

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            },
            {
                "id": "u2",
                "username": "admin@server.local",
                "password": "pass",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

        And the recharge card profile "u1" has a debt of 200 CUC

        And I am authenticating as "admin@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Liquidating a debt
        When I send a POST request to "/recharge-card/liquidate-debt" with body:
        """
        {
            "uniqueness": "u1",
            "amount": 200
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        {}
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        And the system should have the following recharge profile for "u1":
        """
        {
            "uniqueness": "u1",
            "debt": 0,
            "cards": []
        }
        """

        And the system should have the following recharge card profile debt operations for "u1":
        """
        [
            {
                "uniqueness": "u1",
                "amount": 200,
                "impact": "-",
                "description": "Liquidaci\u00f3n",
                "created": "@integer@"
            }
        ]
        """

    Scenario: Liquidating a debt greater than real
        When I send a POST request to "/recharge-card/liquidate-debt" with body:
        """
        {
            "uniqueness": "u1",
            "amount": 250
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "RECHARGE_CARD.PROFILE.GREATER_THAN_REAL_DEBT"
        }
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        And the system should have the following recharge profile for "u1":
        """
        {
            "uniqueness": "u1",
            "debt": 200,
            "cards": []
        }
        """

        And the system should have the following recharge card profile debt operations for "u1":
        """
        [
        ]
        """