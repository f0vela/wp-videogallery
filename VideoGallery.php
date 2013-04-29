<?php 
	/*
	Plugin Name: Video Galeria (Youtube - Vimeo)
	Plugin URI: http://www.frisleyvelasquez.com/wpplugins/videogaleria
	Description: Video Galeria para Youtube - Vimeo
	Author: Frisley Velasquez
	Version: 0.0.1
	Author URI: http://www.frisleyvelasquez.com
	*/
	function my_admin_init() {
		//$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
		$pluginfolder = plugin_dir_url(__FILE__).'VideoGallery';
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
	}
	
	function videogallery_admin() {
		include('videogallery_admin.php');
	}
		
	
	function vg_admin_actions() {
		add_options_page("Video Galeria", "Video Galeria", 1, "VideoGallery", "videogallery_admin");	
	}
	
	function get_youtube_id($vurl)
	{
		parse_str( parse_url( $vurl, PHP_URL_QUERY ), $myarr );
		return $myarr['v'];
	}
	
	function recentVideos()
	{
		$vg_activo	= get_option('vg_activo');
		
		if($vg_activo == 'youtube')
		{
			$rvideos = recentYoutubeVideos();
		}elseif($vg_activo == 'vimeo')
		{
			$rvideos = recentVimeoVideos();
		}
		
		return $rvideos;
	}
	
	function recentYoutubeVideos(){
		
		$yt_name		= get_option('yt_name');
		$yt_ancho		= get_option('yt_ancho');
		if($yt_ancho == ''){ $yt_ancho = '313'; }
		$yt_alto		= get_option('yt_alto');
		if($yt_alto == ''){ $yt_alto = '235'; }
		$yt_embeded		= get_option('yt_embeded');
		if($yt_embeded == ''){ $yt_embeded = 'new'; }

		$plugin_directory = plugin_dir_url(__FILE__);
		
		$url = 'http://gdata.youtube.com/feeds/api/videos?max-results=3&alt=json&orderby=published&format=5&author='.$yt_name.'';
		
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		
		curl_close($ch);

        $search = json_decode($data , $assoc = true);
        
		$vidarray = array();
		$i = 0;
		
		?>
		<script>
			function loadVideo(vid,clicked){
				jQuery('.vgal_youtube_video').css('background-color','#ffffff');
				<?php 
					if($yt_embeded == 'new'){ 
				?>
				jQuery('#vgal_bigvid').html('<iframe width="<?php echo $yt_ancho; ?>" height="<?php echo $yt_alto; ?>" src="http://www.youtube.com/embed/'+vid+'" frameborder="0" allowfullscreen></iframe>');
				<?php 
					}else{
				?>
				jQuery('#vgal_bigvid').html('<object width="<?php echo $yt_ancho; ?>" height="<?php echo $yt_alto; ?>"><param name="movie" value="http://www.youtube.com/v/'+vid+'?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'+vid+'?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="<?php echo $yt_ancho; ?>" height="<?php echo $yt_alto; ?>" allowscriptaccess="always" allowfullscreen="true"></embed></object>');
				<?php		
					}
				?>
				jQuery('#vgal_youtube_video_'+clicked).css('background-color','#f0f0f0');
			}
		</script>
		<link href="<?php echo plugin_dir_url(__FILE__).'VideoGallery'; ?>/css/vg_default.css" rel="stylesheet" type="text/css"/>
		<?php
		$ryt = '<div id="vgal_youtube">';
        foreach($search['feed']['entry'] as $video)
        {
			
			$video_url 		= $video['link'][0]['href'];
        	$video_thumb 	= $video['media$group']['media$thumbnail'][0]['url'];
        	$video_id 		= get_youtube_id($video_url);
        	$video_time 	= $video['media$group']['media$thumbnail'][0]['time'];
			$vgalcolor 		= '';
			
		
        	if($i == 0){
        		$fvideo		= '<div id="vgal_bigvid">';
        		if($yt_embeded == 'new'){ 
    	    		$fvideo		.= '<iframe width="'.$yt_ancho.'" height="'.$yt_alto.'" src="http://www.youtube.com/embed/'.$video_id.'" frameborder="0" allowfullscreen></iframe>';
        		}else{
  	    	  		$fvideo		.= '<object width="'.$yt_ancho.'" height="'.$yt_alto.'"><param name="movie" value="http://www.youtube.com/v/'.$video_id.'?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$video_id.'?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="'.$yt_ancho.'" height="'.$yt_alto.'" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
				}
        		//$fvideo	.= '<img src="'.$video['media$group']['media$thumbnail'][0]['url'].'" border="0" width="313" />';
				$fvideo		.= '</div>';
				$vgalcolor 	= 'style="background-color:#f0f0f0;"';
        	}
            $ryt .= '<div id="vgal_youtube_video_'.$i.'" class="vgal_youtube_video" '.$vgalcolor.'>';
            $ryt .= '<img src="'.$video_thumb.'" border="0" width="100" />';
            $ryt .= '<div id="vgal_youtube_link"><a href="javascript: void(0);" class="vgal_link_vid" onclick="loadVideo(\''.$video_id.'\','.$i.');">'.$video['title']['$t'].'</a><br />';
            $ryt .= $video_time;
            $ryt .= '</div>';
			$ryt .= '</div>';
        	$i++;
        }
        $ryt .= '</div>';
        
        $lyt = '<div id="vgal_wrapper">';
        $lyt .= $fvideo; //Big left video
        $lyt .= $ryt; //Small right videos
        $lyt .= '<div style="clear:both;">&nbsp;</div>';
        $lyt .= '</div>';
        
        return $lyt;
	}
	
	function recentVimeoVideos(){
		
		$vmo_name		= get_option('vmo_name');
		$vmo_ancho		= get_option('vmo_ancho');
		if($vmo_ancho == ''){ $vmo_ancho = '313'; }
		$vmo_alto		= get_option('vmo_alto');
		if($vmo_alto == ''){ $vmo_alto = '235'; }
		$vmo_tipo		= get_option('vmo_tipo');
		
		$plugin_directory = plugin_dir_url(__FILE__);
		
		$url = 'http://vimeo.com/api/v2/'.$vmo_name.'/videos.json';
		if($vmo_tipo == 'usuario'){
			$url = 'http://vimeo.com/api/v2/'.$vmo_name.'/videos.json';
		}elseif($vmo_tipo == 'canal'){
			$url = 'http://vimeo.com/api/v2/channel/'.$vmo_name.'/videos.json';
		}
		
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		
		curl_close($ch);

        $search = json_decode($data , $assoc = true);
        
		$vidarray = array();
		$i = 0;
		
		?>
		<script>
			function loadVideo(vid,clicked){
				jQuery('.vgal_youtube_video').css('background-color','#ffffff');
				jQuery('#vgal_bigvid').html('<iframe src="http://player.vimeo.com/video/'+vid+'" width="<?php echo $vmo_ancho; ?>" height="<?php echo $vmo_alto; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
				jQuery('#vgal_youtube_video_'+clicked).css('background-color','#f0f0f0');
			}
		</script>
		<link href="<?php echo plugin_dir_url(__FILE__).'VideoGallery'; ?>/css/vg_default.css" rel="stylesheet" type="text/css"/>
		<?php
		$ryt = '<div id="vgal_vimeo">';
        foreach($search as $video)
        {
			$video_url 		= $video['url'];
        	$video_thumb 	= $video['thumbnail_large'];
        	$video_id 		= $video['id'];
        	$video_time 	= gmdate("H:i:s",$video['duration']);
        	$video_title 	= $video['title'];
			$vgalcolor 		= '';
			
		
        	if($i == 0){
        		$fvideo		= '<div id="vgal_bigvid">';
    	    		$fvideo		.= '<iframe src="http://player.vimeo.com/video/'.$video_id.'" width="'.$vmo_ancho.'" height="'.$vmo_alto.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				$fvideo		.= '</div>';
				$vgalcolor 	= 'style="background-color:#f0f0f0;"';
        	}
            $ryt .= '<div id="vgal_vimeo_video_'.$i.'" class="vgal_vimeo_video" '.$vgalcolor.'>';
            $ryt .= '<img src="'.$video_thumb.'" border="0" width="100" />';
            $ryt .= '<div id="vgal_vimeo_link"><a href="javascript: void(0);" class="vgal_link_vid" onclick="loadVideo(\''.$video_id.'\','.$i.');">'.$video_title.'</a><br />';
            $ryt .= $video_time;
            $ryt .= '</div>';
			$ryt .= '<div style="clear:both;"></div></div>';
        	$i++;
        }
        $ryt .= '</div>';
        
        $lyt = '<div id="vgal_wrapper">';
        $lyt .= $fvideo; //Big left video
        $lyt .= $ryt; //Small right videos
        $lyt .= '<div style="clear:both;"></div>';
        $lyt .= '</div>';
        
        return $lyt;
	}
	
	add_action('admin_init', 'my_admin_init');
	add_action('admin_menu', 'vg_admin_actions');
	add_shortcode('video_gallery', 'recentVideos');
?>
