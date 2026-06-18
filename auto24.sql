-- Auto24 Datenbankschema - kann einfach in phpmyadmin SQL kopiert werden  

CREATE DATABASE IF NOT EXISTS auto24
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE auto24;

-- Nutzer
CREATE TABLE IF NOT EXISTS users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(100) NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    email        VARCHAR(200) DEFAULT NULL,
    phone        VARCHAR(50)  DEFAULT NULL,
    city         VARCHAR(100) DEFAULT NULL,
    is_locked    TINYINT(1)   NOT NULL DEFAULT 0,
    is_admin     TINYINT(1)   NOT NULL DEFAULT 0,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);
 
-- Fahrzeugliste (statt items.json)
CREATE TABLE IF NOT EXISTS cars (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    iid             INT          NOT NULL UNIQUE,
    name            VARCHAR(200) NOT NULL,
    beschreibung    TEXT,
    imagepath       MEDIUMTEXT,
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
    antrieb      VARCHAR(50),
    type         VARCHAR(50),
    cond         VARCHAR(50),
    price        DECIMAL(12,2),
    description  TEXT,
    contact_name VARCHAR(100),
    email        VARCHAR(200),
    phone        VARCHAR(50),
    images       MEDIUMTEXT,
    status       ENUM('eingereicht','genehmigt','abgelehnt') NOT NULL DEFAULT 'eingereicht',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Dummy Daten KI generiert! (u.a. Claude verwendet)
-- Also Fahrzeugdaten stimmen nicht mit Realität überein SQ7 hat ja nicht 250 ps etc..

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
(12, 'Volkswagen Tiguan R',   'Der VW Tiguan R ist der sportlichste Tiguan aller Zeiten. Mit 320 PS und 4MOTION Allradantrieb macht er jeden Ausflug zum Erlebnis.','https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Volkswagen_Tiguan_R_1X7A0362.jpg/1920px-Volkswagen_Tiguan_R_1X7A0362.jpg',                                                                                                                     52400,  'gebrauchtwagen', 'suv',       'Volkswagen',    'Tiguan R',       2022, 'Benzin', 21000, 320, '4MOTION'),

-- Neuwagen
(13, 'BMW i4 M50',                  'Der BMW i4 M50 ist ein vollelektrischer Gran Coupé mit 544 PS und bis zu 520 km Reichweite. Sportlichkeit trifft auf Nachhaltigkeit.',                                                            'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/BMW_i4_IMG_6695.jpg/960px-BMW_i4_IMG_6695.jpg',                                                                            77900,  'neuwagen', 'limousine',  'BMW',           'i4 M50',                 2025, 'Elektro', 0,     544, 'xDrive'),
(14, 'Mercedes-Benz EQS 450+',      'Die Mercedes-Benz EQS Limousine setzt neue Maßstäbe in der Elektromobilität. 333 PS, 770 km Reichweite und der riesige Hyperscreen – Luxus neu definiert.',                                      'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Mercedes-Benz_V297_IAA_2021_1X7A0251.jpg/960px-Mercedes-Benz_V297_IAA_2021_1X7A0251.jpg',                                                     109900, 'neuwagen', 'limousine',  'Mercedes-Benz', 'EQS 450+',               2025, 'Elektro', 0,     333, 'RWD'),
(15, 'Porsche 911 Carrera GTS',     'Der Porsche 911 Carrera GTS verkörpert den Kern des Sportwagenbaus: 480 PS, Hinterradantrieb und zeitloses Design. Der reinste 911 aller Zeiten.',                                               'https://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Porsche_911_Carrera_4_GTS_%28991%2C_Facelift%29_%E2%80%93_f_28042021.jpg/1920px-Porsche_911_Carrera_4_GTS_%28991%2C_Facelift%29_%E2%80%93_f_28042021.jpg',                                                                                                    167800, 'neuwagen', 'sportwagen', 'Porsche',       '911 Carrera GTS',        2025, 'Benzin',  0,     480, 'RWD'),
(16, 'Volkswagen ID.4 GTX',         'Der VW ID.4 GTX ist das sportliche Topmodell der ID.4-Familie. 299 PS, Allradantrieb und über 500 km Reichweite – emissionsfreier Fahrspaß.',                                                    'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/VW_ID4_1st_Max.jpg/960px-VW_ID4_1st_Max.jpg',                                                                                                    54900,  'neuwagen', 'suv',        'Volkswagen',    'ID.4 GTX',               2025, 'Elektro', 0,     299, 'AWD'),
(17, 'BMW M5 Touring',              'Der neue BMW M5 Touring kombiniert 727 PS Plug-in-Hybrid-Power mit dem Platz eines Kombis. M xDrive, 0–100 km/h in 3,5 Sekunden – die neue Benchmark im Hochleistungssegment.',                  'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/BMW_M5_%28G90%29_MYLE_Festival_2025_DSC_9765.jpg/960px-BMW_M5_%28G90%29_MYLE_Festival_2025_DSC_9765.jpg',                                                                                                                              149500, 'neuwagen', 'kombi',      'BMW',           'M5 Touring',             2025, 'Hybrid',  0,     727, 'xDrive'),
(18, 'Tesla Model Y Long Range',    'Das meistverkaufte Auto Europas. Der Tesla Model Y Long Range bietet 533 km Reichweite, Over-the-Air Updates und Platz für die ganze Familie.',                                                   'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Tesla_Model_Y_1X7A6211.jpg/960px-Tesla_Model_Y_1X7A6211.jpg',                                                                                           47990,  'neuwagen', 'suv',        'Tesla',         'Model Y Long Range',     2025, 'Elektro', 0,     456, 'AWD'),
(19, 'Audi Q8 e-tron 55 quattro',   'Der Audi Q8 e-tron 55 quattro ist der elektrische Flaggschiff-SUV von Audi. 408 PS, quattro Allrad und bis zu 600 km Reichweite.',                                                               'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Audi_e-tron_IMG_3579.jpg/960px-Audi_e-tron_IMG_3579.jpg',                                                                                       84900,  'neuwagen', 'suv',        'Audi',          'Q8 e-tron 55 quattro',   2025, 'Elektro', 0,     408, 'quattro'),

-- Weitere Gebrauchtwagen
(20, 'Skoda Octavia RS Combi',      'Der Skoda Octavia RS Combi vereint Alltagstauglichkeit mit Sportlichkeit. 245 PS, riesiger Kofferraum und hervorragendes Preis-Leistungs-Verhältnis.',                                            'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/%C5%A0koda_Octavia_IV_Combi_Facelift_DSC_6045.jpg/1280px-%C5%A0koda_Octavia_IV_Combi_Facelift_DSC_6045.jpg', 28400, 'gebrauchtwagen', 'kombi',     'Skoda',   'Octavia RS',          2022, 'Benzin',  38000, 245, 'FWD'),
(21, 'Ford Mustang GT Fastback',    'Der Ford Mustang GT Fastback ist eine Ikone der Automobilgeschichte. V8 mit 450 PS, Hinterradantrieb und unvergleichlichem Sound – amerikanische Leidenschaft pur.',                             'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/2024_Ford_Mustang_GT_Las_Vegas_2025_%28cropped%29.jpg/1280px-2024_Ford_Mustang_GT_Las_Vegas_2025_%28cropped%29.jpg',                                                                     52900,  'gebrauchtwagen', 'sportwagen', 'Ford',   'Mustang GT',          2023, 'Benzin',  14500, 450, 'RWD'),
(22, 'Toyota GR86',                 'Der Toyota GR86 ist der Sportwagen für Puristen. Boxer-Vierzylinder, 234 PS, Hinterradantrieb und ein perfektes 50:50-Gewichtsverhältnis für maximalen Fahrspaß.',                               'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Toyota_GR86_IMG_7863.jpg/3840px-Toyota_GR86_IMG_7863.jpg',                                                                                                                    35500,  'gebrauchtwagen', 'sportwagen', 'Toyota', 'GR86',                2022, 'Benzin',  22000, 234, 'RWD'),
(23, 'Kia EV6 GT',                  'Der Kia EV6 GT ist das Topmodell unter den Elektro-Crossovern. 585 PS, 0–100 km/h in 3,5 Sekunden und koreanisches Design, das alle Blicke auf sich zieht.',                                    'https://upload.wikimedia.org/wikipedia/commons/6/67/Kia_EV6_Auto_Zuerich_2021_IMG_0435.jpg',                          55900,  'gebrauchtwagen', 'suv',        'Kia',    'EV6 GT',              2023, 'Elektro', 18700, 585, 'AWD'),
(24, 'CUPRA Leon VZ',               'Der CUPRA Leon VZ ist die sportlichste Version des Leon. 300 PS, Torque Vectoring AWD und exklusives CUPRA Design – kompaktes Fahrerlebnis auf höchstem Niveau.',                                'https://upload.wikimedia.org/wikipedia/commons/e/e9/Cupra_Leon_Mk4_IMG_0036.jpg',                                                                                                                      31900,  'gebrauchtwagen', 'kompakt',    'CUPRA',  'Leon VZ',             2022, 'Benzin',  29000, 300, 'AWD'),
(25, 'Mazda CX-5 Skyactiv-D 184',  'Der Mazda CX-5 mit Skyactiv-D 184 Dieselmotor überzeugt durch elegantes Kodo-Design, 184 PS und i-Activ AWD. Japanische Zuverlässigkeit zum vernünftigen Preis.',                               'https://upload.wikimedia.org/wikipedia/commons/f/f3/Mazda_CX-5_SKYACTIV-G_165_Newground_%28II%2C_Facelift%29_%E2%80%93_f_14042024.jpg',                                                            38900,  'gebrauchtwagen', 'suv',        'Mazda',  'CX-5 Skyactiv-D 184', 2022, 'Diesel',  42000, 184, 'i-Activ AWD');

-- Nachrichten (Inbox für eingeloggte Nutzer)
CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    title      VARCHAR(200) NOT NULL,
    body       TEXT,
    is_read    TINYINT(1)   NOT NULL DEFAULT 0,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_messages_user (user_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
