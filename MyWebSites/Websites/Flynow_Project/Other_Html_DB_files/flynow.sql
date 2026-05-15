create database flynow;
use flynow;

create table passenger (
	passenger_id int not null auto_increment,
	p_name varchar(15),
	p_lastname varchar(15),
	city varchar(15),
	primary key (passenger_id));

create table airline_company (
	air_com_id int not null auto_increment,
	comp_name varchar(40),
	primary key(air_com_id));

create table booking (
	booking_id int auto_increment,
	passenger_id int not null,
	primary key (booking_id),
	foreign key (passenger_id) references passenger(passenger_id));
					
create table flight (
	flight_id int not null auto_increment,
	arrival_date date,
	departure_date date,
	destination_airport varchar(40),
	departure_airport varchar(40),
	business_class int(10),
	economy_seat int(10),
	price_economy float(4),
	price_business float(4),
	air_com_id int,
	passenger_id int,
	primary key (flight_id),
	foreign key(air_com_id) references airline_company(air_com_id),
	foreign key(passenger_id) references passenger(passenger_id));