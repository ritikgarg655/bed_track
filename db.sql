CREATE TABLE Hospital_details (
	hospital_id INT not null AUTO_INCREMENT,
    hospital_name VARCHAR(255) not null,
    hospital_long VARCHAR(255) not null,
    hospital_lat VARCHAR(255) not null,
    username VARCHAR(255) not null,
    pass VARCHAR(255) not null,
    option_selected INT not null,
    PRIMARY KEY(hospital_id)
);

create table Hospital_option_a(`ip_add` varchar(255) not null,`dbname` varchar(255) not null,`tablename` varchar(255) not null,`fiel_name_tot_bed` varchar(255) not null,`username` VARCHAR(255) not null UNIQUE, `pass` VARCHAR(255) not null,`fiel_name_unoc_bed` varchar(255) not null,`hospital_ref` int not null,FOREIGN KEY (`hospital_ref`) REFERENCES Hospital_details(hospital_id));

CREATE TABLE `bed_trcker`.`option_b` (`tot_bed` INT NOT NULL ,`unocc_bed` INT NOT NULL,`hosp_id` int not null,FOREIGN KEY (hosp_id) REFERENCES Hospital_details(hospital_id)) ENGINE = InnoDB;

CREATE TABLE user_details (
    user_id int not null AUTO_INCREMENT,
	user_name varchar(255) not null UNIQUE,
    email varchar(255) not null UNIQUE,
    pass varchar(255) not null,
	PRIMARY KEY(user_id)
);

CREATE TABLE rating (
	user_id int not null ,
    hospital_id int not null,
    rating int not null,
    CONSTRAINT FOREIGN key (user_id) REFERENCES user_details(user_id) on DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN key (hospital_id) REFERENCES hospital_detail(hospital_id) on DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id,hospital_id)
);
