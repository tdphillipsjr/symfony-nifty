CREATE TABLE `invoice` (
  `invoice_id`                          bigint(20)                      NOT NULL,
  `invoice_num`                         varchar(100)                    NOT NULL,
  `order_id`                            bigint(20)                      NOT NULL,
  `basket_id`                           bigint(20)                      NOT NULL,
  `invoice_status`                      varchar(255)                    NOT NULL,
  `invoice_date`                        datetime        DEFAULT NULL,
  `user_id`                             bigint(20)                      NOT NULL,
  `invoice_user_first_name`             varchar(100)                    NOT NULL,
  `invoice_user_last_name`              varchar(100)                    NOT NULL,
  `invoice_user_email`                  varchar(100)                    NOT NULL,
  `invoice_amount`                      double                          NOT NULL,
  `invoice_amount_coupon`               double                          NOT NULL,
  `invoice_amount_vat`                  double                          NOT NULL,
  `invoice_amount_shipping`             double                          NOT NULL,
  `invoice_amount_credits`              double                          NOT NULL,
  `invoice_amount_total`                double                          NOT NULL,
  `invoice_products`                    longtext                        NOT NULL,
  `invoice_shipping_method`             varchar(255)    DEFAULT NULL,
  `invoice_shipping_address_first_name` varchar(64)                     NOT NULL,
  `invoice_shipping_address_last_name`  varchar(64)                     NOT NULL,
  `invoice_shipping_address_street`     varchar(255)                    NOT NULL,
  `invoice_shipping_address_city`       varchar(64)                     NOT NULL,
  `invoice_shipping_address_state`      varchar(64)                     NOT NULL,
  `invoice_shipping_address_zipcode`    varchar(20)                     NOT NULL,
  `invoice_shipping_address_country`    varchar(64)                     NOT NULL,
  `invoice_shipping_status`             enum('processing','expedited','track shipment','shipped') DEFAULT NULL,
  `invoice_shipping_tracking_url`       varchar(1024)   DEFAULT NULL,
  `invoice_billing_address_first_name`  varchar(64)                     NOT NULL,
  `invoice_billing_address_last_name`   varchar(64)                     NOT NULL,
  `invoice_billing_address_street`      varchar(255)                    NOT NULL,
  `invoice_billing_address_city`        varchar(64)                     NOT NULL,
  `invoice_billing_address_state`       varchar(64)                     NOT NULL,
  `invoice_billing_address_zipcode`     varchar(20)                     NOT NULL,
  `invoice_billing_address_country`     varchar(64)                     NOT NULL,
  `invoice_user_ip_address`             varchar(255)                    NOT NULL,
  `coupon_id`                           bigint(20)      DEFAULT NULL,
  PRIMARY KEY (`invoice_id`),
  INDEX (`order_id`),
  INDEX (`basket_id`),
  INDEX (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Invoices' ;