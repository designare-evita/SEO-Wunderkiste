/**
 * Decent Lightbox – frontend script
 *
 * Pure vanilla JS, no dependencies. Listens to clicks on images that have
 * been decorated with `data-decent-lightbox="1"` by the PHP filter.
 */
( function () {
	'use strict';

	const SELECTOR = 'img[data-decent-lightbox="1"]';
	const l10n = window.decentLightboxL10n || {
		close: 'Close lightbox',
		loading: 'Loading image…'
	};

	let overlay = null;
	let imageEl = null;
	let closeBtn = null;
	let lastFocused = null;

	/**
	 * Builds the overlay DOM once on demand.
	 */
	function ensureOverlay() {
		if ( overlay ) {
			return overlay;
		}

		overlay = document.createElement( 'div' );
		overlay.className = 'decent-lightbox';
		overlay.setAttribute( 'role', 'dialog' );
		overlay.setAttribute( 'aria-modal', 'true' );
		overlay.setAttribute( 'aria-hidden', 'true' );
		overlay.tabIndex = -1;

		const stage = document.createElement( 'div' );
		stage.className = 'decent-lightbox__stage';

		imageEl = document.createElement( 'img' );
		imageEl.className = 'decent-lightbox__image';
		imageEl.alt = '';
		imageEl.decoding = 'async';

		const spinner = document.createElement( 'div' );
		spinner.className = 'decent-lightbox__spinner';
		spinner.setAttribute( 'aria-hidden', 'true' );

		closeBtn = document.createElement( 'button' );
		closeBtn.type = 'button';
		closeBtn.className = 'decent-lightbox__close';
		closeBtn.setAttribute( 'aria-label', l10n.close );
		closeBtn.innerHTML = '<span aria-hidden="true">&times;</span>';

		stage.appendChild( imageEl );
		stage.appendChild( spinner );
		overlay.appendChild( stage );
		overlay.appendChild( closeBtn );
		document.body.appendChild( overlay );

		// Click on backdrop (but not on the image itself) closes.
		overlay.addEventListener( 'click', function ( event ) {
			if ( event.target === overlay || event.target === stage ) {
				close();
			}
		} );

		closeBtn.addEventListener( 'click', close );

		return overlay;
	}

	/**
	 * Opens the lightbox with the given full-size URL and alt text.
	 *
	 * @param {string} src
	 * @param {string} alt
	 */
	function open( src, alt ) {
		ensureOverlay();
		lastFocused = document.activeElement;

		imageEl.alt = alt || '';
		imageEl.removeAttribute( 'src' );
		overlay.classList.add( 'is-loading' );

		const preload = new Image();
		preload.onload = function () {
			imageEl.src = src;
			overlay.classList.remove( 'is-loading' );
		};
		preload.onerror = function () {
			overlay.classList.remove( 'is-loading' );
			close();
		};
		preload.src = src;

		document.body.classList.add( 'decent-lightbox-open' );
		overlay.classList.add( 'is-open' );
		overlay.setAttribute( 'aria-hidden', 'false' );

		document.addEventListener( 'keydown', onKeydown );

		// Move focus into the dialog for keyboard / screen-reader users.
		window.requestAnimationFrame( function () {
			closeBtn.focus();
		} );
	}

	/**
	 * Closes the lightbox.
	 */
	function close() {
		if ( ! overlay || ! overlay.classList.contains( 'is-open' ) ) {
			return;
		}

		overlay.classList.remove( 'is-open' );
		overlay.setAttribute( 'aria-hidden', 'true' );
		document.body.classList.remove( 'decent-lightbox-open' );
		document.removeEventListener( 'keydown', onKeydown );

		// Free memory once the transition is done.
		window.setTimeout( function () {
			if ( imageEl ) {
				imageEl.removeAttribute( 'src' );
			}
		}, 250 );

		if ( lastFocused && typeof lastFocused.focus === 'function' ) {
			lastFocused.focus();
		}
	}

	/**
	 * Keyboard handling: ESC to close, Tab is trapped inside the dialog.
	 *
	 * @param {KeyboardEvent} event
	 */
	function onKeydown( event ) {
		if ( event.key === 'Escape' ) {
			event.preventDefault();
			close();
			return;
		}

		if ( event.key === 'Tab' ) {
			// Only one focusable element (close button), keep focus there.
			event.preventDefault();
			closeBtn.focus();
		}
	}

	/**
	 * Click delegation: handle clicks on enabled images anywhere on the page.
	 */
	document.addEventListener(
		'click',
		function ( event ) {
			const img = event.target.closest( SELECTOR );

			if ( ! img ) {
				return;
			}

			const fullSrc =
				img.getAttribute( 'data-decent-lightbox-full' ) ||
				img.currentSrc ||
				img.src;

			if ( ! fullSrc ) {
				return;
			}

			// If the image is wrapped in a link, prevent default navigation.
			const link = img.closest( 'a' );
			if ( link ) {
				event.preventDefault();
			}

			event.preventDefault();
			open( fullSrc, img.alt );
		},
		false
	);
} )();
