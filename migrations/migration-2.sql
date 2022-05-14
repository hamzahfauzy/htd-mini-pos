ALTER TABLE products
    ADD COLUMN default_stock VARCHAR(20) DEFAULT 'tersedia';

INSERT INTO migrations (filename) VALUES ('migration-2.sql');