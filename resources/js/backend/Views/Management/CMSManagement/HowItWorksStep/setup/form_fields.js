/**
 * Form Fields Configuration
 * Auto-generated — edit data_list / class / is_visible as needed.
 */

export default [
	{
		name: "step_number",
		label: "Enter Step Number",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "title",
		label: "Enter Title",
		type: "text",
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
		class: "col-md-6",
	},
	{
		name: "icon",
		label: "Enter Icon",
		type: "text",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "icon_image",
		label: "Upload Icon Image",
		type: "file",
		multiple: false,
		accept: "image/*",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "display_order",
		label: "Enter Display Order",
		type: "number",
		step: "1",
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
	{
		name: "is_active",
		label: "Select Is Active",
		type: "select",
		label: "Select Is Active",
		multiple: false,
		data_list: [
			{ label: "Yes", value: "yes" },
			{ label: "No", value: "no" },
		],
		value: "",
		is_visible: true,
		class: "col-md-6",
	},
];
