@info_sms

Feature: Manage infos

    Background:
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

    Scenario: Creating an info
        When I send a POST request to "/info-sms/create-info" with body:
        """
        {
            "body": "First info",
            "topics": ["t1"]
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "@string@",
                "body": "First info",
                "created": "@integer@",
                "topics": ["t1"]
            }
        ]
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "@string@",
                "body": "First info",
                "created": "@integer@",
                "topics": ["t1"]
            }
        ]
        """

    Scenario: Creating an info with blank body
        When I send a POST request to "/info-sms/create-info" with body:
        """
        {
            "body": "",
            "topics": ["t1"]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.BLANK_BODY"
        }
        """

    Scenario: Creating an info with no info sms topics
        When I send a POST request to "/info-sms/create-info" with body:
        """
        {
            "body": "First Info",
            "topics": []
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.NO_TOPICS"
        }
        """

    Scenario: Creating an info with non existent info sms topics
        When I send a POST request to "/info-sms/create-info" with body:
        """
        {
            "body": "First Info",
            "topics": ["t10"]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.NON_EXISTENT_TOPIC"
        }
        """

    Scenario: Collecting infos
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"]
            },
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t1"]
            }
        ]
        """

        When I send a GET request to "/info-sms/collect-infos"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t1"],
                "created": "@integer@"
            },
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "created": "@integer@"
            }
        ]
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t1"],
                "created": "@integer@"
            },
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "created": "@integer@"
            }
        ]
        """

    Scenario: Updating an info
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First inf",
                "topics": ["t1"]
            }
        ]
        """

        When I send a POST request to "/info-sms/update-info/i1" with body:
        """
        {
            "body": "First info",
            "topics": ["t1"]
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "created": "@integer@",
                "topics": ["t1"]
            }
        ]
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "created": "@integer@",
                "topics": ["t1"]
            }
        ]
        """

    Scenario: Updating an info with blank body
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First inf",
                "topics": ["t1"]
            }
        ]
        """

        When I send a POST request to "/info-sms/update-info/i1" with body:
        """
        {
            "body": "",
            "topics": ["t1"]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.BLANK_BODY"
        }
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First inf",
                "topics": ["t1"],
                "created": "@integer@"
            }
        ]
        """

    Scenario: Updating an info with no info sms topics
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"]
            }
        ]
        """

        When I send a POST request to "/info-sms/update-info/i1" with body:
        """
        {
            "body": "First info",
            "topics": []
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.NO_TOPICS"
        }
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "created": "@integer@"
            }
        ]
        """

    Scenario: Updating an info with non existent info sms topics
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"]
            }
        ]
        """

        When I send a POST request to "/info-sms/update-info/i1" with body:
        """
        {
            "body": "First info",
            "topics": ["t10"]
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.INFO.NON_EXISTENT_TOPIC"
        }
        """

        And the system should have the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "created": "@integer@"
            }
        ]
        """

    Scenario: Deleting an info
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"]
            }
        ]
        """

        When I send a POST request to "/info-sms/delete-info/i1"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms infos:
        """
        [
        ]
        """