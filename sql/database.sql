

PRAGMA foreign_keys = ON;
.headers on
.mode columns

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Friend;
DROP TABLE IF EXISTS Host;
DROP TABLE IF EXISTS Guest;
DROP TABLE IF EXISTS Accommodation;
DROP TABLE IF EXISTS Ad;
DROP TABLE IF EXISTS Reservation;
DROP TABLE IF EXISTS Transportation_type;
DROP TABLE IF EXISTS City;
DROP TABLE IF EXISTS Point_of_interest;
DROP TABLE IF EXISTS Budget;


CREATE TABLE User(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT NOT NULL,
    city INTEGER NOT NULL REFERENCES City,
    phone_number INTEGER NOT NULL
);

CREATE TABLE Friend(
    user1_id INTEGER PRIMARY KEY REFERENCES User,
    user2_id INTEGER PRIMARY KEY REFERENCES User,
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
    host_ac INTEGER NOT NULL REFERENCES User,
    address TEXT NOT NULL,
    city INTEGER NOT NULL REFERENCES City,
    capacity INTEGER NOT NULL,
    CHECK (capacity>0)
);

CREATE TABLE Ad(
    ad_id INTEGER PRIMARY KEY AUTOINCREMENT,
    host_ad INTEGER NOT NULL REFERENCES User,
    city INTEGER NOT NULL REFERENCES City,
    date_on TEXT NOT NULL,
    date_off TEXT NOT NULL,
    descrip TEXT,
    priv_publ BOOLEAN NOT NULL,
    accomodation INTEGER NOT NULL REFERENCES Accommodation,
    CHECK (strftime('%s',date_off) > strftime('%s',date_on)),
    CHECK (host_ad == host_ac)
);

CREATE TABLE Reservation(
    reservation_id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_in TEXT NOT NULL,
    date_out TEXT NOT NULL,
    transportation_type TEXT NOT NULL REFERENCES Transportation_type,
    guest INTEGER NOT NULL REFERENCES User,
    number_of_guests INTEGER NOT NULL,
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
    average_cost_of_living INTEGER NOT NULL,
    lat INTEGER NOT NULL,
    lon INTEGER NOT NULL,
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


