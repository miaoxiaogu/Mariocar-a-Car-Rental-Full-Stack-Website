drop database if exists here_we_go;
create database here_we_go;
use here_we_go;

CREATE TABLE SYSTEM_INFO( RECORD_ID INTEGER NOT NULL,
                          MEMBERSHIP_FEE INTEGER NOT NULL,
                          TIMEPOINT_ONE INTEGER NOT NULL ,
                          TIMEPOINT_TWO INTEGER NOT NULL,
                          TIME_RANGE_DISCOUNT_ONE FLOAT NOT NULL,
                          TIME_RANGE_DISCOUNT_TWO FLOAT NOT NULL,
                          TIME_RANGE_DISCOUNT_THREE FLOAT NOT NULL,
                          DISCOUNT_OVER_25 FLOAT NOT NULL,
                          PRIMARY KEY (RECORD_ID)
);


CREATE TABLE MANAGER_INFO ( MANAGER_ID INTEGER NOT NULL AUTO_INCREMENT,
                            MANAGER_EMAIL VARCHAR(50) NOT NULL,
                            MANAGER_PASSWORD VARCHAR(100) NOT NULL,
                            PRIMARY KEY (MANAGER_ID)
                            );


CREATE TABLE TYPE_INFO( TYPE_ID INTEGER NOT NULL AUTO_INCREMENT,
                        TYPE_NAME VARCHAR(30) NOT NULL,
                        TYPE_RATE FLOAT,
                        PRIMARY KEY (TYPE_ID),
                        UNIQUE (TYPE_NAME)
                        );



CREATE TABLE USER_INFO(USER_ID INTEGER NOT NULL AUTO_INCREMENT,
                    USER_EMAIL VARCHAR(50) NOT NULL,
                    USER_PASSWORD VARCHAR(100) NOT NULL,
                    MEMBERSHIP_STATUE VARCHAR(30),
                    MEMBER_STARTTIME DATETIME,
                    MEMBER_ENDTIME DATETIME,
                    USER_FIRST_NAME VARCHAR(50) NOT NULL,
                    USER_LAST_NAME VARCHAR(50) NOT NULL,
                    PRIMARY KEY (USER_ID),
                    UNIQUE KEY (USER_EMAIL)

                    );


CREATE TABLE DRIVER_LICENSE (DRIVER_LICENSE_ID INTEGER NOT NULL AUTO_INCREMENT,
                            DRIVER_LICENSE_NUMBER VARCHAR(20) NOT NULL,
                            DRIVER_BIRTHDAY DATE NOT NULL,
                            DRIVER_LICENCE_EXPIREDATE DATE NOT NULL,
                            DRIVER_LICENSE_STATE VARCHAR(5) NOT NULL,
                            ADDRESS_STREET VARCHAR(50) NOT NULL,
                            ADDRESS_CITY VARCHAR(50) NOT NULL,
                            ADDRESS_STATE VARCHAR(5) NOT NULL,
                            ADDRESS_ZIPCODE INTEGER NOT NULL,
                            USER_ID INTEGER,
                            PRIMARY KEY (DRIVER_LICENSE_ID),
                            UNIQUE (DRIVER_LICENSE_NUMBER),
                            FOREIGN KEY (USER_ID) REFERENCES USER_INFO(USER_ID) ON DELETE CASCADE

                            );


CREATE TABLE CREDIT_CARD (CARD_ID INTEGER NOT NULL AUTO_INCREMENT,
                            CARD_NUMBER VARCHAR(50) NOT NULL,
                            CARD_EXPIREDATE DATE NOT NULL,
                            CVI INTEGER NOT NULL,
                            ZIPCODE INTEGER NOT NULL,
                            CARD_BILL_ADDRESS_STREET VARCHAR(80) NOT NULL,
                            CARD_BILL_ADDRESS_CITY VARCHAR(50) NOT NULL,
                            CARD_BILL_ADDRESS_STATE VARCHAR(10) NOT NULL,
                            CARD_BILL_ADDRESS_ZIPCODE INTEGER NOT NULL,
                            USER_ID INTEGER,
                            PRIMARY KEY (CARD_ID),
                            FOREIGN KEY (USER_ID) REFERENCES USER_INFO(USER_ID) ON DELETE CASCADE
                            );



CREATE TABLE PARK_LOCATION ( LOCATION_ID INTEGER NOT NULL AUTO_INCREMENT,
                            LOCATION_STATE VARCHAR(8) NOT NULL,
                            LOCATION_CITY VARCHAR(30) NOT NULL,
                            LOCATION_ZIPCODE INTEGER NOT NULL,
                            LOCATION_STREET VARCHAR(30),
                            LOCATION_CAPACITY INTEGER,
                            LOCATION_NAME VARCHAR(50) NOT NULL,
                            PRIMARY KEY (LOCATION_ID)
);

CREATE TABLE VEHICLE_INFO (VEHICLE_ID INTEGER NOT NULL AUTO_INCREMENT,
                            LICENSE_PLATE VARCHAR(20) NOT NULL,
                            VEHICLE_TYPE VARCHAR(20) NOT NULL,
                            VEHICLE_CONDITION VARCHAR(20) NOT NULL,
                            VEHICLE_BRAND VARCHAR(30) NOT NULL,
                            VEHICLE_MODEL VARCHAR(30) NOT NULL,
                            VEHICLE_CURRENT_MILEAGE integer,
                            VEHICLE_YEAR INTEGER,
                            VEHICLE_LOCATION INTEGER,
                            VEHICLE_REGISTRATION_TAG VARCHAR(50) NOT NULL,
                            LAST_SERVICED_TIME DATE,
                            VEHICLE_STATUS VARCHAR(20),
                            ORDER_ID INTEGER,
                            PRIMARY KEY (VEHICLE_ID),
                            UNIQUE (LICENSE_PLATE,VEHICLE_REGISTRATION_TAG),
                            FOREIGN KEY (VEHICLE_TYPE) REFERENCES TYPE_INFO(TYPE_NAME) ON DELETE CASCADE,
                            FOREIGN KEY (VEHICLE_LOCATION) REFERENCES PARK_LOCATION(LOCATION_ID)ON DELETE CASCADE
);

CREATE TABLE ORDER_INFO (ORDER_ID INTEGER NOT NULL AUTO_INCREMENT,
                    ORDER_CONFIRMED_TIME DATETIME NOT NULL,
                    REGIST_START_TIME DATETIME NOT NULL,
                    REGIST_END_TIME DATETIME NOT NULL,
                    ACTUAL_START_TIME DATETIME,
                    ACTUAL_END_TIME DATETIME,
                    CANCEL_TIME DATETIME,
                    ORDER_USER_ID INTEGER NOT NULL,
                    VEHICLE_LICENSE_PLATE VARCHAR(20) NOT NULL,
                    VEHICLE_PARK_LOCATION INTEGER NOT NULL,
                    PRICE FLOAT,
                    VEHICLE_CONDITION VARCHAR(30),
                    COMMENTS VARCHAR(5000),
                    ORDER_STATUS VARCHAR(30) NOT NULL,
                    PRIMARY KEY (ORDER_ID),
                    FOREIGN KEY (ORDER_USER_ID) REFERENCES USER_INFO(USER_ID) ON DELETE CASCADE
);
