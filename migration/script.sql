--
-- PostgreSQL database dump
--

-

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 851 (class 1247 OID 17081)
-- Name: type_compte; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.type_compte AS ENUM (
    'comptePrincipal',
    'compteSecondaire'
);


ALTER TYPE public.type_compte OWNER TO postgres;

--
-- TOC entry 854 (class 1247 OID 17086)
-- Name: type_transactions; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.type_transactions AS ENUM (
    'depot',
    'retrait',
    'paiement'
);


ALTER TYPE public.type_transactions OWNER TO postgres;

--
-- TOC entry 236 (class 1255 OID 17166)
-- Name: creer_compte_secondaire(integer, character varying, numeric); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.creer_compte_secondaire(p_id_user integer, p_telephone character varying, p_montant_initial numeric DEFAULT 0) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_compte_principal_id INTEGER;
    v_nouveau_compte_id INTEGER;
BEGIN
    SELECT id INTO v_compte_principal_id 
    FROM compte 
    WHERE id_user = p_id_user AND is_principal = TRUE;

    IF v_compte_principal_id IS NULL THEN
        RAISE EXCEPTION 'Aucun compte principal trouvé';
    END IF;

    IF p_montant_initial > 0 THEN
        IF (SELECT solde FROM compte WHERE id = v_compte_principal_id) < p_montant_initial THEN
            RAISE EXCEPTION 'Solde insuffisant';
        END IF;
    END IF;

    INSERT INTO compte (id_user, id_telephone, solde, type_compte, is_principal, password)
    VALUES (p_id_user, NEXTVAL('compte_id_seq'), p_montant_initial, 'compteSecondaire', FALSE,
            (SELECT password FROM "user" WHERE id = p_id_user))
    RETURNING id INTO v_nouveau_compte_id;

    IF p_montant_initial > 0 THEN
        UPDATE compte SET solde = solde - p_montant_initial WHERE id = v_compte_principal_id;

        INSERT INTO transactions (id_compte, montant, type_transactions, description, reference)
        VALUES (v_compte_principal_id, p_montant_initial, 'retrait', 'Transfert vers secondaire', 'TRANS_' || v_nouveau_compte_id);

        INSERT INTO transactions (id_compte, montant, type_transactions, description, reference)
        VALUES (v_nouveau_compte_id, p_montant_initial, 'depot', 'Transfert depuis principal', 'TRANS_' || v_compte_principal_id);
    END IF;

    RETURN v_nouveau_compte_id;
END;
$$;


ALTER FUNCTION public.creer_compte_secondaire(p_id_user integer, p_telephone character varying, p_montant_initial numeric) OWNER TO postgres;

