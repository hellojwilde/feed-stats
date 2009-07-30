/*
    Copyright (c) 2008 - 2009 Jonathan Wilde

    This file is part of Feed Stats for WordPress.

    Feed Stats for WordPress is free software: you can redistribute 
    it and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 2 of the License, 
    or (at your option) any later version.

    Feed Stats for WordPress is distributed in the hope that it will 
    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Feed Stats for WordPress.  If not, see 
    <http://www.gnu.org/licenses/>.
*/

function selectTab (container, tab) {
    var tc = document.getElementById(container);
    var panels = tc.getElementsByTagName('div');
    var tabs = tc.getElementsByTagName('li');

    for (var i = 0; i < panels.length; i++)
        panels[i].style.display = 'none';

    for (var i = 0; i < tabs.length; i++)
        tabs[i].className = '';

    document.getElementById(tab).style.display = 'block';
    document.getElementById(tab + '-tab').className = 'active';
}
