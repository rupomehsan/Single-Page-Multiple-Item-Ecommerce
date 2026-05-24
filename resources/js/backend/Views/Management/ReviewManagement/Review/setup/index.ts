/**
 * Review Module Setup Configuration
 *
 * This file contains all configuration for the Review module including:
 * - API endpoints and versioning
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * Generated automatically - Modifications will be preserved if regenerated
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "Review";

const setup: setup_type = {
    // Module Identity
    prefix,
    module_name: "review",
    store_prefix: "review",
    route_prefix: "Review",
    route_path: "review",

    // Permission Configuration
    permission: ["admin", "super_admin"],
    permission_slugs: {
        view: "review-view",
        details: "review-details",
        create: "review-create",
        edit: "review-edit",
        delete: "review-delete",
        import: "review-import",
    },

    // API Configuration
    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: "reviews",

    // Field Selection for API requests
    select_fields: [
        "id",
        "order_id",
            "product_id",
            "customer_id",
            "customer_name",
            "customer_location",
            "rating",
            "title",
            "review_text",
            "verified_purchase",
            "is_published",
            "is_featured",
            "helpful_count",
            "unhelpful_count",
            "admin_response",
            "admin_response_date",
        "status",
        "slug",
        "created_at",
        "deleted_at"
    ],

    // Available columns for sorting
    sort_by_cols: [
        "id",
        "order_id",
            "product_id",
            "customer_id",
            "customer_name",
            "customer_location",
            "rating",
            "title",
            "review_text",
            "verified_purchase",
            "is_published",
            "is_featured",
            "helpful_count",
            "unhelpful_count",
            "admin_response",
            "admin_response_date",
        "status",
        "created_at",
    ],

    // Table header columns (shown in list view)
    table_header_data: [
        "id",
        "order_id",
            "product_id",
            "customer_id",
            "customer_name",
            "customer_location",
            "rating",
            "title",
            "review_text",
            "verified_purchase",
            "is_published",
            "is_featured",
            "helpful_count",
            "unhelpful_count",
            "admin_response",
            "admin_response_date",
        "status",
        "created_at",
    ],

    // Table row data fields (rendered in list view)
    table_row_data: [
        "id",
        "order_id",
            "product_id",
            "customer_id",
            "customer_name",
            "customer_location",
            "rating",
            "title",
            "review_text",
            "verified_purchase",
            "is_published",
            "is_featured",
            "helpful_count",
            "unhelpful_count",
            "admin_response",
            "admin_response_date",
        "status",
        "created_at",
    ],

    // Quick view modal data fields
    quick_view_data: [
        "id",
        "order_id",
            "product_id",
            "customer_id",
            "customer_name",
            "customer_location",
            "rating",
            "title",
            "review_text",
            "verified_purchase",
            "is_published",
            "is_featured",
            "helpful_count",
            "unhelpful_count",
            "admin_response",
            "admin_response_date",
        "status",
        "created_at",
    ],

    // UI Labels and Titles
    layout_title: prefix + " Management",
    page_title: `${prefix} Management`,
    all_page_title: "All " + prefix,
    details_page_title: "Details " + prefix,
    create_page_title: "Create " + prefix,
    edit_page_title: "Edit " + prefix,
};

export default setup;
