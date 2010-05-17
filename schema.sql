--
-- PostgreSQL database dump
--

-- Started on 2010-05-17 21:18:07 EST

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
-- TOC entry 1507 (class 1259 OID 16432)
-- Dependencies: 3
-- Name: t_exercise_data; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_exercise_data (
    session_date timestamp with time zone NOT NULL,
    "time" time without time zone NOT NULL,
    distance double precision,
    heartrate double precision,
    speed double precision,
    userid character varying(32) NOT NULL,
    latitude double precision,
    longitude double precision
);


ALTER TABLE public.t_exercise_data OWNER TO ptarcher;

--
-- TOC entry 1509 (class 1259 OID 16582)
-- Dependencies: 3
-- Name: t_exercise_total_types; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_exercise_total_types (
    total_type character varying(16) NOT NULL,
    description text
);


ALTER TABLE public.t_exercise_total_types OWNER TO ptarcher;

--
-- TOC entry 1506 (class 1259 OID 16407)
-- Dependencies: 3
-- Name: t_exercise_totals; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_exercise_totals (
    type_short character varying(4),
    session_date timestamp with time zone NOT NULL,
    description text,
    duration time without time zone,
    distance double precision,
    comment text,
    avg_heartrate double precision,
    avg_speed double precision,
    userid character varying(32),
    id oid
);


ALTER TABLE public.t_exercise_totals OWNER TO ptarcher;

--
-- TOC entry 1504 (class 1259 OID 16386)
-- Dependencies: 3
-- Name: t_training_categories; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_training_categories (
    category character varying(32) NOT NULL,
    description text
);


ALTER TABLE public.t_training_categories OWNER TO ptarcher;

--
-- TOC entry 1814 (class 0 OID 0)
-- Dependencies: 1504
-- Name: TABLE t_training_categories; Type: COMMENT; Schema: public; Owner: ptarcher
--

COMMENT ON TABLE t_training_categories IS 'List of training categories';


--
-- TOC entry 1505 (class 1259 OID 16394)
-- Dependencies: 3
-- Name: t_training_types; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_training_types (
    category character varying(32) NOT NULL,
    type_short character varying(4) NOT NULL,
    type text NOT NULL,
    description text
);


ALTER TABLE public.t_training_types OWNER TO ptarcher;

--
-- TOC entry 1508 (class 1259 OID 16463)
-- Dependencies: 3
-- Name: t_users; Type: TABLE; Schema: public; Owner: ptarcher; Tablespace: 
--

CREATE TABLE t_users (
    userid character varying(32) NOT NULL,
    password_hash character varying(64) NOT NULL,
    password_salt character varying(64) NOT NULL
);


ALTER TABLE public.t_users OWNER TO ptarcher;

--
-- TOC entry 1807 (class 0 OID 16432)
-- Dependencies: 1507
-- Data for Name: t_exercise_data; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:01', 0.10000000000000001, 65, 56, 'ptarcher', -34.060000000000002, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:02', 0.20000000000000001, 70, 57, 'ptarcher', -34.07, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:03', 0.29999999999999999, 100, 60, 'ptarcher', -34.079999999999998, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:04', 0.40000000000000002, 110, 60, 'ptarcher', -34.090000000000003, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:05', 0.59999999999999998, 150, 70, 'ptarcher', -34.100000000000001, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:06', 0.69999999999999996, 110, 70, 'ptarcher', -34.109999999999999, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:07', 0.69999999999999996, 100, 20, 'ptarcher', -34.119999999999997, 151.00999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:08', 0.80000000000000004, 101, 55, 'ptarcher', -34.049999999999997, 151.02000000000001);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:09', 0.90000000000000002, 102, 55, 'ptarcher', -34.049999999999997, 151.03);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:10', 1, 105, 50, 'ptarcher', -34.049999999999997, 151.03999999999999);
INSERT INTO t_exercise_data (session_date, "time", distance, heartrate, speed, userid, latitude, longitude) VALUES ('2009-01-01 00:00:00+11', '00:00:00', 0, 60, 55, 'ptarcher', -34.054268, 151.013577);


--
-- TOC entry 1809 (class 0 OID 16582)
-- Dependencies: 1509
-- Data for Name: t_exercise_total_types; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_exercise_total_types (total_type, description) VALUES ('Planned', 'The exercise is planned to be completed in the future');
INSERT INTO t_exercise_total_types (total_type, description) VALUES ('Missed', 'The exercise session was missed');
INSERT INTO t_exercise_total_types (total_type, description) VALUES ('Completed', 'The exercise session was completed');
INSERT INTO t_exercise_total_types (total_type, description) VALUES ('Changed', 'The exercise session was changed to something different');


--
-- TOC entry 1806 (class 0 OID 16407)
-- Dependencies: 1506
-- Data for Name: t_exercise_totals; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid, id) VALUES ('E1', '2009-01-01 00:00:00+11', 'First Ride', '00:00:10', 1, 'Really Good Ride', 150, 30.800000000000001, 'ptarcher', 1);
INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid, id) VALUES ('E2', '2010-01-01 00:00:00+11', 'Second Ride', '01:30:22', 45.600000000000001, 'Longer Ride, It was tough', 164, 32.5, 'ptarcher', 2);
INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid, id) VALUES ('E2', '2010-12-03 10:00:00+11', 'Third Ride', '02:30:22', 50.200000000000003, 'Test Ride2', 102, 28.300000000000001, 'ptarcher', 3);
INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid, id) VALUES ('E2', '2010-03-20 10:00:00+11', 'Fourth Ride', '00:01:10', 10, 'Comments', 120, 10, 'ptarcher', NULL);
INSERT INTO t_exercise_totals (type_short, session_date, description, duration, distance, comment, avg_heartrate, avg_speed, userid, id) VALUES ('E1', '2010-03-06 01:00:00+11', 'Fifth Ride', '01:00:00', 5, 'Really short  ride', 100, 5, 'ptarcher', NULL);


