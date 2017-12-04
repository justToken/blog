<?php
if(!defined('EMLOG_ROOT')) {exit('error!');} 
/*
Author:秋天日记
Author Url:http://qtnote.com
*/


/*	Jane模板核心类库	*/



//获取图片基类
class Pic{
	protected $db;
		//构造方法，初始化数据库操作对象
		function __construct(){
				$this->db = MySql::getInstance();
		}
		//获取随机图片
		protected function pic_auto(){
						$img_url= TEMPLATE_URL.'pic/ap'.rand(1,15).'.jpg';
						return $img_url;
		}
		//(获取附件图片)
		protected function pic_file($blogid) {
		   $sql = "SELECT * FROM ".DB_PREFIX."attachment WHERE blogid=".$blogid." AND (`filepath` LIKE '%jpg' OR `filepath` LIKE '%gif' OR `filepath` LIKE '%png') ORDER BY `aid` ASC LIMIT 0,1";
			$imgs = $this->db->query($sql);
			$img_url = '';
		    while($row = $this->db->fetch_array($imgs)){
			        $img_url= ''.BLOG_URL.substr($row['filepath'],3,strlen($row['filepath'])).'';
		    }
		    return $img_url;
		}
		//（获取图片链接）
		protected function pic_link($content){
		    //preg_match_all全局匹配content中的图片地址，并存入$img变量
		    preg_match_all("%<img[^>]*?src=[\'\"]((?:(?!\/admin\/|>).)+?)[\'\"][^>]*?>%s", $content, $img);
		    //当图片存在时，获取第一张图片，地址保存在$imgsrc中
		    $img_url = !empty($img[1]) ? $img[1][0] : '';
			if($img_url){
				return $img_url;
			}
		}
}
//jane模板配置类基类
	class Jane_init extends Pic{
			protected $init_path='./content/cache/jane_init.php';//jane配置文件
			protected $c_file_new = './content/cache/newlog.php';//缓存更新依靠的文件路径
			protected $c_file_sta = './content/cache/sta.php';//缓存更新依靠的文件路径
			protected $style = "./content/templates/jane/jane.init.css";//风格配置文件
			protected $cache_file=null;						//缓存文件
			protected $jane_init;							//配置数据
			protected $num=5;								//查询数目 默认5
			public $view_hot=300;							//热门文章标志 显示条件
			public $view_new=3;								//最新文章标志 显示条件

			//初始化成员属性
			function __construct($style=null){
				parent::__construct();
				if($style=='tw'){
					$this->style = '../content/templates/jane/jane.init.css';
					$this->init_path = '../content/cache/jane_init.php';
				}
				//判断是否存在jane配置文件
				if(is_file($this->init_path)){
					$init = file_get_contents($this->init_path);
					$init_data = str_replace("<?php exit;//", '', $init);
					$this->jane_init = json_decode($init_data, true);
					$this->view_new = !empty($this->jane_init['style']['style_view_new']) ? $this->jane_init['style']['style_view_new'] : 3;
					$this->view_hot = !empty($this->jane_init['style']['style_view_hot']) ? $this->jane_init['style']['style_view_hot'] : 300;
				}else{
					@touch($this->init_path);
				}
			}

		//更新缓存方法
		protected function is_cache(array $sid, $file){
				//判断是否存在配置缓存文件 //存在则判断是否更新缓存
				if( is_file($file) ){
					//自由列表 的缓存更新方法
					if(in_array('hot',$sid) || in_array('rand',$sid)){
						if(filemtime($file) < filemtime($this->c_file_sta) || filemtime($file) < filemtime($this->init_path)){
							$this->save_cache($sid);
						}
					}else{
						//其它缓存的更新方法
						if(filemtime($file) < filemtime($this->c_file_new) || filemtime($file) < filemtime($this->init_path)){
							$this->save_cache($sid);
						}
					}
				}else{

						//不存在缓存则生成缓存
						$this->save_cache($sid); 
				}
		}
		//写入缓存方法
		private function save_cache(array $sids){
				global $CACHE;
				//获取数据库相关内容   生成配置缓存文件
				if(!empty($sids)){
					if($this->cache_file!=null){//判断是否指定了缓存文件
							$data = array();
							for($i=0; $i<count($sids); $i++){
								if(array_key_exists('sql', $sids)){
										$sql = $sids['sql'];
								}else{
									$sid = $sids[$i];
									if($sid == 'hot'){
								     	$sql = "SELECT gid,title,content,date FROM ".DB_PREFIX."blog ORDER BY views DESC LIMIT 0,{$this->num}";
										
										}elseif($sid == 'rand'){
								     		$sql = "SELECT gid,title,content,date FROM ".DB_PREFIX."blog  ORDER BY RAND() LIMIT {$this->num}";
										}else{
											$sql = "SELECT a.gid,a.title,a.content,a.date FROM ".DB_PREFIX."blog a,".DB_PREFIX."sort b WHERE  a.sortid={$sid}  AND  b.sid={$sid} OR b.pid={$sid} AND a.sortid=b.sid AND hide='n'  ORDER BY `date` DESC LIMIT 0,{$this->num}";
									}
								}
								$result = $this->db->query($sql);
								//遍历数据
								$list = array();
								while($row = $this->db->fetch_array($result)){
									if($row['pic'] = $this->pic_link($row['content'])){
									}elseif($row['pic'] = $this->pic_file($row['gid'])){
									}else{
										$row['pic'] = $this->pic_auto();
									}
									  		$row['url'] = URL::log($row['gid']);

									  		unset($row['content']);
									  		unset($row['gid']);
									  		$list[] = $row;
							 }
							 $data[]=$list;
						}
					$w_str = json_encode($data);
				  	 //生成缓存
				  	$CACHE->cacheWrite($w_str, $this->cache_file);
				 }
		}
	}
}
//站点统计
class log_Total extends Jane_init
{
	public function __construct(){
		parent::__construct();
		if(isset($this->jane_init['style']['style_total_link']) && $this->jane_init['style']['style_total_link']=='true'){
			$this->show_Total();

		}
	}
	private function show_Total(){
		global $CACHE;
		$str = $CACHE->readCache('sta');
		$str['lognum'] = !empty($str['lognum']) ? $str['lognum'] : 0;
		$str['hidecomnum'] = !empty($str['hidecomnum']) ? $str['hidecomnum'] : 0;
		$str['comnum'] = !empty($str['comnum']) ? $str['comnum'] : 0;
		$html = "<div class='panel-footer'>文章总数：{$str['lognum']}&nbsp;&nbsp;评论总数：{$str['comnum']}&nbsp;&nbsp;待审核评论：{$str['hidecomnum']}</div>";
		echo $html;
	}
}
//风格类
class init_Style extends Jane_init{
	public $style_power=0;				//风格开关
	public $backg=false;		//模糊背景
	public $f_link=false;		//底部友链
	public $is_f_link=false;		//仅首页底部友链
	public $img_url='';			//背景图片
	public $bc='';				//纯色背景
	function __construct($style){
		parent::__construct($style);
		if(isset($this->jane_init['style']['style_f_link']) && !empty($this->jane_init['style']['style_f_link'])){
				$this->f_link = $this->jane_init['style']['style_f_link'];
			}
			if(isset($this->jane_init['style']['style_is_f_link']) && !empty($this->jane_init['style']['style_is_f_link'])){
				$this->is_f_link = $this->jane_init['style']['style_is_f_link'];
			}
		if(isset($this->jane_init['style']['style_power']) && !empty($this->jane_init['style']['style_power'])){
			$this->style_power = $this->jane_init['style']['style_power'];
			if(isset($this->jane_init['style']['style_bg'])){
				$this->backg = !empty($this->jane_init['style']['style_bg']) ? $this->jane_init['style']['style_bg'] : false;
			}
			
			if(isset($this->jane_init['style']['style_img_url']) && !empty($this->jane_init['style']['style_img_url'])){
				$this->img_url = $this->jane_init['style']['style_img_url'];
			}
			if(isset($this->jane_init['style']['style_bc']) && !empty($this->jane_init['style']['style_bc'])){
				$this->bc = $this->jane_init['style']['style_bc'];
			}
			
			if(is_file($this->style) && $this->style_power=='true'){
				echo "<link href='{$this->style}' rel='stylesheet' type='text/css' />";
			}
		}else{
			if(is_file($this->style)){
				@unlink($this->style);
			}
		}
	}
	//显示nav加载方法
public function show_scroll(){
		$nav_scroll='<div class="wrapper"><div class="loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
		if(isset($this->jane_init['style']['style_nav_scroll'])){
				switch($this->jane_init['style']['style_nav_scroll']){
					case 'none':
						return '';
					break;
					case 'pc_mobile':
						return $nav_scroll;
					break;
					case 'pc':
						if(!$this->is_pc()){
							return $nav_scroll;
						}
					break;
					case 'mobile':
						if($this->is_pc()){
							return $nav_scroll;
						}
					break;
					default:
						return '';
					break;
				}
		}
}
//判断是否pc或者移动
 private function is_pc() {
		static $is_mobile = null;

		if ( isset( $is_mobile ) ) {
			return $is_mobile;
		}

		if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
		} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}

		return $is_mobile;
	}

}

