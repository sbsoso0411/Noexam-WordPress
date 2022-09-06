## CHANGELOG

### v1.2.0 (2016-07-24)

**Features and Improvements**

* Support for decimal deal values [#15](https://github.com/basecrm/basecrm-php/pull/15)
* Deal values are now Floats [#15](https://github.com/basecrm/basecrm-php/pull/15)

### v1.1.2 (2016-02-26)

**Features and Improvements**

* Properly encode `\DateTime` objects [#8](https://github.com/basecrm/basecrm-php/pull/8)
* Properly encode boolean query parameters [#9](https://github.com/basecrm/basecrm-php/pull/9)
* `verifySSL` configuration option [#10](https://github.com/basecrm/basecrm-php/pull/10)
* All `private` attributes methods are now marked as `protected` [#6](https://github.com/basecrm/basecrm-php/pull/6)
* README updates
  * Usage examples syntax fixes [#5](https://github.com/basecrm/basecrm-php/pull/5)
  * Improve the phpunit command line example [#7](https://github.com/basecrm/basecrm-php/pull/7)

### v1.1.1 (2015-11-03)

**Improvements**

* `\BaseCRM\LeadsService` allows to set `source_id`
* `\BaseCRM\DealsService` allows to set `estimated_close_date`

### v1.1.0 (2015-06-01)

**Features and Improvements**

* `\BaseCRM\HttpClient` methods accept additional `$options` 
* Sync API support
  * New low-level`\BaseCRM\SyncService`
  * High-level `\BaseCRM\Sync` wrapper

### v1.0.0 (2015-04-22)

* Birth!
