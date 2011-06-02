CREATE TABLE t_training_zones
(
   "zone" character varying(64) NOT NULL, 
   "Description" text, 
   CONSTRAINT t_training_zones_pk PRIMARY KEY ("zone")
) 
WITH (
  OIDS = FALSE
)
;

INSERT INTO t_training_zones (zone, "Description") VALUES('Zone 0', 'Warmup');
INSERT INTO t_training_zones (zone, "Description") VALUES('Zone 1', 'Recovery');
INSERT INTO t_training_zones (zone, "Description") VALUES('Zone 2', 'Aerobic');
INSERT INTO t_training_zones (zone, "Description") VALUES('Zone 3', 'Anaerobic');
INSERT INTO t_training_zones (zone, "Description") VALUES('Zone 4', 'Red');

CREATE TABLE t_users_zones
(
   "zone" character varying(64) NOT NULL, 
   min_heartrate numeric NOT NULL, 
   userid character varying(32) NOT NULL, 
   CONSTRAINT t_users_zones_fk_users FOREIGN KEY (userid) REFERENCES t_users (userid) ON UPDATE NO ACTION ON DELETE NO ACTION, 
   CONSTRAINT t_users_zones_pk PRIMARY KEY (userid, "zone")
) 
WITH (
  OIDS = FALSE
)
;

INSERT INTO t_users_zones (zone, min_heartrate, userid) VALUES('Zone 0',   0, 'ptarcher');
INSERT INTO t_users_zones (zone, min_heartrate, userid) VALUES('Zone 1', 115, 'ptarcher');
INSERT INTO t_users_zones (zone, min_heartrate, userid) VALUES('Zone 2', 134, 'ptarcher');
INSERT INTO t_users_zones (zone, min_heartrate, userid) VALUES('Zone 3', 153, 'ptarcher');
INSERT INTO t_users_zones (zone, min_heartrate, userid) VALUES('Zone 4', 172, 'ptarcher');


CREATE OR REPLACE VIEW v_users_zones
AS
    SELECT
        zones.*,
        (SELECT
            MIN(t.min_heartrate) - 1
            FROM t_users_zones t
         WHERE
              t.userid = zones.userid AND
              t.min_heartrate > zones.min_heartrate) AS max_heartrate
    FROM
        t_users_zones zones;

CREATE OR REPLACE VIEW v_exercise_data
AS
    SELECT 
        data.*,
        (SELECT 
            MIN(t.time)
            FROM t_exercise_data t
         WHERE
              t.session_date = data.session_date AND
              t.userid       = data.userid       AND
              t.time         > data.time) - data.time AS length,
        zones.zone
    FROM 
        t_exercise_data data,
        v_users_zones   zones
    WHERE
    data.heartrate > zones.min_heartrate AND
    (zones.max_heartrate IS NULL OR data.heartrate < zones.max_heartrate);

CREATE TABLE t_climbs_categories
(
   rank         numeric              NOT NULL, 
   category     text                 NOT NULL, 
   cat          character varying(6) NOT NULL,
   min_gradient double precision     NOT NULL,
   min_distance double precision     NOT NULL,
   min_height   numeric              NOT NULL,

   CONSTRAINT primary_t_climbs_categories PRIMARY KEY (rank)
) 
WITH (
  OIDS = FALSE
)
;
ALTER TABLE t_climbs_categories OWNER TO ptarcher;

INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(0, 'Hors Category', 'HC', 7.0, 10000.0, 1000);
INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(1, 'Category 1', 'Cat 1', 6.0, 20000.0, 1500);
INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(2, 'Category 2', 'Cat 2', 8.0,  5000.0,  500);
INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(3, 'Category 3', 'Cat 3', 5.0,  5000.0,  150);
INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(4, 'Category 4', 'Cat 4', 4.0,  2000.0,   80);
INSERT INTO t_climbs_categories (rank, category, cat, min_gradient, min_distance, min_height) VALUES(5, 'Category 5', 'Cat 5', 3.0,   500.0,   15);

