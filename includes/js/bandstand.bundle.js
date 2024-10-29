require=(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";
/* jshint browserify: true, -W079 */

'use strict';

module.exports = {
	/**
	 * Convert time in seconds to a human-readable time code.
	 *
	 * @param  {number} time Time in seconds.
	 * @return {string}      Human-readable time code in hh:mm:ss format.
	 */
	formatTime: function( time ) {
		var hours, minutes, seconds,
			timeCode = '';

		seconds = parseInt( time, 10 ) % 60;
		minutes = Math.floor( time / 60 ) % 60;
		hours   = Math.floor( time / 3600 );
		seconds = seconds < 10 ? '0' + seconds : seconds;

		if ( hours ) {
			minutes = minutes < 10 ? '0' + minutes : minutes;
			timeCode += hours + ':';
		}

		timeCode += minutes + ':' + seconds;

		return timeCode;
	}
};

},{}],"bandstand":[function(require,module,exports){
(function (global){
"use strict";
/* jshint browserify: true */

'use strict';

var _ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	utils = require( './utils' );

function Application() {
	var settings = {};

	_.extend( this, {
		collection: {},
		controller: {},
		l10n: {},
		model: {},
		util: utils,
		view: {}
	});

	this.settings = function( options ) {
		if ( options ) {
			_.extend( settings, options );
		}

		if ( settings.l10n ) {
			this.l10n = _.extend( this.l10n, settings.l10n );
			delete settings.l10n;
		}

		return settings || {};
	};
}

global.bandstand = global.bandstand || new Application();
module.exports = global.bandstand;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./utils":1}]},{},[]);
