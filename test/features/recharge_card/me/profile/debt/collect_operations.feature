@recharge_card

Feature: Collect operations

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
                "username": "recharge_card_reseller@muchacuba.local",
                "password": "pass2",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            }
        ]
        """

        And the system has the following recharge card profile debt operations:
        """
        [
            {
                "uniqueness": "u1",
                "description": "Préstamo de 10 tarjetas de categoría \"Tarjeta de 10\"",
                "impact": "-",
                "amount": 100
            },
            {
                "uniqueness": "u1",
                "description": "Liquidación",
                "impact": "+",
                "amount": 100
            },
            {
                "uniqueness": "u2",
                "description": "Préstamo de 10 tarjetas de categoría \"Tarjeta de 10\"",
                "impact": "-",
                "amount": 200
            },
            {
                "uniqueness": "u2",
                "description": "Liquidación",
                "impact": "+",
                "amount": 200
            }
        ]
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Collecting operations
        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "description": "Liquidación",
                "impact": "+",
                "amount": 100,
                "created": "@integer@"
            },
            {
                "uniqueness": "u1",
                "description": "Préstamo de 10 tarjetas de categoría \"Tarjeta de 10\"",
                "impact": "-",
                "amount": 100,
                "created": "@integer@"
            }
        ]
        """