<div role="contentinfo" id="wpfooter">
    <p class="alignleft" id="footer-left">
        <span id="footer-thankyou">Thank you for creating with <a href="https://en-ca.wordpress.org/">WordPress</a>.</span> </p>
    <p class="alignright" id="footer-upgrade">
        Version 4.4 </p>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    list_args = {
        "class": "WP_Comments_List_Table",
        "screen": {
            "id": "dashboard",
            "base": "dashboard"
        }
    };
</script>
<script type="text/javascript">
    list_args = {
        "class": "WP_Comments_List_Table",
        "screen": {
            "id": "dashboard",
            "base": "dashboard"
        }
    };
</script>
<div class="hidden" id="wp-auth-check-wrap">
    <div id="wp-auth-check-bg"></div>
    <div id="wp-auth-check">
        <div title="Close" tabindex="0" class="wp-auth-check-close"></div>
        <div data-src="http://localhost:8082/wordpress1/wp-login.php?interim-login=1" id="wp-auth-check-form"></div>
        <div class="wp-auth-fallback">
            <p><b tabindex="0" class="wp-auth-fallback-expired">Session expired</b></p>
            <p><a target="_blank" href="http://localhost:8082/wordpress1/wp-login.php">Please log in again.</a> The login page will open in a new window. After logging in you can close it and return to this page.</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    /* &lt;![CDATA[ */
    var commonL10n = {
        "warnDelete": "You are about to permanently delete these items.\n  'Cancel' to stop, 'OK' to delete.",
        "dismiss": "Dismiss this notice."
    };
    var wpAjax = {
        "noPerm": "You do not have permission to do that.",
        "broken": "An unidentified error has occurred."
    };
    var quicktagsL10n = {
        "closeAllOpenTags": "Close all open tags",
        "closeTags": "close tags",
        "enterURL": "Enter the URL",
        "enterImageURL": "Enter the URL of the image",
        "enterImageDescription": "Enter a description of the image",
        "textdirection": "text direction",
        "toggleTextdirection": "Toggle Editor Text Direction",
        "dfw": "Distraction-free writing mode",
        "strong": "Bold",
        "strongClose": "Close bold tag",
        "em": "Italic",
        "emClose": "Close italic tag",
        "link": "Insert link",
        "blockquote": "Blockquote",
        "blockquoteClose": "Close blockquote tag",
        "del": "Deleted text (strikethrough)",
        "delClose": "Close deleted text tag",
        "ins": "Inserted text",
        "insClose": "Close inserted text tag",
        "image": "Insert image",
        "ul": "Bulleted list",
        "ulClose": "Close bulleted list tag",
        "ol": "Numbered list",
        "olClose": "Close numbered list tag",
        "li": "List item",
        "liClose": "Close list item tag",
        "code": "Code",
        "codeClose": "Close code tag",
        "more": "Insert Read More tag"
    };
    var adminCommentsL10n = {
        "hotkeys_highlight_first": "",
        "hotkeys_highlight_last": "",
        "replyApprove": "Approve and Reply",
        "reply": "Reply",
        "warnQuickEdit": "Are you sure you want to edit this comment?\nThe changes you made will be lost.",
        "docTitleComments": "Comments",
        "docTitleCommentsCount": "Comments (%s)"
    };
    var _wpCustomizeLoaderSettings = {
        "url": "http:\/\/localhost:8082\/wordpress1\/wp-admin\/customize.php",
        "isCrossDomain": false,
        "browser": {
            "mobile": false,
            "ios": false
        },
        "l10n": {
            "saveAlert": "The changes you made will be lost if you navigate away from this page.",
            "mainIframeTitle": "Customiser"
        }
    };
    var thickboxL10n = {
        "next": "Next &gt;",
        "prev": "&lt; Prev",
        "image": "Image",
        "of": "of",
        "close": "Close",
        "noiframes": "This feature requires inline frames. You have iframes disabled or your browser does not support them.",
        "loadingAnimation": "http:\/\/localhost:8082\/wordpress1\/wp-includes\/js\/thickbox\/loadingAnimation.gif"
    };
    var plugininstallL10n = {
        "plugin_information": "Plugin Information:",
        "ays": "Are you sure you want to install this plugin?"
    };
    var heartbeatSettings = {
        "nonce": "a38d9fb476"
    };
    var authcheckL10n = {
        "beforeunload": "Your session has expired. You can log in again from this page or go to the login page.",
        "interval": "180"
    };
    var wpLinkL10n = {
        "title": "Insert\/edit link",
        "update": "Update",
        "save": "Add Link",
        "noTitle": "(no title)",
        "noMatchesFound": "No results found."
    }; /* ]]&gt; */
