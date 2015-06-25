@info_sms

Feature: Manage packages

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

    Scenario: Creating a package
        When I send a POST request to "/info-sms/create-package" with body:
        """
        {
            "name": "Package 1",
            "amount": 1000,
            "price": 20
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            }
        ]
        """

        And the system should have the following info sms packages:
        """
        [
            {
                "id": "@string@",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            }
        ]
        """

    Scenario: Collecting packages
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

        When I send a GET request to "/info-sms/collect-packages"

        Then the response code should be 200

        And the response should contain json:
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

        And the system should have the following info sms packages:
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

    Scenario: Updating a package
        Given the system has the following info sms packages:
        """
        [
            {
                "id": "p1",
                "name": "Packag 1",
                "amount": 999,
                "price": 9
            }
        ]
        """

        When I send a POST request to "/info-sms/update-package/p1" with body:
        """
        {
            "name": "Package 1",
            "amount": 1000,
            "price": 20
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            }
        ]
        """

        And the system should have the following info sms packages:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            }
        ]
        """

    Scenario: Deleting a package
        Given the system has the following info sms packages:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "amount": 1000,
                "price": 20
            }
        ]
        """

        When I send a POST request to "/info-sms/delete-package/p1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms packages:
        """
        [
        ]
        """