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
DROP TABLE IF EXISTS Climate;


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
    user1_name TEXT REFERENCES User(username),
    user2_name TEXT REFERENCES User(username),
    CHECK(user1_name <> user2_name)
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
    CHECK (capacity>0)
);

CREATE TABLE Ad(
    ad_id INTEGER PRIMARY KEY AUTOINCREMENT,
    host_ad TEXT NOT NULL REFERENCES User,
    city INTEGER NOT NULL REFERENCES City,
    date_on DATE NOT NULL,
    date_off DATE NOT NULL,
    descrip TEXT,
    priv_publ BOOLEAN NOT NULL,
    accommodation INTEGER NOT NULL REFERENCES Accommodation,
    CHECK (strftime('%s',date_off) > strftime('%s',date_on))
);

CREATE TABLE Reservation(
    reservation_id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_in DATE NOT NULL,
    date_out DATE NOT NULL,
    ad_point_id INTEGER REFERENCES Ad,
    transportation_type TEXT NOT NULL REFERENCES Transportation_type,
    guest TEXT NOT NULL REFERENCES User,
    number_of_guests INTEGER NOT NULL,
    host TEXT NOT NULL REFERENCES USER,
    capacity INTEGER NOT NULL REFERENCES Ad(capacity),
    CHECK (strftime('%s',date_out) > strftime('%s',date_in)),
    CHECK (number_of_guests<=capacity)
);

CREATE TABLE Transportation_type(
    transportation_name TEXT PRIMARY KEY,
    cost_per_km INTEGER NOT NULL
);

CREATE TABLE City(
    city_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    meteorology TEXT NOT NULL REFERENCES Climate,
    average_cost_of_living REAL NOT NULL,
    lat REAL NOT NULL,
    lon REAL NOT NULL,
    CHECK (average_cost_of_living>0)
);

CREATE TABLE Point_of_interest(
    point_name TEXT PRIMARY KEY,
    point_category TEXT NOT NULL,
    city INTEGER NOT NULL REFERENCES City
);

/*ver se está certo*/
CREATE TABLE Budget(
    reservation INTEGER PRIMARY KEY REFERENCES Reservation,
    total INTEGER NOT NULL,
    /*type_of_transportation TEXT NOT NULL,*/
    duration INTEGER NOT NULL,
    ad_point_id INTEGER REFERENCES Ad,
    /*cost_of_living INTEGER NOT NULL, 
    cost_per_km INTEGER NOT NULL,*/
    distance INTEGER NOT NULL,
    CHECK (distance>=0),
    CHECK (duration>0),
    CHECK (total>0)
);

CREATE TABLE Climate(
    id_climate TEXT PRIMARY KEY,
    common_name TEXT NOT NULL,
    avg_cold TEXT NOT NULL,
    avg_hot TEXT NOT NULL,
    precipitation TEXT NOT NULL
);

INSERT INTO Transportation_type VALUES ('Car',0.36);
INSERT INTO Transportation_type VALUES ('Plane',0.14);
INSERT INTO Transportation_type VALUES ('Train',0.24);

/* 
Cost of living: https://www.numbeo.com/cost-of-living/ 
Lat and Long: https://latitudelongitude.org/
Meteorologia: https://en.wikipedia.org/wiki/K%C3%B6ppen_climate_classification 
*/

