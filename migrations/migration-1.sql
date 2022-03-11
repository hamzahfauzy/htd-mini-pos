ALTER TABLE application
    ADD COLUMN footer_struk TEXT DEFAULT 'Selamat Berbelanja',
    ADD COLUMN cash_drawer_status VARCHAR(10) DEFAULT 'Off';

ALTER TABLE products ALTER pic SET DEFAULT 'assets/img/default-product-image.png';

INSERT INTO migrations (filename) VALUES ('migration-1.sql');