drop table every_month_number;

create table every_month_number
(
	item_code varchar(20) not null,
	transaction_date timestamp,
	transaction_value double,
	primary key(item_code, transaction_date)
);
