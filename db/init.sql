create table add_services
(
	id int auto_increment
		primary key,
	tourid int null,
	title varchar(150) not null,
	price int null,
	description varchar(100) null,
	price2 int null,
	foto varchar(30) null,
	foto2 varchar(30) null,
	foto1 varchar(50) null,
	price1 int null,
	price3 int null,
	price4 int null,
	price5 int null,
	type int not null,
	placeid int null,
	category int null,
	confdesc varchar(8000) null,
	visible int default '1' not null,
	stype int null
)
;

create table add_u_reserves
(
	id int auto_increment
		primary key,
	reserve_id int null,
	service_id int not null,
	value int not null,
	orderid int null,
	config varchar(8000) null,
	conf_startdate date null,
	conf_length int null,
	conf_comment varchar(500) null
)
;

create table bills
(
	id int auto_increment
		primary key,
	sum int not null,
	uid int not null,
	comment varchar(500) null,
	phone varchar(50) null,
	email varchar(50) null,
	rid int null,
	orderid int null,
	status int null,
	dat datetime null
)
;

create index orderkey_idx
	on bills (orderid)
;

create table categories
(
	id int auto_increment
		primary key,
	name varchar(45) null,
	surl varchar(45) null,
	type int null,
	visible int null,
	maincat int default '0' null,
	stext varchar(20) not null,
	visible_tours int null
)
;

create table category_tree
(
	id int auto_increment
		primary key,
	cid int not null,
	subcatid int not null,
	dir int not null
)
;

create table clients
(
	id int auto_increment
		primary key,
	name varchar(500) null,
	phone varchar(20) null,
	pwd varchar(20) null,
	prepay int null,
	email varchar(100) null,
	lastenterdate datetime null,
	invsent int null,
	type int null,
	regdate datetime null,
	logemail varchar(50) null,
	bulknew int null,
	siteid int default '0' null,
	eluser int null,
	regdate2 timestamp default CURRENT_TIMESTAMP null,
	constraint clients_eluser_uindex
		unique (eluser)
)
;

create table countries
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	flagurl varchar(50) not null
)
;

create table ctypes
(
	id int auto_increment
		primary key,
	tourid int not null,
	name varchar(50) not null
)
;

create table dates
(
	tourid int not null,
	date date not null,
	id int auto_increment
		primary key,
	comment varchar(2000) not null,
	realmaxlimit int default '0' not null,
	showplaces int default '0' not null,
	limitperagency int default '0' not null,
	gid int null
)
;

create table delete_history
(
	id int auto_increment
		primary key,
	type int not null,
	who int not null,
	deletedid int not null,
	date datetime not null
)
;

create table dlog
(
	id int auto_increment
		primary key,
	logvalue varchar(200) not null,
	date1 timestamp default CURRENT_TIMESTAMP not null
)
;

create table el_offer
(
	id int auto_increment
		primary key,
	qtext longtext null,
	phone varchar(45) null,
	dat timestamp default CURRENT_TIMESTAMP null
)
;

create table emails
(
	id int auto_increment
		primary key,
	email varchar(100) not null
)
;

create table event_days
(
	id int auto_increment
		primary key,
	event_name varchar(45) null,
	day_old int null,
	month_old int null,
	day_new int null,
	month_new int null,
	dateforyear varchar(45) null,
	event_description varchar(45) null
)
;

create table event_links
(
	id int auto_increment
		primary key,
	sourceid int null,
	linkedid int null
)
;

create table hotels_schedule
(
	id int auto_increment
		primary key,
	hotelid int null,
	date date null,
	status int null,
	orderid int null
)
;

create table main_users
(
	user_id int auto_increment
		primary key,
	user_login varchar(30) not null,
	user_password varchar(32) not null,
	user_hash varchar(32) not null,
	user_ip int(10) unsigned default '0' not null,
	company varchar(500) not null,
	comment varchar(2000) not null,
	type int not null,
	md5 varchar(32) not null
)
;

create table my_collection
(
	doc json null,
	`_id` varchar(32) not null
		primary key
)
;