INSERT INTO Climate VALUES ("A","Tropical",">18ºC",">18ºC",">=60mm driest month");
INSERT INTO Climate VALUES ("BWh","Hot Desert","15-25ºC","29-35ºC","<10mm monthly");
INSERT INTO Climate VALUES ("BWk","Cold Desert","<5ºC","20-30ºC","<15mm monthly");
INSERT INTO Climate VALUES ("BSh","Hot semi-arid","15-25ºC","20-30ºC","<30mm monthly");
INSERT INTO Climate VALUES ("BSk","Cold semi-arid","5-10ºC","20-30ºC","<30mm monthly");
INSERT INTO Climate VALUES ("Csa","Mediterranean of hot summer",">0ºC",">22ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Csb","Mediterranean of chill summer",">0ºC","<22ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Csc","Mediterranean of cold summer",">0ºC","<20ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Cwa","Subtropical Humid with monsoon",">0ºC",">22ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Cwb","Subtropical of Altitude",">0ºC","<22ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Cwc","Cold Subptropical of Altitude",">0ºC","<20ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Cfa","Subtropical Humid",">0ºC",">22ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Cfb","Temperate Oceanic",">0ºC","<22ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Cfc","Subpolar Oceanic",">0ºC","<20ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Dsa","Mediterranean influenced continental of hot summer","<0ºC",">22ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Dsb","Mediterranean influenced continental of chill summer","<0ºC","<22ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Dsc","Subartic with dry season","<0ºC","<15ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Dsd","Subartic extremely cold with dry season","<-38ºC","<10ºC",">30mm winter months");
INSERT INTO Climate VALUES ("Dwa","Continental Humid of hot summer with monsoon","<0ºC",">22ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Dwb","Continental Humid of chill summer with monsoon","<0ºC","<22ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Dwc","Subartic with monsoon","<0ºC","<15ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Dwd","Extremely Cold Subartic with monsoon","<0ºC","<10ºC",">30mm summer months");
INSERT INTO Climate VALUES ("Dfa","Continental Humid of hot summer","<0ºC",">22ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Dfb","Continental Humid of chill summer","<0ºC","<22ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Dfc","Subartic without dry season","<0ºC","<15ºC","~30mm monthly");
INSERT INTO Climate VALUES ("Dfd","Subartic extremely cold without dry season","<-38ºC","<10ºC","~30mm monthly");
INSERT INTO Climate VALUES ("ET","Thundra","<-38ºC","-3-10ºC","~30mm monthly");
INSERT INTO Climate VALUES ("EF","Glacial","<-38ºC","<0ºC","~30mm monthly");


INSERT INTO City VALUES (1,'Oporto','Csa',21.25,41.14961,-8.61099);
INSERT INTO City VALUES (2,'Lisbon','Csa',23.71,38.71667,-9.13333);
INSERT INTO City VALUES (3,'Madrid','BSk',24.19,40.4165,-3.70256);
INSERT INTO City VALUES (4,'Barcelona','Csb',25.66,41.38879,2.15899);
INSERT INTO City VALUES (5,'London','Cfb',42.33,51.50853,-0.12574);
INSERT INTO City VALUES (6,'Paris','Cfb',35.90,48.85341,2.3488);
INSERT INTO City VALUES (7,'Rome','Csa',29.09,41.89193,12.51133);
INSERT INTO City VALUES (8,'Milano','Cfa',33.68,45.46427,9.18951);
INSERT INTO City VALUES (9,'Berlin','Cfb',33.57,52.52437,13.41053);
INSERT INTO City VALUES (10,'Frankfurt','Cfb',31.97,50.11552,8.68417);
INSERT INTO City VALUES (11,'Vienna','Cfb',32.64,48.20849,16.37208);
INSERT INTO City VALUES (12,'Amsterdam','Cfb',32.49,52.37403,4.88969);
INSERT INTO City VALUES (13,'Utrecht','Cfb',31.65,52.09083,5.12222);
INSERT INTO City VALUES (14,'Prague','Cfb',31.95,50.08804, 14.42076);
INSERT INTO City VALUES (15,'Brussels','Cfb',32.64,50.85045, 4.34878);

