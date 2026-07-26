=== SEO Wunderkiste ===
Contributors: michaelkanda
Tags: seo, meta tags, schema, image optimization, security, performance, noindex, svg, conversion tracking, lightbox
Requires at least: 6.3
Tested up to: 7.0
Stable tag: 2.11
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Die modulare All-in-One Lösung für WordPress SEO, Performance, Sicherheit und Administration. | The modular all-in-one solution for WordPress SEO, performance, security, and administration.

== Description ==

🇩🇪 **DEUTSCHE BESCHREIBUNG**

SEO Wunderkiste vereint **18 leistungsstarke Module** in einem einzigen Plugin. Um die Performance deiner Website zu schützen, sind alle Module standardmäßig deaktiviert. Aktiviere nur die Funktionen, die du wirklich benötigst – unter "Einstellungen → SEO Wunderkiste".

**Das Konzept:** Keine Bloatware, kein Overhead. Jedes Modul lädt nur, wenn es aktiv ist.

= 🔍 SEO & Content (5 Module) =

**1. SEO Meta Settings**
Erweiterte Meta-Tag-Verwaltung pro Seite/Beitrag:
* Eigener SEO-Titel (max. 60 Zeichen)
* Meta-Description (max. 160 Zeichen)
* Robots-Tag-Kontrolle (index/noindex, follow/nofollow)
* Open Graph Tags (og:title, og:description, og:image)
* Twitter Card Tags
* Automatische Canonical-URLs
* Fallback auf Beitragsbild wenn kein OG-Image gesetzt

**2. SEO Schema (JSON-LD)**
Fügt ein Textfeld im Editor hinzu, um eigene strukturierte Daten (Schema.org) einzufügen:
* Eingabe als reines JSON (ohne Script-Tags)
* Validierung vor der Ausgabe
* Unterstützt alle Schema.org-Typen (Article, Product, FAQ, LocalBusiness, etc.)

**3. Bulk NoIndex Manager**
Massenhafte Indexierungs-Kontrolle:
* Bulk-Aktion "NoIndex setzen" für Beiträge und Seiten
* Bulk-Aktion "NoIndex entfernen"
* Visuelle Spalte zeigt Indexierungs-Status (✓ Index / ✗ NoIndex)
* Automatische Meta-Robots-Ausgabe im Frontend

**4. SEO Zombie Killer (Attachment Redirects)**
Eliminiert "Zombie-Seiten" – diese nutzlosen Anhang-URLs, die WordPress für jedes Bild erstellt:
* 301-Redirect auf den Eltern-Beitrag (wenn vorhanden)
* 302-Redirect zur Startseite (bei verwaisten Anhängen)
* Verbessert Crawl-Budget und verhindert Duplicate Content

**5. Conversion Tracker**
GA4 und Google Ads Conversion-Tracking pro Seite:
* Google Analytics 4: Event-Name + optionaler Wert
* Google Ads: Conversion-ID + Label + Wert
* Ideal für Danke-Seiten nach Formular-Absendung
* Admin-Spalte zeigt aktives Tracking (GA4 / Ads)

= 🖼️ Bild & Media (6 Module) =

**6. Image Resizer (800px / 1200px)**
Skaliert Bilder mit einem Klick in der Mediathek:
* Zwei Zielgrößen: 800px oder 1200px (längste Seite)
* 92% JPEG-Qualität für optimales Verhältnis
* Verfügbar im Attachment-Detail und in der Listenansicht
* Überschreibt das Original – ideal für große Uploads

**7. Upload Cleaner**
Bereinigt Dateinamen automatisch beim Upload:
* Umlaute werden umgewandelt (ä→ae, ö→oe, ü→ue, ß→ss)
* Leerzeichen werden zu Bindestrichen
* Alles wird kleingeschrieben
* SEO-freundliche URLs ohne manuelle Nacharbeit

**8. Zero-Click Image SEO**
Automatische Generierung von SEO-Attributen beim Bild-Upload:
* Titel wird aus dem Dateinamen generiert (aufgehübscht)
* Alt-Text wird automatisch gesetzt (wenn leer)
* Bindestriche/Unterstriche werden zu Leerzeichen
* Erster Buchstabe jedes Wortes groß

**9. Media Inspector**
Zusätzliche Spalten in der Medienübersicht:
* Dateigröße (z.B. "245.32 KB")
* Pixel-Dimensionen (z.B. "1920 x 1080")
* Schnelle Übersicht ohne jedes Bild öffnen zu müssen

