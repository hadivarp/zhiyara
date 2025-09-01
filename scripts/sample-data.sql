-- Sample restaurant data for Zhiyara Motorcycle Guide
-- Insert sample restaurants with motorcycle-friendly features

INSERT INTO wp_posts (post_title, post_content, post_excerpt, post_status, post_type, post_date, post_date_gmt, post_modified, post_modified_gmt) VALUES
('رستوران کوهستان', 'رستوران کوهستان با منظره‌ای فوق‌العاده از کوه‌های البرز، محیطی آرام و دلنشین برای موتورسواران فراهم کرده است. غذاهای سنتی ایرانی با کیفیت بالا و خدمات ویژه برای موتورسواران.', 'رستوران کوهستان - تجربه‌ای منحصر به فرد در دامنه کوه', 'publish', 'restaurant', NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP()),
('کافه موتور', 'کافه‌ای مخصوص موتورسواران با پارکینگ اختصاصی و امکانات شستشوی موتور. قهوه‌های تازه و محیطی صمیمی برای دورهمی موتورسواران.', 'کافه موتور - مکانی برای موتورسواران', 'publish', 'restaurant', NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP()),
('رستوران جاده ابریشم', 'رستوران جاده ابریشم با غذاهای محلی شمال ایران و خدمات ویژه برای مسافران موتورسوار. امکانات کامل نگهداری موتور و تخفیف گروهی.', 'رستوران جاده ابریشم - طعم اصیل شمال', 'publish', 'restaurant', NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP());

-- Get the post IDs (assuming they are the last 3 inserted)
SET @restaurant1_id = LAST_INSERT_ID();
SET @restaurant2_id = @restaurant1_id + 1;
SET @restaurant3_id = @restaurant1_id + 2;

-- Insert motorcycle-specific meta data
INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
-- Restaurant 1 (کوهستان)
(@restaurant1_id, '_motorcycle_parking', 'available'),
(@restaurant1_id, '_helmet_storage', 'yes'),
(@restaurant1_id, '_bike_wash', 'no'),
(@restaurant1_id, '_rider_discount', 'yes'),
(@restaurant1_id, '_group_friendly', 'yes'),
(@restaurant1_id, '_motorcycle_accessibility', 'excellent'),
(@restaurant1_id, '_zhiyara_visit_date', '1403/06/15'),
(@restaurant1_id, '_restaurant_rating', '5'),

-- Restaurant 2 (کافه موتور)
(@restaurant2_id, '_motorcycle_parking', 'available'),
(@restaurant2_id, '_helmet_storage', 'yes'),
(@restaurant2_id, '_bike_wash', 'yes'),
(@restaurant2_id, '_rider_discount', 'yes'),
(@restaurant2_id, '_group_friendly', 'yes'),
(@restaurant2_id, '_motorcycle_accessibility', 'excellent'),
(@restaurant2_id, '_zhiyara_visit_date', '1403/06/20'),
(@restaurant2_id, '_restaurant_rating', '5'),

-- Restaurant 3 (جاده ابریشم)
(@restaurant3_id, '_motorcycle_parking', 'limited'),
(@restaurant3_id, '_helmet_storage', 'no'),
(@restaurant3_id, '_bike_wash', 'no'),
(@restaurant3_id, '_rider_discount', 'yes'),
(@restaurant3_id, '_group_friendly', 'yes'),
(@restaurant3_id, '_motorcycle_accessibility', 'good'),
(@restaurant3_id, '_zhiyara_visit_date', '1403/06/25'),
(@restaurant3_id, '_restaurant_rating', '4');

-- Insert restaurant categories
INSERT INTO wp_terms (name, slug, term_group) VALUES
('رستوران سنتی', 'traditional-restaurant', 0),
('کافه', 'cafe', 0),
('فست فود', 'fast-food', 0);

-- Insert taxonomy data
INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES
(LAST_INSERT_ID() - 2, 'restaurant_category', 'رستوران‌های سنتی ایرانی', 0, 2),
(LAST_INSERT_ID() - 1, 'restaurant_category', 'کافه‌ها و قهوه‌خانه‌ها', 0, 1),
(LAST_INSERT_ID(), 'restaurant_category', 'رستوران‌های فست فود', 0, 0);

-- Link restaurants to categories
INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES
(@restaurant1_id, LAST_INSERT_ID() - 2, 0),
(@restaurant2_id, LAST_INSERT_ID() - 1, 0),
(@restaurant3_id, LAST_INSERT_ID() - 2, 0);
