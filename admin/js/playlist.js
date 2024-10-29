/* globals _, _bandstandPlaylistSettings, Backbone, cue, jQuery, wp */

window.cue = window.cue || {};

(function( window, $, _, Backbone, wp, undefined ) {
	'use strict';

	var l10n = _bandstandPlaylistSettings.l10n,
		frame;

	/**
	 * ========================================================================
	 * MODELS
	 * ========================================================================
	 */

	/**
	 * Fetch a track by id.
	 */
	cue.model.Track.prototype.fetch = function() {
		// @todo Make sure the track has an id.
		return wp.ajax.post( 'bandstand_ajax_get_playlist_track', {
			post_id: this.get( 'id' )
		});
	};

	/**
	 * Add one or more track CPTs to the collection.
	 *
	 * @param int|array ids One or more track CPT IDs.
	 */
	cue.model.Tracks.prototype.addTracks = function( ids ) {
		var collection = this;

		return wp.ajax.post( 'bandstand_ajax_get_playlist_tracks', {
			post__in: ids
		}).done(function( tracks ) {
			collection.add( tracks );
		});
	};

	/**
	 * ========================================================================
	 * CONTROLLERS
	 * ========================================================================
	 */

	/**
	 * Tracks controller.
	 *
	 * cue.controller.BandstandPlaylistTracks
	 */
	cue.controller.BandstandPlaylistTracks = wp.media.controller.State.extend({
		defaults: {
			id:      'bandstand-playlist-tracks',
			menu:    'default',
			content: 'bandstand-playlist-tracks',
			toolbar: 'main-bandstand-playlist-tracks',
			title:   l10n.frameTitle,
			button:  { text: l10n.frameButtonText },
			menuItem: { text: l10n.frameMenuItemText, priority: 100 }
		},

		initialize: function() {
			this.set( 'records', new Backbone.Collection() );
			this.set( 'selection', new Backbone.Collection() );
		}
	});

	/**
	 * ========================================================================
	 * VIEWS
	 * ========================================================================
	 */

	/**
	 * Tracks content.
	 *
	 * cue.view.BandstandPlaylistTracksContent
	 */
	cue.view.BandstandPlaylistTracksContent = wp.media.View.extend({
		className: 'media-bandstand-playlist-tracks',

		initialize: function() {
			this._paged = 1;
			this._pending = false;

			this.listenTo( this.collection, 'add', this.addRecord );
			this.listenTo( this.collection, 'reset', this.render );
		},

		render: function() {
			if ( ! this.collection.length ) {
				this.getRecords();
			}

			this.$el.off( 'scroll' ).on( 'scroll', _.bind( this.scroll, this ) );

			this.$el.html( '<ul />' );
			this.collection.each( this.addRecord, this );
			return this;
		},

		addRecord: function( record ) {
			var recordView = new cue.view.BandstandPlaylistRecord({
				controller: this.controller,
				model: record
			}).render();

			this.$el.children( 'ul' ).append( recordView.el );
		},

		scroll: function() {
			if ( ! this._pending && this.el.scrollHeight < this.el.scrollTop + this.el.clientHeight * 3 ) {
				this._pending = true;
				this.getRecords();
			}
		},

		getRecords: function() {
			var view = this;
			wp.ajax.post( 'bandstand_ajax_get_playlist_records', { paged: view._paged }).done(function( data ) {
				view.collection.add( data.records );

				if ( view._paged <= data.maxNumPages ) {
					view._paged++;
					view._pending = false;
					view.scroll();
				}
			});
		}
	});

	/**
	 * Tracks toolbar.
	 *
	 * cue.view.BandstandPlaylistTracksToolbar
	 */
	cue.view.BandstandPlaylistTracksToolbar = wp.media.view.Toolbar.extend({
		initialize: function() {
			var options = this.options,
				controller = this.controller,
				state = controller.state();

			// This is a button.
			options.items = _.defaults( options.items || {}, {
				select: {
					text: state.get( 'button' ).text,
					style: 'primary',
					priority: 80,
					requires: {
						selection: true
					},
					click: function() {
						var selection = state.get( 'selection' ),
							trackIds = selection.pluck( 'id' );

						cue.tracks.addTracks( trackIds );

						controller.close();
						state.trigger( 'select', trackIds );

						// Restore and reset the default state.
						controller.setState( controller.options.state );
						controller.reset();
						selection.reset();
					}
				}
			});

			wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );
		}
	});

	/**
	 * Record view.
	 *
	 * cue.view.BandstandPlaylistRecord
	 */
	cue.view.BandstandPlaylistRecord = wp.media.View.extend({
		tagName: 'li',
		className: 'bandstand-playlist-record',
		template: wp.template( 'bandstand-playlist-record' ),

		events: {
			'click .bandstand-playlist-record-track': 'toggleSelection'
		},

		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) );
			this.refreshSelected();
			return this;
		},

		refreshSelected: function() {
			var view = this,
				selection = this.controller.state().get( 'selection' ).pluck( 'id' ),
				tracks = this.model.get( 'tracks' );

			_.each( tracks, function( track ) {
				var $track = view.$el.find( '.bandstand-playlist-record-track[data-id="' + track.id + '"]' );

				// Select tracks that are in the selection.
				$track.toggleClass( 'is-selected', -1 !== _.indexOf( selection, parseInt( track.id, 10 ) ) );
			});
		},

		toggleSelection: function( e ) {
			var $track = $( e.target ).closest( '.bandstand-playlist-record-track' ),
				trackId = $track.data( 'id' ),
				selection = this.controller.state().get( 'selection' );

			$track.toggleClass( 'is-selected' );

			if ( $track.hasClass( 'is-selected' ) ) {
				selection.add({ id: trackId });
			} else {
				selection.remove( trackId );
			}
		}
	});

	/**
	 * ========================================================================
	 * SETUP
	 * ========================================================================
	 */

	// Initialize the frame.
	frame = cue.workflows.get( 'addTracks' );

	// Add a new state.
	frame.states.add([
		new cue.controller.BandstandPlaylistTracks()
	]);

	// Set the content view.
	frame.on( 'content:render:bandstand-playlist-tracks', _.bind( function( view ) {
		view = new cue.view.BandstandPlaylistTracksContent({
			controller: this,
			mode: this.state(),
			collection: this.state().get( 'records' )
		});

		this.content.set( view );
	}, frame ) );

	// Set the toolbar view.
	frame.on( 'toolbar:create:main-bandstand-playlist-tracks', _.bind( function( toolbar ) {
		toolbar.view = new cue.view.BandstandPlaylistTracksToolbar({
			controller: this
		});
	}, frame ) );

})( this, jQuery, _, Backbone, wp );