**10. SVG Upload Support**
Ermöglicht sichere SVG-Uploads:
* MIME-Type-Registrierung für .svg und .svgz
* Automatische Sanitization: Entfernt Scripts, Event-Handler, gefährliche Attribute
* Vorschau in der Mediathek funktioniert
* Dimensionen werden aus viewBox/width/height extrahiert
* Admin-Hinweis informiert über aktive Sanitization

**11. Decent Lightbox**
Schlanker Bild-Lightbox – pro Bild in der Mediathek aktivierbar:
* Vanilla JavaScript, keine externen Abhängigkeiten (jQuery-frei)
* Per Checkbox in den Mediathek-Details aktivieren
* Tastatur-Navigation (ESC zum Schließen, Tab-Trap)
* `prefers-reduced-motion` wird respektiert
* Lazy-Aufbau im DOM, lädt erst bei Bedarf
* Vollbild-Anzeige mit Spinner während Bildvorbereitung

= ⚡ Performance (1 Modul) =

**11. Emoji Bloat Remover**
Entfernt unnötige WordPress-Emoji-Ressourcen:
* Entfernt Emoji-Detection-Script (wp-emoji-release.min.js)
* Entfernt Emoji-CSS
* Betrifft Frontend UND Admin
* Spart ca. 15-20KB pro Seitenaufruf

= 🔒 Sicherheit & Admin (4 Module) =

**12. XML-RPC Blocker**
Deaktiviert die XML-RPC-Schnittstelle komplett:
* Schützt vor Brute-Force-Angriffen
* Schließt potenzielle Sicherheitslücke
* Einfacher Ein-Zeilen-Filter
* Hinweis: Deaktiviert auch Apps, die XML-RPC benötigen (z.B. WordPress Mobile App)

**13. Login Türsteher**
Versteckt die Login-Seite hinter einem geheimen Parameter:
* Zugriff nur via: `wp-login.php?DEIN_SCHLUESSEL`
* Ohne Parameter → Redirect zur Startseite
* Konfigurierbarer Schlüssel in den Einstellungen
* Standard: "hintereingang"
* Einfacher Schutz gegen automatisierte Angriffe

**14. Comment Blocker**
Deaktiviert Kommentare global und gründlich:
* Entfernt Kommentar-Support von allen Post-Types
* Versteckt Kommentar-Menü im Admin
* Entfernt Dashboard-Widget "Letzte Kommentare"
* Entfernt Meta-Boxen aus dem Editor
* Schließt bestehende Kommentare (Filter)
* Deaktiviert Kommentar-Feed
* Entfernt Feed-Links aus dem Header
* Versteckt Kommentar-Spalte in Listen
* Bulk-Aktion zum Schließen bestehender Kommentare
* Optionale Funktion: Alle DB-Einträge schließen (manuell aufrufbar)

**15. ID Column Display**
Zeigt die Post/Page/Media-ID in allen Admin-Übersichten:
* Spalte direkt nach der Checkbox
* Klick auf ID kopiert sie in die Zwischenablage
* Sortierbar
* Funktioniert für Posts, Pages, Media UND Custom Post Types
* Responsive: Versteckt auf Mobilgeräten

= 📝 Content Tools (2 Module) =

**16. Date Shortcode**
Fügt das aktuelle Datum dynamisch ein:

Shortcodes:
* `[seowk_date]` oder `[datum]` - Standard-Format (TT.MM.JJJJ)
* `[jahr]` - Nur das Jahr
* `[monat]` - Nur der Monat (deutsch)

Attribute:
* `format` - Vordefiniert: numeric, numeric_short, full, full_day, month_year, year, month, day, iso, us, time, datetime
* `format` - Oder eigenes PHP-Datumsformat
* `timezone` - Zeitzone (z.B. "Europe/Berlin")
* `prefix` - Text vor dem Datum
* `suffix` - Text nach dem Datum
* `wrapper` - HTML-Tag (span, time, div, p, strong, em)
* `class` - CSS-Klasse
* `lang` - "de" für deutsche Monatsnamen (Standard)

