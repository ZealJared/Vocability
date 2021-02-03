BEGIN TRANSACTION;
DROP TABLE IF EXISTS "word";
CREATE TABLE IF NOT EXISTS "word" (
	"id"	INTEGER,
	"created_at"	TEXT NOT NULL,
	"updated_at"	TEXT NOT NULL,
	"spelling"	TEXT NOT NULL UNIQUE,
	"illustration"	TEXT,
	"pronunciation"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "word_list_word";
CREATE TABLE IF NOT EXISTS "word_list_word" (
	"id"	INTEGER,
	"created_at"	TEXT NOT NULL,
	"updated_at"	TEXT NOT NULL,
	"word_list_id"	INTEGER NOT NULL,
	"word_id"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("word_id") REFERENCES "word"("id") on update cascade,
	FOREIGN KEY("word_list_id") REFERENCES "word_list"("id") on update cascade
);
DROP TABLE IF EXISTS "user";
CREATE TABLE IF NOT EXISTS "user" (
	"id"	INTEGER,
	"created_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"updated_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"name"	TEXT NOT NULL UNIQUE,
	"password"	varchar(255) NOT NULL,
	"token"	varchar(255) NOT NULL,
	"active"	INTEGER(1) DEFAULT 1,
	"admin"	INTEGER(1) DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "story";
CREATE TABLE IF NOT EXISTS "story" (
	"id"	INTEGER NOT NULL,
	"created_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"updated_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"title"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "story_page_word";
CREATE TABLE IF NOT EXISTS "story_page_word" (
	"id"	INTEGER NOT NULL,
	"created_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"updated_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"story_page_id"	INTEGER NOT NULL,
	"word_id"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("story_page_id") REFERENCES "story_page"("id") ON UPDATE CASCADE,
	FOREIGN KEY("word_id") REFERENCES "word"("id") ON UPDATE CASCADE
);
DROP TABLE IF EXISTS "word_list";
CREATE TABLE IF NOT EXISTS "word_list" (
	"id"	INTEGER,
	"created_at"	TEXT NOT NULL,
	"updated_at"	TEXT NOT NULL,
	"story_id"	INTEGER NOT NULL,
	"name"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("story_id") REFERENCES "story"("id") ON UPDATE CASCADE
);
DROP TABLE IF EXISTS "user_word";
CREATE TABLE IF NOT EXISTS "user_word" (
	"id"	INTEGER,
	"created_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"updated_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"user_id"	INTEGER NOT NULL,
	"word_id"	INTEGER NOT NULL,
	"score"	FLOAT NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("user_id") REFERENCES "user"("id") ON UPDATE CASCADE,
	FOREIGN KEY("word_id") REFERENCES "word"("id") on update cascade
);
DROP TABLE IF EXISTS "story_page";
CREATE TABLE IF NOT EXISTS "story_page" (
	"id"	INTEGER NOT NULL,
	"created_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"updated_at"	TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
	"story_id"	INTEGER NOT NULL,
	"text"	TEXT,
	"illustration"	TEXT NOT NULL,
	"audio"	TEXT NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT),
	FOREIGN KEY("story_id") REFERENCES "story"("id") ON UPDATE CASCADE
);
INSERT INTO "user" VALUES (1,'2019-09-04 6:23:49','2019-10-14 2:21:37','zealmayfield','$2y$10$drN55XnsdUZZaXUKVw4DWe0xupzgzU9LxkwW3F4w5IhqIcWnu4eiK','510db202ec363d5037552f1fd0c4d756d8a3e2c440a7aea09b4d4fb7f4b1e3c7a281c5f0bea1f5fbd8c61d019f28498a7c0d017942aa1f56e4419a52a3037e948340b1add85abee5bfb9113e5d1a7e91720c16eacc281cb593ddc77d3eb88164895b734e',1,1);
DROP INDEX IF EXISTS "word_only_once_per_list";
CREATE UNIQUE INDEX IF NOT EXISTS "word_only_once_per_list" ON "word_list_word" (
	"word_list_id",
	"word_id"
);
DROP TRIGGER IF EXISTS "word_updated_at";
CREATE TRIGGER [word_updated_at]
    AFTER UPDATE
    ON word
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE word SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "word_list_word_updated_at";
CREATE TRIGGER [word_list_word_updated_at]
    AFTER UPDATE
    ON word_list_word
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE word_list_word SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "user_updated_at";
CREATE TRIGGER [user_updated_at]
    AFTER UPDATE
    ON user
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE user SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "story_updated_at";
CREATE TRIGGER [story_updated_at]
    AFTER UPDATE
    ON story
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE story SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "story_page_word_updated_at";
CREATE TRIGGER [story_page_word_updated_at]
    AFTER UPDATE
    ON story_page_word
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE story_page_word SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "word_list_updated_at";
CREATE TRIGGER [word_list_updated_at]
    AFTER UPDATE
    ON word_list
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE word_list SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "user_word_updated_at";
CREATE TRIGGER [user_word_updated_at]
    AFTER UPDATE
    ON user_word
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE user_word SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
DROP TRIGGER IF EXISTS "story_page_updated_at";
CREATE TRIGGER [story_page_updated_at]
    AFTER UPDATE
    ON story_page
    FOR EACH ROW
    WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE story_page SET updated_at=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;
COMMIT;