--
-- TOC entry 1804 (class 0 OID 16386)
-- Dependencies: 1504
-- Data for Name: t_training_categories; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_training_categories (category, description) VALUES ('Endurance', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Strength', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Speed Skills', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Power', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Muscular Endurance', NULL);
INSERT INTO t_training_categories (category, description) VALUES ('Anerobic Endurance', NULL);


--
-- TOC entry 1805 (class 0 OID 16394)
-- Dependencies: 1505
-- Data for Name: t_training_types; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_training_types (category, type_short, type, description) VALUES ('Endurance', 'E1', 'Recovery', 'Recovery Ride');
INSERT INTO t_training_types (category, type_short, type, description) VALUES ('Endurance', 'E2', 'Aerobic', 'Aerobic maintence and endurance training');


--
-- TOC entry 1808 (class 0 OID 16463)
-- Dependencies: 1508
-- Data for Name: t_users; Type: TABLE DATA; Schema: public; Owner: ptarcher
--

INSERT INTO t_users (userid, password_hash, password_salt) VALUES ('heather', '1234', '890');
INSERT INTO t_users (userid, password_hash, password_salt) VALUES ('testinguser', 'eb1264e41be705d3ab418e0ceb67d88b19e29194', 'hbl384j3iedw5zaxrch1u8y6d2xwwnmr9y9n1unoycmsijr1i6c80apjjuxo2cno');
INSERT INTO t_users (userid, password_hash, password_salt) VALUES ('ptarcher', 'f1e8fd5aa1896c59c8b8f14116ed4160446fee23', '3s5dei759opksaoyk4tfp09xrsfkrr2ziu7erk4s4cyc0z9dv0cp8f1v4s62fizc');


--
-- TOC entry 1788 (class 2606 OID 16498)
-- Dependencies: 1504 1504
-- Name: pk_t_training_categories; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_training_categories
    ADD CONSTRAINT pk_t_training_categories PRIMARY KEY (category);


--
-- TOC entry 1794 (class 2606 OID 16436)
-- Dependencies: 1507 1507 1507
-- Name: primary_t_exercise_data; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT primary_t_exercise_data PRIMARY KEY (session_date, "time");


--
-- TOC entry 1792 (class 2606 OID 16414)
-- Dependencies: 1506 1506
-- Name: primary_t_exercise_totals; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT primary_t_exercise_totals PRIMARY KEY (session_date);


--
-- TOC entry 1790 (class 2606 OID 16484)
-- Dependencies: 1505 1505
-- Name: primary_t_trainging_types; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_training_types
    ADD CONSTRAINT primary_t_trainging_types PRIMARY KEY (type_short);


--
-- TOC entry 1796 (class 2606 OID 16558)
-- Dependencies: 1508 1508
-- Name: primary_t_users; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_users
    ADD CONSTRAINT primary_t_users PRIMARY KEY (userid);


--
-- TOC entry 1798 (class 2606 OID 16589)
-- Dependencies: 1509 1509
-- Name: t_exercise_total_types_pk; Type: CONSTRAINT; Schema: public; Owner: ptarcher; Tablespace: 
--

ALTER TABLE ONLY t_exercise_total_types
    ADD CONSTRAINT t_exercise_total_types_pk PRIMARY KEY (total_type);


--
-- TOC entry 1803 (class 2606 OID 16564)
-- Dependencies: 1795 1507 1508
-- Name: foreign_t_exercise_data_f1; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT foreign_t_exercise_data_f1 FOREIGN KEY (userid) REFERENCES t_users(userid);


--
-- TOC entry 1802 (class 2606 OID 16478)
-- Dependencies: 1507 1506 1791
-- Name: foreign_t_exercise_data_f2; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_data
    ADD CONSTRAINT foreign_t_exercise_data_f2 FOREIGN KEY (session_date) REFERENCES t_exercise_totals(session_date);


--
-- TOC entry 1800 (class 2606 OID 16511)
-- Dependencies: 1789 1506 1505
-- Name: foreign_t_exercise_totals; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT foreign_t_exercise_totals FOREIGN KEY (type_short) REFERENCES t_training_types(type_short) MATCH FULL;


--
-- TOC entry 1801 (class 2606 OID 16559)
-- Dependencies: 1506 1508 1795
-- Name: foreign_t_exercise_totals_f2; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_exercise_totals
    ADD CONSTRAINT foreign_t_exercise_totals_f2 FOREIGN KEY (userid) REFERENCES t_users(userid);


--
-- TOC entry 1799 (class 2606 OID 16545)
-- Dependencies: 1787 1505 1504
-- Name: foreign_traing_types_1; Type: FK CONSTRAINT; Schema: public; Owner: ptarcher
--

ALTER TABLE ONLY t_training_types
    ADD CONSTRAINT foreign_traing_types_1 FOREIGN KEY (category) REFERENCES t_training_categories(category) MATCH FULL;


--
-- TOC entry 1813 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2010-05-17 21:18:08 EST

--
-- PostgreSQL database dump complete
--