Beispiele:
* `[datum format="full"]` → 10. Januar 2026
* `[datum format="full_day"]` → Samstag, 10. Januar 2026
* `[datum prefix="Stand: " suffix=" Uhr" format="datetime"]` → Stand: 10.01.2026 14:30 Uhr
* `[datum wrapper="time" class="updated"]` → `<time datetime="..." class="updated">10.01.2026</time>`

**17. Semantic Blocks**
HTML5-semantische Wrapper-Blöcke für bessere Dokumentstruktur:

Verfügbare Blöcke:
* `<article>` - Eigenständiger Inhalt
* `<section>` - Thematischer Abschnitt
* `<aside>` - Ergänzender Inhalt
* `<header>` - Einleitungsbereich
* `<footer>` - Fußbereich
* `<main>` - Hauptinhalt
* `<figure>` - Abbildung mit Caption
* `<address>` - Kontaktinformationen
* `<details>` + `<summary>` - Aufklappbarer Bereich
* `<mark>` - Hervorgehobener Text

Attribute für alle Blöcke:
* CSS-Klasse
* CSS-ID

Hinweis: Diese Blöcke sind serverseitig registriert. Editor-UI wird in zukünftigen Versionen hinzugefügt.

= 🗑️ Saubere Deinstallation =

Beim Löschen des Plugins über "Plugins → Löschen" werden automatisch alle Daten entfernt:

* Plugin-Einstellungen (`seowk_settings`)
* Alle Post-Meta-Daten (SEO-Titel, Descriptions, OG-Tags, Schema, NoIndex, Conversion-Tracking)
* User-Meta-Daten (z.B. dismissed Notices)
* Transients
* Bei Multisite: Daten auf allen Sites

**Hinweis:** Deaktivieren allein löscht keine Daten – nur das vollständige Löschen des Plugins.

---

🇬🇧 **ENGLISH DESCRIPTION**

SEO Wunderkiste combines **18 powerful modules** in a single plugin. To protect your site's performance, all modules are disabled by default. Enable only the features you actually need – under "Settings → SEO Wunderkiste".

**The concept:** No bloatware, no overhead. Each module only loads when active.

= 🔍 SEO & Content (5 Modules) =

**1. SEO Meta Settings**
Extended meta tag management per page/post:
* Custom SEO title (max. 60 characters)
* Meta description (max. 160 characters)
* Robots tag control (index/noindex, follow/nofollow)
* Open Graph tags (og:title, og:description, og:image)
* Twitter Card tags
* Automatic canonical URLs
* Fallback to featured image when no OG image is set

**2. SEO Schema (JSON-LD)**
Adds a text field in the editor to insert custom structured data (Schema.org):
* Input as pure JSON (without script tags)
* Validation before output
* Supports all Schema.org types (Article, Product, FAQ, LocalBusiness, etc.)

**3. Bulk NoIndex Manager**
Mass indexing control:
* Bulk action "Set NoIndex" for posts and pages
* Bulk action "Remove NoIndex"
* Visual column shows indexing status (✓ Index / ✗ NoIndex)
* Automatic meta robots output in frontend

**4. SEO Zombie Killer (Attachment Redirects)**
Eliminates "zombie pages" – those useless attachment URLs WordPress creates for every image:
* 301 redirect to parent post (if exists)
* 302 redirect to homepage (for orphaned attachments)
* Improves crawl budget and prevents duplicate content

**5. Conversion Tracker**
GA4 and Google Ads conversion tracking per page:
* Google Analytics 4: Event name + optional value
* Google Ads: Conversion ID + Label + Value
* Ideal for thank-you pages after form submission
* Admin column shows active tracking (GA4 / Ads)

= 🖼️ Image & Media (6 Modules) =

**6. Image Resizer (800px / 1200px)**
Scales images with one click in the media library:
* Two target sizes: 800px or 1200px (longest side)
* 92% JPEG quality for optimal balance
* Available in attachment detail and list view
* Overwrites the original – ideal for large uploads

**7. Upload Cleaner**
Automatically cleans filenames on upload:
* Umlauts are converted (ä→ae, ö→oe, ü→ue, ß→ss)
* Spaces become hyphens
* Everything is lowercased
* SEO-friendly URLs without manual work

**8. Zero-Click Image SEO**
Automatic generation of SEO attributes on image upload:
* Title is generated from filename (prettified)
* Alt text is set automatically (if empty)
* Hyphens/underscores become spaces
* First letter of each word capitalized