create table new_table
(
	id int auto_increment
		primary key,
	sitename varchar(45) null
)
;

create table orders
(
	id int auto_increment
		primary key,
	uid int not null,
	num int null,
	dtime timestamp default CURRENT_TIMESTAMP null,
	config varchar(4000) null,
	dealerid int default '1' not null,
	status int null,
	deleted int null,
	dateid int null,
	phone varchar(50) null,
	hid int null,
	sdate date null,
	edate date null,
	sum int null,
	prepaysum int null,
	psum int null,
	elid int null,
	elname varchar(100) null
)
;

alter table bills
	add constraint orderkey
		foreign key (orderid) references orders (id)
;

create table payments
(
	id int auto_increment
		primary key,
	userid int not null,
	sum int not null,
	status int null,
	billid int null,
	orderid int null,
	comment varchar(100) null,
	constraint bilusers
		foreign key (userid) references clients (id),
	constraint billlinj
		foreign key (billid) references payments (id)
)
;

create index billlinj_idx
	on payments (billid)
;

create index bilusers_idx
	on payments (userid)
;

create table photos
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	width int null,
	height int null,
	tid int null,
	asid int null,
	sorder int null,
	comment varchar(500) null,
	pid int null,
	gal int null
)
;

create index ks2
	on photos (id, name)
;

create table place_links
(
	id int auto_increment
		primary key,
	sourceid int not null,
	linkedid int not null
)
;

create table places
(
	id int auto_increment
		primary key,
	name varchar(100) not null,
	descr text null,
	photodescr varchar(500) null,
	type int not null,
	datedescr varchar(500) null,
	lat double null,
	lon double null,
	mainphoto varchar(500) null,
	address varchar(500) null,
	visible int null,
	mainvisible int null,
	rating int null,
	url varchar(45) null,
	country int default '1' not null,
	siteid int default '2' not null,
	cityid int default '0' null,
	date1 date null,
	showtop int null,
	main_descr varchar(50) default '' null,
	elitsy_url varchar(50) null,
	tname varchar(150) null,
	d_author varchar(50) null
)
;

create index `FullText`
	on places (name)
;

create index id_index
	on places (id)
;

create index ks
	on places (id, type)
;

create table places_types
(
	type int not null
		primary key,
	surl varchar(45) null
)
;

create table reserves
(
	id int auto_increment
		primary key,
	dealerid int not null,
	tourid int not null,
	client_name varchar(100) null,
	client_phones varchar(100) null,
	client_comment varchar(500) null,
	num int not null,
	price int not null,
	turdate int not null,
	reservedate date not null
)
;

create table retrieve_requests
(
	id int auto_increment
		primary key,
	line varchar(50) not null,
	detectedid int not null
)
;

create table saints
(
	id int auto_increment
		primary key,
	title varchar(45) null,
	description varchar(4000) null,
	visible int null
)
;

create table seo
(
	id int auto_increment
		primary key,
	url varchar(50) not null,
	title varchar(50) not null,
	description varchar(2000) not null,
	metakeywords varchar(500) not null,
	metadescription varchar(500) not null,
	siteid int not null,
	seotext varchar(2000) null
)
;

create table sites
(
	id int auto_increment
		primary key,
	name varchar(20) not null,
	prefix varchar(20) not null,
	baseurl varchar(50) not null
)
;

create table smshistory
(
	id int auto_increment
		primary key,
	phones varchar(50) not null,
	smstext varchar(500) not null,
	date datetime not null,
	status varchar(500) not null,
	bstatus int not null
)
;

create table stops
(
	id int auto_increment
		primary key,
	tid int not null
)
;

create table tmp_node
(
	user_id int(10) unsigned default '0' not null,
	max_id int(10) unsigned null,
	constraint user_id
		unique (user_id)
)
;

create table tmp_user
(
	city_id int(10) unsigned default '0' not null,
	city_name varchar(64) not null,
	offset int null,
	points_scheme int null,
	user_id int default '0' not null,
	account_id int default '0' not null
)
;

