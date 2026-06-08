-- Auto24 Datenbankschema

CREATE DATABASE IF NOT EXISTS auto24
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE auto24;

-- Nutzer
CREATE TABLE IF NOT EXISTS users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(100) NOT NULL UNIQUE,
    password      VARCHAR(255) NOT NULL,
    is_locked    TINYINT(1)   NOT NULL DEFAULT 0,
    is_admin     TINYINT(1)   NOT NULL DEFAULT 0,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);
 
-- Fahrzeugkatalog (aus items.json)
CREATE TABLE IF NOT EXISTS cars (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    iid             INT          NOT NULL UNIQUE,
    name            VARCHAR(200) NOT NULL,
    beschreibung    TEXT,
    imagepath       TEXT,
    preis           DECIMAL(12,2) NOT NULL,
    kategorie       VARCHAR(50),
    unterkategorie  VARCHAR(50),
    marke           VARCHAR(100),
    modell          VARCHAR(100),
    baujahr         INT,
    kraftstoff      VARCHAR(50),
    kilometerstand  INT,
    leistung_ps     INT,
    antrieb         VARCHAR(100)
);

-- Buchungen
CREATE TABLE IF NOT EXISTS bookings (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    booking_key  VARCHAR(60)   NOT NULL UNIQUE,
    user_id      INT           NOT NULL DEFAULT 0,
    username     VARCHAR(100)  NOT NULL,
    car_id       INT           NOT NULL,
    car_name     VARCHAR(200),
    car_price    DECIMAL(12,2),
    status       ENUM('bestellt','in_bearbeitung','versandt','fertig','storniert','abgelehnt')
                 NOT NULL DEFAULT 'bestellt',
    reason       TEXT,
    created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                               ON UPDATE CURRENT_TIMESTAMP
);

-- Favoriten (dauerhaft für eingeloggte Nutzer)
CREATE TABLE IF NOT EXISTS favorites (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    car_id     INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, car_id)
);

