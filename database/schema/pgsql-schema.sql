--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 14.5

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: activity_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_logs (
    id bigint NOT NULL,
    user_id bigint,
    event character varying(255) NOT NULL,
    description character varying(255),
    subject_type character varying(255),
    subject_id bigint,
    properties json,
    ip_address character varying(45),
    user_agent text,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted_at timestamp(0) without time zone
);


--
-- Name: activity_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_logs_id_seq OWNED BY public.activity_logs.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: exports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.exports (
    id bigint NOT NULL,
    completed_at timestamp(0) without time zone,
    file_disk character varying(255) NOT NULL,
    file_name character varying(255),
    exporter character varying(255) NOT NULL,
    processed_rows integer DEFAULT 0 NOT NULL,
    total_rows integer NOT NULL,
    successful_rows integer DEFAULT 0 NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: exports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.exports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: exports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.exports_id_seq OWNED BY public.exports.id;


--
-- Name: failed_import_rows; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_import_rows (
    id bigint NOT NULL,
    data json NOT NULL,
    import_id bigint NOT NULL,
    validation_error text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: failed_import_rows_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_import_rows_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_import_rows_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_import_rows_id_seq OWNED BY public.failed_import_rows.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gtk; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gtk (
    id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    nik character varying(255),
    nip character varying(255),
    nokarpeg character varying(255),
    nuptk character varying(255),
    jenis_kelamin character varying(255),
    tempat_lahir character varying(255),
    tanggal_lahir date,
    alamat text,
    desa character varying(255),
    kecamatan character varying(255),
    kabupaten character varying(255),
    provinsi character varying(255),
    agama character varying(255),
    pendidikan_terakhir character varying(255),
    daerah_asal character varying(255),
    jenis_gtk character varying(255),
    status_kepegawaian character varying(255),
    tmt_pns date,
    pangkat_gol_terakhir character varying(255),
    tmt_pangkat_gol_terakhir date,
    nama_bank_gaji character varying(255),
    no_rek_gaji character varying(255),
    nama_bank_tunjangan character varying(255),
    no_rek_tunjangan character varying(255),
    npwp character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT gtk_daerah_asal_check CHECK (((daerah_asal)::text = ANY ((ARRAY['Papua'::character varying, 'Non Papua'::character varying])::text[]))),
    CONSTRAINT gtk_jenis_gtk_check CHECK (((jenis_gtk)::text = ANY ((ARRAY['Kepala Sekolah'::character varying, 'Guru'::character varying, 'Tenaga Administrasi'::character varying])::text[]))),
    CONSTRAINT gtk_jenis_kelamin_check CHECK (((jenis_kelamin)::text = ANY ((ARRAY['Laki-laki'::character varying, 'Perempuan'::character varying])::text[]))),
    CONSTRAINT gtk_status_kepegawaian_check CHECK (((status_kepegawaian)::text = ANY ((ARRAY['PNS'::character varying, 'CPNS'::character varying, 'PPPK'::character varying, 'GTY/PTY'::character varying, 'Kontrak'::character varying, 'Honorer Sekolah'::character varying])::text[])))
);


--
-- Name: gtk_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gtk_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gtk_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gtk_id_seq OWNED BY public.gtk.id;


--
-- Name: gtk_kehadiran; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gtk_kehadiran (
    id bigint NOT NULL,
    gtk_id bigint NOT NULL,
    laporan_id bigint,
    tgl_presensi date NOT NULL,
    presensi character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT gtk_kehadiran_presensi_check CHECK (((presensi)::text = ANY (ARRAY['H'::text, 'I'::text, 'S'::text, 'A'::text, 'L'::text])))
);


--
-- Name: gtk_kehadiran_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gtk_kehadiran_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gtk_kehadiran_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gtk_kehadiran_id_seq OWNED BY public.gtk_kehadiran.id;


--
-- Name: gtk_mengajar; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gtk_mengajar (
    id bigint NOT NULL,
    gtk_id bigint NOT NULL,
    rombel_id bigint,
    mapel_id bigint,
    jumlah_jam integer,
    semester character varying(255),
    tahun_ajaran character varying(255),
    laporan_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT gtk_mengajar_semester_check CHECK (((semester)::text = ANY ((ARRAY['ganjil'::character varying, 'genap'::character varying])::text[])))
);


--
-- Name: gtk_mengajar_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gtk_mengajar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gtk_mengajar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gtk_mengajar_id_seq OWNED BY public.gtk_mengajar.id;


--
-- Name: gtk_pendidikan; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gtk_pendidikan (
    id bigint NOT NULL,
    gtk_id bigint NOT NULL,
    thn_tamat_sd character varying(255),
    thn_tamat_smp character varying(255),
    thn_tamat_sma character varying(255),
    thn_tamat_d1 character varying(255),
    jurusan_d1 character varying(255),
    perguruan_tinggi_d1 character varying(255),
    thn_tamat_d2 character varying(255),
    jurusan_d2 character varying(255),
    perguruan_tinggi_d2 character varying(255),
    thn_tamat_d3 character varying(255),
    jurusan_d3 character varying(255),
    perguruan_tinggi_d3 character varying(255),
    thn_tamat_s1 character varying(255),
    jurusan_s1 character varying(255),
    perguruan_tinggi_s1 character varying(255),
    thn_tamat_s2 character varying(255),
    jurusan_s2 character varying(255),
    perguruan_tinggi_s2 character varying(255),
    thn_tamat_s3 character varying(255),
    jurusan_s3 character varying(255),
    perguruan_tinggi_s3 character varying(255),
    thn_akta4 character varying(255),
    jurusan_akta4 character varying(255),
    perguruan_tinggi_akta4 character varying(255),
    gelar_depan character varying(255),
    gelar_belakang character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: gtk_pendidikan_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gtk_pendidikan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gtk_pendidikan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gtk_pendidikan_id_seq OWNED BY public.gtk_pendidikan.id;


--
-- Name: gtk_tugas_tambahan; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gtk_tugas_tambahan (
    id bigint NOT NULL,
    gtk_id bigint NOT NULL,
    tugas_tambahan character varying(255),
    jumlah_jam integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: gtk_tugas_tambahan_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gtk_tugas_tambahan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gtk_tugas_tambahan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gtk_tugas_tambahan_id_seq OWNED BY public.gtk_tugas_tambahan.id;


--
-- Name: imports; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.imports (
    id bigint NOT NULL,
    completed_at timestamp(0) without time zone,
    file_name character varying(255) NOT NULL,
    file_path character varying(255) NOT NULL,
    importer character varying(255) NOT NULL,
    processed_rows integer DEFAULT 0 NOT NULL,
    total_rows integer NOT NULL,
    successful_rows integer DEFAULT 0 NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: imports_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.imports_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: imports_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.imports_id_seq OWNED BY public.imports.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: kehadiran_gtk; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kehadiran_gtk (
    id bigint NOT NULL,
    gtk_id bigint NOT NULL,
    laporan_id bigint,
    bulan integer,
    tahun integer,
    data_harian jsonb,
    hadir integer DEFAULT 0 NOT NULL,
    sakit integer DEFAULT 0 NOT NULL,
    izin integer DEFAULT 0 NOT NULL,
    alfa integer DEFAULT 0 NOT NULL,
    hari_kerja integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: kehadiran_gtk_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kehadiran_gtk_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kehadiran_gtk_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kehadiran_gtk_id_seq OWNED BY public.kehadiran_gtk.id;


--
-- Name: kelulusan; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kelulusan (
    id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    tahun integer NOT NULL,
    jumlah_peserta_ujian integer NOT NULL,
    jumlah_lulus integer NOT NULL,
    persentase_kelulusan numeric(5,2) NOT NULL,
    jumlah_lanjut_pt integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: kelulusan_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kelulusan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kelulusan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kelulusan_id_seq OWNED BY public.kelulusan.id;


--
-- Name: laporan; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan (
    id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    bulan integer NOT NULL,
    tahun integer NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    tanggal_submit timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    verified_at timestamp(0) without time zone,
    verified_by bigint,
    is_identitas_sekolah_valid boolean DEFAULT false NOT NULL,
    is_nominatif_gtk_valid boolean DEFAULT false NOT NULL,
    is_nominatif_siswa_valid boolean DEFAULT false NOT NULL,
    is_kondisi_sarpras_valid boolean DEFAULT false NOT NULL,
    is_kondisi_gtk_valid boolean DEFAULT false NOT NULL,
    is_kondisi_siswa_valid boolean DEFAULT false NOT NULL,
    is_sebaran_jam_valid boolean DEFAULT false NOT NULL,
    is_rekap_kehadiran_valid boolean DEFAULT false NOT NULL,
    is_kelulusan_valid boolean DEFAULT false NOT NULL,
    is_siswa_rombel_valid boolean DEFAULT false NOT NULL,
    is_siswa_umur_valid boolean DEFAULT false NOT NULL,
    is_siswa_agama_valid boolean DEFAULT false NOT NULL,
    is_siswa_daerah_valid boolean DEFAULT false NOT NULL,
    is_siswa_disabilitas_valid boolean DEFAULT false NOT NULL,
    is_siswa_beasiswa_valid boolean DEFAULT false NOT NULL,
    is_gtk_agama_valid boolean DEFAULT false NOT NULL,
    is_gtk_daerah_valid boolean DEFAULT false NOT NULL,
    is_gtk_status_valid boolean DEFAULT false NOT NULL,
    is_gtk_umur_valid boolean DEFAULT false NOT NULL,
    is_gtk_pendidikan_valid boolean DEFAULT false NOT NULL,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'verified'::character varying])::text[])))
);


--
-- Name: laporan_gedung; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_gedung (
    id bigint NOT NULL,
    laporan_id bigint NOT NULL,
    nama_ruang character varying(255) NOT NULL,
    jumlah_total integer NOT NULL,
    jumlah_baik integer NOT NULL,
    jumlah_rusak integer NOT NULL,
    status_kepemilikan character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_gedung_status_kepemilikan_check CHECK (((status_kepemilikan)::text = ANY ((ARRAY['milik'::character varying, 'pinjam'::character varying])::text[])))
);


--
-- Name: laporan_gedung_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_gedung_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_gedung_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_gedung_id_seq OWNED BY public.laporan_gedung.id;


--
-- Name: laporan_gtk; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_gtk (
    id bigint NOT NULL,
    laporan_id bigint NOT NULL,
    jenis_gtk character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_gtk_jenis_gtk_check CHECK (((jenis_gtk)::text = ANY ((ARRAY['kepala_sekolah'::character varying, 'guru'::character varying, 'tenaga_administrasi'::character varying])::text[])))
);


--
-- Name: laporan_gtk_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_gtk_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_gtk_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_gtk_id_seq OWNED BY public.laporan_gtk.id;


--
-- Name: laporan_gtk_kategori; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_gtk_kategori (
    id bigint NOT NULL,
    laporan_gtk_id bigint NOT NULL,
    jenis_kategori character varying(255) NOT NULL,
    sub_kategori character varying(255) NOT NULL,
    jumlah integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: laporan_gtk_kategori_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_gtk_kategori_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_gtk_kategori_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_gtk_kategori_id_seq OWNED BY public.laporan_gtk_kategori.id;


--
-- Name: laporan_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_id_seq OWNED BY public.laporan.id;


--
-- Name: laporan_keuangan; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_keuangan (
    id bigint NOT NULL,
    laporan_id bigint NOT NULL,
    tanggal date,
    jenis_transaksi character varying(255) NOT NULL,
    keterangan text,
    nominal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    saldo numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_keuangan_jenis_transaksi_check CHECK (((jenis_transaksi)::text = ANY ((ARRAY['kredit'::character varying, 'debit'::character varying])::text[])))
);


--
-- Name: laporan_keuangan_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_keuangan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_keuangan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_keuangan_id_seq OWNED BY public.laporan_keuangan.id;


--
-- Name: laporan_siswa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_siswa (
    id bigint NOT NULL,
    laporan_id bigint NOT NULL,
    rombel_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: laporan_siswa_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_siswa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_siswa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_siswa_id_seq OWNED BY public.laporan_siswa.id;


--
-- Name: laporan_siswa_kategori; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_siswa_kategori (
    id bigint NOT NULL,
    laporan_siswa_id bigint NOT NULL,
    jenis_kategori character varying(255) NOT NULL,
    sub_kategori character varying(255) NOT NULL,
    laki_laki integer NOT NULL,
    perempuan integer NOT NULL,
    total integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_siswa_kategori_jenis_kategori_check CHECK (((jenis_kategori)::text = ANY ((ARRAY['umur'::character varying, 'agama'::character varying, 'asal_daerah'::character varying, 'disabilitas'::character varying, 'beasiswa'::character varying])::text[])))
);


--
-- Name: laporan_siswa_kategori_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_siswa_kategori_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_siswa_kategori_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_siswa_kategori_id_seq OWNED BY public.laporan_siswa_kategori.id;


--
-- Name: laporan_siswa_rekap; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.laporan_siswa_rekap (
    id bigint NOT NULL,
    laporan_siswa_id bigint NOT NULL,
    kategori character varying(255) NOT NULL,
    laki_laki integer NOT NULL,
    perempuan integer NOT NULL,
    total integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT laporan_siswa_rekap_kategori_check CHECK (((kategori)::text = ANY ((ARRAY['awal_bulan'::character varying, 'mutasi_masuk'::character varying, 'mutasi_keluar'::character varying, 'putus_sekolah'::character varying, 'mengulang'::character varying, 'akhir_bulan'::character varying])::text[])))
);


--
-- Name: laporan_siswa_rekap_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.laporan_siswa_rekap_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: laporan_siswa_rekap_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.laporan_siswa_rekap_id_seq OWNED BY public.laporan_siswa_rekap.id;


--
-- Name: mapel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mapel (
    id bigint NOT NULL,
    kode_mapel character varying(255),
    nama_mapel character varying(255) NOT NULL,
    jjp numeric(8,2),
    jenjang character varying(255),
    tingkat character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: mapel_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.mapel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mapel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.mapel_id_seq OWNED BY public.mapel.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data json NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: notifikasi; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notifikasi (
    id bigint NOT NULL,
    subject character varying(255) NOT NULL,
    content text NOT NULL,
    recipient_type character varying(255) NOT NULL,
    target_ids json,
    sender_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: notifikasi_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.notifikasi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: notifikasi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.notifikasi_id_seq OWNED BY public.notifikasi.id;


--
-- Name: operator_sekolah; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operator_sekolah (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT operator_sekolah_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: operator_sekolah_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.operator_sekolah_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: operator_sekolah_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.operator_sekolah_id_seq OWNED BY public.operator_sekolah.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: rombel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.rombel (
    id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    tingkat integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: rombel_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.rombel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: rombel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.rombel_id_seq OWNED BY public.rombel.id;


--
-- Name: sekolah; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sekolah (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    npsn character varying(255) NOT NULL,
    nss character varying(255),
    npwp character varying(255),
    jenjang character varying(255),
    alamat text,
    desa character varying(255),
    kecamatan character varying(255),
    kabupaten character varying(255),
    provinsi character varying(255),
    tahun_berdiri integer,
    nomor_sk_pendirian character varying(255),
    tanggal_sk_pendirian date,
    nama_yayasan character varying(255),
    alamat_yayasan text,
    nomor_sk_yayasan character varying(255),
    tanggal_sk_yayasan date,
    akreditasi character varying(255),
    status_tanah character varying(255),
    luas_tanah integer,
    email character varying(255),
    foto character varying(255),
    logo character varying(255),
    latitude numeric(10,8),
    longitude numeric(11,8),
    nama_rekening_bop character varying(255),
    nomor_rekening_bop character varying(255),
    nama_bank_bop character varying(255),
    nama_rekening_bosp character varying(255),
    nomor_rekening_bosp character varying(255),
    nama_bank_bosp character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT sekolah_jenjang_check CHECK (((jenjang)::text = ANY ((ARRAY['sma'::character varying, 'smk'::character varying])::text[]))),
    CONSTRAINT sekolah_status_tanah_check CHECK (((status_tanah)::text = ANY ((ARRAY['shm'::character varying, 'hgb'::character varying, 'ulayat'::character varying])::text[])))
);


--
-- Name: sekolah_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sekolah_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sekolah_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sekolah_id_seq OWNED BY public.sekolah.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: siswa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.siswa (
    id bigint NOT NULL,
    sekolah_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    nisn character varying(255),
    nokk character varying(255),
    nik character varying(255),
    nobpjs character varying(255),
    jenis_kelamin character varying(255),
    tempat_lahir character varying(255),
    tanggal_lahir date,
    alamat text,
    desa character varying(255),
    kecamatan character varying(255),
    kabupaten character varying(255),
    provinsi character varying(255),
    agama character varying(255),
    daerah_asal character varying(255),
    nama_ayah character varying(255),
    nama_ibu character varying(255),
    nama_wali character varying(255),
    nohp_ortuwali character varying(255),
    disabilitas character varying(255) DEFAULT 'tidak'::character varying NOT NULL,
    beasiswa character varying(255) DEFAULT 'tidak'::character varying NOT NULL,
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT siswa_agama_check CHECK (((agama)::text = ANY ((ARRAY['Islam'::character varying, 'Kristen'::character varying, 'Katolik'::character varying, 'Hindu'::character varying, 'Buddha'::character varying, 'Konghucu'::character varying])::text[]))),
    CONSTRAINT siswa_beasiswa_check CHECK (((beasiswa)::text = ANY ((ARRAY['tidak'::character varying, 'beasiswa_pemerintah_pusat'::character varying, 'beasiswa_pemerintah_daerah'::character varying, 'beasisswa_swasta'::character varying, 'beasiswa_khusus'::character varying, 'beasiswa_afirmasi'::character varying, 'beasiswa_lainnya'::character varying])::text[]))),
    CONSTRAINT siswa_daerah_asal_check CHECK (((daerah_asal)::text = ANY ((ARRAY['Papua'::character varying, 'Non Papua'::character varying])::text[]))),
    CONSTRAINT siswa_disabilitas_check CHECK (((disabilitas)::text = ANY ((ARRAY['tidak'::character varying, 'tuna_netra'::character varying, 'tuna_rungu'::character varying, 'tuna_wicara'::character varying, 'tuna_daksa'::character varying, 'tuna_grahita'::character varying, 'tuna_lainnya'::character varying])::text[]))),
    CONSTRAINT siswa_jenis_kelamin_check CHECK (((jenis_kelamin)::text = ANY ((ARRAY['Laki-laki'::character varying, 'Perempuan'::character varying])::text[]))),
    CONSTRAINT siswa_status_check CHECK (((status)::text = ANY ((ARRAY['aktif'::character varying, 'mutasi_masuk'::character varying, 'mutasi_keluar'::character varying, 'lulus'::character varying, 'putus_sekolah'::character varying, 'mengulang'::character varying])::text[])))
);


--
-- Name: siswa_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.siswa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: siswa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.siswa_id_seq OWNED BY public.siswa.id;


--
-- Name: siswa_rombel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.siswa_rombel (
    id bigint NOT NULL,
    siswa_id bigint NOT NULL,
    rombel_id bigint NOT NULL,
    tahun_ajaran character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: siswa_rombel_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.siswa_rombel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: siswa_rombel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.siswa_rombel_id_seq OWNED BY public.siswa_rombel.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    nohp character varying(255),
    CONSTRAINT users_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'active'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: wilayah_kab_bintuni; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.wilayah_kab_bintuni (
    kode character varying(255) NOT NULL,
    nama character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: activity_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs ALTER COLUMN id SET DEFAULT nextval('public.activity_logs_id_seq'::regclass);


--
-- Name: exports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exports ALTER COLUMN id SET DEFAULT nextval('public.exports_id_seq'::regclass);


--
-- Name: failed_import_rows id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_import_rows ALTER COLUMN id SET DEFAULT nextval('public.failed_import_rows_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gtk id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk ALTER COLUMN id SET DEFAULT nextval('public.gtk_id_seq'::regclass);


--
-- Name: gtk_kehadiran id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_kehadiran ALTER COLUMN id SET DEFAULT nextval('public.gtk_kehadiran_id_seq'::regclass);


--
-- Name: gtk_mengajar id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar ALTER COLUMN id SET DEFAULT nextval('public.gtk_mengajar_id_seq'::regclass);


--
-- Name: gtk_pendidikan id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_pendidikan ALTER COLUMN id SET DEFAULT nextval('public.gtk_pendidikan_id_seq'::regclass);


--
-- Name: gtk_tugas_tambahan id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_tugas_tambahan ALTER COLUMN id SET DEFAULT nextval('public.gtk_tugas_tambahan_id_seq'::regclass);


--
-- Name: imports id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.imports ALTER COLUMN id SET DEFAULT nextval('public.imports_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: kehadiran_gtk id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kehadiran_gtk ALTER COLUMN id SET DEFAULT nextval('public.kehadiran_gtk_id_seq'::regclass);


--
-- Name: kelulusan id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelulusan ALTER COLUMN id SET DEFAULT nextval('public.kelulusan_id_seq'::regclass);


--
-- Name: laporan id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan ALTER COLUMN id SET DEFAULT nextval('public.laporan_id_seq'::regclass);


--
-- Name: laporan_gedung id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gedung ALTER COLUMN id SET DEFAULT nextval('public.laporan_gedung_id_seq'::regclass);


--
-- Name: laporan_gtk id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk ALTER COLUMN id SET DEFAULT nextval('public.laporan_gtk_id_seq'::regclass);


--
-- Name: laporan_gtk_kategori id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk_kategori ALTER COLUMN id SET DEFAULT nextval('public.laporan_gtk_kategori_id_seq'::regclass);


--
-- Name: laporan_keuangan id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_keuangan ALTER COLUMN id SET DEFAULT nextval('public.laporan_keuangan_id_seq'::regclass);


--
-- Name: laporan_siswa id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa ALTER COLUMN id SET DEFAULT nextval('public.laporan_siswa_id_seq'::regclass);


--
-- Name: laporan_siswa_kategori id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_kategori ALTER COLUMN id SET DEFAULT nextval('public.laporan_siswa_kategori_id_seq'::regclass);


--
-- Name: laporan_siswa_rekap id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_rekap ALTER COLUMN id SET DEFAULT nextval('public.laporan_siswa_rekap_id_seq'::regclass);


--
-- Name: mapel id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapel ALTER COLUMN id SET DEFAULT nextval('public.mapel_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: notifikasi id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifikasi ALTER COLUMN id SET DEFAULT nextval('public.notifikasi_id_seq'::regclass);


--
-- Name: operator_sekolah id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operator_sekolah ALTER COLUMN id SET DEFAULT nextval('public.operator_sekolah_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: rombel id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rombel ALTER COLUMN id SET DEFAULT nextval('public.rombel_id_seq'::regclass);


--
-- Name: sekolah id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sekolah ALTER COLUMN id SET DEFAULT nextval('public.sekolah_id_seq'::regclass);


--
-- Name: siswa id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa ALTER COLUMN id SET DEFAULT nextval('public.siswa_id_seq'::regclass);


--
-- Name: siswa_rombel id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa_rombel ALTER COLUMN id SET DEFAULT nextval('public.siswa_rombel_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: activity_logs activity_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs
    ADD CONSTRAINT activity_logs_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: exports exports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exports
    ADD CONSTRAINT exports_pkey PRIMARY KEY (id);


--
-- Name: failed_import_rows failed_import_rows_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_import_rows
    ADD CONSTRAINT failed_import_rows_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: gtk_kehadiran gtk_kehadiran_gtk_id_tgl_presensi_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_kehadiran
    ADD CONSTRAINT gtk_kehadiran_gtk_id_tgl_presensi_unique UNIQUE (gtk_id, tgl_presensi);


--
-- Name: gtk_kehadiran gtk_kehadiran_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_kehadiran
    ADD CONSTRAINT gtk_kehadiran_pkey PRIMARY KEY (id);


--
-- Name: gtk_mengajar gtk_mengajar_gtk_id_rombel_id_mapel_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_gtk_id_rombel_id_mapel_id_unique UNIQUE (gtk_id, rombel_id, mapel_id);


--
-- Name: gtk_mengajar gtk_mengajar_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_pkey PRIMARY KEY (id);


--
-- Name: gtk_pendidikan gtk_pendidikan_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_pendidikan
    ADD CONSTRAINT gtk_pendidikan_pkey PRIMARY KEY (id);


--
-- Name: gtk gtk_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk
    ADD CONSTRAINT gtk_pkey PRIMARY KEY (id);


--
-- Name: gtk_tugas_tambahan gtk_tugas_tambahan_gtk_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_tugas_tambahan
    ADD CONSTRAINT gtk_tugas_tambahan_gtk_id_unique UNIQUE (gtk_id);


--
-- Name: gtk_tugas_tambahan gtk_tugas_tambahan_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_tugas_tambahan
    ADD CONSTRAINT gtk_tugas_tambahan_pkey PRIMARY KEY (id);


--
-- Name: imports imports_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.imports
    ADD CONSTRAINT imports_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: kehadiran_gtk kehadiran_gtk_gtk_id_laporan_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kehadiran_gtk
    ADD CONSTRAINT kehadiran_gtk_gtk_id_laporan_id_unique UNIQUE (gtk_id, laporan_id);


--
-- Name: kehadiran_gtk kehadiran_gtk_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kehadiran_gtk
    ADD CONSTRAINT kehadiran_gtk_pkey PRIMARY KEY (id);


--
-- Name: kelulusan kelulusan_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelulusan
    ADD CONSTRAINT kelulusan_pkey PRIMARY KEY (id);


--
-- Name: kelulusan kelulusan_sekolah_id_tahun_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelulusan
    ADD CONSTRAINT kelulusan_sekolah_id_tahun_unique UNIQUE (sekolah_id, tahun);


--
-- Name: laporan_gedung laporan_gedung_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gedung
    ADD CONSTRAINT laporan_gedung_pkey PRIMARY KEY (id);


--
-- Name: laporan_gtk_kategori laporan_gtk_kategori_laporan_gtk_id_jenis_kategori_sub_kategori; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk_kategori
    ADD CONSTRAINT laporan_gtk_kategori_laporan_gtk_id_jenis_kategori_sub_kategori UNIQUE (laporan_gtk_id, jenis_kategori, sub_kategori);


--
-- Name: laporan_gtk_kategori laporan_gtk_kategori_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk_kategori
    ADD CONSTRAINT laporan_gtk_kategori_pkey PRIMARY KEY (id);


--
-- Name: laporan_gtk laporan_gtk_laporan_id_jenis_gtk_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk
    ADD CONSTRAINT laporan_gtk_laporan_id_jenis_gtk_unique UNIQUE (laporan_id, jenis_gtk);


--
-- Name: laporan_gtk laporan_gtk_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk
    ADD CONSTRAINT laporan_gtk_pkey PRIMARY KEY (id);


--
-- Name: laporan_keuangan laporan_keuangan_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_keuangan
    ADD CONSTRAINT laporan_keuangan_pkey PRIMARY KEY (id);


--
-- Name: laporan laporan_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_pkey PRIMARY KEY (id);


--
-- Name: laporan laporan_sekolah_id_bulan_tahun_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_sekolah_id_bulan_tahun_unique UNIQUE (sekolah_id, bulan, tahun);


--
-- Name: laporan_siswa_kategori laporan_siswa_kategori_laporan_siswa_id_jenis_kategori_sub_kate; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_kategori
    ADD CONSTRAINT laporan_siswa_kategori_laporan_siswa_id_jenis_kategori_sub_kate UNIQUE (laporan_siswa_id, jenis_kategori, sub_kategori);


--
-- Name: laporan_siswa_kategori laporan_siswa_kategori_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_kategori
    ADD CONSTRAINT laporan_siswa_kategori_pkey PRIMARY KEY (id);


--
-- Name: laporan_siswa laporan_siswa_laporan_id_rombel_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa
    ADD CONSTRAINT laporan_siswa_laporan_id_rombel_id_unique UNIQUE (laporan_id, rombel_id);


--
-- Name: laporan_siswa laporan_siswa_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa
    ADD CONSTRAINT laporan_siswa_pkey PRIMARY KEY (id);


--
-- Name: laporan_siswa_rekap laporan_siswa_rekap_laporan_siswa_id_kategori_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_rekap
    ADD CONSTRAINT laporan_siswa_rekap_laporan_siswa_id_kategori_unique UNIQUE (laporan_siswa_id, kategori);


--
-- Name: laporan_siswa_rekap laporan_siswa_rekap_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_rekap
    ADD CONSTRAINT laporan_siswa_rekap_pkey PRIMARY KEY (id);


--
-- Name: mapel mapel_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapel
    ADD CONSTRAINT mapel_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: notifikasi notifikasi_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_pkey PRIMARY KEY (id);


--
-- Name: operator_sekolah operator_sekolah_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operator_sekolah
    ADD CONSTRAINT operator_sekolah_pkey PRIMARY KEY (id);


--
-- Name: operator_sekolah operator_sekolah_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operator_sekolah
    ADD CONSTRAINT operator_sekolah_user_id_unique UNIQUE (user_id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: rombel rombel_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rombel
    ADD CONSTRAINT rombel_pkey PRIMARY KEY (id);


--
-- Name: sekolah sekolah_npsn_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sekolah
    ADD CONSTRAINT sekolah_npsn_unique UNIQUE (npsn);


--
-- Name: sekolah sekolah_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sekolah
    ADD CONSTRAINT sekolah_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: siswa siswa_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa
    ADD CONSTRAINT siswa_pkey PRIMARY KEY (id);


--
-- Name: siswa_rombel siswa_rombel_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa_rombel
    ADD CONSTRAINT siswa_rombel_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: wilayah_kab_bintuni wilayah_kab_bintuni_kode_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.wilayah_kab_bintuni
    ADD CONSTRAINT wilayah_kab_bintuni_kode_unique UNIQUE (kode);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: activity_logs activity_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs
    ADD CONSTRAINT activity_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: exports exports_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.exports
    ADD CONSTRAINT exports_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: failed_import_rows failed_import_rows_import_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_import_rows
    ADD CONSTRAINT failed_import_rows_import_id_foreign FOREIGN KEY (import_id) REFERENCES public.imports(id) ON DELETE CASCADE;


--
-- Name: gtk_kehadiran gtk_kehadiran_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_kehadiran
    ADD CONSTRAINT gtk_kehadiran_gtk_id_foreign FOREIGN KEY (gtk_id) REFERENCES public.gtk(id) ON DELETE CASCADE;


--
-- Name: gtk_kehadiran gtk_kehadiran_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_kehadiran
    ADD CONSTRAINT gtk_kehadiran_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE SET NULL;


--
-- Name: gtk_mengajar gtk_mengajar_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_gtk_id_foreign FOREIGN KEY (gtk_id) REFERENCES public.gtk(id) ON DELETE CASCADE;


--
-- Name: gtk_mengajar gtk_mengajar_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE SET NULL;


--
-- Name: gtk_mengajar gtk_mengajar_mapel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_mapel_id_foreign FOREIGN KEY (mapel_id) REFERENCES public.mapel(id) ON DELETE CASCADE;


--
-- Name: gtk_mengajar gtk_mengajar_rombel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_mengajar
    ADD CONSTRAINT gtk_mengajar_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES public.rombel(id) ON DELETE CASCADE;


--
-- Name: gtk_pendidikan gtk_pendidikan_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_pendidikan
    ADD CONSTRAINT gtk_pendidikan_gtk_id_foreign FOREIGN KEY (gtk_id) REFERENCES public.gtk(id) ON DELETE CASCADE;


--
-- Name: gtk gtk_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk
    ADD CONSTRAINT gtk_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- Name: gtk_tugas_tambahan gtk_tugas_tambahan_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gtk_tugas_tambahan
    ADD CONSTRAINT gtk_tugas_tambahan_gtk_id_foreign FOREIGN KEY (gtk_id) REFERENCES public.gtk(id) ON DELETE CASCADE;


--
-- Name: imports imports_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.imports
    ADD CONSTRAINT imports_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: kehadiran_gtk kehadiran_gtk_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kehadiran_gtk
    ADD CONSTRAINT kehadiran_gtk_gtk_id_foreign FOREIGN KEY (gtk_id) REFERENCES public.gtk(id) ON DELETE CASCADE;


--
-- Name: kehadiran_gtk kehadiran_gtk_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kehadiran_gtk
    ADD CONSTRAINT kehadiran_gtk_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: kelulusan kelulusan_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelulusan
    ADD CONSTRAINT kelulusan_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- Name: laporan_gedung laporan_gedung_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gedung
    ADD CONSTRAINT laporan_gedung_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan_gtk_kategori laporan_gtk_kategori_laporan_gtk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk_kategori
    ADD CONSTRAINT laporan_gtk_kategori_laporan_gtk_id_foreign FOREIGN KEY (laporan_gtk_id) REFERENCES public.laporan_gtk(id) ON DELETE CASCADE;


--
-- Name: laporan_gtk laporan_gtk_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_gtk
    ADD CONSTRAINT laporan_gtk_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan_keuangan laporan_keuangan_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_keuangan
    ADD CONSTRAINT laporan_keuangan_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- Name: laporan_siswa_kategori laporan_siswa_kategori_laporan_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_kategori
    ADD CONSTRAINT laporan_siswa_kategori_laporan_siswa_id_foreign FOREIGN KEY (laporan_siswa_id) REFERENCES public.laporan_siswa(id) ON DELETE CASCADE;


--
-- Name: laporan_siswa laporan_siswa_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa
    ADD CONSTRAINT laporan_siswa_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan_siswa_rekap laporan_siswa_rekap_laporan_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa_rekap
    ADD CONSTRAINT laporan_siswa_rekap_laporan_siswa_id_foreign FOREIGN KEY (laporan_siswa_id) REFERENCES public.laporan_siswa(id) ON DELETE CASCADE;


--
-- Name: laporan_siswa laporan_siswa_rombel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan_siswa
    ADD CONSTRAINT laporan_siswa_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES public.rombel(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_verified_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_verified_by_foreign FOREIGN KEY (verified_by) REFERENCES public.users(id);


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_sender_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_sender_id_foreign FOREIGN KEY (sender_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: operator_sekolah operator_sekolah_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operator_sekolah
    ADD CONSTRAINT operator_sekolah_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- Name: operator_sekolah operator_sekolah_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operator_sekolah
    ADD CONSTRAINT operator_sekolah_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: rombel rombel_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.rombel
    ADD CONSTRAINT rombel_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- Name: siswa_rombel siswa_rombel_rombel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa_rombel
    ADD CONSTRAINT siswa_rombel_rombel_id_foreign FOREIGN KEY (rombel_id) REFERENCES public.rombel(id) ON DELETE CASCADE;


--
-- Name: siswa_rombel siswa_rombel_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa_rombel
    ADD CONSTRAINT siswa_rombel_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES public.siswa(id) ON DELETE CASCADE;


--
-- Name: siswa siswa_sekolah_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswa
    ADD CONSTRAINT siswa_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES public.sekolah(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 14.5

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
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_07_13_003809_create_ref_wilayah	1
5	2026_04_08_013311_sekolah	1
6	2026_04_08_013312_operator_sekolah	1
7	2026_04_08_013330_rombel	1
8	2026_04_08_013334_gtk	1
9	2026_04_08_013350_gtk_pendidikan	1
10	2026_04_08_013358_siswa	1
11	2026_04_08_013401_mapel	1
12	2026_04_08_013402_siswa_rombel	1
13	2026_04_08_013417_laporan	1
14	2026_04_08_013425_laporan_gedung	1
15	2026_04_08_013430_laporan_siswa	1
16	2026_04_08_013435_laporan_siswa_rekap	1
17	2026_04_08_013442_laporan_siswa_kategori	1
18	2026_04_08_013450_laporan_gtk	1
19	2026_04_08_013455_laporan_gtk_kategori	1
20	2026_04_08_013503_gtk_mengajar	1
21	2026_04_08_013503_gtk_tugas_tambahan	1
22	2026_04_08_013510_kehadiran_gtk	1
23	2026_04_08_013515_kelulusan	1
24	2026_04_08_020909_create_permission_tables	1
25	2026_04_15_214650_create_activity_logs_table	1
26	2026_04_16_141534_create_imports_table	1
27	2026_04_16_141535_create_exports_table	1
28	2026_04_16_141536_create_failed_import_rows_table	1
29	2026_04_21_125954_create_notifikasi_table	1
30	2026_04_21_131059_create_notifications_table	1
31	2026_04_26_152139_create_laporan_keuangans_table	1
32	2026_05_01_081630_create_gtk_kehadiran_table	1
33	2026_05_15_074418_update_presensi_enum_in_gtk_kehadiran_table	2
34	2026_05_16_053000_update_gender_enum_in_gtk_table	3
35	2026_05_16_104305_add_nohp_to_users_table	4
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 35, true);


--
-- PostgreSQL database dump complete
--

