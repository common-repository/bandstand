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

},{}],2:[function(require,module,exports){
(function (global){
"use strict";
/* jshint browserify: true */
/* globals _bandstandTracks */

'use strict';

var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null),
	cuebone = require( 'cuebone' );

$( '.bandstand-tracklist' ).each(function() {
	var $tracklist = $( this );

	var tracks = new cuebone.collection.Tracks( _bandstandTracks, {
		id: 'tracklist-tracks'
	});

	var player = new cuebone.controller.Player({
		id: 'tracklist-player'
	}, {
		events: cuebone.Events,
		tracks: tracks
	});

	cuebone.players.add( player );

	$tracklist.on( 'click', '.bandstand-track', function( e ) {
		var index = $tracklist.find( '.bandstand-track' ).index( this );
		player.setCurrentTrack( index );

		if ( 'playing' === player.media.first().get( 'status' ) ) {
			player.pause();
		} else {
			player.play();
		}
	});
});

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"cuebone":3}],3:[function(require,module,exports){
(function (global){
"use strict";
var _ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null),
	cuebone = {},
	JPlayerAdapter = require( './src/adapters/jplayer' ),
	MediaElementJsAdapter = require( './src/adapters/mediaelementjs' );

_.extend( cuebone, {
	players: {},
	collection: {},
	controller: {},
	model: {}
});

cuebone.Events = _.extend({}, Backbone.Events );

cuebone.controller.Mediator = require( './src/controllers/mediator' );
cuebone.controller.Player = require( './src/controllers/player.js' );
cuebone.model.Track = require( './src/models/track.js' );
cuebone.collection.Tracks = require( './src/collections/tracks.js' );

cuebone.players = new cuebone.controller.Mediator([], {
	events: cuebone.Events
});

cuebone.players.jplayer = new JPlayerAdapter( cuebone.players, cuebone.Events );
cuebone.players.mediaelementjs = new MediaElementJsAdapter( cuebone.players, cuebone.Events );

module.exports = cuebone;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./src/adapters/jplayer":4,"./src/adapters/mediaelementjs":5,"./src/collections/tracks.js":6,"./src/controllers/mediator":7,"./src/controllers/player.js":8,"./src/models/track.js":10}],4:[function(require,module,exports){
(function (global){
"use strict";
var Player,
	$ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null),
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null);

// @todo Watch for remove events.

Player = Backbone.Model.extend({
	defaults: {
		id: null,
		player: null
	},

	initialize: function( attributes, options ) {
		var player = this.get( 'player' );
		this.events = options.events;
		player.on( $.jPlayer.event.play, _.bind( this.onPlay, this ) );
	},

	pause: function() {
		this.get( 'player' ).jPlayer( 'pause' );
	},

	onPlay: function() {
		this.events.trigger( 'play', this );
	}
});

function Adapter( collection, events ) {
	this.events = events;
	this.collection = collection;
	this.model = Player;

	this.initialize();
}

Adapter.prototype.initialize = function() {
	var jPlayerInit,
		self = this;

	if ( ! _.isFunction( $.jPlayer ) ) {
		return;
	}

	// Proxies the jPlayer init method to automatically register players
	// created after initialization.
	jPlayerInit = $.jPlayer.prototype._init;
	$.jPlayer.prototype._init = function() {
		jPlayerInit.apply( this, arguments );
		self.register( this.internal.instance, this.element );
	};

	// Register players that have already been created.
	this.registerPlayers();
};

Adapter.prototype.registerPlayers = function() {
	if ( ! _.isFunction( $.jPlayer ) ) {
		return;
	}

	_.each( $.jPlayer.prototype.instances, function( player, id ) {
		this.register( id, player );
	}, this );
};

Adapter.prototype.register = function( id, player ) {
	var model = new this.model({
		id: id,
		player: player
	}, {
		events: this.events
	});

	this.collection.add( model );
};

module.exports = Adapter;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],5:[function(require,module,exports){
(function (global){
"use strict";
var Player,
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null),
	mejs = (typeof window !== "undefined" ? window['mejs'] : typeof global !== "undefined" ? global['mejs'] : null);

// @todo Watch for remove events.

Player = Backbone.Model.extend({
	defaults: {
		id: null,
		player: null
	},

	initialize: function( attributes, options ) {
		this.events = options.events;
		this.get( 'player' ).$media.on( 'play', _.bind( this.onPlay, this ) );
	},

	pause: function() {
		this.get( 'player' ).pause();
	},

	onPlay: function() {
		this.events.trigger( 'play', this );
	}
});

function Adapter( collection, events ) {
	this.events = events;
	this.collection = collection;
	this.model = Player;

	this.initialize();
}

Adapter.prototype.initialize = function() {
	var mejsPlayerInit,
		self = this;

	if ( _.isUndefined( mejs ) ) {
		return;
	}

	// Proxies the MediaElement.js init method to automatically register players
	// created after initialization.
	mejsPlayerInit = mejs.MediaElementPlayer.prototype.init;
	mejs.MediaElementPlayer.prototype.init = function() {
		mejsPlayerInit.apply( this, arguments );
		self.register( this );
	};

	// Register players that have already been created.
	this.registerPlayers();
};

Adapter.prototype.registerPlayers = function() {
	if ( _.isUndefined( mejs ) ) {
		return;
	}

	_.each( mejs.players, function( player ) {
		this.register( player );
	}, this );
};

Adapter.prototype.register = function( player ) {
	var model = new this.model({
		id: player.id,
		player: player
	}, {
		events: this.events
	});

	this.collection.add( model );
};

module.exports = Adapter;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],6:[function(require,module,exports){
(function (global){
"use strict";
var Tracks,
	$ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null),
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null),
	Track = require( '../models/track.js' );

Tracks = Backbone.Collection.extend({
	model: Track,

	initialize: function( models, options ) {
		this.options = _.extend({
			id: 'cuebone-default-tracks'
		}, options );
	},

	fetch: function() {
		var tracks,
			deferred = $.Deferred();

		tracks = JSON.parse( localStorage.getItem( this.options.id ) );
		if ( null !== tracks ) {
			this.reset( tracks );
		}

		return deferred.resolve( tracks ).promise();
	},

	save: function() {
		localStorage.setItem( this.options.id, JSON.stringify( this.toJSON() ) );
	}
});

module.exports = Tracks;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"../models/track.js":10}],7:[function(require,module,exports){
(function (global){
"use strict";
/**
 * Players mediator.
 *
 * The collection of players acts as a mediator between all registered media
 * elements on a page to ensure only one is being played at any given time.
 *
 * Aside from cuebone players, support for MediaElement.js and jPlayer is built
 * in. Support for other players can be added by adding a Backbone model that
 * triggers a 'play' event on the main event bus when the player begins playing
 * and defining a 'pause' method to be called when another player starts.
 */

var Mediator,
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null);

Mediator = Backbone.Collection.extend({
	initialize: function( models, options ) {
		this.listenTo( options.events, 'play', this.pauseOthers );
	},

	pauseOthers: function( model ) {
		_.chain( this.models ).without( model ).invoke( 'pause' );
	}
});

module.exports = Mediator;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],8:[function(require,module,exports){
(function (global){
"use strict";
var Player,
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null),
	instance = 0,
	Tracks = require( '../collections/tracks' );

Player = Backbone.Model.extend({
	defaults: {
		id: null,
		currentTrackIndex: 0,
		loop: false,
		muted: false,
		repeat: false,
		shuffle: false,
		volume: 0.8
	},

	initialize: function( attributes, options ) {
		var ua = window.navigator.userAgent.toLowerCase();

		this.mediaElement = document.createElement( 'audio' );

		this.options = _.extend({
			events: null,
			persist: false,
			tracks: null
		}, options );

		// Set a unique id for this instance if one hasn't been set.
		if ( ! this.get( 'id' ) ) {
			this.set( 'id', 'cuebone-player-' + instance );
			// A static id is needed to persist the player.
			this.options.persist = false;
			instance++;
		}

		// @todo Move this stuff into utils?
		this.isAndroid = ( ua.match( /android/i ) !== null );
		this.isiPad = ( ua.match( /ipad/i ) !== null );
		this.isiPhone = ( ua.match( /iphone/i ) !== null );
		this.isiOS = this.isiPhone || this.isiPad;

		this.current = new Tracks();
		this.media = new Backbone.Collection();
		this.tracks = this.options.tracks || new Tracks([{}]);
		delete this.options.tracks;

		// Remember the player state between requests.
		if ( this.options.persist ) {
			this.fetchTracks();
			this.on( 'change', this.save, this );
			this.current.on( 'add change remove reset', this.save, this );
			this.media.on( 'add change remove reset', this.save, this );
			this.tracks.on( 'add remove reset', this.tracks.save, this.tracks );
		}

		this.listenTo( this.media, 'ended', this.onTrackEnded );

		// Clear the current track index when replacing tracks.
		this.listenTo( this.tracks, 'reset', this.onTracksReset );

		this.fetch();

		return this;
	},

	getCurrentTrack: function() {
		return this.current.first();
	},

	isMuted: function() {
		return !! this.get( 'muted' );
	},

	loadTrack: function( track ) {
		var currentTrack = this.current.first();

		this.pause();

		if ( currentTrack ) {
			currentTrack.media.set( 'status', 'error' === currentTrack.get( 'status') ? 'error' : 'paused' );
			currentTrack.media.unload();
		}

		// Update the current track.
		this.set( 'status', 'loading' );
		this.current.reset( track );
		this.media.reset( track.media );

		// Re-use the media element between tracks because Android and iOS won't
		// play dynamically created media elements without user interaction.
		track.media.mediaElement = this.mediaElement;

		track.media.load();
	},

	mute: function() {
		this.current.first().media.mute();
		this.set( 'muted', true );
		return this;
	},

	nextTrack: function() {
		var nextIndex,
			currentIndex = this.get( 'currentTrackIndex' );

		if ( this.get( 'shuffle' ) ) {
			nextIndex = Math.floor( Math.random() * this.tracks.length );
		} else {
			nextIndex = currentIndex + 1 >= this.tracks.length ? 0 : currentIndex + 1;
		}

		this.setCurrentTrack( nextIndex );

		return this;
	},

	pause: function() {
		if ( this.current.length ) {
			this.current.first().media.pause();
		}
		return this;
	},

	play: function() {
		this.options.events.trigger( 'play', this );
		this.current.first().media.play();
		return this;
	},

	previousTrack: function() {
		var currentIndex = this.get( 'currentTrackIndex' ),
			previousIndex = currentIndex - 1 < 0 ? this.tracks.length - 1 : currentIndex - 1;

		this.setCurrentTrack( previousIndex );
		return this;
	},

	seekTo: function( time ) {
		this.current.first().media.seekTo( time );
		return this;
	},

	setCurrentTrack: function( index ) {
		while ( index >= this.tracks.length ) {
			index--;
		}

		if ( index !== this.get( 'currentTrackIndex' ) || ! this.current.length ) {
			this.set( 'currentTrackIndex', index );
			this.loadTrack( this.tracks.at( index ) );
		}

		return this;
	},

	setVolume: function( volume ) {
		this.current.first().media.setVolume( volume );
		this.unmute();
		this.set( 'volume', volume );
		return this;
	},

	unmute: function() {
		this.current.first().media.unmute();
		this.set( 'muted', false );
		return this;
	},

	fetch: function() {
		var attributes,
			id = this.get( 'id' );

		if ( ! this.options.persist ) {
			this.setCurrentTrack( this.get( 'currentTrackIndex' ) );
			return;
		}

		attributes = JSON.parse( localStorage.getItem( id ) );

		if ( null === attributes ) {
			this.setCurrentTrack( this.get( 'currentTrackIndex' ) );
			return;
		}

		this.setCurrentTrack ( attributes.currentTrackIndex );
		this.seekTo( attributes.current_time );

		this.set({
			loop: attributes.loop,
			repeat: attributes.repeat,
			shuffle: attributes.shuffle
		});

		// Don't auto play on mobile devices.
		if ( 'playing' === attributes.status && ! this.isAndroid && ! this.isiOS ) {
			this.play();
		}
	},

	fetchTracks: function() {
		var id = this.get( 'id' ),
			cachedSignature = localStorage.getItem( id + '-signature' );

		// Don't fetch tracks from localStorage if the signature has changed.
		if ( 'signature' in this.options && cachedSignature !== this.options.signature ) {
			localStorage.removeItem( id );
			localStorage.removeItem( id + '-signature' );
			localStorage.removeItem( id + '-tracks' );
			return;
		}

		this.tracks.fetch();
	},

	save: function() {
		var data, track,
			id = this.get( 'id' );

		if ( ! this.options.persist ) {
			return;
		}

		data = this.toJSON();
		data.current_time = 0;
		data.status = 'paused';

		if ( this.current.length ) {
			track = this.current.first();
			data.current_time = track.media.get( 'current_time' );
			data.status = track.media.get( 'status' );
		}

		localStorage.setItem( id, JSON.stringify( data ) );

		if ( 'signature' in this.options ) {
			localStorage.setItem( id + '-signature', this.options.signature );
		}
	},

	onTrackEnded: function() {
		if ( this.get( 'repeat' ) ) {
			this.seekTo( 0 ).play();
		} else if (
			this.get( 'currentTrackIndex' ) < this.tracks.length - 1 ||
			this.get( 'loop' ) ||
			this.get( 'shuffle' )
		) {
			this.nextTrack().play();
			this.play();
		} else {
			this.nextTrack().pause();
		}
	},

	onTracksReset: function() {
		this.set( 'currentTrackIndex', null );
	}
});

module.exports = Player;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"../collections/tracks":6}],9:[function(require,module,exports){
(function (global){
"use strict";
var Media,
	$ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null),
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null);

Media = Backbone.Model.extend({
	defaults: {
		current_time: 0,
		duration: 0, // Seconds.
		loaded: 0, // Percentage.
		source: '',
		status: '', // Values: loading, ready, paused, playing.
		track: null,
		type: ''
	},

	initialize: function( attributes, options ) {
		this.canPlay = $.Deferred();
		this.mediaElement = null;
		this.ready = $.when( this.canPlay );

		_.bindAll(
			this,
			'onEnded',
			'onError',
			'onLoadedMetadata',
			'onPause',
			'onPlaying',
			'onReady',
			'onTimeUpdate',
			'updateLoaded'
		);
	},

	getProgress: function() {
		var currentTime = this.get( 'current_time' ),
			duration = this.get( 'duration' );

		return currentTime / duration;
	},

	load: function() {
		this.set( 'status', 'loading' );

		if ( ! this.mediaElement ) {
			this.set( 'status', 'error' );
			return;
		}

		this.mediaElement.setAttribute( 'preload', 'auto' );
		this.mediaElement.setAttribute( 'src', this.get( 'source' ) );
		this.mediaElement.setAttribute( 'type', this.get( 'type' ) );

		this.mediaElement.addEventListener( 'canplay',        this.onReady );
		this.mediaElement.addEventListener( 'loadedmetadata', this.onReady );
		this.mediaElement.addEventListener( 'ended',          this.onEnded );
		this.mediaElement.addEventListener( 'loadedmetadata', this.onLoadedMetadata );
		this.mediaElement.addEventListener( 'error',          this.onError );
		// abort, emptied, stalled, suspend
		this.mediaElement.addEventListener( 'pause',          this.onPause );
		this.mediaElement.addEventListener( 'playing',        this.onPlaying );
		this.mediaElement.addEventListener( 'loadedmetadata', this.onTimeUpdate );
		this.mediaElement.addEventListener( 'timeupdate',     this.onTimeUpdate );
		this.mediaElement.addEventListener( 'progress',       this.updateLoaded );
		this.mediaElement.addEventListener( 'timeupdate',     this.updateLoaded );

		// https://code.google.com/p/chromium/issues/detail?id=73609
		//this.mediaElement.addEventListener( 'canplaythrough', this.onReady );

		this.mediaElement.load();
	},

	unload: function() {
		this.set( 'status', 'paused' );

		this.mediaElement.removeEventListener( 'canplay',        this.onReady );
		this.mediaElement.removeEventListener( 'loadedmetadata', this.onReady );
		this.mediaElement.removeEventListener( 'ended',          this.onEnded );
		this.mediaElement.removeEventListener( 'loadedmetadata', this.onLoadedMetadata );
		this.mediaElement.removeEventListener( 'error',          this.onError );
		this.mediaElement.removeEventListener( 'pause',          this.onPause );
		this.mediaElement.removeEventListener( 'playing',        this.onPlaying );
		this.mediaElement.removeEventListener( 'loadedmetadata', this.onTimeUpdate );
		this.mediaElement.removeEventListener( 'timeupdate',     this.onTimeUpdate );
		this.mediaElement.removeEventListener( 'progress',       this.updateLoaded );
		this.mediaElement.removeEventListener( 'timeupdate',     this.updateLoaded );
	},

	mute: function() {
		this.mediaElement.muted = true;
	},

	pause: function() {
		if ( this.mediaElement ) {
			this.mediaElement.pause();
		}

		this.set( 'status', 'paused' );
	},

	play: function() {
		var self = this;
		this.ready.done(function() {
			self.mediaElement.play();
			self.set( 'status', 'playing' );
		});
	},

	seekTo: function( time ) {
		var intervalId, readyState,
			deferred = $.Deferred(),
			media = this.mediaElement;

		if ( _.isUndefined( time ) ) {
			return;
		}

		this.ready = $.when( this.canPlay, deferred );

		readyState = function() {
			if ( 4 === media.readyState ) { //  && ( ! mejs.MediaFeatures.isWebkit || time < node.buffered.end( 0 ) )
				media.currentTime = time;
				clearInterval( intervalId );
				deferred.resolve();
				return true;
			}
			return false;
		};

		if ( ! readyState() ) {
			intervalId = setInterval( readyState, 50 );
		}
	},

	setVolume: function( volume ) {
		this.mediaElement.volume = volume;
	},

	unmute: function() {
		this.mediaElement.muted = false;
	},

	onError: function() {
		this.set( 'status', 'error' );
	},

	onEnded: function() {
		this.trigger( 'ended' );
	},

	onLoadedMetadata: function() {
		this.set( 'duration', this.mediaElement.duration );
	},

	onPause: function() {
		this.set( 'status', 'paused' );
	},

	onPlaying: function() {
		this.set( 'status', 'playing' );
	},

	onReady: function() {
		this.canPlay.resolve();
		this.set( 'status', 'ready' );
	},

	onTimeUpdate: function() {
		if ( this.mediaElement ) {
			this.set( 'current_time', this.mediaElement.currentTime );
		}
	},

	updateLoaded: function() {
		var loaded;

		try {
			loaded = this.mediaElement.buffered.end( this.mediaElement.buffered.length - 1 );
			this.set( 'loaded', loaded / this.mediaElement.duration * 100 );
		} catch ( ex ) {}
	}
});

module.exports = Media;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}],10:[function(require,module,exports){
(function (global){
"use strict";
var Track,
	$ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null),
	_ = (typeof window !== "undefined" ? window['_'] : typeof global !== "undefined" ? global['_'] : null),
	Backbone = (typeof window !== "undefined" ? window['Backbone'] : typeof global !== "undefined" ? global['Backbone'] : null),
	Media = require( './media' );

// @todo Media nodes can have multiple sources to handle different formats.
// @todo Add credit/source icon and link attributes?

Track = Backbone.Model.extend({
	defaults: {
		artist: '',
		artwork_url: '',
		download_url: '',
		duration: '',
		purchase_url: '',
		record_id: null,
		stream_url: '',
		title: '',
		track_number: ''
	},

	initialize: function( attributes, options ) {
		this.media = new Media({
			source: this.get( 'stream_url' ),
			track: this
		});
	}
});

module.exports = Track;

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"./media":9}],"bandstand":[function(require,module,exports){
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
},{"./utils":1}]},{},[2]);
