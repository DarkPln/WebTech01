-- Migration: User profile fields + messages
-- Run once against the auto24 database

USE auto24;

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS email  VARCHAR(200) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS phone  VARCHAR(50)  DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS city   VARCHAR(100) DEFAULT NULL;

ALTER TABLE listings
    ADD COLUMN IF NOT EXISTS antrieb VARCHAR(50) NULL,
    ADD COLUMN IF NOT EXISTS images  TEXT        NULL;

CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    title      VARCHAR(200) NOT NULL,
    body       TEXT,
    is_read    TINYINT(1)   NOT NULL DEFAULT 0,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_messages_user (user_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
