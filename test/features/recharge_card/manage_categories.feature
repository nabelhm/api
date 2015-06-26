@recharge_card

Feature: Manage categories

    Background:
        Given the system has the following recharge card categories:
        """
        """

        And the system has the following user accounts:
        """
        [
            {
                "id": "a1",
                "username": "admin@server.local",
                "password": "pass",
                "roles": ["ROLE_ADMIN"]
            }
        ]
        """

        And I am authenticating as "admin@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Creating a category
        When I send a POST request to "/recharge-card/create-category" with body:
        """
        {
            "name": "Category 1",
            "utility": 10
        }
        """
        
        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "name": "Category 1",
                "utility": 10
            }
        ]
        """

    Scenario: Collecting categories
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
        And I send a GET request to "/recharge-card/collect-categories"
        Then the response code should be 200

        And the response should contain json:
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

    Scenario: Updating a category
        Given the system has the following recharge card categories:
        """
        [
            {
                "id": "c1",
                "name": "Categor 1",
                "utility": 9
            }
        ]
        """

        When I send a POST request to "/recharge-card/update-category/c1" with body:
        """
        {
            "name": "Category 1",
            "utility": 10
        }
        """
        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "c1",
                "name": "Category 1",
                "utility": 10
            }
        ]
        """

    Scenario: Deleting a category
        Given the system has the following recharge card categories:
        """
        [
            {
                "id": "c1",
                "name": "Category 1",
                "utility": 9
            }
        ]
        """

        When I send a POST request to "/recharge-card/delete-category/c1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """
