@recharge_card

Feature: Manage packages

    Background:
        Given the system has the following recharge card categories:
        """
        [
            {
                "id": "c1",
                "name": "Category 1",
                "utility": 10
            },
            {
                "id": "c2",
                "name": "Category 2",
                "utility": 20
            }
        ]
        """

        And the system has the following user accounts:
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
        When I send a POST request to "/recharge-card/create-package" with body:
        """
        {
            "name": "Package 1",
            "category": "c1",
            "amount": 1000,
            "price": 10
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "name": "Package 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            }
        ]
        """

    Scenario: Collecting packages
        Given the system has the following recharge card packages:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            },
            {
                "id": "p2",
                "name": "Package 2",
                "category": "c2",
                "amount": 2000,
                "price": 20
            }
        ]
        """

        When I send a GET request to "/recharge-card/collect-packages"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            },
            {
                "id": "p2",
                "name": "Package 2",
                "category": "c2",
                "amount": 2000,
                "price": 20
            }
        ]
        """

    Scenario: Updating a package
        Given the system has the following recharge card packages:
        """
        [
            {
                "id": "p1",
                "name": "Packag 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            }
        ]
        """

        When I send a POST request to "/recharge-card/update-package/p1" with body:
        """
        {
            "name": "Package 1",
            "category": "c1",
            "amount": 1000,
            "price": 10
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "p1",
                "name": "Package 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            }
        ]
        """

    Scenario: Deleting a package
        Given the system has the following recharge card packages:
        """
        [
            {
                "id": "p1",
                "name": "Packag 1",
                "category": "c1",
                "amount": 1000,
                "price": 10
            }
        ]
        """

        When I send a POST request to "/recharge-card/delete-package/p1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """