CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE role_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    route_path VARCHAR(100) NOT NULL,
    CONSTRAINT fk_role_routes_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE application (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pos_mode VARCHAR(100) NULL,
    is_demo INT NULL,
    icon_url VARCHAR(100) DEFAULT 'assets/img/main-logo.png',
    footer_struk TEXT DEFAULT 'Selamat Berbelanja',
    cash_drawer_status VARCHAR(10) DEFAULT 'Off',
    fee_sales DOUBLE DEFAULT NULL,
    fee_sales_type VARCHAR(20) DEFAULT NULL,
    tax DOUBLE DEFAULT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(100) NOT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_categories_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unit_id INT NULL,
    name VARCHAR(100) NOT NULL,
    shortname VARCHAR(12) NOT NULL,
    code VARCHAR(100) NOT NULL,
    pic VARCHAR(100) NOT NULL DEFAULT 'assets/img/default-product-image.png',
    default_stock VARCHAR(20) DEFAULT 'tersedia',
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by INT DEFAULT NULL,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT fk_products_unit_id FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE SET NULL,
    CONSTRAINT fk_products_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_products_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    CONSTRAINT fk_product_categories_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_categories_category_id FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE product_stocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    qty INT NOT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by INT DEFAULT NULL,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    CONSTRAINT fk_product_stocks_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_stocks_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_product_stocks_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE product_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    base_price DOUBLE NOT NULL,
    member_price DOUBLE NOT NULL,
    discount_price DOUBLE NOT NULL,
    discount_type VARCHAR(10),
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_prices_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_proces_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(100) NOT NULL,
    address TEXT DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_customers_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    sales_id INT DEFAULT NULL,
    code VARCHAR(100) NOT NULL,
    notes TEXT DEFAULT NULL,
    total DOUBLE NOT NULL,
    remaining_payment DOUBLE DEFAULT 0,
    status VARCHAR(100) NOT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_invoices_customer_id FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    CONSTRAINT fk_invoices_sales_id FOREIGN KEY (sales_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_invoices_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    product_id INT NOT NULL,
    price DOUBLE NOT NULL,
    qty DOUBLE NOT NULL,
    subtotal DOUBLE NOT NULL,
    status VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_invoices_items_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_invoices_items_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    amount DOUBLE NOT NULL,
    amount_total DOUBLE NOT NULL,
    amount_return DOUBLE NOT NULL,
    payment_type VARCHAR(100) NOT NULL,
    payment_request JSON DEFAULT NULL,
    payment_response JSON DEFAULT NULL,
    notes VARCHAR(100) NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    CONSTRAINT fk_transactions_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);