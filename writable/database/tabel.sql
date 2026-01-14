-- public.auth_logins definition

-- Drop table

-- DROP TABLE public.auth_logins;

CREATE TABLE public.auth_logins (
	id serial NOT NULL,
	ip_address varchar(255) NOT NULL,
	user_agent varchar(255) NULL,
	id_type varchar(255) NOT NULL,
	identifier varchar(255) NOT NULL,
	user_id int4 NULL,
	"date" timestamp NOT NULL,
	success int2 NOT NULL,
	CONSTRAINT pk_auth_logins PRIMARY KEY (id)
);
CREATE INDEX auth_logins_id_type_identifier ON public.auth_logins USING btree (id_type, identifier);
CREATE INDEX auth_logins_user_id ON public.auth_logins USING btree (user_id);


-- public.auth_token_logins definition

-- Drop table

-- DROP TABLE public.auth_token_logins;

CREATE TABLE public.auth_token_logins (
	id serial NOT NULL,
	ip_address varchar(255) NOT NULL,
	user_agent varchar(255) NULL,
	id_type varchar(255) NOT NULL,
	identifier varchar(255) NOT NULL,
	user_id int4 NULL,
	"date" timestamp NOT NULL,
	success int2 NOT NULL,
	CONSTRAINT pk_auth_token_logins PRIMARY KEY (id)
);
CREATE INDEX auth_token_logins_id_type_identifier ON public.auth_token_logins USING btree (id_type, identifier);
CREATE INDEX auth_token_logins_user_id ON public.auth_token_logins USING btree (user_id);


-- public.legacy_settings definition

-- Drop table

-- DROP TABLE public.legacy_settings;

CREATE TABLE public.legacy_settings (
	id int4 NOT NULL DEFAULT nextval('settings_id_seq'::regclass),
	"class" varchar(255) NOT NULL,
	"key" varchar(255) NOT NULL,
	value text NULL,
	"type" varchar(31) NOT NULL DEFAULT 'string'::character varying,
	context varchar(255) NULL,
	created_at timestamp NOT NULL,
	updated_at timestamp NOT NULL,
	CONSTRAINT pk_legacy_settings PRIMARY KEY (id)
);


-- public.legacy_users definition

-- Drop table

-- DROP TABLE public.legacy_users;

CREATE TABLE public.legacy_users (
	id int4 NOT NULL DEFAULT nextval('users_id_seq'::regclass),
	username varchar(100) NOT NULL,
	email varchar(100) NOT NULL,
	"password" varchar(255) NOT NULL,
	"role" varchar(10) NOT NULL DEFAULT 'user'::character varying,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	deleted_at timestamp NULL,
	active bool NOT NULL DEFAULT true,
	force_pass_reset varchar(255) NULL,
	last_active timestamp NULL,
	status varchar(255) NULL,
	status_message varchar(255) NULL,
	role_id int4 NULL,
	is_active bool NULL DEFAULT true,
	last_login timestamp NULL,
	CONSTRAINT legacy_users_email_key UNIQUE (email),
	CONSTRAINT legacy_users_username_key UNIQUE (username),
	CONSTRAINT pk_legacy_users PRIMARY KEY (id)
);


-- public.mahasiswa definition

-- Drop table

-- DROP TABLE public.mahasiswa;

CREATE TABLE public.mahasiswa (
	id serial NOT NULL,
	nim varchar(20) NOT NULL,
	nama varchar(100) NOT NULL,
	email varchar(100) NOT NULL,
	jurusan varchar(100) NOT NULL,
	angkatan varchar(4) NOT NULL,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	CONSTRAINT mahasiswa_nim_key UNIQUE (nim),
	CONSTRAINT pk_mahasiswa PRIMARY KEY (id)
);


-- public.migrations definition

-- Drop table

-- DROP TABLE public.migrations;

CREATE TABLE public.migrations (
	id bigserial NOT NULL,
	"version" varchar(255) NOT NULL,
	"class" varchar(255) NOT NULL,
	"group" varchar(255) NOT NULL,
	"namespace" varchar(255) NOT NULL,
	"time" int4 NOT NULL,
	batch int4 NOT NULL,
	CONSTRAINT pk_migrations PRIMARY KEY (id)
);


-- public.permissions definition

-- Drop table

-- DROP TABLE public.permissions;

CREATE TABLE public.permissions (
	id serial NOT NULL,
	name varchar(50) NOT NULL,
	display_name varchar(100) NOT NULL,
	description text NULL,
	"module" varchar(50) NULL,
	is_active bool NOT NULL DEFAULT true,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	CONSTRAINT permissions_name_key UNIQUE (name),
	CONSTRAINT pk_permissions PRIMARY KEY (id)
);


-- public.roles definition

-- Drop table

-- DROP TABLE public.roles;

CREATE TABLE public.roles (
	id serial NOT NULL,
	name varchar(50) NOT NULL,
	display_name varchar(100) NOT NULL,
	description text NULL,
	is_active bool NOT NULL DEFAULT true,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	CONSTRAINT pk_roles PRIMARY KEY (id),
	CONSTRAINT roles_name_key UNIQUE (name)
);


-- public.settings definition

-- Drop table

-- DROP TABLE public.settings;

