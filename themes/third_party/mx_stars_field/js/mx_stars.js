(function($) {
	var onDisplay = function(cell){

		var $select = $('div', cell.dom.$td),
	
			id = cell.field.id+'_'+cell.row.id+'_'+cell.col.id+'_'+Math.floor(Math.random()*100000000);

		$select.attr('id', id);
		$("#" +  id).stars({inputType: "select",   split: mx_stars_field[cell.col.id]});

	};

	Matrix.bind('mx_stars_field', 'display', onDisplay);

})(jQuery);
