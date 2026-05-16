-- ==========================================
-- Campus Code - Admin Dashboard Migration
-- قم بتشغيل هذه الأوامر في phpMyAdmin
-- ==========================================

-- 1. تحديث جدول المستخدمين - إضافة أعمدة جديدة
ALTER TABLE `users`
ADD COLUMN `name` VARCHAR(100) NULL AFTER `id`,
ADD COLUMN `role` ENUM('admin','user') NOT NULL DEFAULT 'user' AFTER `password`,
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- تكبير عمود كلمة المرور لاستيعاب التشفير
ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL;

-- 2. تحديث جدول الدورات - إضافة أعمدة جديدة
ALTER TABLE `courses`
ADD COLUMN `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 3. لإنشاء حساب الأدمن:
-- افتح الرابط التالي في المتصفح مرة واحدة فقط:
-- http://localhost/Campus_code (2)/Campus_code/generate_admin_hash.php
