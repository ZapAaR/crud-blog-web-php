--
-- PostgreSQL database dump
--

\restrict pUtAEPBcPgeE2FYDWWHxzSU05aBsaIfGueK5DUciitlGLNYMeqHoq6VU54svFLI

-- Dumped from database version 18.0
-- Dumped by pg_dump version 18.0

-- Started on 2025-12-03 06:35:47

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 224 (class 1259 OID 16466)
-- Name: comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comments (
    id integer NOT NULL,
    posts_id integer,
    user_id integer,
    komentar text NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.comments OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16465)
-- Name: comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comments_id_seq OWNER TO postgres;

--
-- TOC entry 4937 (class 0 OID 0)
-- Dependencies: 223
-- Name: comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;


--
-- TOC entry 222 (class 1259 OID 16453)
-- Name: posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.posts (
    id integer NOT NULL,
    user_id integer,
    judul character varying(200) NOT NULL,
    body text NOT NULL,
    image character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.posts OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16452)
-- Name: posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.posts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.posts_id_seq OWNER TO postgres;

--
-- TOC entry 4938 (class 0 OID 0)
-- Dependencies: 221
-- Name: posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.posts_id_seq OWNED BY public.posts.id;


--
-- TOC entry 220 (class 1259 OID 16439)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    foto character varying(255)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16438)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 4939 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4769 (class 2604 OID 16469)
-- Name: comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);


--
-- TOC entry 4767 (class 2604 OID 16456)
-- Name: posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts ALTER COLUMN id SET DEFAULT nextval('public.posts_id_seq'::regclass);


--
-- TOC entry 4765 (class 2604 OID 16442)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4931 (class 0 OID 16466)
-- Dependencies: 224
-- Data for Name: comments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comments (id, posts_id, user_id, komentar, created_at) FROM stdin;
12	7	3	p	2025-12-01 20:08:28.317435
13	7	5	laptopnya jelek	2025-12-01 20:12:36.324951
14	9	5	p	2025-12-01 20:26:25.168687
15	9	5	p	2025-12-01 20:26:29.426536
19	7	5	p	2025-12-01 20:30:49.501322
20	11	5	alamak cantik sekali	2025-12-01 20:43:02.49341
22	10	3	cantiknyo	2025-12-02 20:14:25.686204
\.


--
-- TOC entry 4929 (class 0 OID 16453)
-- Dependencies: 222
-- Data for Name: posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.posts (id, user_id, judul, body, image, created_at) FROM stdin;
3	4	laptop	aaa	Array	2025-11-24 18:42:52.31758
4	4	lap	apa	1764166000_692709707a6b1.png	2025-11-24 18:58:25.977512
10	5	oguri cap	barang bagus ga lecet	1764333729_1289492.png	2025-11-28 19:42:09.337349
11	5	oguri cap	oguri cap sangat cantik sekali, sampai saya oleng karena kencatikannya	1764594703_1198730.png	2025-12-01 20:11:43.909084
\.


--
-- TOC entry 4927 (class 0 OID 16439)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, password, created_at, foto) FROM stdin;
1	muzafar	admin@gmail.com	$2y$10$.AMz7yweZFDkUrn/eEB2MOV6fdHXSFCWTb09nW7FnzUVA4yIBA4Ym	2025-11-18 18:45:39.590341	\N
2	muzafar ka	sasuke@mailinator.com	$2y$10$SX/EA9j101hIwmhY1uGAIuAXwJWvEaWIW3Hzb5p/PV6FI1uadMXB.	2025-11-18 19:00:43.662881	1763772082_1198730.png
3	muzafar ka	zafar@gmail.com	$2y$10$KjfjP.wPiAtBUewlOPeVPeERRrmXNfeL9nG0pdCSxIrpIztps9pPK	2025-11-22 13:41:02.639947	1763798652_69216e7ce68f1.png
5	zap	zap@gmail.com	$2y$10$tDsNbR5T/ph8WMQaQLFzq.qxQ6BTfn.Mamlke3eIeAZfOIg4SEJPa	2025-11-28 19:18:09.518104	1764333531_692997db69798.png
\.


--
-- TOC entry 4940 (class 0 OID 0)
-- Dependencies: 223
-- Name: comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comments_id_seq', 22, true);


--
-- TOC entry 4941 (class 0 OID 0)
-- Dependencies: 221
-- Name: posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.posts_id_seq', 16, true);


--
-- TOC entry 4942 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 5, true);


--
-- TOC entry 4778 (class 2606 OID 16476)
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- TOC entry 4776 (class 2606 OID 16464)
-- Name: posts posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.posts
    ADD CONSTRAINT posts_pkey PRIMARY KEY (id);


--
-- TOC entry 4772 (class 2606 OID 16451)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 4774 (class 2606 OID 16449)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


-- Completed on 2025-12-03 06:35:47

--
-- PostgreSQL database dump complete
--

\unrestrict pUtAEPBcPgeE2FYDWWHxzSU05aBsaIfGueK5DUciitlGLNYMeqHoq6VU54svFLI