--
-- TOC entry 237 (class 1255 OID 17167)
-- Name: promouvoir_compte_principal(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.promouvoir_compte_principal(p_id_compte integer, p_id_user integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_ancien_principal_id INTEGER;
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM compte 
        WHERE id = p_id_compte AND id_user = p_id_user AND is_principal = FALSE
    ) THEN
        RAISE EXCEPTION 'Compte non valide ou déjà principal';
    END IF;

    SELECT id INTO v_ancien_principal_id 
    FROM compte 
    WHERE id_user = p_id_user AND is_principal = TRUE;

    UPDATE compte 
    SET is_principal = FALSE, type_compte = 'compteSecondaire' 
    WHERE id = v_ancien_principal_id;

    UPDATE compte 
    SET is_principal = TRUE, type_compte = 'comptePrincipal' 
    WHERE id = p_id_compte;

    RETURN TRUE;
END;
$$;


ALTER FUNCTION public.promouvoir_compte_principal(p_id_compte integer, p_id_user integer) OWNER TO postgres;

--
-- TOC entry 224 (class 1255 OID 17162)
-- Name: update_updated_at_column(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_updated_at_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_updated_at_column() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 220 (class 1259 OID 17123)
-- Name: compte; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compte (
    id integer NOT NULL,
    id_user integer NOT NULL,
    id_telephone integer NOT NULL,
    solde numeric(15,2) DEFAULT 0.00,
    type_compte public.type_compte NOT NULL,
    is_principal boolean DEFAULT false,
    password character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_solde_positive CHECK ((solde >= (0)::numeric))
);


ALTER TABLE public.compte OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 17122)
-- Name: compte_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.compte_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.compte_id_seq OWNER TO postgres;

--
-- TOC entry 3486 (class 0 OID 0)
-- Dependencies: 219
-- Name: compte_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.compte_id_seq OWNED BY public.compte.id;


--
-- TOC entry 218 (class 1259 OID 17107)
-- Name: profil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.profil (
    id integer NOT NULL,
    id_user integer NOT NULL,
    client character varying(100),
    agent_commercial character varying(100),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.profil OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 17106)
-- Name: profil_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.profil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.profil_id_seq OWNER TO postgres;

--
-- TOC entry 3487 (class 0 OID 0)
-- Dependencies: 217
-- Name: profil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.profil_id_seq OWNED BY public.profil.id;


--
-- TOC entry 222 (class 1259 OID 17139)
-- Name: transactions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transactions (
    id integer NOT NULL,
    id_compte integer NOT NULL,
    date_transaction timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    montant numeric(15,2) NOT NULL,
    type_transactions public.type_transactions NOT NULL,
    description text,
    reference character varying(100),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_montant_positive CHECK ((montant > (0)::numeric))
);


ALTER TABLE public.transactions OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 17138)
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transactions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transactions_id_seq OWNER TO postgres;

--
-- TOC entry 3488 (class 0 OID 0)
-- Dependencies: 221
-- Name: transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transactions_id_seq OWNED BY public.transactions.id;


--
-- TOC entry 216 (class 1259 OID 17094)
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    telephone character varying(20) NOT NULL,
    password character varying(255) NOT NULL,
    cni character varying(50),
    nom character varying(100) NOT NULL,
    prenom character varying(100) NOT NULL,
    adresse text,
    photo_recto character varying(255),
    photo_verso character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 17093)
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_id_seq OWNER TO postgres;

--
-- TOC entry 3489 (class 0 OID 0)
-- Dependencies: 215
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- TOC entry 223 (class 1259 OID 17168)
-- Name: vue_dernieres_transactions; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vue_dernieres_transactions AS
 SELECT t.id,
    t.id_compte,
    u.nom,
    u.prenom,
    c.solde AS solde_compte,
    t.montant,
    t.type_transactions,
    t.description,
    t.date_transaction,
    row_number() OVER (PARTITION BY t.id_compte ORDER BY t.date_transaction DESC) AS rang
   FROM ((public.transactions t
     JOIN public.compte c ON ((t.id_compte = c.id)))
     JOIN public."user" u ON ((c.id_user = u.id)));


ALTER VIEW public.vue_dernieres_transactions OWNER TO postgres;

--
-- TOC entry 3291 (class 2604 OID 17126)
-- Name: compte id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte ALTER COLUMN id SET DEFAULT nextval('public.compte_id_seq'::regclass);


--
-- TOC entry 3288 (class 2604 OID 17110)
-- Name: profil id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.profil ALTER COLUMN id SET DEFAULT nextval('public.profil_id_seq'::regclass);


--
-- TOC entry 3296 (class 2604 OID 17142)
-- Name: transactions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions ALTER COLUMN id SET DEFAULT nextval('public.transactions_id_seq'::regclass);


