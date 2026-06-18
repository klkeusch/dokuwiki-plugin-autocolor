<?php
/**
 * AutoColor Plugin: Action component for admin notifications.
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Klaus Keusch <dev@klauskeusch.de>
 */

if (!defined('DOKU_INC')) die();

class action_plugin_autocolor extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        // Include this as soon as DokuWiki is ready.
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'checkConfigPage');
    }

    /**
     * Checks whether the configuration wiki page exists and alerts the admin if it does not.
     */
    public function checkConfigPage(Doku_Event $event, $param) {
        // DokuWiki security check: Is the current user an admin?
        if (!auth_isadmin()) {
            return;
        }

        // Retrieve the page name from the settings (e.g., "admin:autocolor")
        $pageId = $this->getConf('config_page');

        if (!page_exists($pageId)) {
            // DokuWiki's built-in msg() function generates the yellow/red banner from lang files.
            // The parameter -1 indicates an error or warning.
            $warnText = sprintf(
                $this->getLang('missing_config'), 
                htmlspecialchars($pageId, ENT_QUOTES, 'UTF-8')
            );
            msg($warnText, -1);
        }
    }
}