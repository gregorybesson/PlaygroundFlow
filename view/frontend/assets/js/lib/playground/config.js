/**
 * this is the main application file, which one that init project bind event, etc...
 * Copyright (C) 2013 - Playground
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/** Never call this constant by PG
 * @constant Playground
 */
var Playground = Playground || {},

	/** Application settings, need to be fill by partner
	 * @constant _plgd_settings 
	 */
    _pg = _pg || {}, 
    
    /** config create from "pl_config" variable
     * @name  PG.Cache.config
     * @constant PG.Cache.config 
     * @type Object
     */
    pl_config = {
        modules: {
            ears: false,
            mouth: false
        },
        scripts: {
            xpath: '//playground.local/frontend/js/lib/playground/wgxpath.install.js',
            json: '//playground.local/frontend/js/lib/playground/json2.js',
            ears: '//playground.local/frontend/js/lib/playground/ears.min.js',
            mouth: '//ic.adfab.fr/mouthnode/leaderboard/others/client-0/script/pg.connect.js'
        },
        debug: false,
        mode: 'dev',
        env: {
            dev: {
                url: '//playground.local/flow/XX-XX-YY/rest/',
                remote: 'playground.local/easyxdm/index',
                easySwf: 'playground.local/frontend/js/lib/easyxdm/easyxdm.swf',
                nameTransport: 'playground.local/easyxdm/name',
                send: 'playground.local/flow/XX-XX-YY/rest/send',
                connect: 'playground.local/flow/XX-XX-YY/rest/authent'
            },
            prod: {
                url: 'livedemo.fr/playground/',
                send: 'send.php',
                connect: 'connect.php'
            }
        },
        ns: 'Playground'
    };
;