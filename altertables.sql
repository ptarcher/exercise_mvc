ALTER TABLE t_users ADD COLUMN athlete     boolean NOT NULL DEFAULT true;
ALTER TABLE t_users ADD COLUMN coach       boolean NOT NULL DEFAULT false;
ALTER TABLE t_users ADD COLUMN "superuser" boolean NOT NULL DEFAULT false;

UPDATE t_users SET superuser = 't' WHERE userid = 'ptarcher';
