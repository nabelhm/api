@info_sms

Feature: Manage resell packages

    Background:
        Given the system has the following user accounts:
        """
        [
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

    Scenario: Creating a resell package
        When I send a POST request to "/info-sms/create-resell-package" with body:
        """
        {
            "amount": 10,
            "price": 8,
            "description": "10 sms (8 CUC)"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            }
        ]
        """

        And the system should have the following info sms resell packages:
        """
        [
            {
                "id": "@string@",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            }
        ]
        """

    Scenario: Collecting resell packages
        Given the system has the following resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            },
            {
                "id": "rp2",
                "amount": 20,
                "price": 16,
                "description": "20 sms (16 CUC)"
            }
        ]
        """

        When I send a GET request to "/info-sms/collect-resell-packages"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "rp1",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            },
            {
                "id": "rp2",
                "amount": 20,
                "price": 16,
                "description": "20 sms (16 CUC)"
            }
        ]
        """

        And the system should have the following info sms resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            },
            {
                "id": "rp2",
                "amount": 20,
                "price": 16,
                "description": "20 sms (16 CUC)"
            }
        ]
        """

    Scenario: Updating a resell package
        Given the system has the following resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 1,
                "price": 80,
                "description": "1 sms (80 CUC)"
            }
        ]
        """

        When I send a POST request to "/info-sms/update-resell-package/rp1" with body:
        """
        {
            "amount": 10,
            "price": 8,
            "description": "10 sms (8 CUC)"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "rp1",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            }
        ]
        """

        And the system should have the following info sms resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 10,
                "price": 8,
                "description": "10 sms (8 CUC)"
            }
        ]
        """

    Scenario: Deleting a resell package
        Given the system has the following resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 1,
                "price": 8,
                "description": "10 sms (8 CUC)"
            }
        ]
        """

        When I send a POST request to "/info-sms/delete-resell-package/rp1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms resell packages:
        """
        [
        ]
        """