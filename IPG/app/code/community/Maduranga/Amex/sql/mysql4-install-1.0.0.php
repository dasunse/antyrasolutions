<?php


$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS  {$this->getTable('maduranga_amex_pyament')};
CREATE TABLE {$this->getTable('maduranga_amex_pyament')} (
  `amex_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(10) DEFAULT NULL,
  `status` smallint(6) NOT NULL,
  `payment_data` text,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`amex_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


");




$installer->endSetup(); 