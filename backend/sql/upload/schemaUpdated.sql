drop database if exists here_we_go;
create database here_we_go;
use here_we_go;


CREATE TABLE TYPE_INFO( TYPE_ID INTEGER NOT NULL AUTO_INCREMENT,
						TYPE_NAME VARCHAR(30) NOT NULL,
                        TYPE_RATE INTEGER, 
						PRIMARY KEY (TYPE_ID),
                        UNIQUE (TYPE_NAME)
                        );
                        
CREATE TABLE MANAGER_INFO ( MANAGER_ID INTEGER NOT NULL AUTO_INCREMENT,
							MANAGER_EMAIL VARCHAR(50) NOT NULL,
							MANAGER_PASSWORD VARCHAR(100) NOT NULL,
                            MANAGER_FIRST_NAME VARCHAR(50) NOT NULL,
                            MANAGER_LAST_NAME VARCHAR(50) NOT NULL,
                            PRIMARY KEY (MANAGER_ID)
                            );
                            

CREATE TABLE DRIVER_LICENSE (DRIVER_LICENSE_ID INTEGER NOT NULL AUTO_INCREMENT,
							DRIVER_LICENSE_NUMBER VARCHAR(20) NOT NULL,
							DRIVER_FIRST_NAME VARCHAR(50) NOT NULL,
                            DRIVER_LAST_NAME VARCHAR(50) NOT NULL,
                            DRIVER_BIRTHDAY DATE NOT NULL,
                            DRIVER_LICENCE_EXPIREDATE DATE NOT NULL,
                            DRIVER_LICENSE_STATE VARCHAR(5) NOT NULL,
                            PRIMARY KEY (DRIVER_LICENSE_ID),
                            UNIQUE (DRIVER_LICENSE_NUMBER)
							);
                            
CREATE TABLE USER_INFO(USER_ID INTEGER NOT NULL AUTO_INCREMENT,
					USER_EMAIL VARCHAR(50) NOT NULL,
					USER_PASSWORD VARCHAR(100) NOT NULL,
                    FIRST_NAME VARCHAR(20) NOT NULL,
                    LAST_NAME VARCHAR(20) NOT NULL,
                    MEMBERSHIP_STATUE VARCHAR(30),
                    MEMBER_STARTTIME DATE,
                    MEMBER_ENDTIME DATE,
                    USER_DRIVER_ID VARCHAR(20),
                    MANAGER_ID INTEGER,
                    USER_ADDRESS_STREET VARCHAR(50) NOT NULL,
                    USER_ADDRESS_CITY VARCHAR(50) NOT NULL,
                    ADDRESS_STATE VARCHAR(5) NOT NULL,
                    USER_ADDRESS_ZIPCODE INTEGER NOT NULL,
                    EMERGENCY_CONTACT_FIRST_NAME VARCHAR(50),
                    EMERGENCY_CONTACT_LAST_NAME VARCHAR(50),
                    EMERGENCY_PHONE_NUMBER INTEGER(30),
                    PRIMARY KEY (USER_ID),
                    FOREIGN KEY (USER_DRIVER_ID) REFERENCES DRIVER_LICENSE(DRIVER_LICENSE_NUMBER),
                    FOREIGN KEY (MANAGER_ID) REFERENCES MANAGER_INFO(MANAGER_ID)
                    );
                    


                    
CREATE TABLE CREDIT_CARD (CARD_ID INTEGER NOT NULL AUTO_INCREMENT,
							CARD_NUMBER VARCHAR(30) NOT NULL,
							CARD_HOLDER_FIRST_NAME VARCHAR(50) NOT NULL,
                            CARD_HOLDER_LAST_NAME VARCHAR(50) NOT NULL,
                            CARD_EXPIREDATE DATE NOT NULL,
                            CARD_BILL_ADDRESS_STREET VARCHAR(80) NOT NULL,
                            CARD_BILL_ADDRESS_CITY VARCHAR(50) NOT NULL,
                            CARD_BILL_ADDRESS_STATE VARCHAR(10) NOT NULL,
                            CARD_BILL_ADDRESS_ZIPCODE INTEGER NOT NULL,
                            CVI INTEGER NOT NULL, 
                            USER_ID INTEGER NOT NULL,
                            ZIPCODE INTEGER NOT NULL,
                            PRIMARY KEY (CARD_ID),
                            FOREIGN KEY (USER_ID) REFERENCES USER_INFO(USER_ID)
							);

