// items structure
// each item is the array of one or more properties:
// [text, link, settings, subitems ...]
// use the builder to export errors free structure if you experience problems with the syntax

var MENU_ITEMS = [
	['Administración', null, null,
		['Usuarios', null, null, ],
		// this is how custom javascript code can be called from the item
		// note how apostrophes are escaped inside the string, i.e. 'Don't' must be 'Don\'t'
		['Pacientes', 'javascript:alert(\'hello world\')', null, ],
		['Doctores', 'http://www.softcomplex.com/support.html'],
	],
	['Intervenciones', null, null, ],
	['Salir', null, null, ],
];

