'use strict';

/**
 * Module dependencies.
 */
var mongoose = require('mongoose'),
	Schema = mongoose.Schema;

/**
 * Cart Schema
 */
var CartSchema = new Schema({
	// Cart model fields   
	// ...
});

mongoose.model('Cart', CartSchema);