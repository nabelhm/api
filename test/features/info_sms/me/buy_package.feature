@info_sms

Feature: Buy package

    Background:
        Given the system has the following info sms packages:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            },
            {
                "id": "p2",
                "name": "Package 2",
                "amount": 2000,
                "price": 40
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
                "roles": ["ROLE_INFO_SMS_RESELLER"]

            }
        ]
        """

        And the credit profile "u1" has a balance of 20 CUC

        And I am authenticating as "user1@muchacuba.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Buying a package
        When I send a POST request to "/info-sms/me/buy-package" with body:
        """
        {
            "id": "p1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "infoSmsProfile": {
                "uniqueness": "u1",
                "balance": 1000
            },
            "creditProfile": {
                "uniqueness": "u1",
                "balance": 0
            }
        }
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 1000
            }
        ]
        """

        And the system should have the following credit profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 0
            }
        ]
        """

        And the system should have the following credit profile balance operations:
        """
        [
            {
                "uniqueness": "u1",
                "amount": 20,
                "impact": "-",
                "description": "Compra de paquete de noticias por sms \"Package 1\"",
                "created": "@integer@"
            }
        ]
        """

    Scenario: Buying a package with insufficient credit profile balance
        When I send a POST request to "/info-sms/me/buy-package" with body:
        """
        {
            "id": "p2"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.PROFILE.INSUFFICIENT_BALANCE"
        }
        """



