/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `allocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` enum('Imported','Manual') DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `fte` decimal(4,2) DEFAULT NULL,
  `resources_id` int(11) NOT NULL,
  `projects_id` int(11) NOT NULL,
  `status` enum('Proposed','Committed') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_allocations_resources1_idx` (`resources_id`),
  KEY `fk_allocations_projects1_idx` (`projects_id`),
  KEY `allocations_source_index` (`source`),
  CONSTRAINT `fk_allocations_projects1` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_allocations_resources1` FOREIGN KEY (`resources_id`) REFERENCES `resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `assumptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `assumptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `impact` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimate_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assumptions_estimate_id_foreign` (`estimate_id`),
  CONSTRAINT `assumptions_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `change_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `record_type` varchar(255) NOT NULL,
  `record_id` bigint(20) unsigned NOT NULL,
  `field` varchar(255) NOT NULL,
  `old_value` decimal(10,3) NOT NULL,
  `new_value` decimal(10,3) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `requested_by` bigint(20) unsigned DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `change_requests_requested_by_foreign` (`requested_by`),
  KEY `change_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `change_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `change_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_details` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `availability` decimal(3,2) DEFAULT NULL,
  `resources_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permanent` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_contract_resources_idx` (`resources_id`),
  CONSTRAINT `fk_contract_resources` FOREIGN KEY (`resources_id`) REFERENCES `resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dashboard_tiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_tiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `demands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `demands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` enum('Imported','Manual') DEFAULT NULL,
  `demand_date` date DEFAULT NULL,
  `fte` decimal(3,2) DEFAULT NULL,
  `status` enum('Proposed','Committed','Manual') DEFAULT NULL,
  `projects_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_demand_projects1_idx` (`projects_id`),
  CONSTRAINT `fk_Demand_projects1` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `estimates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estimates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `use_name_as_title` tinyint(1) NOT NULL,
  `expiration_date` date NOT NULL,
  `currency_symbol` varchar(255) NOT NULL,
  `currency_decimal_separator` varchar(255) NOT NULL,
  `currency_thousands_separator` varchar(255) NOT NULL,
  `allows_to_select_items` tinyint(1) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `total_cost` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned NOT NULL,
  `estimate_owner` bigint(20) unsigned NOT NULL,
  `partner` bigint(20) unsigned NOT NULL,
  `terms_and_conditions_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estimates_terms_and_conditions_id_unique` (`terms_and_conditions_id`),
  KEY `estimates_created_by_foreign` (`created_by`),
  KEY `estimates_updated_by_foreign` (`updated_by`),
  KEY `estimates_estimate_owner_foreign` (`estimate_owner`),
  KEY `estimates_partner_foreign` (`partner`),
  KEY `estimates_client_id_foreign` (`client_id`),
  CONSTRAINT `estimates_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimates_estimate_owner_foreign` FOREIGN KEY (`estimate_owner`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimates_partner_foreign` FOREIGN KEY (`partner`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimates_terms_and_conditions_id_foreign` FOREIGN KEY (`terms_and_conditions_id`) REFERENCES `terms_and_conditions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estimates_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `funding_approval_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `funding_approval_stages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `obligatory` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimate_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_estimate_id_index` (`estimate_id`),
  CONSTRAINT `items_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `resources_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_leave_resources1_idx` (`resources_id`),
  CONSTRAINT `fk_leave_resources1` FOREIGN KEY (`resources_id`) REFERENCES `resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_region_id_foreign` (`region_id`),
  CONSTRAINT `locations_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plugins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('Report','Import','Export','Other') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_regions_region_id_foreign` (`region_id`),
  KEY `project_regions_project_id_foreign` (`project_id`),
  CONSTRAINT `project_regions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_regions_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `project_service` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_service_project_id_foreign` (`project_id`),
  KEY `project_service_service_id_foreign` (`service_id`),
  CONSTRAINT `project_service_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `empowerID` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `projectManager` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('Proposed','Active','Cancelled','Completed','On Hold','Prioritised') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `public_holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `public_holidays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `public_holidays_region_id_foreign` (`region_id`),
  CONSTRAINT `public_holidays_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jurisdiction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `demand_type_id` bigint(20) unsigned DEFAULT NULL,
  `product_group_function_domain_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  `business_partner` varchar(255) DEFAULT NULL,
  `request_title` varchar(255) DEFAULT NULL,
  `background` text DEFAULT NULL,
  `business_need` text DEFAULT NULL,
  `problem_statement` text DEFAULT NULL,
  `specific_requirements` text DEFAULT NULL,
  `funding_approval_stage_id` bigint(20) unsigned DEFAULT NULL,
  `wbs_number` varchar(255) DEFAULT NULL,
  `expected_start` date DEFAULT NULL,
  `expected_duration` int(11) DEFAULT NULL,
  `business_value` text DEFAULT NULL,
  `business_unit` varchar(255) DEFAULT NULL,
  `additional_expert_contact` varchar(255) DEFAULT NULL,
  `attachments` longtext DEFAULT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  `fte` decimal(3,2) DEFAULT NULL,
  `status` enum('Proposed','Committed','Manual','Closed') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requests_demand_type_id_foreign` (`demand_type_id`),
  KEY `requests_product_group_function_domain_id_foreign` (`product_group_function_domain_id`),
  KEY `requests_site_id_foreign` (`site_id`),
  KEY `requests_funding_approval_stage_id_foreign` (`funding_approval_stage_id`),
  CONSTRAINT `requests_demand_type_id_foreign` FOREIGN KEY (`demand_type_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `requests_funding_approval_stage_id_foreign` FOREIGN KEY (`funding_approval_stage_id`) REFERENCES `funding_approval_stages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `requests_product_group_function_domain_id_foreign` FOREIGN KEY (`product_group_function_domain_id`) REFERENCES `domains` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `requests_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_skill` (
  `resources_id` int(11) NOT NULL,
  `skills_id` int(11) NOT NULL,
  `proficiency_levels` enum('Beginner','Intermediate','Advanced','Expert') NOT NULL DEFAULT 'Beginner',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`resources_id`,`skills_id`),
  KEY `resource_skill_skill_id_foreign` (`skills_id`),
  CONSTRAINT `resource_skill_resources_id_foreign` FOREIGN KEY (`resources_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `resource_skill_skills_id_foreign` FOREIGN KEY (`skills_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `empowerID` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `baseAvailability` double DEFAULT 1,
  `region_id` bigint(20) unsigned DEFAULT NULL,
  `location_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `resource_type` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empowerid_unique` (`empowerID`),
  KEY `resources_region_id_foreign` (`region_id`),
  KEY `resources_location_id_foreign` (`location_id`),
  KEY `resources_user_id_foreign` (`user_id`),
  KEY `resources_resource_type_foreign` (`resource_type`),
  CONSTRAINT `resources_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `resources_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `resources_resource_type_foreign` FOREIGN KEY (`resource_type`) REFERENCES `resource_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `resources_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `risks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `risks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `potential_risks` varchar(255) NOT NULL,
  `mitigation_steps` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimate_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `risks_estimate_id_foreign` (`estimate_id`),
  CONSTRAINT `risks_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `saml2_tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saml2_tenants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `idp_entity_id` varchar(255) NOT NULL,
  `idp_login_url` varchar(255) NOT NULL,
  `idp_logout_url` varchar(255) NOT NULL,
  `idp_x509_cert` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `relay_state_url` varchar(255) DEFAULT NULL,
  `name_id_format` varchar(255) NOT NULL DEFAULT 'persistent',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `scopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `scopes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tasks_deliverables` varchar(255) NOT NULL,
  `timeline` date NOT NULL,
  `exclusions` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimate_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scopes_estimate_id_unique` (`estimate_id`),
  CONSTRAINT `scopes_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `required_skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`required_skills`)),
  `hours_cost` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(255) NOT NULL,
  `skill_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `context` varchar(255) DEFAULT NULL,
  `employers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`employers`)),
  `keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keywords`)),
  `category` varchar(255) DEFAULT NULL,
  `certifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certifications`)),
  `occupations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`occupations`)),
  `license` varchar(255) DEFAULT NULL,
  `derived_from` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`derived_from`)),
  `source_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `authors` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `skills_skill_name_unique` (`skill_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staging_allocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staging_allocations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `allocation_date` date NOT NULL,
  `fte` double NOT NULL,
  `resources_id` int(11) DEFAULT NULL,
  `projects_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staging_allocations_resources_id_foreign` (`resources_id`),
  KEY `staging_allocations_projects_id_foreign` (`projects_id`),
  CONSTRAINT `staging_allocations_projects_id_foreign` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staging_allocations_resources_id_foreign` FOREIGN KEY (`resources_id`) REFERENCES `resources` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staging_demands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staging_demands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `demand_date` date NOT NULL,
  `fte` double NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  `projects_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staging_demands_projects_id_foreign` (`projects_id`),
  CONSTRAINT `staging_demands_projects_id_foreign` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `team_user_user_id_foreign` (`user_id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_team_id` int(10) unsigned DEFAULT NULL,
  `resource_type` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_parent_team_id_index` (`parent_team_id`),
  KEY `teams_resource_type_foreign` (`resource_type`),
  CONSTRAINT `teams_resource_type_foreign` FOREIGN KEY (`resource_type`) REFERENCES `resource_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `terms_and_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `terms_and_conditions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_terms` varchar(255) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `assumptions` varchar(255) NOT NULL,
  `change_management` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `current_team_id` int(10) unsigned DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `reports` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_resource_id_foreign` (`resource_id`),
  KEY `1` (`reports`),
  CONSTRAINT `1` FOREIGN KEY (`reports`) REFERENCES `users` (`id`),
  CONSTRAINT `users_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*M!999999\- enable the sandbox mode */ 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2024_10_24_011912_create_allocations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2024_10_24_011912_create_contract_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_10_24_011912_create_demand_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_10_24_011912_create_leave_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_10_24_011912_create_projects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_10_24_011912_create_resources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2024_10_24_011915_add_foreign_keys_to_allocations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_10_24_011915_add_foreign_keys_to_contract_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_10_24_011915_add_foreign_keys_to_demand_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_10_24_011915_add_foreign_keys_to_leave_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_10_31_011741_resource_add_base_availability',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2024_11_01_014243_fix_allocation_dates',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2024_11_04_031941_fix_demand_dates',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2024_11_08_013735_create_dashboard_tiles_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_11_11_054107_create_skill_library_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_11_11_054122_create_resource_skill_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_11_11_054125_add_foreign_keys_to_resource_skill_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_11_13_035502_update_users_table_add_settings',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2024_11_15_035338_create_service_catalogue_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2024_11_15_040849_create_project_service_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2024_11_15_041428_add_foreign_keys_to_project_service_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2024_11_17_013406_alter_services_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'000001_create_teams_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2024_11_20_063257_teamwork_setup_tables',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2024_11_20_063549_add_foreign_keys_to_teamwork_tables',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2024_11_20_061950_create_permission_tables',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2024_11_21_045954_alter__teams__table_add__resource_type',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2024_11_22_061714_create_clients_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (150,'2024_11_22_062212_create_estimates_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2024_11_22_062303_create_scopes_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2024_11_22_062334_create_assumptions_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2024_11_22_062455_create_items_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2024_11_22_062530_create_terms_and_conditions_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2024_11_22_062637_create_risks_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2024_11_22_063249_add_foreign_keys_to_assumptions_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2024_11_22_063412_add_foreign_keys_to_items_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2024_11_22_063504_add_foreign_keys_to_estimates_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2024_11_22_074218_add_foreign_keys_to_risks',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2024_11_22_074408_add_foreign_key_to_scopes_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (208,'2024_11_27_065516_add_location_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (209,'2024_11_27_065517_add_region_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (210,'2024_11_27_065520_add_project_region_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (211,'2024_11_27_065524_add_location_to_resource',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (212,'2024_11_28_035227_add_foreign_key_to_locations',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (213,'2024_11_28_035428_add_foreign_keys_to_project_regions',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (214,'2024_12_03_014051_add_source_to_demands',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (215,'2024_12_03_015420_add_source_to_allocations',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (218,'2024_12_03_030307_update_skills_table_to_rsd',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (220,'2024_12_05_062941_add_resource_type_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (221,'2019_06_24_140207_create_saml2_tenants_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (222,'2020_10_22_140856_add_relay_state_url_column_to_saml2_tenants_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (223,'2020_10_23_072902_add_name_id_format_column_to_saml2_tenants_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (224,'2025_01_09_013630_add_team_heirarchy',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (225,'2025_01_09_032030_add_resource_link',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (227,'2025_01_09_035530_add_user_linkto_resource',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (228,'2025_01_14_052300_create_staging_demand_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (229,'2025_01_14_052305_create_staging_allocation_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (230,'2025_01_15_035928_create_public_holidays_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (231,'2025_01_15_061839_update_region_with_juristiction',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (232,'2025_02_13_014000_sites_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (233,'2025_02_13_014010_domains_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (234,'2025_02_13_014028_funding_approval_stages_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (247,'2025_02_13_014046_alter_demands_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (248,'2025_02_13_032657_add_demand_foreign_keys',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (249,'2025_02_13_014046_create_requests_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (250,'2025_02_13_032657_add_requests_foreign_keys',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (251,'2025_02_20_004114_add_dates_to_projects_table',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (253,'2025_02_26_024553_add_reports_to_to_users',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (254,'2025_02_28_011519_change_request',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (255,'2025_02_28_034518_add_permanent_flag2_contract',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (256,'2025_04_08_065048_add_resource_type_id_to_resources_and_teams',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (257,'2025_04_08_075718_create_plugins_table',35);
