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