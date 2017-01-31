/**
 * SQLite
 */

DROP TABLE IF EXISTS "page";

CREATE TABLE page (
  id INTEGER PRIMARY KEY NOT NULL,
  title TEXT,
  created_at TIMESTAMP WITH TIME ZONE

);

INSERT INTO "page" VALUES
  (1,	'home page', ''),
  (4,	'about', ''),
  (16386, 'contacts', '');


DROP TABLE IF EXISTS "session";

CREATE TABLE "session" (
  id INTEGER PRIMARY KEY NOT NULL,
  session_id VARCHAR(40),
  expired_at TIMESTAMP WITH TIME ZONE

);
