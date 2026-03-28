-- Creation of MySQL Schema for Sakura Ramen

CREATE DATABASE IF NOT EXISTS sakura_ramen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sakura_ramen;

-- 1. Roles Table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 3. Pages CMS Table
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    is_published TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. SEO Tags Table (per page)
CREATE TABLE IF NOT EXISTS page_seo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    og_title VARCHAR(255),
    og_description TEXT,
    og_image VARCHAR(255),
    canonical_url VARCHAR(255),
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Team Members Table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    bio TEXT,
    image_url VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 6. Menu Categories Table
CREATE TABLE IF NOT EXISTS menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- 7. Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    badges JSON, -- e.g. ["signature", "veggie", "spicy"]
    is_available TINYINT(1) DEFAULT 1,
    is_on_site TINYINT(1) DEFAULT 1,
    is_to_go TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Restaurant Schedules
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_name ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche') NOT NULL,
    is_closed TINYINT(1) DEFAULT 0,
    morning_open TIME,
    morning_close TIME,
    evening_open TIME,
    evening_close TIME
) ENGINE=InnoDB;

-- 9. Contact Messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 10. Global Settings
CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT,
    `group` VARCHAR(50) DEFAULT 'general',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 11. Social Links
CREATE TABLE IF NOT EXISTS social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_class VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Seed Basic Data
INSERT INTO roles (name, description) VALUES ('Admin', 'Full access to CMS and system');

INSERT INTO users (role_id, username, password, email) 
VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@sakura-ramen.fr');

INSERT INTO menu_categories (name, description, slug, display_order) VALUES 
('Entrées (Otsumami)', 'Pour commencer votre voyage', 'entrees', 1),
('Ramen Signature', 'Nos bouillons premium mijotés 12h', 'ramen', 2),
('Drinks & Desserts', 'Une note de douceur sucrée', 'desserts', 3);

INSERT INTO menu_items (category_id, name, description, price, image_url, badges) VALUES 
(1, 'Gyoza Maison (x5)', 'Raviolis grillés au porc et gingembre.', 7.50, '/img/item_gyoza.png', '[]'),
(1, 'Edamame au Fleur de Sel', 'Fèves de soja à la vapeur.', 5.00, '/img/item_edamame.png', '["veggie"]'),
(1, 'Karaage Sakura', 'Poulet frit à la japonaise, mayonnaise au yuzu.', 8.50, '/img/item_karaage.png', '["signature"]'),
(2, 'Shio Ramen Sakura', 'Bouillon clair au sel de mer, chashu de porc fondant, ajitama et bambou.', 14.50, '/img/item_shio_ramen.png', '["signature"]'),
(2, 'Tonkotsu Classic', 'Bouillon d''os de porc crémeux, ail noir, champignons noirs et nori.', 16.00, '/img/item_tonkotsu.png', '["signature"]'),
(2, 'Tantanmen Épicé', 'Porc haché épicé, huile de piment, pâte de sésame et pak choï.', 15.50, '/img/item_tantanmen.png', '["spicy"]'),
(3, 'Mochi Glacé (x2)', 'Parfums : Matcha, Sésame Noir ou Fleur de Cerisier.', 6.50, '/img/item_mochi.png', '["veggie"]'),
(3, 'Sake Premium', 'Servi froid ou chaud, sélection artisanale.', 9.00, '/img/item_sake.png', '["signature"]'),
(3, 'Dorayaki au Haricot Rouge', 'Pancake japonais traditionnel.', 7.00, '/img/item_dorayaki.png', '[]');

INSERT INTO team_members (name, role, bio, image_url, display_order) VALUES 
('Sarah Valloton', 'Fondatrice & Visionnaire', 'Passionnée par la culture nippone depuis son plus jeune âge, Sarah a fondé Sakura Ramen avec une vision simple : apporter l''authenticité des ruelles de Tokyo au cœur de Vannes.', '/img/team_founder.png', 1),
('Chef Kenzo Sushi', 'Chef de Cuisine', 'Originaire d''Osaka, le Chef Kenzo apporte 20 ans d''expérience dans l''art du bouillon. Maître du Shio et du Tonkotsu, il refuse tout compromis sur la qualité.', '/img/team_chef.png', 2),
('Léa Durand', 'Chef de Salle', 'Léa assure la fluidité du service avec une élégance naturelle. Experte en sakés, elle saura vous conseiller l''accord parfait pour sublimer votre Ramen.', '/img/team_lea.jpg', 3),
('Nicolas Petit', 'Maître d''Hôtel', 'Véritable chef d''orchestre, Nicolas veille au confort de chaque client. Son sens du détail et son accueil chaleureux font de chaque visite une expérience unique.', '/img/team_nicolas.jpg', 4),
('Emma Leroy', 'Serveuse Sakura', 'Emma incarne l''esprit de service à la japonaise : discrétion, efficacité et attention constante. Toujours souriante, elle est aux petits soins pour vous.', '/img/team_emma.jpg', 5),
('Thomas Bernard', 'Serveur Sakura', 'Thomas partage sa passion pour la gastronomie nippone avec enthousiasme. Rapide et attentif, il s''assure que votre voyage culinaire soit sans fausse note.', '/img/team_thomas.jpg', 6);

INSERT INTO schedules (day_name, is_closed, morning_open, morning_close, evening_open, evening_close) VALUES
('Lundi', 1, NULL, NULL, NULL, NULL),
('Mardi', 0, '11:45:00', '14:00:00', '18:45:00', '22:00:00'),
('Mercredi', 0, '11:45:00', '14:00:00', '18:45:00', '22:00:00'),
('Jeudi', 0, '11:45:00', '14:00:00', '18:45:00', '22:00:00'),
('Vendredi', 0, '11:45:00', '14:00:00', '18:45:00', '22:30:00'),
('Samedi', 0, '11:45:00', '14:00:00', '18:45:00', '22:30:00'),
('Dimanche', 1, NULL, NULL, NULL, NULL);

INSERT INTO settings (`key`, `value`, `group`) VALUES
('site_name', 'Sakura Ramen', 'general'),
('address', '8, boulevard du Sakura, 56000 Vannes', 'general'),
('phone', '02 40 50 60 70', 'general'),
('contact_email', 'contact@sakura-ramen.fr', 'general');
