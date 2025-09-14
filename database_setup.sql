-- 就活スケジューラー データベース初期化SQL
-- 使用方法: mysql -u root -p < database_setup.sql

-- データベース選択
USE fuelphp;

-- 既存テーブルを削除（外部キー制約の順序に注意）
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS statuses;
DROP TABLE IF EXISTS users;

-- 1. usersテーブル（1:n関係の親）
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. statusesテーブル（マスターデータ）
CREATE TABLE statuses (
    id INT(11) NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(30) NOT NULL,
    label_ja VARCHAR(30) NOT NULL,
    color_hex CHAR(7) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY key_unique (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. companiesテーブル（1:n関係の子）
CREATE TABLE companies (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    status_id INT(11) NOT NULL,
    website_url VARCHAR(255) NULL,
    position_title VARCHAR(100) NULL,
    employment_type VARCHAR(30) NULL,
    location_text VARCHAR(100) NULL,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id_index (user_id),
    KEY status_id_index (status_id),
    UNIQUE KEY user_company_unique (user_id, name),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. ステータス初期データ
INSERT INTO statuses (id, `key`, label_ja, color_hex) VALUES
(1, 'consider', '検討中', '#64748b'),
(2, 'entry', 'エントリー', '#3b82f6'),
(3, 'es', 'ES', '#f59e0b'),
(4, 'first', '一次', '#10b981'),
(5, 'second', '二次', '#8b5cf6'),
(6, 'final', '最終', '#ef4444'),
(7, 'naitei', '内々定', '#059669'),
(8, 'reject', '不合格', '#6b7280');

-- 5. テスト用ユーザー
INSERT INTO users (id, name, email, password) VALUES
(1, 'テストユーザー', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

SELECT 'データベース初期化完了' as result;