CREATE TABLE t_climbs
(
   name             character varying(128) NOT NULL,
   description      text,

   top_latitude     double precision NOT NULL, 
   top_longitude    double precision NOT NULL, 
   top_radius       double precision NOT NULL DEFAULT 100,

   bottom_latitude  double precision NOT NULL, 
   bottom_longitude double precision NOT NULL, 
   bottom_radius    double precision NOT NULL DEFAULT 100,

   CONSTRAINT primary_t_climbs PRIMARY KEY (name)
) 
WITH (
  OIDS = FALSE
)
;
ALTER TABLE t_climbs OWNER TO ptarcher;

CREATE TABLE t_climbs_data
(
   userid           character varying(32) NOT NULL,
   session_date     timestamp with time zone NOT NULL,
   climb_num        numeric NOT NULL,

   bottom           interval NOT NULL,
   top              interval NOT NULL,

   /* Totals */
   gradient_avg     double precision,
   gradient_max     double precision,
   total_distance   double precision,
   total_climbed    numeric,
   min_altitude     numeric,
   max_altitude     numeric,

   CONSTRAINT primary_t_climbs_data PRIMARY KEY (userid, session_date, climb_num)
) 
WITH (
  OIDS = FALSE
)
;
ALTER TABLE t_climbs_data OWNER TO ptarcher;

CREATE OR REPLACE VIEW v_climbs_data_rank
AS
    SELECT 
        data.userid,
        data.session_date,
        data.climb_num,
        data.bottom,
        data.top,
        data.gradient_avg,
        data.gradient_max,
        data.total_distance,
        data.total_climbed,
        data.min_altitude,
        data.max_altitude,
        MIN(categories.rank) AS rank
    FROM 
        t_climbs_data data,
        t_climbs_categories categories
    WHERE
        data.gradient_avg   > categories.min_gradient AND
        data.total_distance*1000 > categories.min_distance AND
        data.total_climbed  > categories.min_height
    GROUP BY
        userid, session_date, climb_num,
        bottom, top, gradient_avg, gradient_max,
        total_distance, total_climbed,
        min_altitude, max_altitude;

CREATE OR REPLACE VIEW v_climbs_data
AS
    SELECT 
        data.*,
        categories.cat
    FROM
        t_climbs_categories categories,
        v_climbs_data_rank  data
    WHERE categories.rank = data.rank;

CREATE LANGUAGE plpgsql;

-- Heaver Sin
CREATE OR REPLACE FUNCTION heaver_sin(FLOAT) RETURNS FLOAT AS $$
    SELECT power(sin($1/2.0), 2);
$$ LANGUAGE SQL;

-- Heaver Arcsine
CREATE OR REPLACE FUNCTION arc_heaver_sin(FLOAT) RETURNS FLOAT AS $$
    SELECT 2.0*asin(sqrt($1));
$$ LANGUAGE SQL;

-- Provides the Earth radius 
CREATE OR REPLACE FUNCTION earth_radius(lat FLOAT) RETURNS float AS $$
    DECLARE
        rad_equatorial FLOAT := 6378.137;
        rad_polar FLOAT := 6356.752;
    BEGIN
    RETURN sqrt(power(rad_equatorial, 2) * power(cos(lat), 2) + power(rad_polar, 2)* power(sin(lat), 2)/rad_equatorial*power(cos(lat), 2) + rad_polar*power(sin(lat), 2));
    END;
$$ LANGUAGE plpgsql;

-- Give two sets of coordinates in degree form
CREATE OR REPLACE FUNCTION distance_in_km(lat1 float, lon1 float, lat2 float, lon2 float) 
    RETURNS float AS $$
DECLARE
    lat1_r FLOAT := radians(lat1);
    lon1_r FLOAT := radians(lon1);
    lat2_r FLOAT := radians(lat2);
    lon2_r FLOAT := radians(lon2);
BEGIN

    RETURN earth_radius((lat1_r + lat2_r) / 2.0) * arc_heaver_sin(heaver_sin(abs(lat1_r-lat2_r)) + cos(lat1_r)*cos(lat2_r)*heaver_sin(abs(lon1_r - lon2_r)));
    END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE VIEW v_climbs_details