**9. Media Inspector**
Additional columns in the media overview:
* File size (e.g., "245.32 KB")
* Pixel dimensions (e.g., "1920 x 1080")
* Quick overview without opening each image

**10. SVG Upload Support**
Enables secure SVG uploads:
* MIME type registration for .svg and .svgz
* Automatic sanitization: Removes scripts, event handlers, dangerous attributes
* Preview in media library works
* Dimensions extracted from viewBox/width/height
* Admin notice informs about active sanitization

**11. Decent Lightbox**
Lightweight image lightbox – enable per image in the media library:
* Vanilla JavaScript, no external dependencies (jQuery-free)
* Toggle via checkbox in media library item details
* Keyboard navigation (ESC to close, Tab-trap)
* Respects `prefers-reduced-motion`
* Lazy DOM build, only loaded when needed
* Full-screen display with spinner during image preload

= ⚡ Performance (1 Module) =

**11. Emoji Bloat Remover**
Removes unnecessary WordPress emoji resources:
* Removes emoji detection script (wp-emoji-release.min.js)
* Removes emoji CSS
* Affects frontend AND admin
* Saves approx. 15-20KB per page load

= 🔒 Security & Admin (4 Modules) =

**12. XML-RPC Blocker**
Completely disables the XML-RPC interface:
* Protects against brute-force attacks
* Closes potential security vulnerability
* Simple one-line filter
* Note: Also disables apps that need XML-RPC (e.g., WordPress Mobile App)

**13. Login Guardian**
Hides the login page behind a secret parameter:
* Access only via: `wp-login.php?YOUR_KEY`
* Without parameter → Redirect to homepage
* Configurable key in settings
* Default: "hintereingang"
* Simple protection against automated attacks

**14. Comment Blocker**
Disables comments globally and thoroughly:
* Removes comment support from all post types
* Hides comment menu in admin
* Removes dashboard widget "Recent Comments"
* Removes meta boxes from editor
* Closes existing comments (filter)
* Disables comment feed
* Removes feed links from header
* Hides comment column in lists
* Bulk action to close existing comments
* Optional function: Close all DB entries (manually callable)

**15. ID Column Display**
Shows Post/Page/Media ID in all admin overviews:
* Column right after checkbox
* Click on ID copies it to clipboard
* Sortable
* Works for Posts, Pages, Media AND Custom Post Types
* Responsive: Hidden on mobile

= 📝 Content Tools (2 Modules) =

**16. Date Shortcode**
Dynamically inserts the current date:

Shortcodes:
* `[seowk_date]` or `[datum]` - Default format (DD.MM.YYYY)
* `[jahr]` - Year only
* `[monat]` - Month only (German)

Attributes:
* `format` - Predefined: numeric, numeric_short, full, full_day, month_year, year, month, day, iso, us, time, datetime
* `format` - Or custom PHP date format
* `timezone` - Timezone (e.g., "Europe/Berlin")
* `prefix` - Text before date
* `suffix` - Text after date
* `wrapper` - HTML tag (span, time, div, p, strong, em)
* `class` - CSS class
* `lang` - "de" for German month names (default)

Examples:
* `[datum format="full"]` → 10. Januar 2026
* `[datum format="full_day"]` → Saturday, January 10, 2026
* `[datum prefix="As of: " suffix=" hrs" format="datetime"]` → As of: 01/10/2026 14:30 hrs

**17. Semantic Blocks**
HTML5 semantic wrapper blocks for better document structure:

Available blocks:
* `<article>` - Self-contained content
* `<section>` - Thematic section
* `<aside>` - Complementary content
* `<header>` - Introductory area
* `<footer>` - Footer area
* `<main>` - Main content
* `<figure>` - Figure with caption
* `<address>` - Contact information
* `<details>` + `<summary>` - Collapsible area
* `<mark>` - Highlighted text

Attributes for all blocks:
* CSS class
* CSS ID

Note: These blocks are server-side registered. Editor UI will be added in future versions.

= 🗑️ Clean Uninstall =

When deleting the plugin via "Plugins → Delete", all data is automatically removed:

* Plugin settings (`seowk_settings`)
* All post meta data (SEO titles, descriptions, OG tags, Schema, NoIndex, Conversion tracking)
* User meta data (e.g., dismissed notices)
* Transients
* On Multisite: Data on all sites

**Note:** Deactivating alone does not delete any data – only completely deleting the plugin does.