CREATE TABLE public.settings (
	id serial NOT NULL,
	"class" varchar(255) NOT NULL,
	"key" varchar(255) NOT NULL,
	value text NULL,
	"type" varchar(31) NOT NULL DEFAULT 'string'::character varying,
	created_at timestamp NOT NULL,
	updated_at timestamp NOT NULL,
	context varchar(255) NULL,
	CONSTRAINT pk_settings PRIMARY KEY (id)
);


-- public.users definition

-- Drop table

-- DROP TABLE public.users;

CREATE TABLE public.users (
	id serial NOT NULL,
	username varchar(30) NULL,
	status varchar(255) NULL,
	status_message varchar(255) NULL,
	active int2 NOT NULL DEFAULT 0,
	last_active timestamp NULL,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	deleted_at timestamp NULL,
	CONSTRAINT pk_users PRIMARY KEY (id),
	CONSTRAINT users_username UNIQUE (username)
);


-- public.auth_groups_users definition

-- Drop table

-- DROP TABLE public.auth_groups_users;

CREATE TABLE public.auth_groups_users (
	id serial NOT NULL,
	user_id int4 NOT NULL,
	"group" varchar(255) NOT NULL,
	created_at timestamp NOT NULL,
	CONSTRAINT pk_auth_groups_users PRIMARY KEY (id),
	CONSTRAINT auth_groups_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- public.auth_identities definition

-- Drop table

-- DROP TABLE public.auth_identities;

CREATE TABLE public.auth_identities (
	id serial NOT NULL,
	user_id int4 NOT NULL,
	"type" varchar(255) NOT NULL,
	name varchar(255) NULL,
	secret varchar(255) NOT NULL,
	secret2 varchar(255) NULL,
	expires timestamp NULL,
	extra text NULL,
	force_reset int2 NOT NULL DEFAULT 0,
	last_used_at timestamp NULL,
	created_at timestamp NULL,
	updated_at timestamp NULL,
	CONSTRAINT auth_identities_type_secret UNIQUE (type, secret),
	CONSTRAINT pk_auth_identities PRIMARY KEY (id),
	CONSTRAINT auth_identities_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE INDEX auth_identities_user_id ON public.auth_identities USING btree (user_id);


-- public.auth_permissions_users definition

-- Drop table

-- DROP TABLE public.auth_permissions_users;

CREATE TABLE public.auth_permissions_users (
	id serial NOT NULL,
	user_id int4 NOT NULL,
	"permission" varchar(255) NOT NULL,
	created_at timestamp NOT NULL,
	CONSTRAINT pk_auth_permissions_users PRIMARY KEY (id),
	CONSTRAINT auth_permissions_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- public.auth_remember_tokens definition

-- Drop table

-- DROP TABLE public.auth_remember_tokens;

CREATE TABLE public.auth_remember_tokens (
	id serial NOT NULL,
	selector varchar(255) NOT NULL,
	"hashedValidator" varchar(255) NOT NULL,
	user_id int4 NOT NULL,
	expires timestamp NOT NULL,
	created_at timestamp NOT NULL,
	updated_at timestamp NOT NULL,
	CONSTRAINT auth_remember_tokens_selector UNIQUE (selector),
	CONSTRAINT pk_auth_remember_tokens PRIMARY KEY (id),
	CONSTRAINT auth_remember_tokens_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- public.menus definition

-- Drop table

-- DROP TABLE public.menus;

CREATE TABLE public.menus (
	id serial NOT NULL,
	parent_id int4 NULL,
	"label" varchar(255) NOT NULL,
	url varchar(255) NULL,
	icon varchar(50) NULL,
	"permission" varchar(100) NULL,
	"type" varchar(20) NOT NULL DEFAULT 'item'::character varying,
	order_position int4 NOT NULL DEFAULT 0,
	is_active bool NOT NULL DEFAULT true,
	created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT menus_pkey PRIMARY KEY (id),
	CONSTRAINT fk_menus_parent FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE
);


-- public.role_permissions definition

-- Drop table

-- DROP TABLE public.role_permissions;

CREATE TABLE public.role_permissions (
	id serial NOT NULL,
	role_id int4 NOT NULL,
	permission_id int4 NOT NULL,
	created_at timestamp NULL,
	CONSTRAINT pk_role_permissions PRIMARY KEY (id),
	CONSTRAINT role_permissions_role_id_permission_id UNIQUE (role_id, permission_id),
	CONSTRAINT role_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions(id) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT role_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE CASCADE
);


-- public.user_apps_activity definition

-- Drop table

-- DROP TABLE public.user_apps_activity;

CREATE TABLE public.user_apps_activity (
	id serial NOT NULL,
	route text NOT NULL,
	"method" varchar(255) NOT NULL,
	is_ajax bpchar(1) NULL,
	create_date timestamp NOT NULL,
	id_user int4 NOT NULL,
	active int2 NOT NULL DEFAULT 1,
	"data" text NOT NULL,
	platform varchar(64) NULL,
	browser varchar(32) NULL,
	CONSTRAINT pk_user_apps_activity PRIMARY KEY (id),
	CONSTRAINT user_apps_activity_id_user_foreign FOREIGN KEY (id_user) REFERENCES legacy_users(id) ON UPDATE CASCADE ON DELETE CASCADE
);