AS
    SELECT 
        data.userid,
        data.session_date,
        data.climb_num,
        climbs.name,
        climbs.description
    FROM
        t_climbs climbs,
        (SELECT
            tops.userid,
            tops.session_date,
            tops.climb_num,
            tops.top,
            tops.top_latitude,
            tops.top_longitude,
            bottoms.bottom_latitude,
            bottoms.bottom_longitude,
            bottoms.bottom
        FROM 
            (SELECT
                cd.userid,
                cd.session_date,
                cd.climb_num,
                cd.top,
                ed.latitude  AS top_latitude, 
                ed.longitude AS top_longitude
            FROM
                t_climbs_data   cd,
                t_exercise_data ed
            WHERE
                cd.userid       = ed.userid       AND
                cd.session_date = ed.session_date AND
                ed.time         = cd.top) tops,
            (SELECT
                cd.userid,
                cd.session_date,
                cd.climb_num,
                cd.bottom,
                ed.latitude  AS bottom_latitude, 
                ed.longitude AS bottom_longitude
            FROM
                t_climbs_data   cd,
                t_exercise_data ed
            WHERE
                cd.userid       = ed.userid       AND
                cd.session_date = ed.session_date AND
                ed.time         = cd.bottom) bottoms
        WHERE 
            tops.userid       = bottoms.userid       AND
            tops.session_date = bottoms.session_date AND
            tops.climb_num    = bottoms.climb_num) data
    WHERE
        distance_in_km(data.top_latitude,   data.top_longitude,
                       climbs.top_latitude, climbs.top_longitude)       * 1000.0 < climbs.top_radius       AND
        distance_in_km(data.bottom_latitude,   data.bottom_longitude,
                       climbs.bottom_latitude, climbs.bottom_longitude) * 1000.0 < climbs.top_radius;

INSERT INTO t_climbs
    (name,
     top_latitude,
     top_longitude,
     bottom_latitude,
     bottom_longitude)
VALUES
    ('Bulli Pass',
     -34.311189,
     150.897371,
     -34.320387,
     150.913961);

ALTER TABLE t_exercise_laps ADD COLUMN total_duration interval;
UPDATE t_exercise_laps SET total_duration = duration;

ALTER TABLE t_users ADD COLUMN dob date;
ALTER TABLE t_users ADD COLUMN token character varying(64) NOT NULL DEFAULT 'abc';
ALTER TABLE t_users ADD COLUMN email character varying(64) NOT NULL DEFAULT 'ptarcher@gmail.com';
UPDATE t_users SET resting_heartrate = '60'  WHERE resting_heartrate is NULL;
UPDATE t_users SET max_heartrate     = '180' WHERE max_heartrate     is NULL;
ALTER TABLE t_users ALTER COLUMN email DROP DEFAULT;
ALTER TABLE t_users ALTER COLUMN max_heartrate SET DEFAULT 180;
ALTER TABLE t_users ALTER COLUMN max_heartrate SET NOT NULL;
ALTER TABLE t_users ALTER COLUMN resting_heartrate SET DEFAULT 60;
ALTER TABLE t_users ALTER COLUMN resting_heartrate SET NOT NULL;

ALTER TABLE t_exercise_plans_daily ADD COLUMN session_timestamp timestamp with time zone;
ALTER TABLE t_exercise_plans_daily ADD CONSTRAINT t_users_zones_fk_sessions FOREIGN KEY (status) REFERENCES t_exercise_totals (session_date,userid) ON UPDATE NO ACTION ON DELETE NO ACTION;

CREATE OR REPLACE VIEW v_exercise_totals
AS
    SELECT 
        t_exercise_totals.*,
        ROUND(t_exercise_totals.avg_heartrate / t_users.max_heartrate * 100, 1) AS avg_heartrate_percent, 
        ROUND(t_exercise_totals.max_heartrate / t_users.max_heartrate * 100, 1) AS max_heartrate_percent
    FROM 
        t_users, 
        t_exercise_totals 
    WHERE 
        t_users.userid = t_exercise_totals.userid;

