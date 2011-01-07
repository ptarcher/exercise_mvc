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



