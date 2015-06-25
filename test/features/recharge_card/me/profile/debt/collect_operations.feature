@recharge_card

Feature: Collect operations

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "user1@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            },
            {
                "id": "u2",
                "username": "user2@muchacuba.local",
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

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Collecting operations
        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "description": "Liquidaci\u00f3n",
                "impact": "+",
                "amount": 100,
                "created": "@integer@"
            },
            {
                "uniqueness": "u1",
                "description": "Pr\u00e9stamo de 10 tarjetas de categor\u00eda \u0022Tarjeta de 10\u0022",
                "impact": "-",
                "amount": 100,
                "created": "@integer@"
            }
        ]
        """
