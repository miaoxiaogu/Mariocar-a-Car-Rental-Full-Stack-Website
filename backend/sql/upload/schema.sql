drop database if exists here_we_go;
create database here_we_go;
use here_we_go;


CREATE TABLE TYPE_INFO(TYPE_NAME VARCHAR(30) NOT NULL,
						PRIMARY KEY (TYPE_NAME)
                        );
                        
CREATE TABLE MANAGER_INFO ( MANAGER_EMAIL VARCHAR(50) NOT NULL,
							MANAGER_PASSWORD VARCHAR(100) NOT NULL,
                            PRIMARY KEY (MANAGER_EMAIL)
                            );

CREATE TABLE DRIVER_LICENSE (DRIVER_ID VARCHAR(20) NOT NULL,
							DRIVER_NAME VARCHAR(50) NOT NULL,
                            DRIVER_BIRTHDAY DATE NOT NULL,
                            DRIVER_LICENCE_EXPIREDATE DATE NOT NULL,
                            DRIVER_ADDRESS VARCHAR(80),
                            PRIMARY KEY (DRIVER_ID)
							);
                            
                            
CREATE TABLE USER_INFO (USER_EMAIL VARCHAR(50) NOT NULL,
					USER_PASSWORD VARCHAR(100) NOT NULL,
                    MEMBERSHIP_STATE VARCHAR(30),
                    MEMBER_STARTTIME DATE,
                    MEMBER_ENDTIME DATE,
                    USER_DRIVER_ID VARCHAR(20),
                    MANAGER_EMAIL VARCHAR(50),
                    PRIMARY KEY (USER_EMAIL),
                    FOREIGN KEY (USER_DRIVER_ID) REFERENCES DRIVER_LICENSE(DRIVER_ID),
                    FOREIGN KEY (MANAGER_EMAIL) REFERENCES MANAGER_INFO(MANAGER_EMAIL)
                    );
                    
                    
CREATE TABLE CREDIT_CARD (CARD_NUMBER INTEGER NOT NULL,
							CARD_HOLDER VARCHAR(50) NOT NULL,
                            CARD_EXPIREDATE DATE NOT NULL,
                            CARD_BILL_ADDRESS VARCHAR(80) NOT NULL,
                            CVI INTEGER NOT NULL, 
                            USER_ID VARCHAR(50) NOT NULL,
                            FOREIGN KEY (USER_ID) REFERENCES USER_INFO(USER_EMAIL)
							);

CREATE TABLE PARK_LOCATION ( LOCATION_ID VARCHAR(10) NOT NULL,
							LOCATION_STATE VARCHAR(8) NOT NULL,
                            LOCATION_CITY VARCHAR(30) NOT NULL,
                            LOCATION_ZIPCODE INTEGER NOT NULL,
                            LOCATION_STREET VARCHAR(30),
                            LOCATION_CAPACITY INTEGER,
                            PRIMARY KEY (LOCATION_ID)
);

CREATE TABLE VEHICLE_INFO (LICENSE_PLATE VARCHAR(20) NOT NULL,
							VEHICLE_TYPE VARCHAR(20) NOT NULL,
                            VEHICLE_CONDITION VARCHAR(20) NOT NULL,
                            VEHICLE_BRAND VARCHAR(30) NOT NULL,
                            VEHICLE_MODEL VARCHAR(30) NOT NULL,
                            VEHICLE_CURRENT_MILEAGE integer,
                            VEHICLE_MANAGER VARCHAR(50),
                            VEHICLE_LOCATION VARCHAR(10),
                            PRIMARY KEY (LICENSE_PLATE),
                            FOREIGN KEY (VEHICLE_TYPE) REFERENCES TYPE_INFO(TYPE_NAME),
                            FOREIGN KEY (VEHICLE_MANAGER) REFERENCES MANAGER_INFO(MANAGER_EMAIL),
                            FOREIGN KEY (VEHICLE_LOCATION) REFERENCES PARK_LOCATION(LOCATION_ID)
);

CREATE TABLE ORDER_INFO (ORDER_NUMBER VARCHAR(20)NOT NULL,
					ORDER_STARTTIME TIME NOT NULL,
                    ORDER_ENDTIME TIME NOT NULL,
                    CUSTOMER_EMAIL VARCHAR(50) NOT NULL,
                    VEHICLE_LICENSE_PLATE VARCHAR(20) NOT NULL,
                    VEHICLE_PARK_LOCATION VARCHAR(10) NOT NULL,
                    MANAGEMENT_EMAIL VARCHAR(50) NOT NULL,
                    PRIMARY KEY (ORDER_NUMBER),
                    FOREIGN KEY (CUSTOMER_EMAIL) REFERENCES USER_INFO(USER_EMAIL),
                    FOREIGN KEY (VEHICLE_LICENSE_PLATE) REFERENCES VEHICLE_INFO(LICENSE_PLATE),
                    FOREIGN KEY (VEHICLE_PARK_LOCATION) REFERENCES PARK_LOCATION(LOCATION_ID),
                    FOREIGN KEY (MANAGEMENT_EMAIL) REFERENCES MANAGER_INFO(MANAGER_EMAIL)
);