CREATE TABLE t_users_bikes_types
(
   "type" character varying(32) NOT NULL, 
   "description" text, 
   CONSTRAINT t_users_bikes_types_pk PRIMARY KEY ("type")
)
WITH (
  OIDS = FALSE
)
;

INSERT INTO t_users_bikes_types (type, "description") VALUES('Road', 'Road Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('Time-Trial', 'Time Trial Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('Mountain', 'Mountain Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('Downhill', 'Downhill Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('Track', 'Track Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('Commuter', 'Commuter Bike');
INSERT INTO t_users_bikes_types (type, "description") VALUES('BMX', 'BMX Bike');

CREATE SEQUENCE t_users_bikes_seq START 10000;

CREATE TABLE t_users_bikes
(
   userid character varying(32) NOT NULL, 
   "id" integer DEFAULT NEXTVAL('t_users_bikes_seq') NOT NULL,
   "name" text, 
   "type" character varying(32) NOT NULL, 
   "description" text, 
   "created" timestamp DEFAULT now(), 

   CONSTRAINT t_users_bikes_pk PRIMARY KEY (userid, "id"),

   CONSTRAINT t_users_bikes_fk_users FOREIGN KEY (userid) REFERENCES t_users (userid) ON UPDATE NO ACTION ON DELETE NO ACTION, 
   CONSTRAINT t_users_bikes_fk_types FOREIGN KEY (type) REFERENCES t_users_bikes_types (type) ON UPDATE NO ACTION ON DELETE NO ACTION 
) 
WITH (
  OIDS = FALSE
)
;

CREATE TABLE t_users_bikes_parts_categories
(
   "category"       character varying(32) NOT NULL, 
   "description"    text, 

   CONSTRAINT t_users_bikes_parts_categories_pk PRIMARY KEY ("category")
)
WITH (
  OIDS = FALSE
)
;

INSERT INTO 
    t_users_bikes_parts_categories (category, description) 
VALUES 
    ('Front Wheel', 'Front Wheel'),
    ('Rear Wheel',  'Rear Wheel'),
    ('Frame',  'Frame'),
    ('Drive Chain', 'Drive Chain'),
    ('Lights',      'Lights'),
    ('Other',       'Other');


CREATE TABLE t_users_bikes_parts_types
(
   "part"           character varying(32) NOT NULL, 
   "category"       character varying(32) NOT NULL, 
   "description"    text, 

   CONSTRAINT t_users_bikes_parts_types_pk PRIMARY KEY ("part", "category"),
   CONSTRAINT t_users_bikes_parts_types_fk_category FOREIGN KEY (category) REFERENCES t_users_bikes_parts_categories (category) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS = FALSE
)
;

INSERT INTO 
    t_users_bikes_parts_types (part, category, description) 
VALUES 
    /* Front Wheel */
    ('Front Tyre',                 'Front Wheel', 'Front Tyre'),
    ('Front Wheel',                'Front Wheel', 'Front Wheel'),
    ('Front Tube',                 'Front Wheel', 'Front Inner Tube'),
    ('Front Skewer',               'Front Wheel', 'Front Wheel Skewer'),
    ('Front Brakes',               'Front Wheel', 'Front Brakes'),
    ('Front Brake-pads',           'Front Wheel', 'Front Brake-pads'),
    ('Front Cables',               'Front Wheel', 'Front Brake Cable'),
    ('Front Brake Lever',          'Front Wheel', 'Front Brake Lever'),
    ('Front Wheel Bearing',        'Front Wheel', 'Front Wheel Bearing'),

    /* Rear Wheel */
    ('Rear Tyre',                  'Rear Wheel', 'Rear Tyre'),
    ('Rear Wheel',                 'Rear Wheel', 'Rear Wheel'),
    ('Rear Tube',                  'Rear Wheel', 'Rear Inner Tube'),
    ('Rear Skewer',                'Rear Wheel', 'Rear Wheel Skewer'),
    ('Rear Brakes',                'Rear Wheel', 'Rear Brakes'),
    ('Rear Brake-pads',            'Rear Wheel', 'Rear Brake-pads'),
    ('Rear Brake Cables',          'Rear Wheel', 'Rear Brake Cables'),
    ('Rear Brake Lever',           'Rear Wheel', 'Rear Brake Lever'),
    ('Rear Free-Wheel',            'Rear Wheel', 'Rear Free-Wheel'),
    ('Rear Wheel Bearing',         'Rear Wheel', 'Rear Wheel Bearing'),

    /* Drive Chain */
    ('Chain',                      'Drive Chain', 'Chain'),
    ('Cassette',                   'Drive Chain', 'Rear Cassette'),
    ('Small Chain-Ring',           'Drive Chain', 'Small Front Chain-Ring'),
    ('Middle Chain-Ring',          'Drive Chain', 'Middle Front Chain-Ring'),
    ('Large Chain-Ring',           'Drive Chain', 'Large Front Chain-Ring'),
    ('Bottom Bracket',             'Drive Chain', 'Bottom Bracket'),

    ('Front De-railer',            'Drive Chain', 'Front De-railer'),
    ('Front De-railer Cables',     'Drive Chain', 'Front De-railer Cables'),
    ('Rear De-railer',             'Drive Chain', 'Rear De-railer'),
    ('Rear De-railer Cables',      'Drive Chain', 'Rear De-railer Cables'),

    ('Left Peddle',                'Drive Chain', 'Left-Hand-Side Peddle'),
    ('Right Peddle',               'Drive Chain', 'Right-Hand-Side Peddle'),

    /* Frame */
    ('Bar Tape',                   'Frame', 'Handle Bar Tape'),
    ('Handle Bars',                'Frame', 'Handle Bars'),
    ('Front Stem',                 'Frame', 'Front Stem'),
    ('Seat',                       'Frame', 'Seat'),
    ('Seat Tube',                  'Frame', 'Seat Tube'),
    ('Bike Frame',                 'Frame', 'Bike Frame'),
    ('TT Bars',                    'Frame', 'Clip-On Time-Trial Bars'),
    ('Bottle-Holder',              'Frame', 'Bottle-Holder'),

    /* Lights */
    ('Front Headlight',            'Lights', 'Front Headlight'),
    ('Front Headlight Batteries',  'Lights', 'Front Headlight Batteries'),
    ('Read Tail-light',            'Lights', 'Rear Tailight'),
    ('Rear Tail-light Batteries',  'Lights', 'Rear Tail-light Batteries'),

    /* Other */
    ('Bicycle Computer',           'Other', 'Bicycle Computer'),
    ('Other',                      'Other', 'Other parts not listed');


CREATE SEQUENCE t_users_bikes_parts_seq START 10000;


CREATE TABLE t_users_bikes_parts
(
   userid                   character varying(32) NOT NULL, 
   "bike_id"                integer NOT NULL,
   "id"                     integer NOT NULL DEFAULT NEXTVAL('t_users_bikes_parts_seq'),
   "category"               character varying(32) NOT NULL, 
   "part"                   character varying(32) NOT NULL, 
   "description"            text, 
   "inspection_peiod_date"  interval, 
   "inspection_period_km"   integer, 
   "inspected_date"         timestamp, 
   "inspected_km"           integer, 
   "replaced_date"          timestamp, 
   "replaced_km"            integer, 
   "withdrawn_date"         timestamp, 
   "withdrawn_km"           integer, 

   CONSTRAINT t_users_bikes_parts_pk PRIMARY KEY (userid, "id"),

   CONSTRAINT t_users_bikes_parts_fk_users FOREIGN KEY (userid) REFERENCES t_users (userid) ON UPDATE NO ACTION ON DELETE NO ACTION, 
   CONSTRAINT t_users_bikes_parts_fk_parts FOREIGN KEY (part, category) REFERENCES t_users_bikes_parts_types (part, category) ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;

