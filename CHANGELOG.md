2.0.0
=============

* Important changes:
    * The web api endpoints were changed. We added the prefix to all endpoints (edpuzzle-)
    * The `CustomerPriceRepositoryInterface::getList` method was changed. The result is search results entity
    * Added the `epuzzle/magento2-module-base` module as a required dependency
    * ACL structure was changed. Depends on the `epuzzle/magento2-module-base` module
    * The di.xml, events.xml, etc. were changed. The plugin names, keys, etc. were changed
    * The customer price code was changed
    * The module is enabled by default
* New features:
    * Supported new Magento and PHP versions
    * Supported configurable and grouped products - if the simple product has a customer price, the parent product will
      have the same price
* Bugs fixed:
    * Fixed the issue when the customer price is more than the product price for the search engines
    * Fixed all phpcs/phpstan
* GitHub issues:
    * [#3](https://github.com/epuzzle/magento2-customer-price/issues/3) - Different logics to apply the customer prices