</script>
<script src="http://localhost:8082/wordpress1/wp-admin/load-scripts.php?c=1&amp;load%5B%5D=hoverIntent,common,admin-bar,wp-ajax-response,jquery-color,wp-lists,quicktags,jquery-query,admin-comments,jquery-ui-core,jquery-&amp;load%5B%5D=ui-widget,jquery-ui-mouse,jquery-ui-sortable,postbox,dashboard,underscore,customize-base,customize-loader,thickbox,plugin-instal&amp;load%5B%5D=l,shortcode,media-upload,svg-painter,heartbeat,wp-auth-check,wplink&amp;ver=4.4" type="text/javascript"></script>

<script type="text/javascript">
    tinyMCEPreInit = {
        baseURL: "",
        suffix: ".min",
        mceInit: {},
        qtInit: {
            'replycontent': {
                id: "replycontent",
                buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,close"
            }
        },
        ref: {
            plugins: "",
            theme: "modern",
            language: ""
        },
        load_ext: function (url, lang) {
            var sl = tinymce.ScriptLoader;
            sl.markDone(url + '/langs/' + lang + '.js');
            sl.markDone(url + '/langs/' + lang + '_dlg.js');
        }
    };
</script>
<script type="text/javascript">
    (function () {
        var init, id, $wrap;
        if (typeof tinymce !== 'undefined') {
            for (id in tinyMCEPreInit.mceInit) {
                init = tinyMCEPreInit.mceInit[id];
                $wrap = tinymce.$('#wp-' + id + '-wrap');
                if (($wrap.hasClass('tmce-active') || !tinyMCEPreInit.qtInit.hasOwnProperty(id)) & amp; & amp; !init.wp_skip_init) {
                    tinymce.init(init);
                    if (!window.wpActiveEditor) {
                        window.wpActiveEditor = id;
                    }
                }
            }
        }

        if (typeof quicktags !== 'undefined') {
            for (id in tinyMCEPreInit.qtInit) {
                quicktags(tinyMCEPreInit.qtInit[id]);
                if (!window.wpActiveEditor) {
                    window.wpActiveEditor = id;
                }
            }
        }
    }());
</script>
<div style="display: none" id="wp-link-backdrop"></div>
<div style="display: none" class="wp-core-ui" id="wp-link-wrap">
    <form tabindex="-1" id="wp-link">
        <input type="hidden" value="ee414691e5" name="_ajax_linking_nonce" id="_ajax_linking_nonce">
        <div id="link-modal-title">
            Insert/edit link
            <button id="wp-link-close" type="button"><span class="screen-reader-text">Close</span></button>
        </div>
        <div id="link-selector">
            <div id="link-options">
                <p class="howto">Enter the destination URL</p>
                <div>
                    <label><span>URL</span>
                        <input type="text" id="wp-link-url">
                    </label>
                </div>
                <div class="wp-link-text-field">
                    <label><span>Link Text</span>
                        <input type="text" id="wp-link-text">
                    </label>
                </div>
                <div class="link-target">
                    <label><span>&nbsp;</span>
                        <input type="checkbox" id="wp-link-target"> Open link in a new tab</label>
                </div>
            </div>
            <p class="howto"><a id="wp-link-search-toggle" href="#">Or link to existing content</a></p>
            <div id="search-panel">
                <div class="link-search-wrapper">
                    <label>
                        <span class="search-label">Search</span>
                        <input type="search" autocomplete="off" class="link-search-field" id="wp-link-search">
                        <span class="spinner"></span>
                    </label>
                </div>
                <div tabindex="0" class="query-results" id="search-results">
                    <ul></ul>
                    <div class="river-waiting">
                        <span class="spinner"></span>
                    </div>
                </div>
                <div tabindex="0" class="query-results" id="most-recent-results">
                    <div id="query-notice-message" class="query-notice">
                        <em class="query-notice-default">No search term specified. Showing recent items.</em>
                        <em class="query-notice-hint screen-reader-text">Search or use up and down arrow keys to select an item.</em>
                    </div>
                    <ul></ul>
                    <div class="river-waiting">
                        <span class="spinner"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="submitbox">
            <div id="wp-link-cancel">
                <a href="#" class="submitdelete deletion">Cancel</a>
            </div>
            <div id="wp-link-update">
                <input type="submit" name="wp-link-submit" id="wp-link-submit" class="button button-primary" value="Add Link">
            </div>
        </div>
    </form>
