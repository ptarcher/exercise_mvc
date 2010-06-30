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

SELECT 
    session_date,
    zone,
    SUM(length)
FROM 
    v_exercise_data 
GROUP BY
    zone,
    session_date
ORDER BY
    session_date, zone;

ALTER TABLE t_users ADD COLUMN max_heartrate numeric;
ALTER TABLE t_users ADD COLUMN resting_heartrate numeric;

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
