<script>


$(document).ready(function(){
	$("#frm_leasing").submit(function(){
		
		$.ajax({
			url : $("#frm_leasing").attr('action'),
			data : $(this).serialize(),
			dataType : 'json',
			type : 'post',
			success : function(obj) {
				if(obj.error==false){
						//location.href=('<?php echo site_url("leasing"); ?>');
						//$("#message").html("<strong>ERROR</strong><br/>");
						$("#salah").hide();
						$("#message2").html(obj.message);
						$("#benar").hide().show('fast');
						
						$("#vUSER_NAME").focus();
						
						if($("#mode").val() == "I") { 
						$("#frm_leasing")[0].reset(); }
						
						//$('#myform')
					}
					else {
						$("#benar").hide();
						$("#message").html("<strong>ERROR</strong><br/>");
						$("#message").append(obj.message);
						$("#salah").hide().show('fast');
					}
			}
		});
		return false;
		/*console.log('test ');
		
		$(this).form('submit',{
				onSubmit: function(){
					return $(this).form('validate');
				},
				success : function() {
					console.log('halo');
				}
			});
		return false;*/
	});



	$("#id_polda").change(function(){
		$.ajax({
			url : '<?php echo site_url("general/get_polres") ?>',
			data : { id_polda : $(this).val() },
			type :'post',
			success : function(html_data){
				$("#id_polres").html(html_data);
			}
		});
	});


	<?php 
		if($mode=='U') { 
	?>

	$.ajax({
		url : '<?php echo site_url("general/get_polres_polda") ?>',
		data : { id_polda :  '<?php echo $id_polda  ?>', id_polres : '<?php echo $id_polres  ?>'  },
			type :'post',
			success : function(html_data){
				$("#id_polres").html(html_data);
			}

	});
	<?php }  ?>

});
 
 

</script>