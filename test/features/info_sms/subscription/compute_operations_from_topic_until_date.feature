@info_sms

Feature: Compute operations from topic until date

    Background:
        Given the system has the following user accounts:
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

        And I set header "content-type" with value "application/json"

        And I am authenticating as "admin@server.local" with "pass" password

    Scenario: Computing operations
        # 1440043200 is 2015-08-20
        # 1448082000 is 2015-11-20
        Given the system has the following info sms subscription trial operations:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": 1440043200
            },
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": 1448082000
            },
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t2"],
                "timestamp": 1448082000
            }
        ]
        """

        # 1440043200 is 2015-08-20
        # 1445400000 is 2015-10-21
        And the system has the following info sms subscription create operations:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": 1440043200
            },
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": 1440043200
            },
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": 1445400000
            }
        ]
        """

        # 1445313600 is 2015-10-20
        When I send a GET request to "/info-sms/subscription/t1/1445313600/compute-operations-from-topic-until-date"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "total": 1,
                "type": 0
            },
            {
                "total": 2,
                "type": 1
            }
        ]
        """