//轮播类
class Carousel extends Jane_init{
	private $foucs_id=array();										//轮播类型
	private $foucs_cache='./content/cache/foucs.php';		//轮播的缓存
	protected $num='5';										//默认显示的条数 5
		//构造方法，初始化属性
		function __construct(){
			parent::__construct();
			if(isset($this->jane_init['foucs']['foucs_power']) &&  $this->jane_init['foucs']['foucs_power']=='true'){
				$this->cache_file = 'foucs';
				$this->foucs_id[] = !empty($this->jane_init['foucs']['foucs_id']) ?  $this->jane_init['foucs']['foucs_id'] : 'rand';
				$this->num = !empty($this->jane_init['foucs']['foucs_num']) ?  $this->jane_init['foucs']['foucs_num'] : 5;
				echo $this->pic_data(null);
			}
		}
		//返回数据 
		public function pic_data($action){
			//查看是需要更新缓存缓存
			parent::is_cache($this->foucs_id, $this->foucs_cache);
			//为遍历数据准备 避免notice
			$carousel_inner='';
			$carousel_indicators = '';
	        $active = 'active';
	        $tonum = 0;
	        //读取缓存
	    	$data = file_get_contents($this->foucs_cache);
			$data = str_replace("<?php exit;//", '', $data);
	    	$data_arr = json_decode($data, true);
	    	//遍历数据
	   		foreach($data_arr[0] as $value){
				$carousel_indicators .= "<li data-target='#myCarousel' data-slide-to='".($tonum++)."'></li>";
				$carousel_inner .="<div class='item {$active}'>
										<a href='{$value['url']}'><img  src='{$value['pic']}' alt='{$value['title']}'>
										<div class='carousel-caption'>{$value['title']}</div></a>
									</div>";
				$active = '';
			}
	$html ="<div id='myCarousel' class='carousel slide col-md-sm'>
		 <!-- 轮播（Carousel）指标 -->
		<ol class='carousel-indicators'>
			{$carousel_indicators}
		</ol>  
		 <!-- 轮播（Carousel）项目 -->
		<div class='carousel-inner'>
			{$carousel_inner}
		</div>
		<!-- 轮播（Carousel）导航 -->
		<a class='carousel-control left' href='#myCarousel' data-slide='prev'>&lsaquo;</a>
		<a class='carousel-control right' href='#myCarousel' data-slide='next'>&rsaquo;</a>
		</div>";

		return $html;
		}
}

	//自定义列表类
	class Freedom_list extends Jane_init{
		private $l_power;//自由列表开关
		private $sid_one=array();//自由列表的第一个列  默认热门文章列
		private $sid_two=array();//自由列表的第二个列 默认随机文章列
		private $tab_one='hot';
		private $tab_two='rand';
		private $tab_power;//选项卡开关
		//初始化自由列表配置
		public function __construct(){
			parent::__construct();
			$this->l_power = isset($this->jane_init['free']['free_l_power']) ? $this->jane_init['free']['free_l_power'] : '';
			$this->tab_power = isset($this->jane_init['free']['free_tab_power']) ? $this->jane_init['free']['free_tab_power'] : '';
			$this->tab_one = isset($this->jane_init['free']['free_tab_one']) ? $this->jane_init['free']['free_tab_one'] : 'hot';
			$this->tab_two = isset($this->jane_init['free']['free_tab_two']) ? $this->jane_init['free']['free_tab_two'] : 'rand';
			$this->sid_one = isset($this->jane_init['free']['free_freedom_list_one']) ? $this->jane_init['free']['free_freedom_list_one'] : 'hot';
			$this->sid_two = isset($this->jane_init['free']['free_freedom_list_two']) ? $this->jane_init['free']['free_freedom_list_two'] : 'rand';
			$this->num = !empty($this->jane_init['free']['free_list_num']) ? $this->jane_init['free']['free_list_num'] : 5;
		}
		//外部使用方法	显示自由列表
		public function show_list($num='one'){
				if($this->l_power=='true'){
					$sid = 'sid_'.$num;
					$tab = 'tab_'.$num;
					$title = 'free_title_'.$num;
					$file = './content/cache/freedom_list_'.$num.'.php';
					$this->cache_file = 'freedom_list_'.$num;
					global $CACHE;
					$arr[]=$this->$sid;
					if($this->tab_power=='true'){
						$arr[] = $this->$tab;
					}
					parent::is_cache($arr, $file);
					$data = file_get_contents($file);

					$data = str_replace("<?php exit;//", '', $data);
					$list_data = json_decode($data, true);
					if(is_array($list_data)){
						$title = $this->jane_init['free'][$title];
						if(!$title[0]){
							if($num=='one'){$title[0]="热门文章";}
							if($num=='two'){$title[0]="随机文章";}
						}
						return $this->html_list($list_data,$title);
					}
					return null;
				}
					return null;
		}

		//组装freelist html  
		private function html_list($list_data, $title){
			$html_data = '';
			$start_tabs ="<ul id='myTab' class='nav nav-tabs'>";
			$end_tabs = '</ul>';
			$tabs = '';
			for($i=0; $i<count($list_data); $i++){
				if(count($list_data)>1){
						$tab_id = '#tab'.rand(1,100);
						$tabs .= empty($i) ? "<li class='active'><a href='{$tab_id}' data-toggle='tab'>".$title[$i]."</a></li>" : "<li><a href='{$tab_id}' data-toggle='tab'>{$title[$i]}</a></li>";
						$tab_content = 'tab-content';
						 $tab_active = empty($i) ? ' in active' : '';
						$tab_pane = 'tab-pane fade ';
						$tab_fade_id = ltrim($tab_id,'#');
				}else{
					/*防止不开启选项卡时的 notice*/
					$tabs='';
					$end_tabs='';
					$tab_pane = '';
					$tab_active = '';
					$tab_fade_id = '';
					$tab_content = '';
					$start_tabs = '';	
					$tabs = $title[0];
				}
					$list = '';
					foreach ($list_data[$i] as $value) {
						$date = date('m-d',$value['date']);
						$list .="<a class='list-group-item ' href='{$value['url']}'><span class='glyphicon glyphicon-star-empty'></span>{$value['title']}
										<span class='freedom-list-date glyphicon glyphicon-time'>-{$date}</span>
									</a>";
					}
					$html = "<ul class='list-group {$tab_pane}{$tab_active}'  id='{$tab_fade_id}'>	
							{$list}
							</ul>";
					$html_data .=$html;
			}
				$result_html = "<div class='panel panel-warning col-sm-6'>
								<div class='panel-heading'>
								<h4 class='panel-title'>{$start_tabs}{$tabs}{$end_tabs}</h4>
								</div><div class='panel-body {$tab_content}'>{$html_data}</div></div>";
				return $result_html;
		}
	}
