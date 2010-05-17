--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: t_exercise_data; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_exercise_data (
    session_date timestamp with time zone NOT NULL,
    "time" time without time zone NOT NULL,
    distance double precision,
    heartrate double precision,
    speed double precision,
    userid character(32) NOT NULL
);


ALTER TABLE public.t_exercise_data OWNER TO ptarcher;

--
-- Name: t_exercise_totals; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_exercise_totals (
    type_short character(4),
    session_date timestamp with time zone NOT NULL,
    description text,
    duration time without time zone,
    distance double precision,
    comment text,
    avg_heartrate double precision,
    avg_speed double precision,
    userid character(32)
);


ALTER TABLE public.t_exercise_totals OWNER TO ptarcher;

--
-- Name: t_training_categories; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_training_categories (
    category character(32) NOT NULL,
    description text
);


ALTER TABLE public.t_training_categories OWNER TO ptarcher;

--
-- Name: TABLE t_training_categories; Type: COMMENT; Schema: public; Owner: ptarcher
--

COMMENT ON TABLE t_training_categories IS 'List of training categories';


--
-- Name: t_training_types; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_training_types (
    category character(32) NOT NULL,
    type_short character(4) NOT NULL,
    type text NOT NULL,
    description text
);


ALTER TABLE public.t_training_types OWNER TO ptarcher;

--
-- Name: t_users; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_users (
    userid character(32) NOT NULL,
    passowrd_hash character(64) NOT NULL,
    password_salt character(64) NOT NULL
);


ALTER TABLE public.t_users OWNER TO ptarcher;

--
-- Data for Name: t_exercise_data; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:00', 0, 60, 55, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:01', 0.10000000000000001, 65, 56, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:02', 0.20000000000000001, 70, 57, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:03', 0.29999999999999999, 100, 60, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:04', 0.40000000000000002, 110, 60, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:05', 0.59999999999999998, 150, 70, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:06', 0.69999999999999996, 110, 70, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:07', 0.69999999999999996, 100, 20, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:08', 0.80000000000000004, 101, 55, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:09', 0.90000000000000002, 102, 55, 'ptarcher                        ');
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid) VALUES ('2009-01-01 00:00:00+11', '00:00:10', 1, 105, 50, 'ptarcher                        ');


--
-- Data for Name: t_exercise_totals; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid) VALUES ('E1  ', '2009-01-01 00:00:00+11', 'First Ride', '00:00:10', 1, 'Really Good Ride', 150, 30.800000000000001, 'ptarcher                        ');
INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid) VALUES ('E2  ', '2010-01-01 00:00:00+11', 'Second Ride', '01:30:22', 45.600000000000001, 'Longer Ride, It was tough', 164, 32.5, 'ptarcher                        ');


--
-- Data for Name: t_training_categories; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_training_categories (category, description) VALUES ('Endurance                       ', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Strength                        ', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Speed Skills                    ', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Power                           ', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Muscular Endurance              ', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Anerobic Endurance              ', NULL);


--
-- Data for Name: t_training_types; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_training_types (category, type_short, type, description) VALUES ('Endurance                       ', 'E1  ', 'Recovery', 'Recovery Ride');
INSERT INTO t_training_types (category, type_short, type, description) VALUES ('Endurance                       ', 'E2  ', 'Aerobic', 'Aerobic maintence and endurance training');


--
-- Data for Name: t_users; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_users (userid, passowrd_hash, password_salt) VALUES ('ptarcher                        ', '1234                                                            ', '567                                                             ');
INSERT INTO t_users (userid, passowrd_hash, password_salt) VALUES ('heather                         ', '1234                                                            ', '890                                                             ');


--
-- Name: pk_t_training_categories; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_training_categories
    ADD CONSTRAINT pk_t_training_categories PRIMARY KEY (category);


--
-- Name: primary_t_exercise_data; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT primary_t_exercise_data PRIMARY KEY (session_date, "time");


--
-- Name: primary_t_exercise_totals; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT primary_t_exercise_totals PRIMARY KEY (session_date);


--
-- Name: primary_t_trainging_types; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_training_types
    ADD CONSTRAINT primary_t_trainging_types PRIMARY KEY (type_short);


--
-- Name: primary_t_users; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_users
    ADD CONSTRAINT primary_t_users PRIMARY KEY (userid);


--
-- Name: foreign_t_exercise_data_f1; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT foreign_t_exercise_data_f1 FOREIGN KEY (userid) REFERENCES t_users(userid);


--
-- Name: foreign_t_exercise_data_f2; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT foreign_t_exercise_data_f2 FOREIGN KEY (session_date) REFERENCES t_exercise_totals(session_date);


--
-- Name: foreign_t_exercise_totals; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT foreign_t_exercise_totals FOREIGN KEY (type_short) REFERENCES t_training_types(type_short) MATCH FULL;


--
-- Name: foreign_t_exercise_totals_f2; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT foreign_t_exercise_totals_f2 FOREIGN KEY (userid) REFERENCES t_users(userid);


--
-- Name: foreign_traing_types_1; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_training_types
    ADD CONSTRAINT foreign_traing_types_1 FOREIGN KEY (category) REFERENCES t_training_categories(category) MATCH FULL;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

