CREATE TABLE t_training_categories
(
   category character(32) NOT NULL, 
   description text, 
   CONSTRAINT pk_t_training_categories PRIMARY KEY (category)
) 
WITH (
  OIDS = FALSE
)
;

CREATE TABLE t_training_types
(
   category character(32) NOT NULL, 
   type_short character(4) NOT NULL, 
   "type" text NOT NULL, 
   description text, 
   CONSTRAINT primary_t_trainging_types PRIMARY KEY (type_short), 
   CONSTRAINT foreign_traing_types_1 FOREIGN KEY (category) REFERENCES t_training_categories (category) MATCH FULL ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;
ALTER TABLE t_training_types OWNER TO ptarcher;
ALTER TABLE t_training_categories OWNER TO ptarcher;
COMMENT ON TABLE t_training_categories IS 'List of training categories';

CREATE TABLE t_exercise_totals
(
   type_short character(4), 
   session_date timestamp with time zone, 
   description text, 
   duration time without time zone, 
   distance double precision, 
   CONSTRAINT primary_t_exercise_totals PRIMARY KEY (session_date), 
   CONSTRAINT foreign_t_exercise_totals FOREIGN KEY (type_short) REFERENCES t_training_types (type_short) MATCH FULL ON UPDATE NO ACTION ON DELETE NO ACTION
) 
WITH (
  OIDS = FALSE
)
;
ALTER TABLE t_exercise_totals OWNER TO ptarcher;

CREATE TABLE t_exercise_data
(
   session_date timestamp with time zone, 
   "time" time without time zone, 
   distance double precision, 
   CONSTRAINT primary_t_exercise_data PRIMARY KEY (session_date, "time")
) 
WITH (
  OIDS = FALSE
)
;

