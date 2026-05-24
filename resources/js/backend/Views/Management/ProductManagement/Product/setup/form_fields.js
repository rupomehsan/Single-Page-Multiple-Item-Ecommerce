/**
 * Form Fields Configuration
 * Auto-generated — edit data_list / class / is_visible as needed.
 */

export default [
	
	{
		name: "name",
		label: "Enter Name",
		type: "text",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "category_id",
		label: "Select Category",
		type: "select",
		multiple: false,
		data_list: [],
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "regular_price",
		label: "Enter Regular Price",
		type: "number",
		step: "0.01",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "sales_price",
		label: "Enter Sales Price",
		type: "number",
		step: "0.01",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "quantity",
		label: "Enter Quantity",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	
	{
		name: "sku",
		label: "Enter Sku",
		type: "text",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "is_featured",
		label: "Select Is Featured",
		type: "select",
		multiple: false,
		data_list: [
			{ label: "Yes", value: "1" },
			{ label: "No", value: "0" },
		],
		value: "1",
		is_visible: true,
		class: "col-md-6",
	},

	{
		name: "image",
		label: "Upload Image",
		type: "file",
		multiple: false,
		accept: "image/*",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	
	{
		name: "description",
		label: "Enter Description",
		type: "textarea",
		rows: 4,
		value: "",
		is_visible: true,
		class: "col-md-12",
	},
	
];
