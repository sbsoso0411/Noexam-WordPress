<?php 


####################### НАСТРОЙКИ #######################

// путь до папки, куда будут создаваться миниатюры (от корня сайта). Нужны права на запись777 (Пример: /wp-content/plugins/kama-thumbnail/thumb)
define('KTCACHE', '/wp-content/plugins/popularity-posts-widget/cache');

// название ключа произвольного поля, которое будет создаваться (Пример: For_Thumb)
define('KTKEY', 'ppw');

// ставим false, если нужно чтобы папка кэша автоматически никогда не очищалась
define('KTCLEARCACHE', true);


class kama_thumbnail
{
	public $no_photo_link;
	public $cache_folder;	
	
	//поддомены на котором могут быть исходные картинки (через запятую): img.site.ru,img2.site.ru
	var $subdomen = '';
	
	
	
	
	### Дальше не редактируем ###
	var $src;
	var $width;
	var $height;
	var $quality;
	var $post_id;
	
	public function __construct(){
		$this->no_photo_link = ($this->no_photo_link=='no_stub') ? '' : WP_PLUGIN_URL . '/popularity-posts-widget/no_photo.jpg';
		
		$this->cache_folder = (KTCACHE!='') ? KTCACHE : str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', WP_PLUGIN_DIR).'/popularity-posts-widget/thumb');
		
		// если не нужно не вызываем функцию
		if( $this->quality ) 
			return $this->do_thumbnail();		
	}

	# Функция создает миниатюру. Возвращает ссылку на миниатюру 
	private function do_thumbnail(){
		if( !$this->src ) // если не передана ссылка которую надо миниатюрить, то ищем её в контенте и записываем пр.поле
			$this->src = $this->get_img_link_or_write_postmeta();
	
		$psrc = parse_url($this->src);
		
		if( !$psrc['path'] )
			return false; // картинка не определена
			
		$doc_root = $_SERVER['DOCUMENT_ROOT'];
		if( strpos($this->subdomen, $psrc['host'])!==false )
			$doc_root = str_replace($_SERVER['HTTP_HOST'], $psrc['host'], $doc_root);
			
		$src = $doc_root .'/'. $psrc['path']; //собираем абс. путь
		$file_name = substr( md5($psrc['path']), -9);		
		$file_name = "{$file_name}_{$this->width}x{$this->height}.png";
		$dest = $_SERVER['DOCUMENT_ROOT'] .'/'. $this->cache_folder . "/$file_name"; //файл миниатюры от корня сайта
		$out_link =  $this->cache_folder . "/$file_name"; //ссылка на изображение;
		
		// если миниатюра уже есть, то возвращаем
		if ( file_exists($dest) )
			return $out_link;  
		elseif( $this->kt_make_thumbnail($src, $this->width, $this->height, $this->quality, $dest) )
			return $out_link;

		return false;
	}
	
	# Берем ссылку на картинку из произвольного поля, или из текста, с созданием произвольного поля.
	# Если в тексте нет картинки, ставим заглушку no_photo
	public function get_img_link_or_write_postmeta(){
		global $post, $wpdb;
		$pID = $this->post_id ? $this->post_id : $post->ID;
		
		$src = get_post_meta($pID, KTKEY, true);
		
		if($src == 'no_photo') 
			return $this->no_photo_link; // если пр.поле вернуло no_photo
		elseif( $src )
			return $src;
			
		//проверяем наличие стандартной миниатюры
		if( $_thumbnail_id = get_post_meta( $pID, '_thumbnail_id', true ) ){
			$src = $wpdb->get_var("SELECT guid FROM {$wpdb->posts} WHERE ID = {$_thumbnail_id} LIMIT 1");
			add_post_meta($pID, KTKEY, $src, true);
			return $src;
		}
		
		//получаем ссылку из контента
		$content = ($this->post_id) ? $wpdb->get_var("SELECT post_content FROM {$wpdb->posts} WHERE ID = {$pID} LIMIT 1") : $post->post_content;
		$src = $this->get_url_from_text($content);
		
		$psrc = parse_url($src);
		if ( $src && ( !$psrc['host'] || strpos($psrc['host'], $_SERVER['HTTP_HOST'] )!==false ) ){ // так же проверяем что картинка с нашего сервера
			add_post_meta($pID, KTKEY, $src, true);
		}
		else { //добавим заглушку no-photo, чтобы постоянно не искать ссылку на картинку в тексте, если картинка не была найдена
			add_post_meta($pID, KTKEY, 'no_photo', true); 
			$src = $this->no_photo_link;
		}		
		return $src;
	}
	
