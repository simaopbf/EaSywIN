PRAGMA foreign_keys = ON;
.headers on
.mode columns

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Friend;
/*DROP TABLE IF EXISTS Host;
DROP TABLE IF EXISTS Guest;*/
DROP TABLE IF EXISTS Accommodation;
DROP TABLE IF EXISTS Ad;
DROP TABLE IF EXISTS Reservation;
DROP TABLE IF EXISTS Transportation_type;
DROP TABLE IF EXISTS City;
DROP TABLE IF EXISTS Point_of_interest;
DROP TABLE IF EXISTS Budget;


CREATE TABLE User(
    /*id INTEGER PRIMARY KEY AUTOINCREMENT,*/
    username TEXT PRIMARY KEY,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    -- city INTEGER NOT NULL REFERENCES City,
    phone_number INTEGER NOT NULL
    
);

CREATE TABLE Friend(
    connection_id INTEGER PRIMARY KEY,
    user1_id INTEGER REFERENCES User(id),
    user2_id INTEGER REFERENCES User(id),
    CHECK(user1_id <> user2_id)
);

/*tirar host e guest
CREATE TABLE Host(
    id INTEGER PRIMARY KEY REFERENCES User
);

CREATE TABLE Guest(
    id INTEGER PRIMARY KEY REFERENCES User
);
*/
CREATE TABLE Accommodation(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    host_ac TEXT NOT NULL REFERENCES User,
    address TEXT NOT NULL,
    city INTEGER NOT NULL REFERENCES City,
    capacity INTEGER NOT NULL,
    image TEXT UNIQUE,
    CHECK (capacity>0)
);

CREATE TABLE Ad(
    ad_id INTEGER PRIMARY KEY AUTOINCREMENT,
    host_ad TEXT NOT NULL REFERENCES User,
    city INTEGER NOT NULL REFERENCES City,
    date_on TEXT NOT NULL,
    date_off TEXT NOT NULL,
    descrip TEXT,
    priv_publ BOOLEAN NOT NULL,
    accommodation INTEGER NOT NULL REFERENCES Accommodation,
    CHECK (strftime('%s',date_off) > strftime('%s',date_on))
);

CREATE TABLE Reservation(
    reservation_id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_in TEXT NOT NULL,
    date_out TEXT NOT NULL,
    transportation_type TEXT NOT NULL REFERENCES Transportation_type,
    guest TEXT NOT NULL REFERENCES User,
    number_of_guests INTEGER NOT NULL,
    capacity INTEGER NOT NULL REFERENCES Ad(capacity),
    CHECK (strftime('%s',date_out) > strftime('%s',date_in)),
    CHECK (number_of_guests<=capacity)
);

CREATE TABLE Transportation_type(
    transportation_name TEXT PRIMARY KEY,
    cost_per_km INTEGER NOT NULL
);

CREATE TABLE City(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    meteorology TEXT NOT NULL,
    average_cost_of_living REAL NOT NULL,
    lat REAL NOT NULL,
    lon REAL NOT NULL,
    CHECK (average_cost_of_living>0)
);

CREATE TABLE Point_of_interest(
    point_name TEXT PRIMARY KEY,
    ponit_category TEXT NOT NULL,
    city INTEGER NOT NULL REFERENCES City
);

/*ver se está certo*/
CREATE TABLE Budget(
    reservation INTEGER PRIMARY KEY REFERENCES Reservation,
    total INTEGER NOT NULL,
    /*type_of_transportation TEXT NOT NULL,*/
    duration INTEGER NOT NULL,
    /*cost_of_living INTEGER NOT NULL, 
    cost_per_km INTEGER NOT NULL,*/
    distance INTEGER NOT NULL,
    CHECK (distance>0),
    CHECK (duration>0),
    CHECK (total>0)
);

INSERT INTO Transportation_type VALUES ('Car',0.36);
INSERT INTO Transportation_type VALUES ('Plane',0.14);
INSERT INTO Transportation_type VALUES ('Train',0.24);

/* 
Cost of living: https://www.numbeo.com/cost-of-living/ 
Lat and Long: https://latitudelongitude.org/
Definir 5 valores de meteorologia, de acordo com a temperatura média ao longo do ano
*/
INSERT INTO City VALUES (1,'Oporto','sol',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (2,'Lisbon','nevoeiro',23.71,38.71667,-9.13333);
INSERT INTO City VALUES (3,'Madrid','sol',24.19,40.4165,-3.70256);
INSERT INTO City VALUES (4,'Barcelona','sol',25.66,41.38879,2.15899);
INSERT INTO City VALUES (5,'London','nevoeiro',42.33,51.50853,-0.12574);
INSERT INTO City VALUES (6,'Paris','nevoeiro',35.90,48.85341,2.3488);
INSERT INTO City VALUES (7,'Rome','sol',29.09,41.89193,12.51133);
INSERT INTO City VALUES (8,'Milan','sol',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (9,'Berlin','chuva',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (10,'Frankfurt','chuva',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (11,'Vienna','neve',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (12,'Amsterdam','chuva',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (13,'Utrecht','chuva',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (14,'Prague','neve',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (15,'Brussels','escaldar',21.25,41.14961,-8.61099);


INSERT INTO User VALUES ('testing','12345678','up0@fe.up.pt',1);
INSERT INTO User VALUES ('a','a','up1@fe.up.pt',2);

INSERT INTO Accommodation VALUES (1,'testing','rua dfgfds',1,2,'bed');
INSERT INTO Accommodation VALUES (2,'a','rua dfgfds',1,2,'room');
INSERT INTO Ad VALUES (1,'testing',1,'1/1/2024','10/1/2024','defdsxa',1,1);