create index idx_1
	on tmp_user (user_id)
;

create table tour_limits
(
	id int auto_increment
		primary key,
	aid int not null,
	`limit` int not null
)
;

create table tour_types
(
	id int auto_increment
		primary key,
	name varchar(50) not null
)
;

create table tour_variants
(
	id int auto_increment
		primary key,
	variant_descr varchar(200) not null,
	variant_exclude varchar(200) not null,
	price int not null,
	foto1 varchar(100) not null,
	foto2 varchar(100) not null,
	foto3 varchar(100) not null,
	foto4 varchar(100) not null
)
;

create table tourlimits
(
	id int auto_increment
		primary key,
	siteid int not null,
	dateid int not null,
	value int not null
)
;

create table tourmain
(
	id int auto_increment
		primary key,
	siteid int not null,
	locid int not null,
	tourid int null,
	sorder int null,
	dateid int not null,
	text1 varchar(20) null,
	text2 varchar(20) null,
	text3 varchar(30) null,
	picurl varchar(50) null,
	url varchar(45) default '/' null
)
;

create table tours
(
	id smallint auto_increment
		primary key,
	title varchar(150) null,
	description varchar(8000) null,
	blength int null,
	baseprice int null,
	include varchar(500) null,
	exclude varchar(500) null,
	mainfoto varchar(200) null,
	main_descr varchar(400) null,
	nights int null,
	program varchar(8000) null,
	visible int null,
	type int null,
	tcat int default '0' null,
	price1 int null,
	price2 int null,
	price3 int null,
	price4 int null,
	price5 int null,
	foto1 varchar(50) null,
	foto2 varchar(50) null,
	foto3 varchar(50) null,
	owner int null,
	confdesc varchar(2000) null,
	surl varchar(45) null,
	startcity int null,
	country int default '1' null,
	organizator int null,
	sync_date date null,
	video_tmp varchar(50) null
)
;

create index visible
	on tours (visible)
;

create table tours_places
(
	id int auto_increment
		primary key,
	tourid int not null,
	placeid int not null,
	sorder int default '0' null,
	surl varchar(45) null
)
;

create table u_reserves
(
	id int auto_increment
		primary key,
	fio varchar(100) null,
	phone varchar(100) null,
	comment varchar(2000) null,
	num int null,
	price int null,
	turdate int null,
	reservedate datetime not null,
	turid int null,
	codes varchar(2000) null,
	status int null,
	dealerid int null,
	sourcesyst varchar(50) null,
	uid int null,
	email varchar(500) null,
	deleted int null,
	checkedin int null,
	payed int null,
	deletereason int null,
	passport varchar(50) null,
	orderid int null,
	ctype int null,
	config varchar(8000) null
)
;

create index idx_u_reserves_turdate_turid_orderid_ctype
	on u_reserves (turdate, turid, orderid, ctype)
;

create table view_main
(
	id int auto_increment
		primary key,
	siteid int not null,
	title varchar(50) not null,
	sorder int not null
)
;

create view add_reserve_data as
CREATE VIEW add_reserve_data AS
  SELECT
    `au`.`reserve_id` AS `reserve_id`,
    `au`.`value`      AS `value`,
    `a_s`.`title`     AS `title`
  FROM (`elitsy`.`add_u_reserves` `au`
    JOIN `elitsy`.`add_services` `a_s`)
  WHERE (`au`.`service_id` = `a_s`.`id`);
;

create view add_u_services_price as
CREATE VIEW add_u_services_price AS
  SELECT
    `elitsy`.`add_u_reserves`.`reserve_id` AS `reserve_id`,
    sum(`elitsy`.`add_services`.`price`)   AS `sum`
  FROM (`elitsy`.`add_u_reserves`
    JOIN `elitsy`.`add_services`)
  WHERE (`elitsy`.`add_u_reserves`.`service_id` = `elitsy`.`add_services`.`id`)
  GROUP BY `elitsy`.`add_u_reserves`.`reserve_id`;
