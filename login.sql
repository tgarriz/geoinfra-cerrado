CREATE DATABASE geoinfra WITH OWNER = postgres;

-- DROP EXTENSION pgcrypto;
CREATE EXTENSION pgcrypto SCHEMA public;

-- DROP SCHEMA seguridad;
CREATE SCHEMA seguridad AUTHORIZATION postgres;

-------------------------------------------------------------------------------
-- DROP TABLE seguridad.usuarios;
CREATE TABLE seguridad.usuarios
(
  gid serial NOT NULL,
  login character varying(20) NOT NULL,
  nombre character varying(80) NOT NULL,
  apellido character varying(80) NOT NULL,
  mail character varying(80) NOT NULL,
  cargo character varying(100),
  dependencia character varying(100),
  fecha_creacion date DEFAULT now(),
  --ult_acceso date,  --no seria necesario, esta almacenado en accesos
  id_perfil integer NOT NULL DEFAULT 1,
  CONSTRAINT usuario_pkey PRIMARY KEY (gid),
  CONSTRAINT usuarios_login_key UNIQUE (login),
  CONSTRAINT usuarios_mail_key UNIQUE (mail)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE seguridad.usuarios
  OWNER TO postgres;

-------------------------------------------------------------------------------
-- DROP TABLE seguridad.claves;
CREATE TABLE seguridad.claves
(
  gid serial NOT NULL,
  id_usuario integer NOT NULL,
  login character varying(32) NOT NULL,  --cambio de tamaño para almacenarlo en md5
  clave character varying(96) NOT NULL,
  CONSTRAINT claves_pkey PRIMARY KEY (gid)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE seguridad.claves
  OWNER TO postgres;

-------------------------------------------------------------------------------
-- DROP TABLE seguridad.perfiles;
CREATE TABLE seguridad.perfiles
(
  gid serial NOT NULL,
  perfil character varying(80) NOT NULL,
  CONSTRAINT perfil_pkey PRIMARY KEY (gid)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE seguridad.perfiles
  OWNER TO postgres;

-------------------------------------------------------------------------------
-- DROP TABLE seguridad.accesos;
CREATE TABLE seguridad.accesos
(
  gid serial NOT NULL,
  id_usuario integer NOT NULL,
  fecha date DEFAULT now(),  
  hora time without time zone DEFAULT ('now'::text)::time without time zone --poner defecto de la hora
  cookie character varying(128),
  ip character varying(20),
  proxy character varying(255),  
  idioma character(2),
  resolucion character varying(10),
  refer character varying(255),
  ua character varying(255),
  visita integer,
  error integer,
  CONSTRAINT accesos_pkey PRIMARY KEY (gid)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE seguridad.accesos
  OWNER TO postgres;

-------------------------------------------------------------------------------
-- DROP FUNCTION agregar_usuario(text, text, text, text, text, text, text, integer);
CREATE OR REPLACE FUNCTION agregar_usuario(login text, clave text, nombre text, apellido text, mail text, cargo text, dependencia text, id_perfil integer)
  RETURNS boolean AS
$BODY$
  DECLARE
	rec int;
	id int;
  BEGIN
	IF (login IS NULL OR login = '') THEN
		RAISE EXCEPTION 'El usuario no puede estar vacío';
		RETURN false;
	END IF;
	IF (clave IS NULL OR clave = '') THEN
		RAISE EXCEPTION 'La clave no puede estar vacía';
		RETURN false;
	END IF;
	IF (nombre IS NULL OR nombre = '') THEN
		RAISE EXCEPTION 'El nombre de usuario no puede estar vacío';
		RETURN false;
	END IF;
	IF (apellido IS NULL OR apellido = '') THEN
		RAISE EXCEPTION 'El apellido no puede estar vacío';
		RETURN false;
	END IF;
	IF (mail IS NULL OR mail = '') THEN
		RAISE EXCEPTION 'El mail no puede estar vacío';
		RETURN false;
	END IF;
	
	SELECT a.gid INTO rec FROM seguridad.claves AS a WHERE login = md5($1);
	IF FOUND THEN
		RAISE EXCEPTION 'El usuario ya existe';
		RETURN false;
	END IF;

	SELECT a.gid INTO rec FROM seguridad.usuarios AS a WHERE a.mail = $5;
	IF FOUND THEN
		RAISE EXCEPTION 'El mail ya existe';
		RETURN false;
	END IF;

	INSERT INTO seguridad.usuarios (login, nombre, apellido, mail, cargo, dependencia, id_perfil) VALUES ( $1, $3, $4, $5, $6, $7, $8 ) RETURNING gid INTO id;
	INSERT INTO seguridad.claves (id_usuario, login, clave) VALUES (id, md5($1), crypt( $2, gen_salt('bf', 8) ));

	RETURN true;
  END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION agregar_usuario(text, text, text, text, text, text, text, integer)
  OWNER TO postgres;

-------------------------------------------------------------------------------
-- DROP FUNCTION validar_usuario(text, text);
CREATE OR REPLACE FUNCTION validar_usuario(IN usuario text, IN clave text)
  RETURNS TABLE(id integer, nombre text, perfil integer) AS
$BODY$ SELECT a.id_usuario, b.nombre, b.id_perfil FROM seguridad.claves AS a
	INNER JOIN seguridad.usuarios AS b ON a.id_usuario = b.gid
	WHERE a.login = md5($1) AND a.clave = crypt($2, a.clave) LIMIT 1 $BODY$
  LANGUAGE sql VOLATILE
  COST 100
  ROWS 1;
ALTER FUNCTION validar_usuario(text, text)
  OWNER TO postgres;
  
--------------------------------------------------------------------------------
-- DROP FUNCTION validar_usuario2(text, text);
CREATE OR REPLACE FUNCTION validar_usuario2(IN usuario text, IN clave text)
  RETURNS json AS
$BODY$ 
DECLARE
	rec RECORD;
BEGIN
	SELECT 0 as success, a.id_usuario as id, b.nombre, b.id_perfil as perfil, a.clave as nodata INTO rec FROM seguridad.claves AS a
	INNER JOIN seguridad.usuarios AS b ON a.id_usuario = b.gid
	WHERE a.login = md5($1) LIMIT 1;

	IF FOUND THEN
		IF (SELECT rec.nodata = crypt($2, rec.nodata)) THEN
			rec.success := 1;
		END IF;
		rec.nodata := null;
	ELSE
		rec.id := 0;
		rec.success := 0;
	END IF;	

	RETURN row_to_json(rec);
END;
 $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;  
  
-------------------------------------------------------------------------------
-- DROP FUNCTION registrar_acceso(integer, text, text, text, text, text, text, text, integer, integer);

--CREATE OR REPLACE FUNCTION registrar_acceso(uid integer, cookie text, ip text, proxy text, lang text, screen text, referer text, user_agent text, visit integer, error integer)
--  RETURNS int AS
--$BODY$ 
--	INSERT INTO seguridad.accesos (id_usuario, cookie, ip, proxy, idioma, resolucion, refer, ua, visita, error) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) RETURNING gid
--$BODY$
--  LANGUAGE sql VOLATILE
--  COST 100;
--ALTER FUNCTION registrar_acceso(integer, text, text, text, text, text, text, text, integer, integer)
--  OWNER TO postgres;

-------------------------------------------------------------------------------
--Registrar nuevo usuario
--SELECT agregar_usuario('login' ,'clave' ,'nombre' ,'apellido' ,'mail@algo.com', null ,null ,1);
/* true o excepcion */

--Comprobar usuario
--SELECT * FROM validar_usuario('login', 'clave');
/* id  | nombre | perfil */

--Comprobar usuario retornando id de un usuario valido aunque falle la contraseña
--SELECT validar_usuario2('login','clave') AS result;
/*return json
"{"success":0,"id":0,"nombre":null,"perfil":null,"nodata":null}"
*/

--Registrar acceso
--SELECT registrar_acceso(0, 'cookie', '192.168.0.2', '192.168.0.1', 'es', '1900x800', 'http://algunaweb.com', 'user_agent', 1, 2);


