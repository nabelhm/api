@info_sms

Feature: Manage subscriptions

    Background:
        Given the system has the following info sms topics:
        """
        [
            {
                "id": "t1",
                "title": "",
                "description": "",
                "average": 0,
                "order": 1
            },
            {
                "id": "t2",
                "title": "",
                "description": "",
                "average": 4,
                "order": 2
            }
        ]
        """

        And the system has the following resell packages:
        """
        [
            {
                "id": "rp0",
                "amount": 10,
                "price": 0,
                "description": "10 sms gratis."
            },
            {
                "id": "rp1",
                "amount": 100,
                "price": 4,
                "description": "100 sms (4 CUC)."
            },
            {
                "id": "rp2",
                "amount": 1500,
                "price": 60,
                "description": "1500 sms (60 CUC)."
            }
        ]
        """

        And the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "user1@server.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            }
        ]
        """

        And the info sms profile "u1" has a balance of 1000 sms

        And I am authenticating as "user1@server.local" with "pass" password

        And I set header "content-type" with value "application/json"

    Scenario: Creating a trial subscription
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp0"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 10,
                "balance": 0,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 10,
                "balance": 0,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 0,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Tu telefono se ha subscrito con 10 sms gratis para recibir noticias del topico que seleccionaste."
            }
        ]
        """

    Scenario: Creating a paid subscription
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Tu telefono se ha subscrito con 100 sms para recibir noticias del topico que seleccionaste."
            }
        ]
        """

    Scenario: Creating a paid subscription that was created once a time as trial will not send welcome message again
        Given the system has the following info sms subscription trial operations:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": 1440043200
            }
        ]
        """

        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 0,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": "@integer@"
            },
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
        ]
        """

    Scenario: Creating a paid subscription that was created once a time as paid will not send welcome message again
        Given the system has the following info sms subscription create operations:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": 1440043200
            }
        ]
        """

        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            },
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
        ]
        """

    Scenario: Creating a subscription with an short mobile number with fix it
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "12345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Tu telefono se ha subscrito con 100 sms para recibir noticias del topico que seleccionaste."
            }
        ]
        """

    Scenario: Creating a subscription with an invalid mobile
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "123456",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.INVALID_MOBILE"
        }
        """

    Scenario: Creating a subscription with a blank alias
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.BLANK_ALIAS"
        }
        """

    Scenario: Creating a subscription with an existent mobile
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
            "code": "INFO_SMS.SUBSCRIPTION.EXISTENT_MOBILE"
        }
        """

    Scenario: Creating a subscription with no topics
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": [],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NO_TOPICS"
        }
        """

    Scenario: Creating a subscription with non existent topic
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t10"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NON_EXISTENT_TOPICS"
        }
        """

    Scenario: Creating a subscription with no resell package
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": ""
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NO_RESELL_PACKAGE"
        }
        """

    Scenario: Creating a subscription as trial for a second time
        Given the system has the following info sms subscription trial operations:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "timestamp": 1440043200
            }
        ]
        """

        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp0"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.TRIAL_NOT_ACCEPTED"
        }
        """

    Scenario: Creating a subscription with insufficient balance
        When I send a POST request to "/info-sms/me/create-subscription" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp2"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.PROFILE.INSUFFICIENT_BALANCE"
        }
        """

    Scenario: Creating a subscription and computing
        When I send a POST request to "/info-sms/me/create-subscription-and-compute" with body:
        """
        {
            "mobile": "+5312345678",
            "alias": "nabel",
            "topics": ["t1"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        {
            "subscriptionsAmount": 1,
            "infoSmsProfile": {
                "uniqueness": "u1",
                "balance":900
            }
        }
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 1,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """


        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Tu telefono se ha subscrito con 100 sms para recibir noticias del topico que seleccionaste."
            }
        ]
        """

    Scenario: Recharging a subscription
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 0,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": ["t1", "t2"],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscriptions for "u1":
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 2,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 0,
                "amount": 100,
                "timestamp": "@integer@"
            }
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 900
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "Tu telefono se ha recargado con 100 sms para seguir recibiendo noticias."
            }
        ]
        """

    Scenario: Recharging a subscription with no topics
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": [],
            "resellPackage": "rp1"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NO_TOPICS"
        }
        """

    Scenario: Recharging a subscription with non existent topic
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": ["t1", "t10"],
            "resellPackage": "rp2"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NON_EXISTENT_TOPIC"
        }
        """

    Scenario: Recharging a subscription with no resell package
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1", "t2"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": ["t1"],
            "resellPackage": ""
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NO_RESELL_PACKAGE"
        }
        """

    Scenario: Recharging a subscription using trial package
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": ["t1"],
            "resellPackage": "rp0"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.TRIAL_NOT_ACCEPTED"
        }
        """

    Scenario: Recharging a subscription with insufficient profile balance
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/recharge-subscription/+5312345678" with body:
        """
        {
            "topics": ["t1"],
            "resellPackage": "rp2"
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.INSUFFICIENT_BALANCE"
        }
        """

    Scenario: The system send reminders to subscriptions with low balance
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 3,
                "active": true
            }
        ]
        """

        And the system processes subscriptions with low balance

        Then the system should have the following info sms subscription low balance reminder logs:
        """
        [
            {
                "mobile": "+5312345678"
            }
        ]
        """

        And the system should have the following sms messages:
        """
        [
            {
                "message": "@string@",
                "receiver": "+5312345678",
                "body": "El saldo de tu subscripcion es de 3 sms. Recarga con la persona que hiciste la subscripcion para seguir recibiendo noticias."
            }
        ]
        """

    Scenario: The system does not repeat the reminder to subscriptions with low balance
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 3,
                "active": true
            }
        ]
        """

        And the system has the following info sms subscription low balance reminder logs:
        """
        [
            {
                "mobile": "+5312345678"
            }
        ]
        """

        And the system processes subscriptions with low balance

        Then the system should have the following info sms subscription low balance reminder logs:
        """
        [
            {
                "mobile": "+5312345678"
            }
        ]
        """

    Scenario: Collecting subscriptions
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When I send a GET request to "/info-sms/me/collect-subscriptions"

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

    Scenario: Computing subscriptions
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "uniqueness": "u1",
                "mobile": "+5312345678",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When I send a GET request to "/info-sms/me/compute-subscriptions"

        Then the response code should be 200

        And the response should contain json:
        """
        1
        """

    Scenario: Updating a subscription
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabl",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/update-subscription/+5312345678" with body:
        """
        {
            "alias": "nabel",
            "topics": ["t2"],
            "active": false
        }
        """

        Then the response code should be 200

        And the response should contain json:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t2"],
                "trial": 0,
                "balance": 100,
                "active": false
            }
        ]
        """

    Scenario: Updating a subscription with a blank alias
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabl",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/update-subscription/+5312345678" with body:
        """
        {
            "alias": "",
            "topics": ["t2"],
            "active": false
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.BLANK_ALIAS"
        }
        """

    Scenario: Updating a subscription with no info sms topics
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/update-subscription/+5312345678" with body:
        """
        {
            "alias": "nabel",
            "topics": [],
            "active": false
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NO_TOPICS"
        }
        """

    Scenario: Updating a subscription with non existent topic
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        And I send a POST request to "/info-sms/me/update-subscription/+5312345678" with body:
        """
        {
            "alias": "nabel",
            "topics": ["t10"],
            "active": false
        }
        """

        Then the response code should be 400

        And the response should contain json:
        """
        {
           "code": "INFO_SMS.SUBSCRIPTION.NON_EXISTENT_TOPIC"
        }
        """

    Scenario: Deleting a subscription
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "active": true
            }
        ]
        """

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 1100
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 3,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "trial": 0,
                "balance": 100,
                "timestamp": "@integer@"
            }
        ]
        """

    Scenario: Deleting a subscription that has trial
        Given the system has the following info sms subscriptions:
        """
        [
            {
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "alias": "nabel",
                "topics": ["t1"],
                "trial": 10,
                "balance": 80,
                "active": true
            }
        ]
        """

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should be 200

        And the response should contain json:
        """
        [
        ]
        """

        And the system should have the following info sms profiles:
        """
        [
            {
                "uniqueness": "u1",
                "balance": 1080
            }
        ]
        """

        And the system should have the following info sms subscription operations:
        """
        [
            {
                "type": 3,
                "mobile": "+5312345678",
                "uniqueness": "u1",
                "topics": ["t1"],
                "trial": 10,
                "balance": 80,
                "timestamp": "@integer@"
            }
        ]
        """
