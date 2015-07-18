@recharge_card

Feature: Consuming card

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

        And the system has the following recharge card cards:
        """
        [
            {
                "code": "wwww-xxxx-yyyy",
                "category": "c1",
                "consumed": false
            },
            {
                "code": "wwww-xxxx-zzzz",
                "category": "c1",
                "consumed": true
            }
        ]
        """

        And the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Consuming card
        When I send a POST request to "/recharge-card/me/consume-card" with body:
        """
        {
            "code": "wwww-xxxx-yyyy"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "uniqueness": "u1",
            "balance": 10
        }
        """

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 10
            }
        ]
        """

        And the system should have the following credit profile balance operations:
        """
        [
            {
                "uniqueness": "u1",
                "amount": 10,
                "impact": "+",
                "description": "Recarga de saldo con tarjeta de recarga \"wwww-xxxx-yyyy\"",
                "created": "@integer@"
            }
        ]
        """

        And the system should have the following recharge card cards:
        """
        [
            {
                "code": "wwww-xxxx-yyyy",
                "category": "c1",
                "consumed": true
            },
            {
                "code": "wwww-xxxx-zzzz",
                "category": "c1",
                "consumed": true
            }
        ]
        """

    Scenario: Consuming a non existent card
        When I send a POST request to "/recharge-card/me/consume-card" with body:
        """
        {
            "code": "wwww-xxxx-yyyz"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "RECHARGE_CARD.CARD.NON_EXISTENT_CODE"
        }
        """

    Scenario: Consuming an already consumed card
        When I send a POST request to "/recharge-card/me/consume-card" with body:
        """
        {
            "code": "wwww-xxxx-zzzz"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "RECHARGE_CARD.CARD.ALREADY_CONSUMED"
        }
        """