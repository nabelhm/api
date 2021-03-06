@recharge_card

Feature: Lend cards

    Background:
        Given the system has the following recharge card categories:
        """
        [
            {
                "id": "c1",
                "name": "Category 1",
                "utility": 10
            }
        ]
        """

        And the system has the following recharge card packages:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "category": "c1",
                "amount": 5,
                "price": 50
            },
            {
                "id": "p2",
                "name": "Package 2",
                "category": "c1",
                "amount": 20,
                "price": 20
            }
        ]
        """

        And the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "recharge_card_reseller@muchacuba.local",
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

        And I am authenticating as "admin@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Lending cards
        When I send a POST request to "/recharge-card/lend-cards" with body:
        """
        {
            "uniqueness": "u1",
            "package": "p1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        {}
        """

        And the system should have the following recharge card cards:
        """
        [
            {
                "code": "@string@",
                "category": "c1",
                "consumed": false
            },
            {
                "code": "@string@",
                "category": "c1",
                "consumed": false
            },
            {
                "code": "@string@",
                "category": "c1",
                "consumed": false
            },
            {
                "code": "@string@",
                "category": "c1",
                "consumed": false
            },
            {
                "code": "@string@",
                "category": "c1",
                "consumed": false
            }
        ]
        """

        And the system should have the following recharge card profiles:
        """
        [
            {
                "uniqueness": "u2",
                "debt": 0,
                "cards": []
            },
            {
                "uniqueness": "u1",
                "debt": 50,
                "cards": [
                    "@string@",
                    "@string@",
                    "@string@",
                    "@string@",
                    "@string@"
                ]
            }
        ]
        """

        And the system should have the following recharge card profiles debt operations
        """
        [
            {
                "uniqueness": "u1",
                "amount": 50,
                "impact": "+",
                "description": "Préstamo de 5 tarjetas de categoría \"Category 1\"",
                "created": "@integer@"
            }
        ]
        """