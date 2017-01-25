drop table users;
CREATE TABLE users (
	user_id integer,
	username varchar(20) NOT NULL,
	password_hash varchar(255) NOT NULL,
	password_salt TIMESTAMP NOT NULL,
	email varchar(20),
	FirstName varchar(20),
	LastName varchar(20),
	PRIMARY KEY (user_id),
	CHECK (user_id>0)
);

drop table bills;
CREATE TABLE bills (
	bill_id integer,
	bill_name varchar(20) NOT NULL,
	amount_paid integer NOT NULL DEFAULT 0,
	amount_owed integer NOT NULL DEFAULT 0,
	bill_paid BOOLEAN DEFAULT FALSE,
	user_id integer NOT NULL,
	group_id integer NOT NULL,
	PRIMARY KEY (bill_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (group_id) REFERENCES groups(group_id),
	CHECK (bill_id>0)
);

drop table groups;
CREATE TABLE groups (
	group_id integer,
	group_name varchar(20) NOT NULL,
	date_created TIMESTAMP NOT NULL,
	user_id integer,
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	PRIMARY KEY (group_id),
	CHECK (group_id>0)
);

drop table UserInGroup;
CREATE TABLE UserInGroup (
	user_id integer NOT NULL,
	group_id integer NOT NULL,
	FOREIGN KEY(user_id) REFERENCES users(user_id),
	FOREIGN KEY(group_id) REFERENCES groups(group_id)
);

drop table UserBill;
CREATE TABLE UserBill (
	user_id integer NOT NULL,
	group_id integer NOT NULL,
	bill_id integer NOT NULL,
	amount_due integer NOT NULL DEFAULT 0,
	FOREIGN KEY(user_id) REFERENCES users(user_id),
	FOREIGN KEY(group_id) REFERENCES groups(group_id),
	FOREIGN KEY(bill_id) REFERENCES bills(bill_id)
);