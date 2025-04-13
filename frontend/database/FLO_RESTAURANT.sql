-- FLO– Flavors, Love, Origin (Hương vị, Yêu thương, Cội nguồn) → Tạo cảm giác gần gũi, phù hợp với ẩm thực truyền thống.
CREATE DATABASE IF NOT EXISTS FLO_RESTAURANT;
USE FLO_RESTAURANT;
-- 🟢 1. Bảng thanh toán aamarpay
CREATE TABLE aamarpay (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- Mã giao dịch, tự tăng
  cus_name VARCHAR(255) NOT NULL,              -- Tên khách hàng
  amount DECIMAL(10,2) NOT NULL,               -- Số tiền thanh toán
  status VARCHAR(100) NOT NULL,                -- Trạng thái thanh toán (ví dụ: thành công, thất bại)
  pay_time TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),  -- Thời gian thanh toán
  transaction_id VARCHAR(100) NOT NULL UNIQUE,  -- Mã giao dịch duy nhất
  card_type VARCHAR(100) NOT NULL,             -- Loại thẻ thanh toán (Visa, MasterCard,...)
  PRIMARY KEY (id)                            -- Khóa chính
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 2. Bảng người dùng tbl_users
CREATE TABLE tbl_users (
  id INT(11) NOT NULL AUTO_INCREMENT,         -- ID người dùng, tự tăng
  name VARCHAR(255) NOT NULL,                 -- Tên người dùng
  email VARCHAR(100) NOT NULL UNIQUE,         -- Email người dùng, phải duy nhất
  add1 VARCHAR(255) NOT NULL,                 -- Địa chỉ người dùng (địa chỉ chi tiết)
  city VARCHAR(100) NOT NULL,                 -- Thành phố của người dùng
  phone BIGINT(20) NOT NULL UNIQUE,           -- Số điện thoại người dùng, phải duy nhất
  username VARCHAR(100) NOT NULL UNIQUE,      -- Tên đăng nhập, phải duy nhất
  password VARCHAR(255) NOT NULL,             -- Mật khẩu người dùng
  PRIMARY KEY (id),                          -- Khóa chính
  role VARCHAR(20) DEFAULT 'user'            -- Quyền của người dùng (user, admin,...)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 3. Bảng quản lý đơn hàng order_manager
CREATE TABLE order_manager (
  order_id INT(10) NOT NULL AUTO_INCREMENT,  -- Mã đơn hàng, tự tăng
  username VARCHAR(100),                     -- Tên đăng nhập của người tạo đơn hàng (liên kết với tbl_users)
  cus_name VARCHAR(255) NOT NULL,            -- Tên khách hàng
  cus_email VARCHAR(100) NOT NULL,           -- Email khách hàng
  cus_add1 VARCHAR(255) NOT NULL,            -- Địa chỉ khách hàng
  cus_city VARCHAR(100) NOT NULL,            -- Thành phố của khách hàng
  cus_phone BIGINT(20) NOT NULL,             -- Số điện thoại khách hàng
  payment_status VARCHAR(100) NOT NULL,      -- Trạng thái thanh toán
  order_date DATETIME NOT NULL,              -- Thời gian đặt hàng
  total_amount DECIMAL(10,2) NOT NULL,       -- Tổng số tiền đơn hàng
  transaction_id VARCHAR(100),               -- Mã giao dịch thanh toán (liên kết với aamarpay)
  order_status VARCHAR(100) NOT NULL,        -- Trạng thái đơn hàng (ví dụ: đang xử lý, hoàn thành)
  PRIMARY KEY (order_id),                    -- Khóa chính
  FOREIGN KEY (transaction_id) REFERENCES aamarpay (transaction_id) ON DELETE SET NULL,  -- Liên kết với bảng thanh toán
  FOREIGN KEY (username) REFERENCES tbl_users (username) ON DELETE SET NULL  -- Liên kết với bảng người dùng
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE order_manager DROP FOREIGN KEY order_manager_ibfk_2;
ALTER TABLE order_manager
ADD CONSTRAINT order_manager_ibfk_2
FOREIGN KEY (username) REFERENCES tbl_users(username)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- 🟢 4. Bảng chi tiết đơn hàng online_orders_new
CREATE TABLE online_orders_new (
  order_id INT(10) NOT NULL,                 -- Mã đơn hàng (liên kết với order_manager)
  item_name VARCHAR(100) NOT NULL,           -- Tên món ăn trong đơn hàng
  price DECIMAL(10,2) NOT NULL,              -- Giá của món ăn
  quantity INT(10) NOT NULL,                 -- Số lượng món ăn
  FOREIGN KEY (order_id) REFERENCES order_manager (order_id) ON DELETE CASCADE  -- Liên kết với bảng đơn hàng
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 5. Bảng danh mục thực phẩm tbl_category
CREATE TABLE tbl_category (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- ID danh mục, tự tăng
  title VARCHAR(100) NOT NULL,                 -- Tiêu đề danh mục
  image_name VARCHAR(255) NOT NULL,            -- Hình ảnh của danh mục
  featured VARCHAR(10) NOT NULL,               -- Danh mục nổi bật hay không
  active VARCHAR(10) NOT NULL,                 -- Trạng thái hoạt động của danh mục
  PRIMARY KEY (id)                             -- Khóa chính
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 6. Bảng sản phẩm thực phẩm tbl_food
CREATE TABLE tbl_food (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- ID sản phẩm, tự tăng
  title VARCHAR(100) NOT NULL,                 -- Tên món ăn
  description TEXT NOT NULL,                   -- Mô tả món ăn
  price DECIMAL(10,2) NOT NULL,                -- Giá món ăn
  image_name VARCHAR(255) NOT NULL,            -- Hình ảnh món ăn
  category_id INT(10) UNSIGNED NOT NULL,       -- ID danh mục (liên kết với tbl_category)
  featured VARCHAR(10) NOT NULL,               -- Món ăn nổi bật hay không
  active VARCHAR(10) NOT NULL,                 -- Trạng thái hoạt động của món ăn
  stock INT(100) UNSIGNED NOT NULL,            -- Số lượng món ăn trong kho
  PRIMARY KEY (id),                            -- Khóa chính
  FOREIGN KEY (category_id) REFERENCES tbl_category (id) ON DELETE CASCADE  -- Liên kết với bảng danh mục
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 7. Bảng lưu trữ đơn hàng tbl_order
CREATE TABLE tbl_order (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- ID đơn hàng, tự tăng
  transaction_id VARCHAR(100) NOT NULL,         -- Mã giao dịch thanh toán (liên kết với aamarpay)
  total DECIMAL(10,2) NOT NULL,                 -- Tổng số tiền đơn hàng
  order_date DATETIME NOT NULL,                 -- Thời gian đặt hàng
  status VARCHAR(50) NOT NULL,                  -- Trạng thái đơn hàng
  customer_name VARCHAR(150) NOT NULL,          -- Tên khách hàng
  customer_contact VARCHAR(20) NOT NULL,        -- Số điện thoại khách hàng
  customer_email VARCHAR(150) NOT NULL,         -- Email khách hàng
  customer_address VARCHAR(200) NOT NULL,       -- Địa chỉ khách hàng
  PRIMARY KEY (id),                            -- Khóa chính
  CONSTRAINT fk_transaction FOREIGN KEY (transaction_id) REFERENCES aamarpay (transaction_id)  -- Liên kết với bảng thanh toán
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 8. Bảng thanh toán điện tử tbl_eipay
-- CREATE TABLE tbl_eipay (
--   id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  -- ID thanh toán, tự tăng
--   table_id VARCHAR(50) NOT NULL,                -- ID bàn thanh toán
--   amount DECIMAL(10,2) NOT NULL,                -- Số tiền thanh toán
--   tran_id VARCHAR(50) NOT NULL UNIQUE,          -- Mã giao dịch thanh toán
--   order_date TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), -- Thời gian thanh toán
--   payment_status VARCHAR(50) NOT NULL,          -- Trạng thái thanh toán
--   order_status VARCHAR(100) NOT NULL,           -- Trạng thái đơn hàng
--   PRIMARY KEY (id)                             -- Khóa chính
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 9. Bảng tin nhắn liên hệ message
-- CREATE TABLE message (
--   id INT(11) NOT NULL AUTO_INCREMENT,          -- ID tin nhắn, tự tăng
--   name VARCHAR(255) NOT NULL,                  -- Tên người gửi
--   phone BIGINT(20) NOT NULL,                   -- Số điện thoại người gửi
--   subject VARCHAR(100) NOT NULL,               -- Chủ đề tin nhắn
--   message LONGTEXT NOT NULL,                   -- Nội dung tin nhắn
--   message_status VARCHAR(100) NOT NULL,        -- Trạng thái tin nhắn
--   date DATETIME NOT NULL,                      -- Thời gian gửi
--   PRIMARY KEY (id)                             -- Khóa chính
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 10. Bảng đặt bàn tbl_reservations
CREATE TABLE tbl_reservations (
  id INT(11) NOT NULL AUTO_INCREMENT,          -- ID đặt bàn, tự tăng
  user_id INT(11),                            -- ID khách hàng (null nếu khách vãng lai)
  table_number INT(11) NOT NULL,              -- Số bàn
  area VARCHAR(100) NOT NULL,                  -- Khu vực đặt bàn (Tầng 1, VIP,...)
  reservation_time DATETIME NOT NULL,          -- Thời gian đặt bàn
  status ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') NOT NULL DEFAULT 'Pending',  -- Trạng thái đặt bàn
  customer_name VARCHAR(255) NOT NULL,         -- Tên khách hàng
  customer_phone VARCHAR(20) NOT NULL,         -- Số điện thoại khách hàng
  customer_email VARCHAR(100),                 -- Email khách hàng
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo đặt bàn
  PRIMARY KEY (id),                           -- Khóa chính
  FOREIGN KEY (user_id) REFERENCES tbl_users (id) ON DELETE SET NULL  -- Liên kết với bảng tbl_users
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 11. Bảng bàn tbl_tables
-- CREATE TABLE tbl_tables (
--   id INT(11) NOT NULL AUTO_INCREMENT,          -- ID bàn, tự tăng
--   table_number INT(11) NOT NULL UNIQUE,        -- Số bàn, duy nhất
--   area VARCHAR(100) NOT NULL,                  -- Khu vực bàn
--   capacity INT(11) NOT NULL,                   -- Số người tối đa
--   status ENUM('Available', 'Reserved', 'Occupied', 'Out of Service') NOT NULL DEFAULT 'Available', -- Trạng thái bàn
--   PRIMARY KEY (id)                             -- Khóa chính
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 12. Bảng khuyến mãi tbl_promotions
CREATE TABLE tbl_promotions (
  id INT(11) NOT NULL AUTO_INCREMENT,          -- ID khuyến mãi, tự tăng
  promo_code VARCHAR(50) NOT NULL UNIQUE,      -- Mã giảm giá, duy nhất
  description TEXT NOT NULL,                   -- Mô tả chương trình khuyến mãi
  discount_percent DECIMAL(5,2),               -- Giảm giá theo %
  discount_amount DECIMAL(10,2),               -- Giảm giá theo số tiền
  valid_from DATETIME NOT NULL,                -- Thời gian bắt đầu áp dụng
  valid_to DATETIME NOT NULL,                  -- Thời gian kết thúc
  status ENUM('Active', 'Expired', 'Disabled') NOT NULL DEFAULT 'Active', -- Trạng thái khuyến mãi
  PRIMARY KEY (id)                             -- Khóa chính
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 13. Bảng đánh giá tbl_reviews
CREATE TABLE tbl_reviews (
  id INT(11) NOT NULL AUTO_INCREMENT,          -- ID đánh giá, tự tăng
  user_id INT(11) NOT NULL,                    -- ID người dùng (liên kết với bảng tbl_users)
  food_id INT(10) UNSIGNED,                    -- Món ăn được đánh giá (liên kết với tbl_food)
  order_id INT(10) NOT NULL,                   -- Đơn hàng liên quan
  rating INT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5), -- Đánh giá từ 1 đến 5 sao
  review_text TEXT,                            -- Nội dung phản hồi
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian đánh giá
  PRIMARY KEY (id),                            -- Khóa chính
  FOREIGN KEY (user_id) REFERENCES tbl_users (id) ON DELETE CASCADE,  -- Liên kết với bảng tbl_users
  FOREIGN KEY (food_id) REFERENCES tbl_food (id) ON DELETE CASCADE,   -- Liên kết với bảng tbl_food
  FOREIGN KEY (order_id) REFERENCES order_manager (order_id) ON DELETE CASCADE  -- Liên kết với bảng order_manager
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 🟢 14. Bảng lịch sử đơn hàng tbl_order_history
CREATE TABLE tbl_order_history (
  id INT(11) NOT NULL AUTO_INCREMENT,          -- ID lịch sử đơn hàng, tự tăng
  user_id INT(11) NOT NULL,                    -- ID người dùng (liên kết với bảng tbl_users)
  food_id INT(10) UNSIGNED NOT NULL,           -- Món ăn đã được đặt (liên kết với tbl_food)
  order_count INT(11) NOT NULL DEFAULT 1,      -- Số lần đặt món này
  last_order_date DATETIME NOT NULL,           -- Lần cuối đặt món
  PRIMARY KEY (id),                            -- Khóa chính
  UNIQUE KEY user_food_unique (user_id, food_id), -- Đảm bảo mỗi món chỉ có 1 dòng cho 1 người dùng
  FOREIGN KEY (user_id) REFERENCES tbl_users (id) ON DELETE CASCADE,  -- Liên kết với bảng tbl_users
  FOREIGN KEY (food_id) REFERENCES tbl_food (id) ON DELETE CASCADE  -- Liên kết với bảng tbl_food
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO aamarpay (cus_name, amount, status, transaction_id, card_type) 
VALUES 
('Nguyen Van A', 100.00, 'Success', 'TX123456', 'VISA'),
('Tran Thi B', 250.50, 'Pending', 'TX123457', 'MasterCard');

INSERT INTO tbl_users (name, email, add1, city, phone, username, password, role)
VALUES 
('Nguyen Thi Lan Hoa', 'lannguysen@gmail.com', '123 Mai Thị Lựu', 'Ho Chi Minh', '98736543210', 'lannguyen1', 'hashedpassword123', 'user'),
('Le Minh Tuan', 'tulemisnh@yahoo.com', '456 Nguyen Trai', 'Hanoi', '91234563789', 'tuleminh1', 'hashedpassword456', 'user'),
('Admin 1', 'adsmin1@example.com', '123 Street', 'Ho Chi Minh', '01234567389', 'admin12', 'adminpassword1', 'admin'),
('Admin 2', 'admsin2@example.com', '456 Avenue', 'Ho Chi Minh', '09876543321', 'admin22', 'adminpassword2', 'admin');
UPDATE tbl_users 
SET password = MD5('newpassword') 
WHERE username = 'admin12';

INSERT INTO order_manager (username, cus_name, cus_email, cus_add1, cus_city, cus_phone, payment_status, order_date, total_amount, transaction_id, order_status)
VALUES 
('lannguyen1', 'Nguyen Thi Lan', 'lannguyen@gmail.com', '123 Mai Thị Lựu', 'Ho Chi Minh', 9876543210, 'Completed', NOW(), 150.00, 'TX123456', 'Shipped'),
('tuleminh1', 'Le Minh Tu', 'tuleminh@yahoo.com', '456 Nguyen Trai', 'Hanoi', 9123456789, 'Pending', NOW(), 200.00, 'TX123457', 'Processing');

INSERT INTO online_orders_new (order_id, item_name, price, quantity)
VALUES 
(1, 'Bánh mì', 30.00, 2),
(1, 'Phở', 120.00, 1),
(2, 'Cơm tấm', 50.00, 4);

INSERT INTO tbl_order (transaction_id, total, order_date, status, customer_name, customer_contact, customer_email, customer_address)
VALUES 
('TX123456', 150.00, NOW(), 'Completed', 'Nguyen Thi Lan', '9876543210', 'lannguyen@gmail.com', '123 Mai Thị Lựu, Ho Chi Minh'),
('TX123457', 200.00, NOW(), 'Pending', 'Le Minh Tu', '9123456789', 'tuleminh@yahoo.com', '456 Nguyen Trai, Hanoi');

INSERT INTO tbl_eipay (table_id, amount, tran_id, payment_status, order_status)
VALUES 
('TX123456', 150.00, 'EP123456', 'Success', 'Shipped'),
('TX123457', 200.00, 'EP123457', 'Pending', 'Processing');

INSERT INTO message (name, phone, subject, message, message_status, date)
VALUES 
('Nguyen Thi Lan', 9876543210, 'Câu hỏi về sản phẩm', 'Tôi muốn biết thêm thông tin về bánh mì.', 'Read', NOW()),
('Le Minh Tu', 9123456789, 'Yêu cầu hỗ trợ', 'Sản phẩm không đúng như mô tả trên website.', 'Unread', NOW());

INSERT INTO order_manager (order_id) VALUES
(36), (37), (38), (39), (40), (41), (42), (43), (44), (45), (46), (47), (48),
(3), (4), (5), (6), (7), (8), (9), (10), (11), (12), (13), (14), (15), (16), (17), 
(18), (19), (20), (21), (22), (23), (24), (25), (26), (27), (28), (29), (30), (31), 
(32), (33), (34), (35), (49), (50), (51), (52), (53), (54), (55), (56), (57), (58), 
(59), (60), (61), (62), (63), (64), (65), (66), (67), (68), (69), (70), (71), (72), 
(73), (74), (75), (76), (77), (78), (79), (80), (81), (82), (83), (84), (85), (86), 
(87), (88), (89), (90), (91), (92), (93), (94), (95), (96), (97), (98), (99), (100);

INSERT INTO online_orders_new (order_id, item_name, price, quantity) 
VALUES
(36, 'Bánh mì', 80, 1),
(37, 'Bánh xèo', 150, 1),
(38, 'Bánh bao', 150, 3),
(38, 'Phở bò', 250, 1),
(39, 'Khoai tây chiên', 80, 1),
(40, 'Bánh mì thịt nướng', 150, 4),
(41, 'Bánh mì gà', 150, 1),
(42, 'Phở gà', 200, 1),
(42, 'Gỏi cuốn', 100, 2),
(43, 'Bánh mì', 150, 1),
(44, 'Bánh mì', 150, 1),
(45, 'Pizza chay', 180, 1),
(46, 'Bánh canh cua', 250, 1),
(47, 'Bánh mì', 180, 1),
(48, 'Bánh mì thịt', 150, 1),
(1, 'Bánh mì ốp la', 250, 1),
(1, 'Cánh gà chiên mắm', 250, 1),
(1, 'Khoai tây chiên', 100, 2),
(2, 'Bánh cuốn', 220, 1),
(3, 'Bún chả', 250, 1),
(3, 'Bánh mì', 150, 1),
(3, 'Khoai tây chiên', 100, 2),
(4, 'Bánh tráng cuốn thịt heo', 180, 1),
(5, 'Bánh xèo', 250, 1),
(6, 'Gà xào sả ớt', 250, 1),
(7, 'Bánh canh', 180, 1),
(8, 'Bánh mì', 150, 1),
(9, 'Cánh gà chiên', 250, 1),
(9, 'Bánh cuốn', 220, 1),
(10, 'Khoai tây chiên', 100, 1),
(11, 'Bánh tráng trộn', 100, 1),
(12, 'Bánh mì thịt nướng', 250, 1),
(12, 'Gỏi cuốn', 220, 1),
(13, 'Bánh bèo', 170, 1),
(14, 'Gà nướng mật ong', 270, 1),
(15, 'Gà rán', 150, 1),
(16, 'Chả giò', 100, 1),
(17, 'Khoai tây chiên', 130, 1),
(18, 'Bánh tráng cuốn', 270, 1),
(19, 'Chả giò', 150, 1),
(20, 'Cánh gà BBQ', 230, 1),
(21, 'Gà nướng mật ong', 270, 1),
(22, 'Cánh gà BBQ', 230, 1),
(23, 'Bánh mì chả cá', 280, 1),
(24, 'Chả giò', 100, 1),
(25, 'Bánh mì', 300, 1),
(26, 'Cánh gà chiên', 250, 1),
(27, 'Gà nướng mật ong', 270, 1),
(28, 'Bánh mì chả lụa', 290, 1),
(29, 'Cánh gà chiên', 250, 2),
(29, 'Gà nướng', 270, 2),
(29, 'Bánh cuốn', 270, 1),
(30, 'Bánh xèo', 200, 1),
(30, 'Bánh mì', 120, 1),
(30, 'Hành tây chiên', 100, 1),
(31, 'Bánh mì kẹp thịt', 490, 1),
(31, 'Bánh mì', 150, 3),
(32, 'Bánh mì', 150, 2),
(32, 'Cháo lòng', 160, 1),
(33, 'Bánh mì', 150, 5),
(34, 'Bánh cuốn', 120, 1),
(34, 'Bánh mì', 150, 4),
(34, 'Bánh tráng', 160, 1),
(35, 'Bánh xèo', 490, 2),
(35, 'Chả giò', 100, 1),
(36, 'Bánh mì thịt', 100, 1),
(36, 'Chả giò', 100, 2),
(37, 'Gà chiên', 150, 1),
(37, 'Khoai tây chiên', 100, 1),
(38, 'Bánh xèo', 490, 1),
(39, 'Bánh mì gà', 120, 2),
(40, 'Khoai tây chiên', 120, 3),
(41, 'Bánh mì', 160, 1),
(42, 'Bánh mì', 160, 1),
(43, 'Bánh mì', 160, 1),
(44, 'Bánh mì', 160, 1),
(45, 'Bánh mì', 160, 1),
(46, 'Bánh mì', 160, 1),
(47, 'Bánh mì', 160, 1),
(48, 'Bánh mì', 160, 1),
(49, 'Bánh mì', 160, 1),
(50, 'Bánh mì phô mai', 110, 1),
(51, 'Bánh mì phô mai', 110, 1),
(52, 'Bánh mì phô mai', 110, 1),
(53, 'Bánh mì phô mai', 110, 1),
(54, 'Bánh mì phô mai', 110, 1),
(55, 'Bánh mì phô mai', 110, 1),
(56, 'Bánh mì phô mai', 110, 1),
(57, 'Bánh mì phô mai', 110, 1),
(58, 'Bánh mì phô mai', 110, 1),
(59, 'Bánh mì phô mai', 110, 1),
(60, 'Bánh mì phô mai', 110, 1),
(61, 'Bánh xèo', 490, 1),
(61, 'Chả giò', 100, 1),
(62, 'Bánh xèo', 490, 1),
(62, 'Chả giò', 100, 1),
(63, 'Bánh xèo', 490, 1),
(63, 'Chả giò', 100, 1),
(64, 'Bánh xèo', 490, 1),
(64, 'Chả giò', 100, 1),
(65, 'Bánh xèo', 490, 1),
(65, 'Chả giò', 100, 1),
(66, 'Bánh xèo', 490, 1),
(66, 'Chả giò', 100, 1),
(67, 'Pizza Hoàng Gia', 450, 1),
(68, 'Bánh mì', 120, 1),
(69, 'Bánh mì', 120, 1),
(70, 'Bánh mì', 120, 1),
(71, 'Bánh mì', 120, 1),
(72, 'Bánh mì', 120, 1),
(73, 'Bánh mì phô mai', 110, 1),
(74, 'Bánh mì phô mai', 110, 1),
(75, 'Gà chiên', 150, 2),
(76, 'Bánh mì gà', 120, 1),
(77, 'Bánh mì gà', 120, 2),
(77, 'Bánh mì thịt', 150, 1),
(78, 'Bánh mì gà', 120, 2),
(78, 'Bánh mì thịt', 150, 2),
(79, 'Bánh mì gà', 120, 2),
(79, 'Bánh mì thịt', 150, 2),
(80, 'Chả gà Kiev', 200, 1),
(81, 'Chả gà Kiev', 200, 1),
(82, 'Bánh mì', 160, 2),
(83, 'Bánh mì gà', 120, 3),
(83, 'Bánh mì thịt', 150, 2),
(84, 'Bánh mì gà', 120, 4),
(85, 'Bánh mì phô mai', 100, 2),
(86, 'Bánh mì phô mai', 100, 3),
(86, 'Bánh mì Phô Mai', 350, 4),
(87, 'Bánh mì gà', 120, 3),
(87, 'Bánh mì thịt', 150, 2),
(88, 'Khoai tây chiên', 120, 4),
(89, 'Pizza Chay', 300, 1),
(90, 'Bánh mì hành tây', 100, 1),
(91, 'Chả giò', 100, 2),
(91, 'Gà Nuggets', 150, 1),
(92, 'Hành tây chiên', 100, 1),
(93, 'Bánh mì cay', 80, 4),
(94, 'Pizza Chay', 300, 1),
(95, 'Bánh mì phô mai', 100, 1),
(96, 'Pizza Chay', 300, 1),
(96, 'Bánh mì cay', 80, 1),
(97, 'Bánh mì', 120, 1),
(98, 'Pizza Chay', 300, 1),
(99, 'Pizza Hoàng Gia', 450, 1),
(100, 'Bánh mì phô mai', 100, 1),
(100, 'Bánh mì thịt', 150, 1);

INSERT INTO tbl_category (title, image_name, featured, active) VALUES
('Món ăn chính', 'mon_an_chinh.jpg', 'yes', 'yes'),
('Đồ uống', 'do_uong.jpg', 'yes', 'yes'),
('Tráng miệng', 'trang_mieng.jpg', 'no', 'yes'),
('Khai vị', 'khai_vi.jpg', 'no', 'yes'),
('Món ăn kèm', 'mon_an_kem.jpg', 'no', 'yes');

-- Chèn món ăn vào bảng tbl_food với category_id từ 1 đến 5
INSERT INTO tbl_food (title, description, price, image_name, category_id, featured, active, stock) VALUES
('Bún chả', 'Bún chả với thịt nướng, bún, rau sống và nước mắm', 50.00, 'bun_cha.jpg', 1, 'yes', 'yes', 100),
('Bánh xèo', 'Bánh xèo giòn, nhân tôm thịt, rau sống và nước mắm', 30.00, 'banh_xeo.jpg', 1, 'no', 'yes', 150),
('Cơm tấm', 'Cơm với thịt sườn nướng, bì và chả', 40.00, 'com_tam.jpg', 1, 'yes', 'yes', 120),
('Mì Quảng', 'Mì Quảng với nước dùng sánh và thịt heo, tôm', 45.00, 'mi_quang.jpg', 1, 'no', 'yes', 110),
('Lẩu hải sản', 'Lẩu hải sản tươi ngon với nước lẩu đậm đà', 120.00, 'lau_hai_san.jpg', 1, 'yes', 'yes', 80),
('Cà phê sữa đá', 'Cà phê đen pha sữa đặc và đá', 25.00, 'ca_phe_sua_da.jpg', 2, 'yes', 'yes', 200),
('Nước mía', 'Nước mía tươi ép mát lạnh', 15.00, 'nuoc_mia.jpg', 2, 'no', 'yes', 250),
('Sinh tố bơ', 'Sinh tố bơ thơm ngon với sữa đặc', 35.00, 'sinh_to_bo.jpg', 2, 'no', 'yes', 180),
('Trà đá', 'Trà xanh hoặc trà đen uống kèm đá', 10.00, 'tra_da.jpg', 2, 'yes', 'yes', 300),
('Nước dừa tươi', 'Nước dừa tươi ngọt ngào từ dừa', 20.00, 'nuoc_dua.jpg', 2, 'no', 'yes', 220),
('Chè đậu xanh', 'Chè đậu xanh ngọt thanh mát', 20.00, 'che_dau_xanh.jpg', 3, 'yes', 'yes', 130),
('Bánh flan', 'Bánh flan trứng, sữa caramel béo ngậy', 25.00, 'banh_flan.jpg', 3, 'no', 'yes', 140),
('Chè khúc bạch', 'Chè khúc bạch với thạch dừa và nước đường', 30.00, 'che_khuc_bach.jpg', 3, 'yes', 'yes', 160),
('Sữa chua', 'Sữa chua dẻo mịn, ngọt thanh', 15.00, 'sua_chua.jpg', 3, 'no', 'yes', 190),
('Chuối nếp nướng', 'Chuối nếp nướng thơm ngon', 20.00, 'chuoi_nep_nuong.jpg', 3, 'no', 'yes', 170),
('Gỏi cuốn', 'Cuốn tôm thịt, bún, rau sống trong bánh tráng', 30.00, 'goi_cuon.jpg', 4, 'yes', 'yes', 200),
('Chả giò', 'Chả giò chiên giòn với nhân thịt và tôm', 25.00, 'cha_gio.jpg', 4, 'no', 'yes', 180),
('Bánh cuốn', 'Bánh cuốn cuộn nhân thịt heo, ăn với nước mắm', 35.00, 'banh_cuon.jpg', 4, 'yes', 'yes', 150),
('Mực xào chua ngọt', 'Mực xào với gia vị chua ngọt hấp dẫn', 50.00, 'muc_xao_chua_ngot.jpg', 4, 'no', 'yes', 120),
('Bánh đúc', 'Bánh đúc với nước mắm và đậu phộng', 20.00, 'banh_duc.jpg', 4, 'no', 'yes', 130),
('Hành ngâm chua', 'Hành ngâm dấm ăn kèm với các món', 5.00, 'hanh_ngam_chua.jpg', 5, 'yes', 'yes', 300),
('Tỏi ngâm chua', 'Tỏi ngâm chua, ăn kèm với các món', 10.00, 'toi_ngam_chua.jpg', 5, 'yes', 'yes', 200),
('Rau sống', 'Các loại rau sống như húng quế, tía tô, rau diếp', 0.00, 'rau_song.jpg', 5, 'yes', 'yes', 400),
('Ớt tươi', 'Ớt tươi dùng để ăn kèm với các món', 5.00, 'ot_tuoi.jpg', 5, 'yes', 'yes', 350),
('Nước mắm pha', 'Nước mắm pha chua ngọt ăn kèm', 10.00, 'nuoc_mam.jpg', 5, 'yes', 'yes', 250),
('Dưa leo', 'Dưa leo cắt lát ăn kèm với các món', 10.00, 'dua_leo.jpg', 5, 'yes', 'yes', 300),
('Đậu phộng rang', 'Đậu phộng rang ăn kèm với các món', 15.00, 'dau_phong_rang.jpg', 5, 'yes', 'yes', 200);

-- INSERT INTO tbl_tables (table_number, area, capacity, status) 
-- VALUES 
-- (1, 'Tầng 1', 4, 'Có sẵn'),
-- (2, 'Tầng 1', 6, 'Đã đặt trước'),
-- (3, 'Tầng 2', 2, 'Đang sử dụng'),
-- (4, 'Tầng 2', 8, 'Có sẵn');

INSERT INTO tbl_reservations (user_id, table_number, area, reservation_time, status, customer_name, customer_phone, customer_email)
VALUES 
(1, 2, 'Tầng 1', NOW(), 'Đã xác nhận', 'Nguyễn Thị Lan', '9876543210', 'lannguyen@gmail.com'),
(2, 3, 'Tầng 2', NOW(), 'Chờ xác nhận', 'Lê Minh Tú', '9123456789', 'tuleminh@yahoo.com');

INSERT INTO tbl_promotions (promo_code, description, discount_percent, discount_amount, valid_from, valid_to, status)
VALUES
('DISCOUNT10', 'Giảm 10% cho đơn hàng', 10, 0, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('DISCOUNT20', 'Giảm 20% cho đơn hàng', 20, 0, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('DISCOUNT30', 'Giảm 30% cho đơn hàng', 30, 0, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('DISCOUNT50', 'Giảm 50% cho đơn hàng', 50, 0, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('DISCOUNT100', 'Giảm 100.000 VND cho đơn hàng', 0, 100000, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('DISCOUNT150', 'Giảm 150.000 VND cho đơn hàng', 0, 150000, '2025-01-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('SUMMER20', 'Giảm 20% mùa hè', 20, 0, '2025-06-01 00:00:00', '2025-08-31 23:59:59', 'Active'),
('WINTER25', 'Giảm 25% mùa đông', 25, 0, '2025-12-01 00:00:00', '2025-12-31 23:59:59', 'Active'),
('NEWYEAR15', 'Giảm 15% năm mới', 15, 0, '2025-01-01 00:00:00', '2025-01-15 23:59:59', 'Active'),
('XMAS50', 'Giảm 50% Giáng sinh', 50, 0, '2025-12-01 00:00:00', '2025-12-25 23:59:59', 'Active');


INSERT INTO tbl_reviews (user_id, food_id, order_id, rating, review_text, created_at)
VALUES 
(1, 1, 1, 5, 'Bánh mì rất ngon, giòn và hương vị tuyệt vời!', NOW()),
(2, 2, 2, 4, 'Phở khá ngon nhưng hơi nhiều nước.', NOW());

INSERT INTO tbl_order_history (user_id, food_id, order_count, last_order_date)
VALUES 
(1, 1, 3, NOW()),  -- Nguyễn Thi Lan đã đặt bánh mì 3 lần
(1, 2, 1, NOW()),  -- Nguyễn Thi Lan đã đặt phở 1 lần
(2, 2, 2, NOW());  -- Lê Minh Tú đã đặt phở 2 lần
