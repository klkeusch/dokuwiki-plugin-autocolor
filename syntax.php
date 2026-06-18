<?php
/**
 * Automatically highlights configured keywords. 
 * Reads the keywords and their colors from a table on a user-defined wiki page.
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Klaus Keusch <dev@klauskeusch.de>
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_autocolor extends DokuWiki_Syntax_Plugin {

    private $colorMap = null;

    // DokuWiki core mispelling
    public function getType() { return 'substition'; }
    public function getSort() { return 300; } 

    public function connectTo($mode) {
        $map = $this->getKeywordMap();
        if (empty($map)) return;

        foreach (array_keys($map) as $word) {
            $pattern = '\b' . preg_quote($word, '/') . '\b';
            $this->Lexer->addSpecialPattern($pattern, $mode, 'plugin_autocolor');
        }
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return array($match);
    }

    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        $word = $data[0];
        $map = $this->getKeywordMap();
        
        $color = isset($map[$word]) ? $map[$word] : '';

        if ($color !== '') {
            $renderer->doc .= '<span style="color: ' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . ';">';
            $renderer->doc .= htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
            $renderer->doc .= '</span>';
        } else {
            $renderer->doc .= htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
        }
        return true;
    }

    /**
     * Reads the DokuWiki table and creates the keyword mapping.
     * The table should be defined on the page specified in the plugin configuration.
     */
    private function getKeywordMap() {
        if ($this->colorMap !== null) {
            return $this->colorMap;
        }

        $this->colorMap = [];
        $pageId = $this->getConf('config_page');

        if (!page_exists($pageId)) {
            // Fallback
            return [
                'DokuWiki' => '#0055b3',
                'Fehler'   => 'red'
            ];
        }

        $rawWikiText = rawWiki($pageId);
        $lines = explode("\n", $rawWikiText);

        foreach ($lines as $line) {
            $line = trim($line);
            
            // We ignore empty lines and header lines (those that start with ^)
            // A valid data line in DokuWiki always starts with | for tables.
            if ($line === '' || strpos($line, '|') !== 0) {
                continue;
            }

            // Split based on |. 
            // Example: "| Keyword1, Keyword2 | #ff0000 |"
            // Array index 0 is empty (before the first |), index 1 contains keywords, and index 2 contains the color.
            $cells = explode('|', $line);
            
            if (count($cells) >= 3) {
                $keywordsRaw = $cells[1];
                $color = trim($cells[2]);

                if ($color === '') continue;

                // Split the comma-separated keywords in this table cell
                $keywordsList = explode(',', $keywordsRaw);
                foreach ($keywordsList as $keyword) {
                    $keyword = trim($keyword);
                    if ($keyword !== '') {
                        $this->colorMap[$keyword] = $color;
                    }
                }
            }
        }

        return $this->colorMap;
    }
}