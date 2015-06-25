@credit

Feature: Collect operations

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "user1@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And the system has the following credit profile balance operations:
        """
        [
            {
                "uniqueness": "u1",
                "amount": 100,
                "impact": "+",
                "description": "Recarga de saldo con tarjeta de recarga aaaa-bbbb-cccc"
            },
            {
                "uniqueness": "u1",
                "amount": 20,
                "impact": "-",
                "description": "Compra de paquete de noticias por sms \"Package 1\""
            },
            {
                "uniqueness": "u1",
                "amount": 100,
                "impact": "+",
                "description": "Recarga de saldo con tarjeta de recarga dddd-eeee-ffff"
            }
        ]
        """

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Collecting operations
        When I send a GET request to "/credit/me/profile/balance/collect-operations"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "uniqueness": "u1",
                "amount": 100,
                "impact": "+",
                "description": "Recarga de saldo con tarjeta de recarga dddd-eeee-ffff",
                "created": "@integer@"
            },
            {
                "uniqueness": "u1",
                "amount": 20,
                "impact": "-",
                "description": "Compra de paquete de noticias por sms \"Package 1\"",
                "created": "@integer@"
            },
            {
                "uniqueness": "u1",
                "amount": 100,
                "impact": "+",
                "description": "Recarga de saldo con tarjeta de recarga aaaa-bbbb-cccc",
                "created": "@integer@"
            }
        ]
        """
