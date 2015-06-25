@info_sms

Feature: Process delivery operations

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
                "title": "Title 2",
                "description": "Description 2",
                "average": 4,
                "order": 2
            }
        ]
        """

        Given the system has the following info sms infos:
        """
        [
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1", "t2"]
            },
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t2"]
            }
        ]
        """

        And the system has the following resell packages:
        """
        [
            {
                "id": "rp1",
                "amount": 100,
                "price": 4,
                "description": "100 sms (4 CUC)"
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
            },
            {
                "id": "u1",
                "username": "user1@server.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And the info sms profile "u1" has a balance of 1000 sms

        And the system has the following info sms subscriptions:
        """
        [
            {
                "uniqueness": "u1",
                "mobile": "+5312345678",
                "alias": "client1",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            },
            {
                "uniqueness": "u1",
                "mobile": "+5312345679",
                "alias": "client1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system processes info sms infos

        And I am authenticating as "admin1@server.local" with "pass1" password

        And I set header "content-type" with value "application/json"

    Scenario: Processing delivery operations
        Given the system send and deliver a message successfully

        And the system send and deliver a message unsuccessfully

        And the system send and deliver a message successfully

        When the system processes delivery operations

        And the system should have the following sms messages:
        """
        [
        ]
        """

        And the system should have the following info sms message links:
        """
        [
        ]
        """

        And the system should have the following info sms message stats:
        """
        [
            {
                "id": "i2",
                "body": "Second info",
                "topics": ["t2"],
                "year": "@string@",
                "month": "@string@",
                "day": "@string@",
                "time": "@string@",
                "total": 1,
                "delivered": 1,
                "notDelivered": 0
            },
            {
                "id": "i1",
                "body": "First info",
                "topics": ["t1", "t2"],
                "year": "@string@",
                "month": "@string@",
                "day": "@string@",
                "time": "@string@",
                "total": 2,
                "delivered": 1,
                "notDelivered": 1
            }
        ]
        """


