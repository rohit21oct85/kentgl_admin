/*global $, SyntaxHighlighter*/

var oTable;


$(document).ready(function () {
	'use strict';
  var d = new Date();
  var month = d.getMonth()+1;
  var day = d.getDate();
 var output = (month<10 ? '0' : '') + month + '/' +
     (day<10 ? '0' : '') + day + '/' + d.getFullYear();
	oTable = $('#example').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "range_date"},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 5, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 6, filter_type: "multi_select", select_type: 'chosen'}]);
		/*yadcf.exFilterColumn(oTable, [
     [0, {from: output, to: output}]
   ]);*/
	SyntaxHighlighter.all();
});



$(document).ready(function () {
	'use strict';

	oTable = $('#attendenceffms').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "range_date"},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'}]);

	SyntaxHighlighter.all();
});
$(document).ready(function () {
	'use strict';
		
	oTable = $('#beatffms').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "range_date"},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 5, filter_type: "multi_select", select_type: 'chosen'}]);

	SyntaxHighlighter.all();
});
$(document).ready(function () {
	'use strict';

	oTable = $('#loginffms').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "range_date"},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'}]);

	SyntaxHighlighter.all();
});
$(document).ready(function () {
	'use strict';

	oTable = $('#usersffms').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 5, filter_type: "multi_select", select_type: 'chosen'},
		{column_number : 6, filter_type: "multi_select", select_type: 'chosen'},
		{column_number : 7, filter_type: "multi_select", select_type: 'chosen'},
		]);

	SyntaxHighlighter.all();
});
$(document).ready(function () {
	'use strict';

	oTable = $('#adduser').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 1, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 2, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 3, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 4, filter_type: "multi_select", select_type: 'chosen'},
	    {column_number : 5, filter_type: "multi_select", select_type: 'chosen'},
		{column_number : 6, filter_type: "multi_select", select_type: 'chosen'},
		{column_number : 7, filter_type: "multi_select", select_type: 'chosen'}
		
		]);

	SyntaxHighlighter.all();
});

$(document).ready(function () {
	'use strict';

	oTable = $('#roleffms').dataTable({
		"bStateSave": false,
		"order": [[ 0, "desc" ]]
	}).yadcf([
	    {column_number : 0, filter_type: "multi_select", select_type: 'chosen'}]);

	SyntaxHighlighter.all();
});