</div>

<div class="clear"></div>
</div>
<!-- wpwrap -->
<script type="text/javascript">
    if (typeof wpOnload == 'function')
        wpOnload();
</script>


<div style="display: none; font-family: 'Open Sans',sans-serif; font-size: 14px; line-height: 19.6px; padding: 6px 7px; white-space: pre-wrap; word-wrap: break-word;" class="quick-draft-textarea-clone"></div>
<div id="customize-container"></div>
<div id="SL_shadow_translator">
    <div id="SL_shadow_balloon" style="display: none; top: -10000px; left: -10000px;">
        <div id="SLplanshet">
            <div id="SLarrowTOP"></div>
            <div align="left" id="SLtopbar">
                <table width="100%" height="25" cellspacing="0" cellpadding="0" border="0" class="SLtable">
                    <tr>
                        <td width="15" align="left" class="SLtd">
                            <div id="SLlogo"></div>
                        </td>
                        <td width="37" valign="center" align="right" class="SLtd">
                            <input type="checkbox" id="SLloc" title="Lock-in language">
                        </td>
                        <td width="95" align="left" class="SLtd">
                            <select id="SLfrom" class="SLselectbox">
                                <option value="auto">Detect language</option>
                                <option value="af">Afrikaans</option>
                                <option value="sq">Albanian</option>
                                <option value="ar">Arabic</option>
                                <option value="hy">Armenian</option>
                                <option value="az">Azerbaijani</option>
                                <option value="eu">Basque</option>
                                <option value="be">Belarusian</option>
                                <option value="bn">Bengali</option>
                                <option value="bg">Bulgarian</option>
                                <option value="ca">Catalan</option>
                                <option value="zh">Chinese (Simplified)</option>
                                <option value="zt">Chinese (Traditional)</option>
                                <option value="hr">Croatian</option>
                                <option value="cs">Czech</option>
                                <option value="da">Danish</option>
                                <option value="nl">Dutch</option>
                                <option value="en">English</option>
                                <option value="eo">Esperanto</option>
                                <option value="et">Estonian</option>
                                <option value="tl">Filipino</option>
                                <option value="fi">Finnish</option>
                                <option value="fr">French</option>
                                <option value="gl">Galician</option>
                                <option value="ka">Georgian</option>
                                <option value="de">German</option>
                                <option value="el">Greek</option>
                                <option value="gu">Gujarati</option>
                                <option value="ht">Haitian Creole</option>
                                <option value="iw">Hebrew</option>
                                <option value="hi">Hindi</option>
                                <option value="hu">Hungarian</option>
                                <option value="is">Icelandic</option>
                                <option value="id">Indonesian</option>
                                <option value="ga">Irish</option>
                                <option value="it">Italian</option>
                                <option value="ja">Japanese</option>
                                <option value="kn">Kannada</option>
                                <option value="ko">Korean</option>
                                <option value="lo">Lao</option>
                                <option value="la">Latin</option>
                                <option value="lv">Latvian</option>
                                <option value="lt">Lithuanian</option>
                                <option value="mk">Macedonian</option>
                                <option value="ms">Malay</option>
                                <option value="mt">Maltese</option>
                                <option value="no">Norwegian</option>
                                <option value="fa">Persian</option>
                                <option value="pl">Polish</option>
                                <option value="pt">Portuguese</option>
                                <option value="ro">Romanian</option>
                                <option value="ru">Russian</option>
                                <option value="sr">Serbian</option>
                                <option value="sk">Slovak</option>
                                <option value="sl">Slovenian</option>
                                <option value="es">Spanish</option>
                                <option value="sw">Swahili</option>
                                <option value="sv">Swedish</option>
                                <option value="ta">Tamil</option>
                                <option value="te">Telugu</option>
                                <option value="th">Thai</option>
                                <option value="tr">Turkish</option>
                                <option value="uk">Ukrainian</option>
                                <option value="ur">Urdu</option>
                                <option value="vi">Vietnamese</option>
                                <option value="cy">Welsh</option>
                                <option value="yi">Yiddish</option>
                            </select>
                        </td>
                        <td width="10" valign="center" align="center" class="SLtd">
                            <div id="SL_switch" title=" Switch languages"></div>
                        </td>
                        <td width="95" valign="center" align="left" class="SLtd">
                            <select id="SLto" class="SLselectbox">
                                <option value="af">Afrikaans</option>
                                <option value="sq">Albanian</option>
                                <option value="ar">Arabic</option>
                                <option value="hy">Armenian</option>
                                <option value="az">Azerbaijani</option>
                                <option value="eu">Basque</option>
                                <option value="be">Belarusian</option>
                                <option value="bn">Bengali</option>
                                <option value="bg">Bulgarian</option>
                                <option value="ca">Catalan</option>
                                <option value="zh">Chinese (Simplified)</option>
                                <option value="zt">Chinese (Traditional)</option>
                                <option value="hr">Croatian</option>
                                <option value="cs">Czech</option>
                                <option value="da">Danish</option>
                                <option value="nl">Dutch</option>
                                <option value="en">English</option>
                                <option value="eo">Esperanto</option>
                                <option value="et">Estonian</option>
                                <option value="tl">Filipino</option>
                                <option value="fi">Finnish</option>
                                <option value="fr">French</option>
                                <option value="gl">Galician</option>
                                <option value="ka">Georgian</option>
                                <option value="de">German</option>
                                <option value="el">Greek</option>
                                <option value="gu">Gujarati</option>
                                <option value="ht">Haitian Creole</option>
                                <option value="iw">Hebrew</option>
                                <option value="hi">Hindi</option>
                                <option value="hu">Hungarian</option>
                                <option value="is">Icelandic</option>
                                <option value="id">Indonesian</option>
                                <option value="ga">Irish</option>
                                <option value="it">Italian</option>
                                <option value="ja">Japanese</option>
                                <option value="kn">Kannada</option>
                                <option value="ko">Korean</option>
                                <option value="lo">Lao</option>
                                <option value="la">Latin</option>
                                <option value="lv">Latvian</option>
                                <option value="lt">Lithuanian</option>
                                <option value="mk">Macedonian</option>
                                <option value="ms">Malay</option>
                                <option value="mt">Maltese</option>
                                <option value="no">Norwegian</option>
                                <option value="fa">Persian</option>
                                <option value="pl">Polish</option>
                                <option value="pt">Portuguese</option>
                                <option value="ro">Romanian</option>
                                <option value="ru">Russian</option>
                                <option value="sr">Serbian</option>
                                <option value="sk">Slovak</option>
                                <option value="sl">Slovenian</option>
                                <option value="es">Spanish</option>
                                <option value="sw">Swahili</option>
                                <option value="sv">Swedish</option>
                                <option value="ta">Tamil</option>
                                <option value="te">Telugu</option>
                                <option value="th">Thai</option>
                                <option value="tr">Turkish</option>
                                <option value="uk">Ukrainian</option>
                                <option value="ur">Urdu</option>
                                <option value="vi">Vietnamese</option>
                                <option value="cy">Welsh</option>
                                <option value="yi">Yiddish</option>
                            </select>
                        </td>
                        <td width="6" align="right" class="SLtd"></td>
                        <td width="12" align="right" class="SLtd">
                            <div id="SL_tts" title="Listen to the translation"></div>
                        </td>
                        <td width="22" align="right" class="SLtd">
                            <div id="SL_copy" title="Copy translation" class="SL_copy"></div>
                        </td>
                        <td width="22" align="right" class="SLtd">
                            <div id="SL_font" title="Font size" class="SL_font_on"></div>
                        </td>
                        <td width="25" align="right" class="SLtd"></td>
                        <td width="22" align="right" class="SLtd">
                            <div id="SL_pin" title="Pin pop-up bubble" class="SL_pin_off"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="SL_result" class="SL_sesultLTRRTL" style="font-size: 13px; line-height: 18px;">
            <div style="margin-bottom:0px"></div>
        </div>
        <div id="SLplanshetBottom">
            <div id="SLDONATE"></div><span id="SL_Goptions">Options</span><span id="SL_dots1">:</span><span id="SL_Ghelp">Help</span><span id="SL_dots2">:</span><span id="SL_Gfeedback">Feedback</span></div>
        <div id="SLarrowBOT"></div>
    </div>
</div>
</body>

</html>