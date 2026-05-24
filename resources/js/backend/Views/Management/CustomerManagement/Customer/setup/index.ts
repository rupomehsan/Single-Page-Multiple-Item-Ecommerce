/**
 * Customer Module Setup Configuration
 *
 * This file contains all configuration for the Customer module including:
 * - API endpoints and versioning
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * Generated automatically - Modifications will be preserved if regenerated
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "Customer";

const setup: setup_type = {
    // Module Identity
    prefix,
    module_name: "customer",
    store_prefix: "customer",
    route_prefix: "Customer",
    route_path: "customer",

    // Permission Configuration
    permission: ["admin", "super_admin"],
    permission_slugs: {
        view: "customer-view",
        details: "customer-details",
        create: "customer-create",
        edit: "customer-edit",
        delete: "customer-delete",
        import: "customer-import",
    },

    // API Configuration
    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: "customers",

    // Field Selection for API requests
    select_fields: [
        "id",
        "user_id",
            "phone_number",
            "address",
            "district",
            "thana",
            "village",
            "total_orders",
            "total_spent",
            "lifetime_discount",
            "loyalty_points",
            "is_verified",
            "last_order_date",
        "status",
        "slug",
        "created_at",
        "deleted_at"
    ],

    // Available columns for sorting
    sort_by_cols: [
        "id",
        "user_id",
            "phone_number",
            "address",
            "district",
            "thana",
            "village",
            "total_orders",
            "total_spent",
            "lifetime_discount",
            "loyalty_points",
            "is_verified",
            "last_order_date",
        "status",
        "created_at",
    ],

    // Table header columns (shown in list view)
    table_header_data: [
        "id",
        "user_id",
            "phone_number",
            "address",
            "district",
            "thana",
            "village",
            "total_orders",
            "total_spent",
            "lifetime_discount",
            "loyalty_points",
            "is_verified",
            "last_order_date",
        "status",
        "created_at",
    ],

    // Table row data fields (rendered in list view)
    table_row_data: [
        "id",
        "user_id",
            "phone_number",
            "address",
            "district",
            "thana",
            "village",
            "total_orders",
            "total_spent",
            "lifetime_discount",
            "loyalty_points",
            "is_verified",
            "last_order_date",
        "status",
        "created_at",
    ],

    // Quick view modal data fields
    quick_view_data: [
        "id",
        "user_id",
            "phone_number",
            "address",
            "district",
            "thana",
            "village",
            "total_orders",
            "total_spent",
            "lifetime_discount",
            "loyalty_points",
            "is_verified",
            "last_order_date",
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