== Installation ==

🇩🇪 **Deutsche Anleitung:**

1. Lade den Ordner 'seo-wunderkiste' nach `/wp-content/plugins/` hoch
2. Aktiviere das Plugin über das "Plugins"-Menü in WordPress
3. Gehe zu "Einstellungen → SEO Wunderkiste"
4. Aktiviere nur die Module, die du benötigst
5. Speichere die Einstellungen

🇬🇧 **English Instructions:**

1. Upload the 'seo-wunderkiste' folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Settings → SEO Wunderkiste"
4. Enable only the modules you need
5. Save settings

== Frequently Asked Questions ==

= 🇩🇪 Sind alle Module standardmäßig aktiv? | 🇬🇧 Are all modules active by default? =

🇩🇪 Nein, alle Module sind standardmäßig deaktiviert. Aktiviere nur, was du brauchst.

🇬🇧 No, all modules are disabled by default. Enable only what you need.

= 🇩🇪 Ist das Plugin mit anderen SEO-Plugins kompatibel? | 🇬🇧 Is this plugin compatible with other SEO plugins? =

🇩🇪 Ja, aber wir empfehlen, überlappende Funktionen zu deaktivieren (z.B. Meta Settings, wenn du Yoast nutzt).

🇬🇧 Yes, but we recommend disabling overlapping features (e.g., Meta Settings if you use Yoast).

= 🇩🇪 Ist der SVG-Upload ein Sicherheitsrisiko? | 🇬🇧 Does SVG upload pose a security risk? =

🇩🇪 Nein, SVG-Dateien werden beim Upload automatisch bereinigt. Scripts und Event-Handler werden entfernt.

🇬🇧 No, SVG files are automatically sanitized on upload. Scripts and event handlers are removed.

= 🇩🇪 Was passiert, wenn ich den Login-Türsteher aktiviere und den Schlüssel vergesse? | 🇬🇧 What happens if I enable Login Guardian and forget the key? =

🇩🇪 Du kannst das Plugin per FTP deaktivieren (Ordner umbenennen) oder in der Datenbank den Schlüssel in `wp_options` → `seowk_settings` nachschlagen.

🇬🇧 You can disable the plugin via FTP (rename folder) or look up the key in the database at `wp_options` → `seowk_settings`.

= 🇩🇪 Warum sehe ich die Semantic Blocks nicht im Editor? | 🇬🇧 Why don't I see Semantic Blocks in the editor? =

🇩🇪 Die Blöcke sind derzeit nur serverseitig registriert. Editor-JavaScript wird in einer zukünftigen Version hinzugefügt. Du kannst sie aktuell als HTML-Block verwenden.

🇬🇧 The blocks are currently only server-side registered. Editor JavaScript will be added in a future version. You can currently use them as HTML blocks.

= 🇩🇪 Kann ich die Währung im Conversion Tracker ändern? | 🇬🇧 Can I change the currency in Conversion Tracker? =

🇩🇪 Derzeit ist EUR fest eingestellt. Filter für eigene Währung kommt in zukünftiger Version.

🇬🇧 Currently EUR is hardcoded. Filter for custom currency coming in future version.

== Screenshots ==

1. Admin-Einstellungsseite mit allen Modulen | Admin settings page with all modules
2. SEO Meta Settings Meta-Box | SEO Meta Settings meta box
3. Mediathek mit Resizer-Buttons und Inspector-Spalten | Media library with resizer buttons and inspector columns
4. NoIndex-Status-Spalte in der Beitragsübersicht | NoIndex status column in post overview
5. Conversion Tracker Meta-Box | Conversion Tracker meta box

== Changelog ==

