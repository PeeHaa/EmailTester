--
-- PostgreSQL database dump
--

-- Started on 2013-08-10 20:44:13

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 1790 (class 1262 OID 26721)
-- Name: mailtester; Type: DATABASE; Schema: -;
--

CREATE DATABASE mailtester WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'nl_NL.1252' LC_CTYPE = 'nl_NL.1252';

\connect mailtester

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 310 (class 2612 OID 16386)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1498 (class 1259 OID 26724)
-- Dependencies: 3
-- Name: addresses; Type: TABLE; Schema: public; Tablespace: 
--

CREATE TABLE addresses (
    id integer NOT NULL,
    address text NOT NULL,
    valid boolean NOT NULL
);

--
-- TOC entry 1497 (class 1259 OID 26722)
-- Dependencies: 1498 3
-- Name: addresses_id_seq; Type: SEQUENCE; Schema: public;
--

CREATE SEQUENCE addresses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

--
-- TOC entry 1793 (class 0 OID 0)
-- Dependencies: 1497
-- Name: addresses_id_seq; Type: SEQUENCE OWNED BY; Schema: public;
--

ALTER SEQUENCE addresses_id_seq OWNED BY addresses.id;


--
-- TOC entry 1500 (class 1259 OID 26747)
-- Dependencies: 3
-- Name: patterns; Type: TABLE; Schema: public; Tablespace: 
--

CREATE TABLE patterns (
    id bigint NOT NULL,
    pattern text NOT NULL
);

--
-- TOC entry 1499 (class 1259 OID 26745)
-- Dependencies: 1500 3
-- Name: patterns_id_seq; Type: SEQUENCE; Schema: public;
--

CREATE SEQUENCE patterns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

--
-- TOC entry 1794 (class 0 OID 0)
-- Dependencies: 1499
-- Name: patterns_id_seq; Type: SEQUENCE OWNED BY; Schema: public;
--

ALTER SEQUENCE patterns_id_seq OWNED BY patterns.id;


--
-- TOC entry 1778 (class 2604 OID 26727)
-- Dependencies: 1497 1498 1498
-- Name: id; Type: DEFAULT; Schema: public;
--

ALTER TABLE addresses ALTER COLUMN id SET DEFAULT nextval('addresses_id_seq'::regclass);


--
-- TOC entry 1779 (class 2604 OID 26750)
-- Dependencies: 1500 1499 1500
-- Name: id; Type: DEFAULT; Schema: public;
--

ALTER TABLE patterns ALTER COLUMN id SET DEFAULT nextval('patterns_id_seq'::regclass);


--
-- TOC entry 1781 (class 2606 OID 26732)
-- Dependencies: 1498 1498
-- Name: pk_addresses; Type: CONSTRAINT; Schema: public; Tablespace: 
--

ALTER TABLE ONLY addresses
    ADD CONSTRAINT pk_addresses PRIMARY KEY (id);


--
-- TOC entry 1785 (class 2606 OID 26755)
-- Dependencies: 1500 1500
-- Name: pk_patterns; Type: CONSTRAINT; Schema: public; Tablespace: 
--

ALTER TABLE ONLY patterns
    ADD CONSTRAINT pk_patterns PRIMARY KEY (id);


--
-- TOC entry 1783 (class 2606 OID 26734)
-- Dependencies: 1498 1498
-- Name: unique_addresses_address; Type: CONSTRAINT; Schema: public; Tablespace: 
--

ALTER TABLE ONLY addresses
    ADD CONSTRAINT unique_addresses_address UNIQUE (address);


--
-- TOC entry 1787 (class 2606 OID 26757)
-- Dependencies: 1500 1500
-- Name: unique_patterns_pattern; Type: CONSTRAINT; Schema: public; Tablespace: 
--

ALTER TABLE ONLY patterns
    ADD CONSTRAINT unique_patterns_pattern UNIQUE (pattern);


--
-- TOC entry 1792 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-08-10 20:44:14

--
-- PostgreSQL database dump complete
--

