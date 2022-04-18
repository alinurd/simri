<?php echo $map['js']; ?>
<input type="text" id="map_tag" name='map_tag' class="form-control" />
<?php echo $map['html'];?>

<script>
	function get_position_map(lat, lang, event){
		$("#lat").val(lat);
        $("#lng").val(lang);
        console.log(event);
	}

	function clearOverlays() {
		for (var i = 0; i < markers_map.length; i++ ) {
			markers_map[i].setMap(null);
		}
		markers_map = [];
	}
</script>