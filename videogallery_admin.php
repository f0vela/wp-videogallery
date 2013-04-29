	<?php 
		if($_POST['detalles_hidden'] == 'Y') {
			
			$yt_name = $_POST['yt_name'];
			update_option('yt_name',$yt_name);
			$yt_embeded = $_POST['yt_embeded'];
			update_option('yt_embeded',$yt_embeded);
			$yt_ancho = $_POST['yt_ancho'];
			update_option('yt_ancho',$yt_ancho);
			$yt_alto = $_POST['yt_alto'];
			update_option('yt_alto',$yt_alto);
			
			$vmo_name = $_POST['vmo_name'];
			update_option('vmo_name',$vmo_name);
			$vmo_tipo = $_POST['vmo_tipo'];
			update_option('vmo_tipo',$vmo_tipo);
			$vmo_alto = $_POST['vmo_alto'];
			update_option('vmo_alto',$vmo_alto);
			$vmo_ancho = $_POST['vmo_ancho'];
			update_option('vmo_ancho',$vmo_ancho);
			
			$vg_activo = $_POST['vg_activo'];
			update_option('vg_activo',$vg_activo);
			
			?>
			<div class="updated"><p><strong><?php _e('Datos Actualizados.' ); ?></strong></p></div>
			<?php
		} else {
		
			$yt_name			= get_option('yt_name');
			$yt_embeded 		= get_option('yt_embeded');
			$yt_ancho			= get_option('yt_ancho');
			$yt_alto			= get_option('yt_alto');

			$vmo_name			= get_option('vmo_name');
			$vmo_tipo			= get_option('vmo_tipo');
			$vmo_ancho			= get_option('vmo_ancho');
			$vmo_alto			= get_option('vmo_alto');
			
			$vg_activo			= get_option('vg_activo');
		}
		
	function my_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}

	function my_admin_styles() {
		wp_enqueue_style('thickbox');
	}

	if (isset($_GET['page']) && $_GET['page'] == 'my_plugin_page') {
		add_action('admin_print_scripts', 'my_admin_scripts');
		add_action('admin_print_styles', 'my_admin_styles');
	}

	?>
	<link href="<?php echo WP_PLUGIN_URL; ?>/VideoGallery/css/vg_admin.css" rel="stylesheet" type="text/css"/>
	<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Video Galeria - Administraci&oacute;n</h2>
		<div class="det_wrap">
			
			<form name="detalles_form" method="post" id="det_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<!-- CONTROLES DE YOUTUBE VIDEOS -->
				<div id="det_transmision">
					<div class="yt_icon"><br></div>
					<h3><?php _e("Canal de Youtube:" ); ?></h3>
					
					<p><label><?php _e("Nombre del canal:" ); ?></label>
						<input type="text" name="yt_name" value="<?php echo $yt_name; ?>" size="20"></p>
					
					<p><label><?php _e("Ancho:" ); ?></label>
						<input type="text" name="yt_ancho" value="<?php echo $yt_ancho; ?>" size="5"></p>
					
					<p><label><?php _e("Alto:" ); ?></label>
						<input type="text" name="yt_alto" value="<?php echo $yt_alto; ?>" size="5"></p>
						
					<p><label><?php _e("Version del embeded:" ); ?></label>
						<input type="radio" name="yt_embeded" id="yt_emb_new" value="new" <?php if($yt_embeded == 'new'){ echo 'checked="checked"'; }elseif($yt_embeded != 'old'){ echo 'checked="checked"'; } ?> /> Nueva
						&nbsp;&nbsp;
						<input type="radio" name="yt_embeded" id="yt_emb_old" value="old" <?php if($yt_embeded == 'old'){ echo 'checked="checked"'; } ?> /> Vieja
					</p>
					<div>
						<p class="submit">
						<input type="submit" name="Submit" value="<?php _e('Actualizar Configuraciones', 'oscimp_trdom' ) ?>" />
						</p>
					
						<div style="float:right; margin:5px 0; padding:1.5em 0"><input type="radio" name="vg_activo" id="vg_activo" value="youtube" <?php if($vg_activo == 'youtube'){ echo 'checked="checked"'; }elseif($vg_activo != 'vimeo'){ echo 'checked="checked"'; } ?> /> Activo</div>
					</div>
				</div>
				
				
				<div id="det_transmision">
					<div class="vmo_icon"><br></div>
					<h3><?php _e("Canal de Vimeo:" ); ?></h3>
				
					<p><label><?php _e("Nombre de Usuario/Canal:" ); ?></label>
						<input type="text" name="vmo_name" value="<?php echo $vmo_name; ?>" size="20"></p>
				
					<p><label><?php _e("Ancho:" ); ?></label>
						<input type="text" name="vmo_ancho" value="<?php echo $vmo_ancho; ?>" size="5"></p>

					<p><label><?php _e("Alto:" ); ?></label>
						<input type="text" name="vmo_alto" value="<?php echo $vmo_alto; ?>" size="5"></p>
				
					<p><label><?php _e("Usuario/Canal?:" ); ?></label>
						<input type="radio" name="vmo_tipo" id="vmo_usuario" value="usuario" <?php if($vmo_tipo == 'usuario'){ echo 'checked="checked"'; }elseif($vmo_tipo != 'canal'){ echo 'checked="checked"'; } ?> /> Usuario
						&nbsp;&nbsp;
						<input type="radio" name="vmo_tipo" id="vmo_canal" value="canal" <?php if($vmo_tipo == 'canal'){ echo 'checked="checked"'; } ?> /> Canal
					</p>
				
					<input type="hidden" name="detalles_hidden" value="Y"/>	
					<div>
						<p class="submit">
						<input type="submit" name="Submit" value="<?php _e('Actualizar Configuraciones', 'oscimp_trdom' ) ?>" />
						</p>
				
						<div style="float:right; margin:5px 0; padding:1.5em 0"><input type="radio" name="vg_activo" id="vg_activo" value="vimeo" <?php if($vg_activo == 'vimeo'){ echo 'checked="checked"'; } ?> /> Activo</div>
					</div>
				</div>
			</form>
		</div>
	</div>