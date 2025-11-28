=== SEO Wunderkiste v2.1 ===
Die modulare Lösung für besseres WordPress SEO, Sicherheit und schlanke Bilder.

--- BESCHREIBUNG ---
Dieses Plugin vereint 10 leistungsstarke Funktionen in einem einzigen Tool. 
Um die Performance deiner Seite zu schonen, sind standardmäßig alle Module deaktiviert. 
Du kannst unter "Einstellungen > SEO Wunderkiste" genau die Funktionen aktivieren, die du benötigst.

--- DIE MODULE ---

1. SEO Schema (JSON-LD)
   [cite_start]Fügt Beiträgen und Seiten ein Eingabefeld hinzu, um individuellen Schema.org Code (JSON-LD) in den <head> einzufügen. [cite: 22]

2. Image Resizer (800px)
   [cite_start]Fügt in den Mediendetails einen Button hinzu, um riesige Bilder mit einem Klick auf webfreundliche 800px herunterzuskalieren. [cite: 23]

3. Upload Cleaner
   Bereinigt Dateinamen automatisch beim Upload.
   [cite_start]Beispiel: Aus "Mein Foto_Übersicht.JPG" wird automatisch "mein-foto-uebersicht.jpg". [cite: 24]

4. Zero-Click Image SEO
   [cite_start]Generiert aus dem Dateinamen automatisch den Bild-Titel und den Alt-Text (Alternativtext) für Google. [cite: 25]
   [cite_start]Beispiel: "mein-neues-projekt.jpg" erzeugt den Alt-Text "Mein Neues Projekt". [cite: 26]

5. Media Library Inspector
   Zeigt in der Medienübersicht (Listenansicht) zwei neue Spalten an:
   - Dateigröße (KB/MB)
   - Abmessungen (Pixel)

6. SEO Zombie Killer (Redirects)
   Leitet sinnlose Anhang-Seiten (Attachment Pages), die Google nicht mag, automatisch per 301-Redirect auf den zugehörigen Beitrag um.

7. SVG Upload Support
   Erlaubt das Hochladen von SVG-Dateien in die Mediathek (standardmäßig von WordPress blockiert).

8. Emoji Bloat Remover
   Entfernt das unnötige JavaScript und CSS für WordPress-Emojis. Das macht die Seite messbar schneller.

9. XML-RPC Blocker
   Deaktiviert die XML-RPC Schnittstelle. Dies schützt deine Seite effektiv vor Brute-Force-Angriffen und unnötiger Serverlast.

10. Login Türsteher
    Schützt deinen Admin-Bereich. Die Login-Seite ist nur noch erreichbar, wenn ein geheimer Parameter an die URL angehängt wird.

--- INSTALLATION & AKTIVIERUNG ---

1. [cite_start]Lade den Ordner 'seo-wunderkiste' in das Verzeichnis '/wp-content/plugins/' hoch oder lade die ZIP-Datei über das WordPress-Backend (Plugins > Installieren) hoch. [cite: 27]
2. [cite_start]Aktiviere das Plugin im Menü 'Plugins' in WordPress. [cite: 28]
3. [cite_start]WICHTIG: Gehe nach der Aktivierung zu "Einstellungen > SEO Wunderkiste". [cite: 28]
4. [cite_start]Setze Haken bei den Modulen, die du nutzen möchtest, und klicke auf "Speichern". [cite: 29]

--- BENUTZUNG ---

> Wie nutze ich das Schema-Feld?
Gehe in einen Beitrag oder eine Seite. Unter dem Editor findest du die Box "Strukturierte Daten". [cite_start]Füge dort dein JSON-Objekt ein (ohne <script> Tags). [cite: 30, 31]

> Wie skaliere ich Bilder?
Gehe in die Mediathek, klicke ein Bild an. [cite_start]In den Details (rechts oder im Modal) findest du den Button "Auf 800px skalieren". [cite: 32, 33]

> Wie nutze ich den Login Türsteher?
Aktiviere das Modul in den Einstellungen und lege ein geheimes Wort fest (z.B. "supergeheim").
Deine Login-Seite ist ab dann nur noch unter dieser Adresse erreichbar:
deineseite.de/wp-login.php?supergeheim
Ohne diesen Zusatz werden Besucher auf die Startseite umgeleitet.

> Wie funktionieren Cleaner, Image SEO und Redirects?
Diese Module arbeiten vollautomatisch im Hintergrund, sobald sie in den Einstellungen aktiviert wurden. [cite_start]Es ist kein weiteres Zutun nötig. [cite: 34, 35]

--- ANFORDERUNGEN ---
* WordPress Version: 5.0 oder höher
* PHP Version: 7.4 oder höher empfohlen

--- AUTOR ---
Entwickelt von Michael Kanda
