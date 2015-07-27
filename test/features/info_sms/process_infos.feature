@info_sms

Feature: Processes info sms infos

    Background:
        Given the system has the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "Title 1",
                "description": "Description 1",
                "average": 4,
                "order": 1
            },
            {
                "id": "t2",
                "title": "Title 1",
                "description": "Description 2",
                "average": 4,
                "order": 2
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
            },
            {
                "id": "u1",
                "username": "user1@server.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And the info sms profile "u1" has a balance of 1000 sms

        And I am authenticating as "admin@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Processing infos
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
            },
            {
                "id": "i3",
                "body": "Third info",
                "topics": ["t2"]
            }
        ]
        """

        And the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When the system processes info sms infos

        Then the system should have the following info sms infos:
        """
        [
        ]
        """

        Then the system should have the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 97,
                "active": true
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "First info"
            },
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Second info"
            },
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Third info"
            }
        ]
        """

        And the system should have the following info sms message links:
        """
        [
            {
                "message": "@string@",
                "info": "i1",
                "subscription": "+5312345678"
            },
            {
                "message": "@string@",
                "info": "i2",
                "subscription": "+5312345678"
            },
            {
                "message": "@string@",
                "info": "i3",
                "subscription": "+5312345678"
            }
        ]
        """

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i3",
                "body": "Third info",
                "topics": ["t2"],
                "timestamp": "@integer@",
                "total": 1,
                "delivered": 0,
                "notDelivered": 0
            },
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 1,
                "delivered": 0,
                "notDelivered": 0
            },
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 1,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

    Scenario: Processing infos when the system has no info sms subscriptions
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

        When the system processes info sms infos

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 0,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

    Scenario: Processing infos when there is a subscription and an info with common topics will send info only one time
        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1", "t2"]
            }
        ]
        """

        And the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When the system processes info sms infos

        Then the system should have the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 99,
                "active": true
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "First info"
            }
        ]
        """

        And the system should have the following info sms message links:
        """
        [
            {
                "message": "@string@",
                "info": "i1",
                "subscription": "+5312345678"
            }
        ]
        """

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1", "t2"],
                "timestamp": "@integer@",
                "total": 1,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

    Scenario: Processing infos when there is an inactive subscription
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

        And the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": false
            }
        ]
        """

        When the system processes info sms infos

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 0,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

    Scenario: Processing infos when there is a subscription with trial
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

        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1"],
                "trial": 10,
                "balance": 100,
                "active": true
            }
        ]
        """

        When the system processes info sms infos

        Then the system should have the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1"],
                "trial": 9,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "First info"
            }
        ]
        """

        And the system should have the following info sms message links:
        """
        [
            {
                "message": "@string@",
                "info": "i1",
                "subscription": "+5312345678"
            }
        ]
        """

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 1,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

    Scenario: Processing infos when there is a subscription with no trial and no balance
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

        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "client1",
                "topics": ["t1"],
                "trial": 0,
                "balance": 0,
                "active": true
            }
        ]
        """

        When the system processes info sms infos

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1"],
                "timestamp": "@integer@",
                "total": 0,
                "delivered": 0,
                "notDelivered": 0
            }
        ]
        """