;

create view alltours_3 as
CREATE VIEW alltours_3 AS
  SELECT
    `elitsy`.`alltours_months`.`id`          AS `id`,
    `elitsy`.`alltours_months`.`title`       AS `title`,
    `elitsy`.`alltours_months`.`description` AS `description`,
    `elitsy`.`alltours_months`.`blength`     AS `blength`,
    `elitsy`.`alltours_months`.`baseprice`   AS `baseprice`,
    `elitsy`.`alltours_months`.`include`     AS `include`,
    `elitsy`.`alltours_months`.`exclude`     AS `exclude`,
    `elitsy`.`alltours_months`.`mainfoto`    AS `mainfoto`,
    `elitsy`.`alltours_months`.`main_descr`  AS `main_descr`,
    `elitsy`.`alltours_months`.`nights`      AS `nights`,
    `elitsy`.`alltours_months`.`program`     AS `program`,
    `elitsy`.`alltours_months`.`visible`     AS `visible`,
    `elitsy`.`alltours_months`.`type`        AS `type`,
    `elitsy`.`alltours_months`.`month`       AS `month`
  FROM `elitsy`.`alltours_months`
  WHERE (`elitsy`.`alltours_months`.`month` IN (month(now()), (month(now()) + 1), (month(now()) + 2)));
;