CREATE TABLE PARK_LOCATION ( LOCATION_ID INTEGER NOT NULL AUTO_INCREMENT,
							LOCATION_STATE VARCHAR(8) NOT NULL,
                            LOCATION_CITY VARCHAR(30) NOT NULL,
                            LOCATION_ZIPCODE INTEGER NOT NULL,
                            LOCATION_STREET VARCHAR(30),
                            LOCATION_CAPACITY INTEGER,
                            LOCATION_MANAGER_ID INTEGER NOT NULL,
                            PRIMARY KEY (LOCATION_ID),
                            FOREIGN KEY (LOCATION_MANAGER_ID) REFERENCES MANAGER_INFO(MANAGER_ID)
);

CREATE TABLE VEHICLE_INFO (VEHICLE_ID INTEGER NOT NULL AUTO_INCREMENT,
							LICENSE_PLATE VARCHAR(20) NOT NULL,
							VEHICLE_TYPE VARCHAR(20) NOT NULL,
                            VEHICLE_CONDITION VARCHAR(20) NOT NULL,
                            VEHICLE_BRAND VARCHAR(30) NOT NULL,
                            VEHICLE_MODEL VARCHAR(30) NOT NULL,
                            VEHICLE_CURRENT_MILEAGE integer,
                            VEHICLE_MANAGER INTEGER,
                            VEHICLE_LOCATION INTEGER,
                            PRIMARY KEY (VEHICLE_ID),
                            UNIQUE (LICENSE_PLATE),
                            FOREIGN KEY (VEHICLE_TYPE) REFERENCES TYPE_INFO(TYPE_NAME),
                            FOREIGN KEY (VEHICLE_MANAGER) REFERENCES MANAGER_INFO(MANAGER_ID),
                            FOREIGN KEY (VEHICLE_LOCATION) REFERENCES PARK_LOCATION(LOCATION_ID)
);

CREATE TABLE ORDER_INFO (ORDER_ID INTEGER NOT NULL AUTO_INCREMENT,
					ORDER_STARTTIME DATETIME NOT NULL,
                    ORDER_ENDTIME DATETIME, 
                    ORDER_USER_ID INTEGER NOT NULL,
                    VEHICLE_LICENSE_PLATE VARCHAR(20) NOT NULL,
                    VEHICLE_PARK_LOCATION INTEGER NOT NULL,
                    MANAGER_ID INTEGER NOT NULL,
                    PRIMARY KEY (ORDER_ID),
                    FOREIGN KEY (VEHICLE_LICENSE_PLATE) REFERENCES VEHICLE_INFO(LICENSE_PLATE),
                    FOREIGN KEY (ORDER_USER_ID) REFERENCES USER_INFO(USER_ID),
                    FOREIGN KEY (VEHICLE_PARK_LOCATION) REFERENCES PARK_LOCATION(LOCATION_ID),
                    FOREIGN KEY (MANAGER_ID) REFERENCES MANAGER_INFO(MANAGER_ID)
);


INSERT TYPE_INFO VALUES
(0001,'minivan', 10),
(2,'two-compartment', 15);

INSERT MANAGER_INFO VALUES
(0001,'admintom@gmail.com', 'kittytom', 'Tom', 'Kitty'),
(2,'adminjasper@gmail.com', 'kettyjasper', 'Jasper', 'Kitty');

INSERT DRIVER_LICENSE VALUES
(0001,'XX12345A','Mario', 'Super', '1900-01-01', '2030-01-01','CA'),
(2,'XX54321A','Mary', 'Bloody', '1950-01-01', '2050-01-01','CA');

INSERT USER_INFO VALUES
(0001,'Mario@super.com', 'iammario','FI','LA', 'active', '2020-01-01', '2020-06-30', 'XX12345A', '0001', '123 Zoo Road', 'New York', 'NY', '12045', 'Bill','Gates', '8888888' );

INSERT CREDIT_CARD VALUES
(0001,'421056789111', 'Mario', 'Super', '2030-12-31', '123 Zoo Road', 'New York', 'NY', 12045, 6543, 000001, 12045);

INSERT PARK_LOCATION VALUES
(0001,'CA', 'SAN JOSE', 95111, '1st University Ave', 100, 0001);

INSERT VEHICLE_INFO VALUES
(0001,'SF0001', 'two-compartment', 'Good', 'Tesla', 's', 6688, 0001, 0001);

INSERT ORDER_INFO VALUES
(2,'2020-04-01 10:00:00', '2020-04-01 13:00:00', 000001, 'SF0001', 0001, 0001 );


