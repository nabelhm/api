@recharge_card
@access
Feature: Checking access

    Background:
        Given the system has the following user accounts:
        """
        [
            {
                "id": "u1",
                "username": "admin@server.local",
                "password": "pass",
                "roles": ["ROLE_ADMIN"]
            },
            {
                "id": "u2",
                "username": "info_sms_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_RESELLER"]
            },
            {
                "id": "u3",
                "username": "info_sms_journalist@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_INFO_SMS_JOURNALIST"]
            },
            {
                "id": "u4",
                "username": "recharge_card_reseller@muchacuba.local",
                "password": "pass",
                "roles": ["ROLE_RECHARGE_CARD_RESELLER"]
            }
        ]
        """

        And I set header "content-type" with value "application/json"

    Scenario: Accessing page collect categories
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-categories"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-categories"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-categories"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-categories"

        Then the response code should not be 403

    Scenario: Accessing page collect packages
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-packages"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-packages"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-packages"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/collect-packages"

        Then the response code should be 403

    Scenario: Accessing page create category
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/create-category"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-category"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-category"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-category"

        Then the response code should be 403

    Scenario: Accessing page create package
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/create-package"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-package"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-package"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/create-package"

        Then the response code should be 403

    Scenario: Accessing page delete category
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-category/c1"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-category/c1"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-category/c1"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-category/c1"

        Then the response code should be 403

    Scenario: Accessing page delete package
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-package/p1"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-package/p1"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-package/p1"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/delete-package/p1"

        Then the response code should be 403

    Scenario: Accessing page lend cards
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/lend-cards"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/lend-cards"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/lend-cards"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/lend-cards"

        Then the response code should be 403

    Scenario: Accessing page liquidate debt
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/liquidate-debt"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/liquidate-debt"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/liquidate-debt"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/liquidate-debt"

        Then the response code should be 403

    Scenario: Accessing page update category
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/update-category/c1"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-category/c1"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-category/c1"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-category/c1"

        Then the response code should be 403

    Scenario: Accessing page update package
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/update-package/p1"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-package/p1"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-package/p1"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/update-package/p1"

        Then the response code should be 403

    Scenario: Accessing page consume card
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a POST request to "/recharge-card/me/consume-card"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/me/consume-card"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/me/consume-card"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a POST request to "/recharge-card/me/consume-card"

        Then the response code should be 403

    Scenario: Accessing page pick profile
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a GET request to "/recharge-card/me/pick-profile"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/pick-profile"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/pick-profile"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/pick-profile"

        Then the response code should not be 403

    Scenario: Accessing page collect debt operation
        Given I am authenticating as "admin@server.local" with "pass" password

        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should not be 403

        Given I am authenticating as "info_sms_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should be 403

        Given I am authenticating as "info_sms_journalist@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should be 403

        Given I am authenticating as "recharge_card_reseller@muchacuba.local" with "pass" password

        When I send a GET request to "/recharge-card/me/profile/debt/collect-operations"

        Then the response code should not be 403