= 2.11 =
* **Sicherheit (hoch):** Der SVG-Sanitizer wurde durch die Bibliothek enshrined/svg-sanitize 0.22.0 ersetzt (mitgeliefert unter includes/vendor/, GPL-2.0-or-later). Die bisherige Eigenbau-Filterung war wirkungslos: ihre XPath-Abfragen fanden keine Elemente im SVG-Namespace, wodurch ein normkonformes SVG mit <script>-Tag unveraendert durchging.
* **Sicherheit (hoch):** SVG-Uploads erfordern jetzt die Berechtigung `unfiltered_html` (filterbar ueber `seowk_svg_upload_capability`) statt nur `upload_files`.
* **Sicherheit (hoch):** Stored XSS im JSON-LD-Feld geschlossen. Die Ausgabe nutzt JSON_HEX_TAG statt JSON_UNESCAPED_SLASHES, ein `</script>` in einem Textwert kann den Script-Block nicht mehr vorzeitig beenden.
* **Sicherheit (mittel):** Der Image Resizer prueft jetzt `edit_post` auf das konkrete Attachment und laesst nicht mehr den Request ueber das Feld `is_bulk` entscheiden, welche Nonce geprueft wird.
* **Sicherheit (niedrig):** Bulk NoIndex prueft die Bearbeitungsrechte pro Beitrag.
* **Sicherheit (niedrig):** Die Ausgabe des Datums-Shortcodes wird escaped.
* Zusaetzlich werden `@import` und externe `url()`-Referenzen aus `<style>`-Bloecken in SVGs entfernt.
* SVGZ-Uploads funktionieren (werden entpackt, bereinigt und wieder komprimiert) statt pauschal abgelehnt zu werden.
* SVG-Masse werden aus width/height bzw. viewBox ausgelesen, dadurch korrekte Vorschau und Bildgroessen.
* **Login Tuersteher komplett neu.** Die alte Fassung leitete auch den POST des Login-Formulars um und sperrte damit alle Benutzer aus. Neu: nur GET-Anfragen auf die Login-Maske werden gefiltert, der Zugang wird ueber ein kurzlebiges HttpOnly-Cookie gemerkt, Logout, Passwort-Reset, geschuetzte Beitraege und Recovery-Modus bleiben erreichbar.
* Der veroeffentlichte Standardschluessel "hintereingang" wurde entfernt. Ohne eigenen Schluessel bleibt das Modul wirkungslos und weist im Backend darauf hin.
* **Comment Blocker nimmt WooCommerce-Produkte aus.** Produktbewertungen sind technisch Kommentare und wurden bisher mit abgeschaltet. Filterbar ueber `seowk_comment_blocker_exempt_post_types`.
* Comment Blocker entfernt nur noch den Kommentar-Feed-Link statt `feed_links_extra` komplett (das nahm auch Kategorie-, Schlagwort- und Autoren-Feeds mit).
* Meta Settings und Bulk NoIndex geben nur noch ein einziges robots-Meta aus; ein gesetztes NoIndex hat Vorrang.
* `og:locale` folgt der Sprache der Website statt fest auf de_DE zu stehen.
* Semantic Blocks auf Block-API v3 umgestellt (noetig fuer den iframed Editor ab WordPress 7.1).
* Lightbox: PHP-8.0-only Typdeklaration entfernt, Textdomain und Script-Handles vereinheitlicht, Filter gegen `null`-Werte abgesichert.
* Textdomain wird auf `init` geladen (Vorgabe seit WordPress 6.7).
* Console-Ausgabe im Backend nur noch bei aktivem WP_DEBUG.
* Uninstall raeumt in Multisite-Installationen jetzt auch Post-Meta aller Sites auf; das pauschale `wp_cache_flush()` entfaellt.
* Kompatibilitaetsangaben korrigiert: Mindestens WordPress 6.3 (der Code nutzt WP_HTML_Tag_Processor und die strategy-Option von wp_register_script), getestet bis WordPress 7.0.

= 2.10 =
* NEU: Decent Lightbox als integriertes Modul (vormals eigenständiges Plugin)
* NEU: Pro-Bild aktivierbare Lightbox direkt in der Mediathek
* VERBESSERT: Plugin-Meta-Links zeigen jetzt auf designare.at
* NEW: Decent Lightbox integrated as a module (previously a standalone plugin)
* NEW: Per-image enabled lightbox directly in the media library
* IMPROVED: Plugin meta links now point to designare.at

= 2.7 =
* NEU: Semantic Blocks Modul mit 10 HTML5-Wrapper-Blöcken
* NEU: Gutenberg-Block für Date Shortcode
* VERBESSERT: Image Resizer bietet jetzt 800px UND 1200px Optionen
* VERBESSERT: SVG-Sanitization für bessere Sicherheit
* VERBESSERT: Code-Qualität und WordPress Coding Standards Compliance
* NEW: Semantic Blocks module with 10 HTML5 wrapper blocks
* NEW: Gutenberg block for Date Shortcode
* IMPROVED: Image Resizer now offers 800px AND 1200px options
* IMPROVED: SVG sanitization for better security
* IMPROVED: Code quality and WordPress coding standards compliance

