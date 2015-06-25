@info_sms

Feature: Manage topics

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

    Scenario: Creating a topic
        When I send a POST request to "/info-sms/create-topic" with body:
        """
        {
            "title": "Topic 1",
            "description": "Description of the topic 1",
            "average": 3,
            "order": 1
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": true,
                "order": 1
            }
        ]
        """

        And the system should have the following info sms topics:
        """
        [
            {
                "id": "@string@",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": true,
                "order": 1
            }
        ]
        """

    Scenario: Collecting topics
        Given the system has the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": true,
                "order": 1
            },
            {
                "id": "t2",
                "title": "Topic 2",
                "description": "Description of the topic 2",
                "average": 4,
                "active": true,
                "order": 2
            }
        ]
        """

        When I send a GET request to "/info-sms/collect-topics"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": true,
                "order": 1
            },
            {
                "id": "t2",
                "title": "Topic 2",
                "description": "Description of the topic 2",
                "average": 4,
                "active": true,
                "order": 2
            }
        ]
        """

        And the system should have the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": true,
                "order": 1
            },
            {
                "id": "t2",
                "title": "Topic 2",
                "description": "Description of the topic 2",
                "average": 4,
                "active": true,
                "order": 2
            }
        ]
        """

    Scenario: Updating a topic
        Given the system has the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Topi 1",
                "description": "Descript of the topic 1",
                "average": 0,
                "order": 2
            }
        ]
        """

        When I send a POST request to "/info-sms/update-topic/t1" with body:
        """
        {
            "title": "Topic 1",
            "description": "Description of the topic 1",
            "average": 3,
            "active": false,
            "order": 1
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": false,
                "order": 1
            }
        ]
        """

        And the system should have the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "active": false,
                "order": 1
            }
        ]
        """

    Scenario: Deleting a topic
        Given the system has the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Topic 1",
                "description": "Description of the topic 1",
                "average": 3,
                "order": 1
            }
        ]
        """

        When I send a POST request to "/info-sms/delete-topic/t1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms topics:
        """
        [
        ]
        """