--
-- TOC entry 3285 (class 2604 OID 17097)
-- Name: user id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- TOC entry 3478 (class 0 OID 17123)
-- Dependencies: 220
-- Data for Name: compte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compte (id, id_user, id_telephone, solde, type_compte, is_principal, password, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3476 (class 0 OID 17107)
-- Dependencies: 218
-- Data for Name: profil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.profil (id, id_user, client, agent_commercial, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3480 (class 0 OID 17139)
-- Dependencies: 222
-- Data for Name: transactions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transactions (id, id_compte, date_transaction, montant, type_transactions, description, reference, created_at) FROM stdin;
\.


--
-- TOC entry 3474 (class 0 OID 17094)
-- Dependencies: 216
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (id, telephone, password, cni, nom, prenom, adresse, photo_recto, photo_verso, created_at, updated_at) FROM stdin;
12	99999999	1234	123456789786	niangv	aida	senegal	recto_6870fc5d8f90b_orangemon.png	verso_6870fc5d8f913_women.jpg	2025-07-11 11:58:21.588468	2025-07-11 11:58:21.588468
13	mmm	1234	111111	mmm	admin@odc.sn	mmm	recto_6871022c485a8_image.png	verso_6871022c485ae_image.png	2025-07-11 12:23:08.297558	2025-07-11 12:23:08.297558
14	771111111	1234	1230000000	zafe	seck	senegal	recto_68710d94bfcd2_women.jpg	verso_68710d94bfcda_realistic-speech-bubble-illustration.png	2025-07-11 13:11:48.786197	2025-07-11 13:11:48.786197
15	7711111110	1234	12300000004	zafea	seck	senegal	recto_68710dbf7f49e_realistic-speech-bubble-illustration.png	verso_68710dbf7f4a8_realistic-speech-bubble-illustration.png	2025-07-11 13:12:31.522667	2025-07-11 13:12:31.522667
16	774444444	1234	10099888	mbodj	mareme	senegal	recto_6871109f51543_women.jpg	verso_6871109f5154c_realistic-speech-bubble-illustration.png	2025-07-11 13:24:47.33405	2025-07-11 13:24:47.33405
17	779091245	1234	28799999	BU	ODC	senegal	recto_687112f398293_realistic-speech-bubble-illustration.png	verso_687112f39829c_om.png	2025-07-11 13:34:43.624654	2025-07-11 13:34:43.624654
18	773456789	12345	2879999976	BUg	ODC	senegal	recto_6871136955416_realistic-speech-bubble-illustration.png	verso_6871136955420_orangemon.png	2025-07-11 13:36:41.350023	2025-07-11 13:36:41.350023
19	12778801947	1234	bb	Niang	ff	senegal	recto_687120fda6678_realistic-speech-bubble-illustration.png	verso_687120fda6680_realistic-speech-bubble-illustration.png	2025-07-11 14:34:37.683121	2025-07-11 14:34:37.683121
20	777777777	12345	123450900	aa	bb	senegal	recto_687125795cc62_women.jpg	verso_687125795cc6d_women.jpg	2025-07-11 14:53:45.381483	2025-07-11 14:53:45.381483
21	779999999	1234	2134567788	nn	nn	senegal	recto_68712a4734d90_realistic-speech-bubble-illustration.png	verso_68712a4734d9a_women.jpg	2025-07-11 15:14:15.217288	2025-07-11 15:14:15.217288
22	778765432	123456	1234567890987	astou	mbow	senegal	recto_68715a36d520c_realistic-speech-bubble-illustration.png	verso_68715a36d5218_women.jpg	2025-07-11 18:38:46.874149	2025-07-11 18:38:46.874149
24	771230987	123456	1231298765543	ji	HU	senegal	recto_6871989d27d9a_women.jpg	verso_6871989d27da2_om.png	2025-07-11 23:05:01.163909	2025-07-11 23:05:01.163909
25	778801945	123456	2344550000	Niang	bb	senegal	recto_68719c61db7f6_realistic-speech-bubble-illustration.png	verso_68719c61db7fe_women.jpg	2025-07-11 23:21:05.899869	2025-07-11 23:21:05.899869
\.


--
-- TOC entry 3490 (class 0 OID 0)
-- Dependencies: 219
-- Name: compte_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.compte_id_seq', 1, false);


--
-- TOC entry 3491 (class 0 OID 0)
-- Dependencies: 217
-- Name: profil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.profil_id_seq', 1, false);


--
-- TOC entry 3492 (class 0 OID 0)
-- Dependencies: 221
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transactions_id_seq', 1, false);


--
-- TOC entry 3493 (class 0 OID 0)
-- Dependencies: 215
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_id_seq', 25, true);


--
-- TOC entry 3315 (class 2606 OID 17132)
-- Name: compte compte_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_pkey PRIMARY KEY (id);


--
-- TOC entry 3311 (class 2606 OID 17116)
-- Name: profil profil_id_user_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.profil
    ADD CONSTRAINT profil_id_user_key UNIQUE (id_user);


--
-- TOC entry 3313 (class 2606 OID 17114)
-- Name: profil profil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.profil
    ADD CONSTRAINT profil_pkey PRIMARY KEY (id);


--
-- TOC entry 3322 (class 2606 OID 17148)
-- Name: transactions transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_pkey PRIMARY KEY (id);


--
-- TOC entry 3303 (class 2606 OID 17175)
-- Name: user unique_cni; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT unique_cni UNIQUE (cni);


--
-- TOC entry 3305 (class 2606 OID 17189)
-- Name: user unique_telephone; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT unique_telephone UNIQUE (telephone);


--
-- TOC entry 3307 (class 2606 OID 17103)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 3309 (class 2606 OID 17177)
-- Name: user user_telephone_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_telephone_key UNIQUE (telephone);


--
-- TOC entry 3316 (class 1259 OID 17158)
-- Name: idx_compte_user; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_compte_user ON public.compte USING btree (id_user);


--
-- TOC entry 3318 (class 1259 OID 17159)
-- Name: idx_transactions_compte; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_transactions_compte ON public.transactions USING btree (id_compte);


--
-- TOC entry 3319 (class 1259 OID 17161)
-- Name: idx_transactions_date; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_transactions_date ON public.transactions USING btree (date_transaction);


--
-- TOC entry 3320 (class 1259 OID 17160)
-- Name: idx_transactions_type; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_transactions_type ON public.transactions USING btree (type_transactions);


--
-- TOC entry 3317 (class 1259 OID 17156)
-- Name: idx_unique_principal_per_user; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_unique_principal_per_user ON public.compte USING btree (id_user) WHERE (is_principal = true);


--
-- TOC entry 3301 (class 1259 OID 17178)
-- Name: idx_user_telephone; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_user_telephone ON public."user" USING btree (telephone);


--
-- TOC entry 3328 (class 2620 OID 17165)
-- Name: compte update_compte_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER update_compte_updated_at BEFORE UPDATE ON public.compte FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- TOC entry 3327 (class 2620 OID 17164)
-- Name: profil update_profil_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER update_profil_updated_at BEFORE UPDATE ON public.profil FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- TOC entry 3326 (class 2620 OID 17163)
-- Name: user update_user_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER update_user_updated_at BEFORE UPDATE ON public."user" FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- TOC entry 3324 (class 2606 OID 17133)
-- Name: compte compte_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_id_user_fkey FOREIGN KEY (id_user) REFERENCES public."user"(id) ON DELETE CASCADE;


--
-- TOC entry 3323 (class 2606 OID 17117)
-- Name: profil profil_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.profil
    ADD CONSTRAINT profil_id_user_fkey FOREIGN KEY (id_user) REFERENCES public."user"(id) ON DELETE CASCADE;


--
-- TOC entry 3325 (class 2606 OID 17149)
-- Name: transactions transactions_id_compte_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transactions
    ADD CONSTRAINT transactions_id_compte_fkey FOREIGN KEY (id_compte) REFERENCES public.compte(id) ON DELETE CASCADE;


-- Completed on 2025-07-12 11:21:24 GMT

--
-- PostgreSQL database dump complete
--

