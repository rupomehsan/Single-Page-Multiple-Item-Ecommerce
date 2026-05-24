/**
 * Form Fields Configuration
 * Auto-generated — edit data_list / class / is_visible as needed.
 */

export default [
	{
		name: "customer_name",
		label: "Enter Customer Name",
		type: "text",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},

	// {
	// 	name: "customer_location",
	// 	label: "Enter Customer Location",
	// 	type: "text",
	// 	value: "",
	// 	is_visible: true,
	// 	class: "col-md-6",
	// },
	
	{
		name: "rating",
		label: "Enter Rating",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	// {
	// 	name: "from_order_id",
	// 	label: "Select From Order",
	// 	type: "select",
	// 	multiple: false,
	// 	data_list: [],
	// 	value: "",
	// 	is_visible: true,
	// 	class: "col-md-6",
	// },
	// {
	// 	name: "is_featured",
	// 	label: "Select Is Featured",
	// 	type: "select",
	// 	multiple: false,
	// 	data_list: [
	// 		{ label: "Yes", value: "1" },
	// 		{ label: "No", value: "0" },
	// 	],
	// 	value: "",
	// 	is_visible: true,
	// 	class: "col-md-6",
	// },
	{
		name: "is_published",
		label: "Select Is Published",
		type: "select",
		multiple: false,
		data_list: [
			{ label: "Yes", value: "1" },
			{ label: "No", value: "0" },
		],
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
		{
		name: "customer_image",
		label: "Upload Customer Image",
		type: "file",
		multiple: false,
		accept: "image/*",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "testimonial_text",
		label: "Enter Testimonial Text",
		type: "textarea",
		rows: 4,
		value: "",
		is_visible: true,
		class: "col-md-12",
	},
	// {
	// 	name: "display_order",
	// 	label: "Enter Display Order",
	// 	type: "number",
	// 	step: "1",
	// 	value: "",
	// 	is_visible: true,
	// 	class: "col-md-6",
	// },
];
