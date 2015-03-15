'use strict';

/**
 * Module dependencies.
 */
var mongoose = require('mongoose'),
	Schema = mongoose.Schema;

/**
 * Province Schema
 */
var ProvinceSchema = new Schema({
	code: {
		type: Number
	},
	name: {
		type: String
	},
	name_eng: {
		type: String
	},
	geo: {
		type: Number
	}
});

mongoose.model('Province', ProvinceSchema);