= 2.6 =
* NEU: Date Shortcode Modul mit 12+ Formaten und Zeitzonen-Support
* NEW: Date Shortcode module with 12+ formats and timezone support

= 2.5 =
* NEU: SEO Meta Settings Modul mit Open Graph und Twitter Cards
* NEW: SEO Meta Settings module with Open Graph and Twitter Cards

= 2.4 =
* NEU: Conversion Tracker für GA4 und Google Ads
* NEU: ID Column Display mit Kopier-Funktion
* NEW: Conversion Tracker for GA4 and Google Ads
* NEW: ID Column Display with copy function

= 2.3 =
* NEU: Bulk NoIndex Manager
* NEU: Comment Blocker (umfassende Kommentar-Deaktivierung)
* NEW: Bulk NoIndex Manager
* NEW: Comment Blocker (comprehensive comment disabling)

= 2.2 =
* NEU: SVG Upload Support mit Sicherheits-Sanitization
* NEU: Media Inspector (Dateigröße und Maße)
* NEW: SVG Upload Support with security sanitization
* NEW: Media Inspector (file size and dimensions)

= 2.1 =
* NEU: Login Türsteher (geheimer Login-Parameter)
* NEU: SEO Zombie Killer (Attachment Redirects)
* NEW: Login Guardian (secret login parameter)
* NEW: SEO Zombie Killer (Attachment Redirects)

= 2.0 =
* Komplette Neustrukturierung als modulares Plugin
* Alle Module standardmäßig deaktiviert
* Complete restructuring as modular plugin
* All modules disabled by default

= 1.0 =
* Erste Veröffentlichung
* Initial release

== Upgrade Notice ==

= 2.7 =
Major Update: Semantic Blocks, verbesserter Gutenberg-Support und verstärkte Sicherheit. | Major update: Semantic Blocks, improved Gutenberg support, and enhanced security.

== Technical Review / Technische Bewertung ==

= 🇩🇪 Bewertung =

**Gesamtnote: 7.5/10**

**Stärken:**
✅ Modulares Design – vorbildlich umgesetzt
✅ Performance-bewusst – lädt nur aktive Module
✅ WordPress Coding Standards größtenteils eingehalten
✅ Sicherheit: Nonces, Sanitization, Escaping korrekt
✅ SVG-Sanitization professionell implementiert
✅ Saubere Datei-Architektur

**Verbesserungspotenzial:**
⚠️ `load_plugin_textdomain()` fehlt (Übersetzungen nicht ladbar)
⚠️ Semantic Blocks ohne Editor-JavaScript
⚠️ Keine `uninstall.php` (Datenbank-Cleanup)
⚠️ Hardcoded Währung (EUR) im Conversion Tracker
⚠️ Globale Variable `$seowk_options`

**Mehrwert-Bewertung: 8/10**
Das Plugin bietet echten Nutzen für WordPress-Betreiber, die keine großen All-in-One SEO-Suiten brauchen. Die Kombination aus SEO-Tools, Bild-Optimierung und Sicherheit in einem modularen Ansatz ist durchdacht.

= 🇬🇧 Review =

**Overall Score: 7.5/10**

**Strengths:**
✅ Modular design – excellently implemented
✅ Performance-conscious – loads only active modules
✅ WordPress Coding Standards mostly followed
✅ Security: Nonces, sanitization, escaping correct
✅ SVG sanitization professionally implemented
✅ Clean file architecture

**Room for Improvement:**
⚠️ `load_plugin_textdomain()` missing (translations not loadable)
⚠️ Semantic Blocks without editor JavaScript
⚠️ No `uninstall.php` (database cleanup)
⚠️ Hardcoded currency (EUR) in Conversion Tracker
⚠️ Global variable `$seowk_options`

**Value Assessment: 8/10**
The plugin offers real value for WordPress operators who don't need large all-in-one SEO suites. The combination of SEO tools, image optimization, and security in a modular approach is well thought out.

== Additional Resources ==

🇩🇪 Für deutsche Dokumentation, besuche die Plugin-Einstellungsseite nach der Aktivierung.
🇬🇧 For documentation, visit the plugin settings page after activation.

== Credits ==

Developed with ❤️ by Michael Kanda
https://designare.at
