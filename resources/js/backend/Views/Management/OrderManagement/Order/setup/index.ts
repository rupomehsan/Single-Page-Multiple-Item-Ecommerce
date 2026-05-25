/**
 * Order Module Setup Configuration
 *
 * This file contains all configuration for the Order module including:
 * - API endpoints and versioning
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * Generated automatically - Modifications will be preserved if regenerated
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "Order";

const setup: setup_type = {
  // Module Identity
  prefix,
  module_name: "order",
  store_prefix: "order",
  route_prefix: "Order",
  route_path: "order",

  // Permission Configuration
  permission: ["admin", "super_admin"],
  permission_slugs: {
    view: "order-view",
    details: "order-details",
    create: "order-create",
    edit: "order-edit",
    delete: "order-delete",
    import: "order-import",
  },

  // API Configuration
  api_host: app_config.api_host,
  api_version: app_config.api_version,
  api_end_point: "orders",

  // Field Selection for API requests
  select_fields: [
    "id",
    "order_number",
    "customer_name",
    "customer_phone",
    "delivery_address",
    "delivery_date",
    "subtotal",
    "total_amount",
    "shipping_charge",
    "payment_status",
    "order_status",
    "created_at",
    "deleted_at",
  ],

  // Available columns for sorting
  sort_by_cols: [
    "id",
    "order_number",
    "customer_name",
    "customer_phone",
    "delivery_address",
    "delivery_date",
    "subtotal",
    "total_amount",
    "shipping_charge",
    "payment_status",
    "order_status",
    "status",
    "created_at",
  ],

  // Table header columns (shown in list view)
  table_header_data: [
    "id",
    "order_number",
    "customer_name",
    "customer_phone",
    "delivery_address",
    "delivery_date",
    "subtotal",
    "total_amount",
    "shipping_charge",
    "payment_status",
    "order_status",
    "status",
    "created_at",
  ],

  // Table row data fields (rendered in list view)
  table_row_data: [
    "id",
    "order_number",
    "customer_name",
    "customer_phone",
    "delivery_address",
    "delivery_date",
    "subtotal",
    "total_amount",
    "shipping_charge",
    "payment_status",
    "order_status",
    "status",
    "created_at",
  ],

  // Quick view modal data fields
  quick_view_data: [
    "id",
    "order_number",
    "customer_name",
    "customer_phone",
    "delivery_address",
    "delivery_date",
    "subtotal",
    "total_amount",
    "shipping_charge",
    "payment_status",
    "order_status",
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
