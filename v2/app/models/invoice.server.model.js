'use strict';

/**
 * Module dependencies.
 */
var mongoose = require('mongoose'),
	Schema = mongoose.Schema;

/**
 * Invoice Schema
 */
var InvoiceSchema = new Schema({
	// Invoice model fields   
	// ...
});

mongoose.model('Invoice', InvoiceSchema);