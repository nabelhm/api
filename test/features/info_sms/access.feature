@info_sms
@access
Feature: Checking access

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "a1",
                "username": "admin1@server.local",
                "password": "pass1",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "id": "sms_reseller",
                "username": "sms_reseller@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            },
            {
                "id": "sms_journalist",
                "username": "sms_journalist@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_INFO_SMS_JOURNALIST"]
            },
            {
                "id": "card_reseller",
                "username": "card_reseller@muchacuba.local",
                "password": "pass1",
                "roles": ["ROLE_RESELL_CARD_RESELLER"]
            }
        ]
        """

        And I set header "content-type" with value "application/json"

    Scenario: Accessing page collect infos
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-infos"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-infos"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-infos"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-infos"

        Then the response code should be 403

    Scenario: Accessing page collect packages
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-packages"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-packages"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-packages"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-packages"

        Then the response code should be 403

    Scenario: Accessing page collect resell packages
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-resell-packages"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-resell-packages"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-resell-packages"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/collect-resell-packages"

        Then the response code should be 403

    Scenario: Accessing page collect topics
        Given I am authenticating as "admin1@server.local" with "pass1" password
  
        When I send a GET request to "/info-sms/collect-topics"
  
        Then the response code should not be 403
  
        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password
  
        When I send a GET request to "/info-sms/collect-topics"
  
        Then the response code should not be 403
  
        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password
  
        When I send a GET request to "/info-sms/collect-topics"
  
        Then the response code should not be 403
  
        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password
  
        When I send a GET request to "/info-sms/collect-topics"
  
        Then the response code should be 403

    Scenario: Accessing page create info
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/create-info"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-info"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-info"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-info"

        Then the response code should be 403

    Scenario: Accessing page create package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/create-package"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-package"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-package"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-package"

        Then the response code should be 403

    Scenario: Accessing page create resell package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/create-resell-package"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-resell-package"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-resell-package"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-resell-package"

        Then the response code should be 403

    Scenario: Accessing page create topic
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/create-topic"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-topic"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-topic"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/create-topic"

        Then the response code should be 403

    Scenario: Accessing page delete info
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-info/i1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-info/i1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-info/i1"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-info/i1"

        Then the response code should be 403

    Scenario: Accessing page delete package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-package/p1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-package/p1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-package/p1"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-package/p1"

        Then the response code should be 403

    Scenario: Accessing page delete resell package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-resell-package/rp1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-resell-package/rp1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-resell-package/rp1"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-resell-package/rp1"

        Then the response code should be 403

    Scenario: Accessing page delete topic
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-topic/t1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-topic/t1"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/delete-topic/t1"

        Then the response code should be 403

    Scenario: Accessing page pick topic
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/pick-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/pick-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/pick-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/pick-topic/t1"

        Then the response code should be 403

    Scenario: Accessing page update info
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/update-info/i1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-info/i1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-info/i1"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-info/i1"

        Then the response code should be 403

    Scenario: Accessing page update package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/update-package/p1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-package/p1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-package/p1"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-package/p1"

        Then the response code should be 403

    Scenario: Accessing page update resell package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/update-resell-package/rp1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-resell-package/rp1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-resell-package/rp1"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-resell-package/rp1"

        Then the response code should be 403

    Scenario: Accessing page update topic
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/update-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-topic/t1"

        Then the response code should be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-topic/t1"

        Then the response code should not be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/update-topic/t1"

        Then the response code should be 403

#    Scenario: Accessing page collect stats from archived info by topic from current week
#        Given I am authenticating as "admin1@server.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should be 403
#
#    Scenario: Accessing page collect stats from archived info by topic from current year
#        Given I am authenticating as "admin1@server.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/archived-info/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should be 403

    Scenario: Accessing page buy package
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/buy-package"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/buy-package"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/buy-package"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/buy-package"

        Then the response code should be 403

    Scenario: Accessing page collect subscriptions
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/me/collect-subscriptions"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/collect-subscriptions"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/collect-subscriptions"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/collect-subscriptions"

        Then the response code should be 403

    Scenario: Accessing page compute subscriptions
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/me/compute-subscriptions"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/compute-subscriptions"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/compute-subscriptions"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/compute-subscriptions"

        Then the response code should be 403

    Scenario: Accessing page create subscription and compute
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription-and-compute"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription-and-compute"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription-and-compute"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription-and-compute"

        Then the response code should be 403

    Scenario: Accessing page create subscription
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/create-subscription"

        Then the response code should be 403

    Scenario: Accessing page delete subscription
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/delete-subscription/+5312345678"

        Then the response code should be 403

    Scenario: Accessing page pick profile
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a GET request to "/info-sms/me/pick-profile"

        Then the response code should be 403

    Scenario: Accessing page resell subscription
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/recharge-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/recharge-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/recharge-subscription/+5312345678"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/recharge-subscription/+5312345678"

        Then the response code should be 403

    Scenario: Accessing page update subscription
        Given I am authenticating as "admin1@server.local" with "pass1" password

        When I send a POST request to "/info-sms/me/update-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/update-subscription/+5312345678"

        Then the response code should not be 403

        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/update-subscription/+5312345678"

        Then the response code should be 403

        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password

        When I send a POST request to "/info-sms/me/update-subscription/+5312345678"

        Then the response code should be 403
#
#    Scenario: Accessing page collect stats from subscription by topic from current week
#        Given I am authenticating as "admin1@server.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should be 403
#
#        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-week"
#
#        Then the response code should be 403
#
#    Scenario: Accessing page collect stats from subscription by topic from current year
#        Given I am authenticating as "admin1@server.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "sms_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should be 403
#
#        Given I am authenticating as "sms_journalist@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should not be 403
#
#        Given I am authenticating as "card_reseller@muchacuba.local" with "pass1" password
#
#        When I send a GET request to "/info-sms/subscription/t1/collect-by-topic-stats-from-current-year"
#
#        Then the response code should be 403