create view alltours_months as
CREATE VIEW alltours_months AS
  SELECT
    `elitsy`.`tours`.`id`          AS `id`,
    `elitsy`.`tours`.`title`       AS `title`,
    `elitsy`.`tours`.`description` AS `description`,
    `elitsy`.`tours`.`blength`     AS `blength`,
    `elitsy`.`tours`.`baseprice`   AS `baseprice`,
    `elitsy`.`tours`.`include`     AS `include`,
    `elitsy`.`tours`.`exclude`     AS `exclude`,
    `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
    `elitsy`.`tours`.`main_descr`  AS `main_descr`,
    `elitsy`.`tours`.`nights`      AS `nights`,
    `elitsy`.`tours`.`program`     AS `program`,
    `elitsy`.`tours`.`visible`     AS `visible`,
    `elitsy`.`tours`.`type`        AS `type`,
    1                              AS `month`
  FROM `elitsy`.`tours`
  WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                  FROM `elitsy`.`dates`
                                  WHERE (month(`elitsy`.`dates`.`date`) = 1))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          2                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 2))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          3                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 3))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          4                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 4))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          5                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 5))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          6                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 6))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          7                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 7))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          8                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 8))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          9                              AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 9))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          10                             AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 10))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          11                             AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 11))
  UNION SELECT
          `elitsy`.`tours`.`id`          AS `id`,
          `elitsy`.`tours`.`title`       AS `title`,
          `elitsy`.`tours`.`description` AS `description`,
          `elitsy`.`tours`.`blength`     AS `blength`,
          `elitsy`.`tours`.`baseprice`   AS `baseprice`,
          `elitsy`.`tours`.`include`     AS `include`,
          `elitsy`.`tours`.`exclude`     AS `exclude`,
          `elitsy`.`tours`.`mainfoto`    AS `mainfoto`,
          `elitsy`.`tours`.`main_descr`  AS `main_descr`,
          `elitsy`.`tours`.`nights`      AS `nights`,
          `elitsy`.`tours`.`program`     AS `program`,
          `elitsy`.`tours`.`visible`     AS `visible`,
          `elitsy`.`tours`.`type`        AS `type`,
          12                             AS `month`
        FROM `elitsy`.`tours`
        WHERE `elitsy`.`tours`.`id` IN (SELECT `elitsy`.`dates`.`tourid`
                                        FROM `elitsy`.`dates`
                                        WHERE (month(`elitsy`.`dates`.`date`) = 12));
;

create view latest_a_cat as
CREATE VIEW latest_a_cat AS
  SELECT
    `lt`.`tourid` AS `tourid`,
    `lt`.`date`   AS `date`,
    `lt`.`dat`    AS `dat`,
    `tr`.`type`   AS `type`,
    `tr`.`title`  AS `title`
  FROM (`elitsy`.`latesttours` `lt`
    JOIN `elitsy`.`tours` `tr`)
  WHERE (`tr`.`id` = `lt`.`tourid`);
;

create view latesttours as
CREATE VIEW latesttours AS
  SELECT
    `elitsy`.`dates`.`tourid`                                AS `tourid`,
    `elitsy`.`dates`.`date`                                  AS `date`,
    abs((to_days(now()) - to_days(`elitsy`.`dates`.`date`))) AS `dat`
  FROM `elitsy`.`dates`
  ORDER BY abs((to_days(now()) - to_days(`elitsy`.`dates`.`date`)));
;

create view my_reserves as
CREATE VIEW my_reserves AS
  SELECT
    `ur`.`id`               AS `id`,
    `ur`.`uid`              AS `uid`,
    `ur`.`ctype`            AS `ctype`,
    `ur`.`phone`            AS `phone`,
    `ur`.`status`           AS `status`,
    `ur`.`fio`              AS `fio`,
    `ur`.`price`            AS `price`,
    `ur`.`turid`            AS `turid`,
    `ur`.`turdate`          AS `turdate`,
    `tr`.`title`            AS `title`,
    `tr`.`main_descr`       AS `main_descr`,
    `tr`.`mainfoto`         AS `mainfoto`,
    `dt`.`date`             AS `date`,
    month(`dt`.`date`)      AS `month`,
    dayofmonth(`dt`.`date`) AS `day`,
    year(`dt`.`date`)       AS `year`,
    `ur`.`reservedate`      AS `reservedate`,
    `ur`.`deleted`          AS `deleted`,
    `ur`.`payed`            AS `payed`,
    `ur`.`orderid`          AS `orderid`,
    `ur`.`sourcesyst`       AS `sourcesyst`,
    `ur`.`email`            AS `email`
  FROM ((`elitsy`.`u_reserves` `ur`
    JOIN `elitsy`.`tours` `tr`) JOIN `elitsy`.`dates` `dt`)
  WHERE ((`dt`.`id` = `ur`.`turdate`) AND (`tr`.`id` = `ur`.`turid`));
;

create view reserved as
CREATE VIEW reserved AS
  SELECT
    count(`elitsy`.`u_reserves`.`id`) AS `num`,
    `elitsy`.`u_reserves`.`turdate`   AS `turdateid`
  FROM `elitsy`.`u_reserves`
  WHERE ((`elitsy`.`u_reserves`.`status` IN (0, 2, 4)) AND (`elitsy`.`u_reserves`.`deleted` <> 1))
  GROUP BY `elitsy`.`u_reserves`.`turdate`;
;

create view top3 as
CREATE VIEW top3 AS
  SELECT DISTINCT `elitsy`.`dates`.`tourid` AS `tourid`
  FROM `elitsy`.`dates`
  WHERE ((`elitsy`.`dates`.`date` > now()) AND `elitsy`.`dates`.`tourid` IN (SELECT `elitsy`.`tours`.`id`
                                                                             FROM `elitsy`.`tours`
                                                                             WHERE ((`elitsy`.`tours`.`visible` = 1) AND
                                                                                    (`elitsy`.`tours`.`type` IN
                                                                                     (1, 2)))))
  ORDER BY `elitsy`.`dates`.`date`
  LIMIT 3;
;

create view u_reserves_prices as
CREATE VIEW u_reserves_prices AS
  SELECT
    `urs`.`id`          AS `id`,
    `urs`.`phone`       AS `phone`,
    `urs`.`fio`         AS `fio`,
    `trs`.`title`       AS `title`,
    `trs`.`mainfoto`    AS `mainfoto`,
    `trs`.`main_descr`  AS `main_descr`,
    `urs`.`passport`    AS `passport`,
    `urs`.`turid`       AS `turid`,
    `urs`.`turdate`     AS `turdate`,
    `trs`.`baseprice`   AS `baseprice`,
    `trs`.`price1`      AS `price1`,
    `trs`.`price2`      AS `price2`,
    `trs`.`price3`      AS `price3`,
    `trs`.`price4`      AS `price4`,
    `trs`.`price5`      AS `price5`,
    `urs`.`reservedate` AS `reservedate`,
    `urs`.`dealerid`    AS `dealerid`
  FROM (`elitsy`.`u_reserves` `urs`
    JOIN `elitsy`.`tours` `trs`)
  WHERE ((`urs`.`turid` = `trs`.`id`) AND (`urs`.`deleted` <> 1));
;

create function `_fs_transliterate_ru` (str text) returns text
CREATE FUNCTION `_fs_transliterate_ru`(str TEXT)
  RETURNS TEXT
  BEGIN
  DECLARE strlow TEXT;
  DECLARE sub VARCHAR(3);
  DECLARE res TEXT;
  DECLARE len INT(11);
  DECLARE i INT(11);
  DECLARE pos INT(11);
  DECLARE alphabeth CHAR(34);

  SET i = 0;
  SET res = '';
  SET strlow = LOWER(str);
  SET len = char_LENGTH(str);
  SET alphabeth = ' абвгдеёжзийклмнопрстуфхцчшщъыьэюя';

  /* идем циклом по символам строки */

  WHILE i < len DO

  SET i = i + 1;
  SET pos = INSTR(alphabeth, SUBSTR(strlow,i,1));

  /*выполняем преобразование припомощи ф-ии ELT */

  SET sub = elt(pos, '-',
  'a','b','v','g', 'd', 'e', 'e','zh', 'z',
  'i','j','k','l', 'm', 'n', 'o', 'p', 'r',
  's','t','u','f', 'h', 'c','ch','sh','sch',
  '', 'y', '','e','ju','ja');

  IF sub IS NOT NULL THEN
    SET res = CONCAT(res, sub);
    END IF;

  END WHILE;

  RETURN res;
END;
;

create function getTourPrice (tourid int) returns int
CREATE FUNCTION getTourPrice(tourid INT)
  RETURNS INT
  BEGIN
declare res int;
set res=(select baseprice from tours where id=tourid);
RETURN res;
END;
;

create function month_name (month_num int) returns varchar(200)
CREATE FUNCTION month_name(month_num INT)
  RETURNS VARCHAR(200)
  BEGIN
declare res varchar(20);

case month_num
when 1  then set res='января' ;
when 2  then set res='февраля' ;
when 3  then set res='марта' ;
when 4  then set res='апреля' ;
when 5  then set res='мая' ;
when 6  then set res='июня' ;
when 7  then set res='июля' ;
when 8  then set res='августа' ;
when 9  then set res='сентября' ;
when 10  then set res='октября' ;
when 11  then set res='ноября' ;
when 12  then set res='декабря' ;
  ELSE set res='Нечто другое';
end case;

return res;
end;
;

create function tour_dates (tourid int, month int) returns varchar(2000)
CREATE FUNCTION tour_dates(tourid INT, month INT)
  RETURNS VARCHAR(2000)
  BEGIN
declare did int default 0;
declare longdate varchar(2000) default "";
declare curline varchar(100) default "";
declare done int default 0;

declare cur_dates cursor for select id, concat("<a href='/tours/",tourid,"/",dt.id, "'>",day(dt.date)," ",month_name(month(dt.date)),"</a>") from dates dt where ((dt.tourid=tourid) and (month(dt.date)=month)) order by dt.date;

DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;
insert into dlog(logvalue) values(tourid);
insert into dlog(logvalue) values(month);
Open cur_dates;
WHILE done = 0 DO
set did=0;
set curline="";
 FETCH cur_dates INTO did, curline;
insert into dlog(logvalue) values(done);
insert into dlog(logvalue) values(did);
insert into dlog(logvalue) values(curline);
insert into dlog(logvalue) values(longdate);

set longdate=concat(longdate, curline);

END WHILE;
close cur_dates;

return longdate;
END;
;