//评论表情
class comment_face
{
	function __construct(){
					$URL = BLOG_URL;
					echo "<script type='text/javascript'>
					var a = false, blog_url = '{$URL}';
					$(function(){
						$('textarea[name=comment]').focus(function() {
							if (!a) {
								$.getScript(blog_url + 'content/plugins/jane/smile.js');
								a = true;
							}
						});
					});
					</script>";
		}
	//匹配表情
	public function face_replace() {
			$output = ob_get_clean();
			$output = preg_replace("|\{smile:(\d+)\}|i",'<img src="' . BLOG_URL . 'content/plugins/jane/face/$1.gif" style="width:32px; height:32px" />',$output);
			ob_start();
			echo $output;
	} 

}

//相关日志类
class related_log extends Jane_init
{
	private $related_log_cache;

	function __construct($sid, $logid){
		parent::__construct();
		if(isset($this->jane_init['style']['style_related_power']) && $this->jane_init['style']['style_related_power']=='true'){
			if(isset($this->jane_init['style']['style_related_num']) && !empty($this->jane_init['style']['style_related_num'])){
				$this->num = $this->jane_init['style']['style_related_num'];
			}else{
				$this->num = 4 ;
			}
			$this->cache_file = 'related_log_'.$sid;
			$new_sid = array();
			$new_sid['sql']="SELECT a.gid,a.title,a.content,a.date FROM ".DB_PREFIX."blog a,".DB_PREFIX."sort b WHERE  a.sortid={$sid}  AND  b.sid={$sid} AND gid!={$logid} AND hide='n' OR b.pid={$sid} AND a.sortid=b.sid  AND gid!={$logid} AND hide='n'  ORDER BY RAND() LIMIT 0,{$this->num}";
			$this->related_log_cache = './content/cache/'.$this->cache_file.'.php';
			parent::is_cache($new_sid,$this->related_log_cache);
			$this->show_ralated_log();
		}

	}
	private function show_ralated_log(){
		if(is_file($this->related_log_cache)){
			$data = file_get_contents($this->related_log_cache);
			$data = str_replace("<?php exit;//", '', $data);
			$data_arr=json_decode($data, true);
			$html = '';
			foreach ($data_arr[0] as $value) {
				$html .="<div class='related-log col-xs-6  col-sm-4  col-md-3 '><a data-toggle='tooltip' data-placement='top' title='{$value['title']}' href='{$value['url']}'><img class='img-thumbnail' src='{$value['pic']}' /><p>{$value['title']}</p></a></div>";
			}
			$result_html = "<div class='panel panel-warning'><div class='panel-heading'><h4 class='panel-title'><span class='glyphicon glyphicon-bookmark'></span> 相关日志</h4></div><div class='panel-body'>{$html}</div></div>";
			echo $result_html;
		}
	}
}
