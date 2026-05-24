/**
 * FAQ Module Setup Configuration
 *
 * This file contains all configuration for the FAQ module including:
 * - API endpoints and versioning
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * Generated automatically - Modifications will be preserved if regenerated
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "FAQ";

const setup: setup_type = {
    // Module Identity
    prefix,
    module_name: "f-a-q",
    store_prefix: "f-a-q",
    route_prefix: "FAQ",
    route_path: "f-a-q",

    // Permission Configuration
    permission: ["admin", "super_admin"],
    permission_slugs: {
        view: "f-a-q-view",
        details: "f-a-q-details",
        create: "f-a-q-create",
        edit: "f-a-q-edit",
        delete: "f-a-q-delete",
        import: "f-a-q-import",
    },

    // API Configuration
    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: "f-a-qs",

    // Field Selection for API requests
    select_fields: [
        "id",
        "product_id",
            "question",
            "answer",
            "category",
            "display_order",
            "is_active",
            "view_count",
            "helpful_count",
        "status",
        "slug",
        "created_at",
        "deleted_at"
    ],

    // Available columns for sorting
    sort_by_cols: [
        "id",
        "product_id",
            "question",
            "answer",
            "category",
            "display_order",
            "is_active",
            "view_count",
            "helpful_count",
        "status",
        "created_at",
    ],

    // Table header columns (shown in list view)
    table_header_data: [
        "id",
        "product_id",
            "question",
            "answer",
            "category",
            "display_order",
            "is_active",
            "view_count",
            "helpful_count",
        "status",
        "created_at",
    ],

    // Table row data fields (rendered in list view)
    table_row_data: [
        "id",
        "product_id",
            "question",
            "answer",
            "category",
            "display_order",
            "is_active",
            "view_count",
            "helpful_count",
        "status",
        "created_at",
    ],

    // Quick view modal data fields
    quick_view_data: [
        "id",
        "product_id",
            "question",
            "answer",
            "category",
            "display_order",
            "is_active",
            "view_count",
            "helpful_count",
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