	# вырезаем ссылку из текста 
	public function get_url_from_text($text){
		if (strpos($text,'src=') !== false){ // проверяем ссылку
			if( preg_match ('@(?:<a[^>]+href=[\'"](.*?)[\'"][^>]*>)?<img[^>]+src=[\'"](.*?)[\'"]@i', $text, $match) ) {
					$src = $match[1] ? $match[2] : $match[1]; 
			}
		}
		
		elseif(strpos($text,'[thumb=') !== false){ // проверяем шоткод
			preg_match ('/\[thumb=\s?(.*?)\s+.*\]/i', $text, $match); 
			$src = $match[1]; 
		}
		elseif( preg_match ('/\[singlepic\s+id=([0-9]*)/i', $text, $match) ){ // Генерируем ссылку на картинку NextGen Gallery
			$src = $this->kt_get_nng_image_url($match[1]);
		}
		if(!$src)
			return false;
		return trim($src);
	}
	
	# Получить полную ссылку на картинку NextGen Gallery по ID
	public function kt_get_nng_image_url($imageID, $picturepath = '', $fileName = ''){
		global $wpdb;
		$imageID = (int) $imageID;
		// получить данные галлереи
		if (empty($fileName)) {
			list($fileName, $picturepath ) = $wpdb->get_row("SELECT p.filename, g.path FROM $wpdb->nggpictures AS p INNER JOIN $wpdb->nggallery AS g ON (p.galleryid = g.gid) WHERE p.pid = '$imageID' ", ARRAY_N);
		}
		if (empty($picturepath)) {
			$picturepath = $wpdb->get_var("SELECT g.path FROM $wpdb->nggpictures AS p INNER JOIN $wpdb->nggallery AS g ON (p.galleryid = g.gid) WHERE p.pid = '$imageID' ");
		}
		$imageURL 	= '/' . $picturepath . '/' . $fileName;
		return $imageURL;	
	}	
	
	# Создание и запись файла-картинки
	private function kt_make_thumbnail($src, $width, $height, $quality, $dest){

		$size=@getimagesize($src);
		if($size===false) 
			return false; //не удалось получить параметры файла;

		$w = $size[0];
		$h = $size[1];
		
		// если не указана одна из сторон задаем ей пропорциональное значение
		if(!$width)
			$width = round( $w*($height/$h) );
		if(!$height)
			$height = round( $h*($width/$w) );

		// Определяем исходный формат по MIME-информации и выбираем соответствующую imagecreatefrom-функцию.
		$format=strtolower( substr( $size['mime'], strpos($size['mime'], '/')+1 ) );
		$icfunc="imagecreatefrom".$format;
		if(!function_exists($icfunc)) 
		return false; // не существует подходящей функции преобразования
		
		$isrc=$icfunc($src);		
		// Создаем холст полноцветного изображения
		$idest = imagecreatetruecolor( $width, $height );
		// Создаем прозрачный канал слоя
		$color = imagecolorallocatealpha( $idest, 0, 0, 0, 127 );
		// Заливка холста новыми каналами
		imagefill($idest, 0, 0, $color);
		// Ставим флаг сохраняющий прозрачный канал
		imagesavealpha($idest, true);
		
		// Определяем необходимость преобразования размера так чтоб вписывалась наименьшая сторона
		#if( $width<$w || $height<$h )
			$ratio = max($width/$w, $height/$h);
			
		$dx = $dy = 0;
		
		//срезать справа и/или слева
		if($height/$h > $width/$w) 
			$dx = round( ($w - $width*$h/$height)/2 ); //отступ слева у источника
		else // срезать верх и низ
			$dy = round( ($h - $height*$w/$width)/2*6/10 ); //отступ сверху у источника *6/10 - чтобы для вертикальных фоток отступ сверху был не половина а процентов 30
		 
		// сколько пикселей считывать c источника
		$wsrc = round($width/$ratio);  // по ширине
		$hsrc = round($height/$ratio); // по высоте

		imagecopyresampled($idest, $isrc, 0, 0, $dx, $dy, $width, $height, $wsrc, $hsrc);
				
		if($format=='png'){
			$quality = floor ($quality * 0.09);
			imagepng($idest,$dest,$quality);
		} else
			imagejpeg($idest,$dest,$quality);
		chmod($dest,0755);
		imagedestroy($isrc);
		imagedestroy($idest);
		  
		return true; 
	}
	
}


class kama_clear_thumb extends kama_thumbnail {
	function __construct(){
		parent::__construct();
		if( $GLOBALS['user_level']>8 && $_GET['kt_clear'] )
			return $this->force_clear($_GET['kt_clear']);
		
		if( KTCLEARCACHE )
			$this->clear();
	}
	
	private function clear(){		
		$folder = $_SERVER['DOCUMENT_ROOT'] . $this->cache_folder;

		$expire = @file_get_contents($folder.'/expire');
		if(!$expire || (int)$expire < time() ){
			$this->clear_cache();
			file_put_contents($folder.'/expire', time()+3600*24*3);
		}
		return;
	}
	