-- Eingesendete Inserate
CREATE TABLE IF NOT EXISTS listings (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    listing_key  VARCHAR(60)   NOT NULL UNIQUE,
    user_id      INT,
    username     VARCHAR(100),
    make         VARCHAR(100),
    model        VARCHAR(100),
    year         VARCHAR(10),
    km           VARCHAR(20),
    fuel         VARCHAR(50),
    gearbox      VARCHAR(50),
    power        VARCHAR(20),
    type         VARCHAR(50),
    cond         VARCHAR(50),
    price        DECIMAL(12,2),
    description  TEXT,
    contact_name VARCHAR(100),
    email        VARCHAR(200),
    phone        VARCHAR(50),
    status       ENUM('eingereicht','genehmigt','abgelehnt') NOT NULL DEFAULT 'eingereicht',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cars (iid, name, beschreibung, imagepath, preis, kategorie, unterkategorie, marke, modell, baujahr, kraftstoff, kilometerstand, leistung_ps, antrieb) VALUES
(1,  'Audi A8L',              'Der Audi A8L ist die Langversion des A8 von Audi. Mit dem S-Line Quattro Antrieb und 510 PS ist er ein echter Sportwagen.',           'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg/1920px-Audi_A8_50_TDI_%28D5%29_%E2%80%93_Frontansicht%2C_24._Dezember_2017%2C_Velbert.jpg', 89900,  'gebrauchtwagen', 'limousine', 'Audi',          'A8L',            2024, 'Benzin', 40000, 510, 'S-Line Quattro'),
(2,  'Audi SQ7',              'Der Audi SQ7 ist ein sportlicher SUV mit Quattro Allradantrieb und 250 PS. Perfekt für Familie und Abenteuer.',                        'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Audi_SQ7_FL_IMG_3595.jpg/1920px-Audi_SQ7_FL_IMG_3595.jpg',                                                                                                                                 75000,  'gebrauchtwagen', 'suv',       'Audi',          'SQ7',            2021, 'Benzin', 28400, 250, 'Quattro'),
(3,  'Mercedes-Benz C 220',   'Die Mercedes-Benz C-Klasse steht für Eleganz und Komfort. Mit S-Tronic Getriebe und 510 PS bietet sie ein sportliches Fahrerlebnis.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Mercedes-Benz_C_220_BlueTEC_Exclusive_%28W_205%29_%E2%80%93_Frontansicht%2C_12._Juli_2014%2C_D%C3%BCsseldorf.jpg/1920px-Mercedes-Benz_C_220_BlueTEC_Exclusive_%28W_205%29_%E2%80%93_Frontansicht%2C_12._Juli_2014%2C_D%C3%BCsseldorf.jpg', 69900, 'gebrauchtwagen', 'limousine', 'Mercedes-Benz', 'C 220',          2024, 'Benzin', 60000, 510, 'S-Tronic'),
(4,  'BMW M3 Competition',    'Der BMW M3 Competition ist der sportlichste M3 aller Zeiten. Mit 510 PS und M xDrive Allradantrieb ist er ein echter Rennwagen.',     'https://upload.wikimedia.org/wikipedia/commons/3/39/2021_BMW_M3_Competition_Automatic_3.0_Front.jpg',                                                                                                                                           94500,  'gebrauchtwagen', 'limousine', 'BMW',           'M3 Competition', 2022, 'Benzin', 18500, 510, 'M xDrive'),
(5,  'Volkswagen Golf GTI',   'Der VW Golf GTI ist der Klassiker unter den Sportkompakten. Sparsam im Verbrauch, stark im Auftritt.',                                'https://upload.wikimedia.org/wikipedia/commons/f/ff/VW_Golf_VIII_GTI_Clubsport.jpg',                                                                                                                                                                     34900,  'gebrauchtwagen', 'kompakt',   'Volkswagen',    'Golf GTI',       2022, 'Benzin', 32000, 245, 'FWD'),
(6,  'Porsche Cayenne GTS',   'Der Porsche Cayenne GTS verbindet sportliche Performance mit Alltagstauglichkeit. V8 Biturbo mit 460 PS.',                            'https://upload.wikimedia.org/wikipedia/commons/a/a9/Porsche_Cayenne_S_%2892A%29_%E2%80%93_Frontansicht%2C_10._Oktober_2011%2C_W%C3%BClfrath.jpg',                                                                                                       118000, 'gebrauchtwagen', 'suv',       'Porsche',       'Cayenne GTS',    2023, 'Benzin',  9800, 460, 'AWD'),
(7,  'Tesla Model 3',         'Der Tesla Model 3 ist das meistverkaufte Elektroauto der Welt. Mit Long Range Batterie bis zu 602 km Reichweite.',                   'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Tesla_Model_3_%282023%29_Autofr%C3%BChling_Ulm_IMG_9282.jpg/960px-Tesla_Model_3_%282023%29_Autofr%C3%BChling_Ulm_IMG_9282.jpg',                                                            42900,  'gebrauchtwagen', 'limousine', 'Tesla',         'Model 3',        2023, 'Elektro',15200, 358, 'AWD'),
(8,  'Mercedes-Benz GLE 53 AMG', 'Der Mercedes-AMG GLE 53 ist ein kraftvoller SUV mit 435 PS und EQ Boost Mild-Hybrid-System.',                                   'https://upload.wikimedia.org/wikipedia/commons/d/d2/Mercedes-AMG_GLE_53_4MATIC_1X7A7342.jpg',                                                                                                                                                          98500,  'gebrauchtwagen', 'suv',       'Mercedes-Benz', 'GLE 53 AMG',     2022, 'Benzin', 24300, 435, '4MATIC'),
(9,  'Audi RS6 Avant',        'Der Audi RS6 Avant ist der ultimative Kombisportwagen. 600 PS, quattro Allrad und Platz für die ganze Familie.',                    'https://upload.wikimedia.org/wikipedia/commons/5/57/Audi_RS6_Avant_C8_IMG_0344.jpg',                                                                                                                                                                      129900, 'gebrauchtwagen', 'kombi',     'Audi',          'RS6 Avant',      2023, 'Benzin', 11200, 600, 'quattro'),
(10, 'BMW X5 xDrive40d',      'Der BMW X5 xDrive40d ist ein luxuriöser SAV mit 340 PS Dieselmotor und intelligentem Allradantrieb.',                               'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/BMW_G05_IMG_4073.jpg/960px-BMW_G05_IMG_4073.jpg',                                                                                                                                               79900,  'gebrauchtwagen', 'suv',       'BMW',           'X5 xDrive40d',   2022, 'Diesel', 38700, 340, 'xDrive'),
(11, 'Mercedes-Benz E 400',   'Die Mercedes-Benz E-Klasse ist die Ikone der Mittelklasse. Mit 333 PS und 4MATIC Allradantrieb bietet sie Komfort auf höchstem Niveau.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/2019_Mercedes-Benz_E220d_SE_Automatic_2.0_Front.jpg/1920px-2019_Mercedes-Benz_E220d_SE_Automatic_2.0_Front.jpg',                                                                        58900,  'gebrauchtwagen', 'limousine', 'Mercedes-Benz', 'E 400',          2021, 'Benzin', 45000, 333, '4MATIC'),
(12, 'Volkswagen Tiguan R',   'Der VW Tiguan R ist der sportlichste Tiguan aller Zeiten. Mit 320 PS und 4MOTION Allradantrieb macht er jeden Ausflug zum Erlebnis.','https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Volkswagen_Tiguan_R_1X7A0362.jpg/1920px-Volkswagen_Tiguan_R_1X7A0362.jpg',                                                                                                                     52400,  'gebrauchtwagen', 'suv',       'Volkswagen',    'Tiguan R',       2022, 'Benzin', 21000, 320, '4MOTION');