INSERT INTO Point_of_interest VALUES ('Torre dos Clérigos','Monument',1);
INSERT INTO Point_of_interest VALUES ('Ribeira','Place',1);
INSERT INTO Point_of_interest VALUES ('Francesinha','Gastronomy',1);
INSERT INTO Point_of_interest VALUES ('B034','Monument',1);
INSERT INTO Point_of_interest VALUES ('Torre de Belém','Monument',2);
INSERT INTO Point_of_interest VALUES ('Praça do Comércio','Place',2);
INSERT INTO Point_of_interest VALUES ('Pastel de Belém','Gastronomy',2);
INSERT INTO Point_of_interest VALUES ('Puerta de Alcala','Monument',3);
INSERT INTO Point_of_interest VALUES ('Museo del Prado','Place',3);
INSERT INTO Point_of_interest VALUES ('Tapas','Gastronomy',3);
INSERT INTO Point_of_interest VALUES ('La Sagrada Familia','Monument',4);
INSERT INTO Point_of_interest VALUES ('Parque Guell','Place',4);
INSERT INTO Point_of_interest VALUES ('Crema catalana','Gastronomy',4);
INSERT INTO Point_of_interest VALUES ('Big Ben','Monument',5);
INSERT INTO Point_of_interest VALUES ('Madame Tussauds','Place',5);
INSERT INTO Point_of_interest VALUES ('Tea','Gastronomy',5);
INSERT INTO Point_of_interest VALUES ('Eiffel Tower','Monument',6);
INSERT INTO Point_of_interest VALUES ('Musée du Louvre','Place',6);
INSERT INTO Point_of_interest VALUES ('Baguette','Gastronomy',6);
INSERT INTO Point_of_interest VALUES ('Fontana de Trevi','Monument',7);
INSERT INTO Point_of_interest VALUES ('Colosseum','Place',7);
INSERT INTO Point_of_interest VALUES ('Pizza','Gastronomy',7);
INSERT INTO Point_of_interest VALUES ('Catedral de Milano','Monument',8);
INSERT INTO Point_of_interest VALUES ('Galeria Vittorio Emanuele II','Place',8);
INSERT INTO Point_of_interest VALUES ('Risotto alla Milanese','Gastronomy',8);
INSERT INTO Point_of_interest VALUES ('Holocaust Memorial','Monument',9);
INSERT INTO Point_of_interest VALUES ('East Side Gallery','Place',9);
INSERT INTO Point_of_interest VALUES ('Currywurst','Gastronomy',9);
INSERT INTO Point_of_interest VALUES ('Frankfurt Romer','Monument',10);
INSERT INTO Point_of_interest VALUES ('Paulskirche','Place',10);
INSERT INTO Point_of_interest VALUES ('Apfelwein','Gastronomy',10);
INSERT INTO Point_of_interest VALUES ('Amalienbad','Monument',11);
INSERT INTO Point_of_interest VALUES ('Belevedere Palace','Place',11);
INSERT INTO Point_of_interest VALUES ('Sachertorte','Gastronomy',11);
INSERT INTO Point_of_interest VALUES ('Holocaust Memorial Wlak','Monument',12);
INSERT INTO Point_of_interest VALUES ('Red Light District','Place',12);
INSERT INTO Point_of_interest VALUES ('Bitterballen','Gastronomy',12);
INSERT INTO Point_of_interest VALUES ('Dom Tower','Monument',13);
INSERT INTO Point_of_interest VALUES ('Railway Museum','Place',13);
INSERT INTO Point_of_interest VALUES ('Stroopwafel','Gastronomy',13);
INSERT INTO Point_of_interest VALUES ('Orloj','Monument',14);
INSERT INTO Point_of_interest VALUES ('Prague Castel','Place',14);
INSERT INTO Point_of_interest VALUES ('Wiener sausages','Gastronomy',14);
INSERT INTO Point_of_interest VALUES ('Manneken Pis','Monument',15);
INSERT INTO Point_of_interest VALUES ('Atomium','Place',15);
INSERT INTO Point_of_interest VALUES ('Brussels Waffles','Gastronomy',15);


/* INSERT INTO User VALUES ('testing','12345678','up0@fe.up.pt',1);
INSERT INTO User VALUES ('a','a','up1@fe.up.pt',2);

INSERT INTO Accommodation VALUES (1,'testing','rua dfgfds',1,2);
INSERT INTO Accommodation VALUES (2,'a','rua dfgfds',1,2);
INSERT INTO Ad VALUES (1,'testing',1,'2024-1-1','2024-10-1','defdsxa',1,1);
 */