	# ?kt_clear=clear_cache - очистит кеш картинок ?kt_clear=del_customs - удалит произвольные поля
	function force_clear($do){
		switch($do){
			case 'clear_cache': $text = $this->clear_cache(); break;
			case 'del_customs': $text = $this->del_customs(); break;
		}
	}

	function clear_cache(){
		$cache_dir = $_SERVER['DOCUMENT_ROOT'] . $this->cache_folder;
		
		if( !$d = opendir($cache_dir) ) 
			die("No such folder: '$cache_dir'");

		$objs = (array) glob($cache_dir."/*");
		foreach( $objs as $obj ){
			unlink($obj);
		}
		add_action('admin_notices', create_function('','echo "<div id=\"message\" class=\"updated\"><p>Кэш <b>kama_thumbnail</b> был очищен.</p></div>";'));
		return;
	}

	function del_customs(){
		global $wpdb;
		
		if( $wpdb->query( $wpdb->prepare("DELETE a FROM $wpdb->postmeta a WHERE meta_key='%s'", KTKEY) ) )
			add_action('admin_notices', create_function('','echo "<div id=\"message\" class=\"updated\"><p>Все произвольные поля <b>".KTKEY."</b> были удалены.</p></div>";'));
		else
			add_action('admin_notices', create_function('','echo "<div id=\"message\" class=\"updated\"><p>Не удалось удалить произвольные поля <b>".KTKEY."</b>.</p></div>";'));

		return;
	}
}


class kama_thumb extends kama_thumbnail {
	var $i;
	
	function __construct($args=''){
		parse_str($args, $this->i);
		$i = $this->i;
		
		$this->width = 		$i['w'] 	 ? trim($i['w'])	 : '';
		$this->height =		$i['h']		 ? trim($i['h'])	 : '';
		$this->src = 		$i['src']	 ? trim($i['src'])	 : '';
		$this->quality =	$i['q']		 ? trim($i['q'])	 : 85;
		$this->post_id = 	$i['post_id']? (int)trim($i['post_id']) : false;		
		if( isset($i['no_stub']) ) 
			$this->no_photo_link = 'no_stub';
		
		if( !$this->width && !$this->height )
			$this->width = $this->height = 100;
	}
	
	function src(){
		return parent::__construct();
	}
	
	function img(){
		$i = $this->i;
		
		$class = 	$i['class']	?	trim($i['class']) : 'aligncenter';
		$alt = 		$i['alt']	?	trim($i['alt'])   : '';
		
		if( $src=$this->src() )
			$out = "<img class='$class' src='$src' alt='$alt'/>\n";
		return $out;
	}

	function a_img(){
		if( $img = $this->img() )
			$out = "<a href='{$this->src}'>$img</a>";
		return $out;
	}
	
}



/* Инициализирующие функции 
--------------------------------------------------------- */
/* Замена в тексте поста 
 * [thumb=<ссылка на картинку> w=<Ширина> h=<высота> alt=<Текст>  class=<CSS класс>  q=<качество> ]
 */
function kama_thumb_img_shotcode($content){
	if (strpos($content, '[thumb='))
		$content = preg_replace_callback('!\[(thumb=.*?) ?\]!s', callback_thumbing, $content);
	
	return $content;
}
function callback_thumbing($a){
	$args = preg_replace('! +([^ ]*=)!', '&\\1', $a[1]);
	$args = str_replace('thumb=','src=', $args); // исправляем название аргумента
	
	$kt = new kama_thumb($args);
	return $kt->a_img();
}

function cut_kt_shortcode($content){
	return preg_replace ('!\[thumb=[^\]]*\]!s', '', $content);
}



add_filter('the_content', 'kama_thumb_img_shotcode' );
add_filter('the_content_rss', 'kama_thumb_img_shotcode' );
add_filter('the_excerpt', 'cut_kt_shortcode' );
add_filter('the_excerpt_rss', 'cut_kt_shortcode' );


/* Удалет произвольное поле со ссылкой при обновлении поста, чтобы создать его заново
*/
add_filter('save_post', 'kt_clear_post_custom');
function kt_clear_post_custom($post_id){
	delete_post_meta($post_id, KTKEY);
}


/* Функции вызова (для шаблона)
 * Аргументы: src, post_id, w, h, q, alt, class(alignleft,alignright), no_stub(чтобы не показывать заглушку)
 * Примечание: если мы не определяем src и переменная пост определяется неправилно, то передаем post_id (идентификатор поста, чтобы правильно получить произвольное поле с картинкой)
 */
# вернет только ссылку
function kama_thumb_src($args=''){
	$kt = new kama_thumb($args);
	return $kt->src();
}
# вернет картинку (готовый тег img)
function kama_thumb_img($args=''){
	$kt = new kama_thumb($args);
	return $kt->img();
}
# вернет ссылку-картинку
function kama_thumb_a_img($args=''){
	$kt = new kama_thumb($args);
	return $kt->a_img();
}

?>