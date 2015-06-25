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
                "username": "user1@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            },
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

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And the system should have the following info sms profile for "u1":
        """
        {
            "uniqueness": "u1",
            "debt": 50,
            "cards": [
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
        }
        """

        And the system should have the following recharge card profile debt operations for "u1":
        """
        [
            {
                "uniqueness": "u1",
                "amount": 50,
                "impact": "+",
                "description": "Pr\u00e9stamo de 5 tarjetas de categor\u00eda \"Category 1\"",
                "created": "@integer@"
            }
        ]
        """