(SELECT
    `user_id`,`kind_id`,`main_category_id`,`icon_id`,`date`,`possession_id`,`amount`,`del_flg`
FROM
    `expenditures` as `e`
WHERE
    `user_id` = 2 AND
    `possession_id` = 1
)
UNION ALL
(SELECT
    `user_id`,
    `kind_id`,
    `main_category_id`,
    `icon_id`,
    `date`,
    `possession_id`,
    `amount`,
    `del_flg`
FROM
    `incomes` as `i`
WHERE
    `user_id` = 2 AND
    `possession_id` = 1
)
ORDER BY `date`

-- 
SELECT
    `i`.`user_id`,`i`.`main_category_id`,`i`.`icon_id`,`i`.`date`,`i`.`possession_id`,`i`.`amount`,`i`.`comment`,`i`.`del_flg`
FROM
    `incomes` as `i`
    left join
        `expenditures` as `e`
    on  
    `i`.`user_id` = `e`.`user_id` AND
    `i`.`main_category_id` = `e`.`main_category_id` AND
    `i`.`icon_id` = `e`.`icon_id` AND
    `i`.`date` = `e`.`date` AND
    `i`.`possession_id` = `e`.`possession_id` AND
    `i`.`amount` = `e`.`amount` AND
    `i`.`comment` = `e`.`comment` AND
    `i`.`del_flg` = `e`.`del_flg`

-- SQL
INSERT INTO `expenditure_data` (
    `id`, 
    `user_id`, 
    `category1_id`, 
    `category2_id`, 
    `date`, 
    `amount`, 
    `name`, 
    `comment`
    ) 
VALUES 
    ('1', '1', '7', '1', '2023-01-24', '1500',  '食材1', NULL);
    ('2', '1', '7', '2', '2023-01-24', '500',   'おやつ', NULL);
    ('3', '1', '7', '3', '2023-01-24', '2500',  '外食', NULL);
    ('4', '1', '8', '4', '2023-01-24', '1000',  '消耗品', NULL);
    ('5', '1', '8', '5', '2023-01-24', '500',   '雑貨', NULL);
    ('6', '1', '9', '6', '2023-01-24', '50000', '家賃', NULL);
    ('7', '1', '9', '7', '2023-01-24', '6000',  '電気代', NULL);
    ('8', '1', '9', '8', '2023-01-24', '5000',  'ガス代', NULL);
    ('9', '1', '9', '9', '2023-01-24', '3000',  '水道代', NULL), 

-- アイコン追加
INSERT INTO `icons` (`id`, `name`, `code`) 
VALUES 
    -- ('4', 'ビル', 'box-icon fa-solid fa-building color3'),
    -- ('5', '家', 'box-icon fa-solid fa-house-chimney-user color5'), 
    ('6', 'ビル', 'box-icon fa-solid fa-building color3'),
;
INSERT INTO `icons` (`id`, `name`, `code`) 
VALUES 
    ('8', 'ショッピング', 'fa-solid fa-cart-shopping'), 
    ('9', '家', 'fa-solid fa-house');
    ('10', '歯車', 'fa-solid fa-gear');
    ('11', '人', 'fa-solid fa-user');
    ('12', 'ファイル', 'fa-solid fa-folder-open');


UPDATE `expenditures` 
SET `icon_id` = '8' WHERE `expenditures`.`id` = 2,
SET `icon_id` = '9' WHERE `expenditures`.`id` = 3,
SET `icon_id` = '10' WHERE `expenditures`.`id` = 4,
SET `icon_id` = '11' WHERE `expenditures`.`id` = 5,

UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 1;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 2;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 3;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 4;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 5;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 6;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 7;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 8;
UPDATE `expenditures` SET `date` = '2023-01-01' WHERE `expenditures`.`id` = 9;

-- 支出追加
INSERT INTO `expenditures`(`id`, `user_id`, `main_category_id`, `sub_category_id`, `icon_id`, `date`, `amount`, `name`, `comment`) 
VALUES 
('35', '1', '8', '4', '8', '2023-01-13', '1000', '消耗品2', NULL),
('36', '1', '8', '4', '8', '2023-01-13', '1000', '消耗品3', NULL),
('37', '1', '8', '4', '8', '2023-01-13', '1000', '消耗品4', NULL),
('38', '1', '8', '4', '8', '2023-01-16', '1000', '消耗品5', NULL),
('39', '1', '8', '4', '8', '2023-01-19', '1000', '消耗品6', NULL),
('40', '1', '8', '4', '8', '2023-01-22', '1000', '消耗品7', NULL),
('41', '1', '8', '4', '8', '2023-01-25', '1000', '消耗品8', NULL),
('42', '1', '8', '4', '8', '2023-01-28', '1000', '消耗品9', NULL),
('43', '1', '8', '4', '8', '2023-01-31', '1000', '消耗品10', NULL),
('44', '1', '8', '5', '8', '2023-01-31', '500', '雑貨2', NULL),
('45', '1', '8', '5', '8', '2023-01-31', '500', '雑貨3', NULL),
('46', '1', '8', '5', '8', '2023-01-31', '500', '雑貨4', NULL),
('47', '1', '8', '5', '8', '2023-01-31', '500', '雑貨5', NULL),
('48', '1', '8', '5', '8', '2023-01-31', '500', '雑貨6', NULL);

    ('8', 'ショッピング', 'fa-solid fa-cart-shopping'), 
    ('9', '家', 'fa-solid fa-house');
    ('10', '歯車', 'fa-solid fa-gear');
    ('11', '人', 'fa-solid fa-user');
    ('12', 'ファイル', 'fa-solid fa-folder-open');

UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 19;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 20;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 21;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 22;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 23;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 24;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 25;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 26;
UPDATE `expenditures` SET `icon_id` = '7' WHERE `expenditures`.`id` = 27;

UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  1;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  2;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  3;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  4;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  5;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  6;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  7;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  8;
UPDATE `expenditures` SET `user_id` = '0', `possession_id` = '0', `amount` = '0' WHERE `expenditures`.`id` =  9;

UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 10;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 11;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 12;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 13;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 14;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 15;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 16;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 17;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 18;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 19;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 20;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 21;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 22;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 23;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 24;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 25;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 26;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 27;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 28;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 29;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 30;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 31;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 32;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 33;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '3' WHERE `expenditures`.`id` = 34;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 35;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 36;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 37;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 38;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 39;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 40;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 41;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 42;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 43;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 44;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 45;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 46;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 47;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 48;
UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 49;

UPDATE `expenditures` SET `user_id` = '2', `possession_id` = '2' WHERE `expenditures`.`id` = 50;


INSERT INTO `expenditures` (
    `user_id`, 
    `main_category_id`, 
    `sub_category_id`, 
    `date`, 
    `possession_id`,
    `amount`, 
    `name`, 
    `comment`
    ) 
VALUES 
    (2, 4, 4, '2023-01-24', 1, 200000, '家賃', NULL),
    (2, 5, 5, '2023-01-24', 1, 30000,  '電気代', NULL),
    (2, 6, 6, '2023-01-24', 1, 4000,  'ガス代', NULL),

INSERT INTO `incomes` (
    `user_id`, 
    `main_category_id`, 
    `icon_id`, 
    `date`, 
    `possession_id`,
    `amount`, 
    `comment`
    ) 
VALUES 
    (2, 4, 4, '2023-01-24', 1, 200000, NULL),
    (2, 5, 5, '2023-01-24', 2, 30000,  NULL),
    (2, 6, 6, '2023-01-24', 3, 4000,   NULL);