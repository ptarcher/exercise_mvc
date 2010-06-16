ALTER TABLE t_exercise_totals DROP CONSTRAINT primary_t_exercise_totals CASCADE;
ALTER TABLE t_exercise_totals ADD CONSTRAINT primary_t_exercise_totals PRIMARY KEY (session_date, userid);

ALTER TABLE t_exercise_data DROP CONSTRAINT primary_t_exercise_data;
ALTER TABLE t_exercise_data ADD CONSTRAINT primary_t_exercise_data PRIMARY KEY (userid, session_date, "time");
ALTER TABLE t_exercise_data ADD CONSTRAINT foreign_t_exercise_data_f2 FOREIGN KEY (session_date, userid) REFERENCES t_exercise_totals (session_date, userid) ON UPDATE NO ACTION ON DELETE NO ACTION;


CREATE TABLE t_training_periods
(
    period character varying(64) NOT NULL, 
    description text,
    CONSTRAINT t_exercise_periods_pk PRIMARY KEY (period)
) 
WITH (
    OIDS = FALSE
);

CREATE TABLE t_exercise_plans_weekly
(
   userid character varying(32) NOT NULL, 
   week_date date NOT NULL, 
   period character varying(64) NOT NULL, 
   description text, 
   "comment" text, 
   CONSTRAINT t_exercise_plans_weekly_pk PRIMARY KEY (userid, week_date), 
   CONSTRAINT t_exercise_plans_weekly_fk_userid FOREIGN KEY (userid) REFERENCES t_users (userid) ON UPDATE NO ACTION ON DELETE NO ACTION, 
   CONSTRAINT t_exercise_plans_weekly_fk_period FOREIGN KEY (period) REFERENCES t_training_periods (period) ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;

CREATE TABLE t_exercise_plans_daily
(
   userid character varying(32) NOT NULL, 
   "timestamp" timestamp with time zone NOT NULL, 
   category character varying(32) NOT NULL, 
   description text, 
   volume numeric NOT NULL, 
   intensity numeric NOT NULL, 
   duration interval NOT NULL, 
   focus character varying(64) NOT NULL, 
   "comment" text, 
   CONSTRAINT t_exercise_plans_daily_pk PRIMARY KEY (userid, "timestamp"), 
   CONSTRAINT t_exercise_plans_daily_fk_category FOREIGN KEY (category) REFERENCES t_training_categories (category) ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;

CREATE TABLE t_exercise_laps
(
   userid character varying(32) NOT NULL, 
   session_date timestamp with time zone NOT NULL, 
   start_time time without time zone, 
   start_pos_lat double precision, 
   start_pos_long double precision, 
   duration interval, 
   calories numeric, 
   distance numeric, 
   avg_heartrate numeric, 
   max_heartrate numeric, 
   avg_speed numeric, 
   max_speed numeric, 
   total_ascent numeric, 
   total_descent numeric, 
   CONSTRAINT t_exercise_laps_pk PRIMARY KEY (userid, session_date), 
   CONSTRAINT t_exercise_laps_fk_sessions FOREIGN KEY (userid, session_date) REFERENCES t_exercise_totals (userid, session_date) ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;

ALTER TABLE t_exercise_laps DROP CONSTRAINT t_exercise_laps_pk;
ALTER TABLE t_exercise_laps ADD COLUMN lap_num numeric;
ALTER TABLE t_exercise_laps ADD CONSTRAINT primary_exercise_laps PRIMARY KEY (userid, session_date, lap_num);


ALTER TABLE t_exercise_totals ALTER distance TYPE numeric;
ALTER TABLE t_exercise_totals ALTER avg_heartrate TYPE numeric;
ALTER TABLE t_exercise_totals ALTER avg_speed TYPE numeric;
ALTER TABLE t_exercise_totals ADD COLUMN calories numeric;
ALTER TABLE t_exercise_totals ADD COLUMN max_heartrate numeric;
ALTER TABLE t_exercise_totals ADD COLUMN max_speed numeric;
ALTER TABLE t_exercise_totals ADD COLUMN total_ascent numeric;
ALTER TABLE t_exercise_totals ADD COLUMN total_descent numeric;

