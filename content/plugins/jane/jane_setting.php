<?php
!defined('EMLOG_ROOT') && exit('access deined!');
?>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<?php

	function plugin_setting_view(){
				if(isset($_POST) && !empty($_POST)){
					$save = true ;
				}
				new Set_view($save);//入口
	}
	//让saddslashes()支持数组
	function saddslashes($string) {
	    if(is_array($string)) {
	    foreach($string as $key => $val) {
	   		 $string[$key] = addslashes($val);
	    }
	    } else {
	  		  $string = addslashes($string);
	    }
	    return $string;
	}
	//模板设置类
	class Set_view{
		private $foucs_id;
		private $foucs_num=5;
		private $post_path;
		private $init_path = '../content/cache/jane_init.php';
		//初始化显示设置页面
		public function __construct($save){
			if($save){
				if($this->save_init()){
					echo "<span class='actived'>插件设置完成</span><br/>";
				}
			}
			$this->post_path = BLOG_URL.'admin/plugin.php?plugin=jane';
			$this->view_init();
		}

		//保存设置
		private function save_init(){ 
				//存入数组
				$jane_init = array();

				foreach ($_POST as $key => $value) {
					$str = mb_substr($key, 0,4,'utf-8');
					if($str == 'free'){
						$jane_init['free'][$key] = saddslashes($_POST[$key]);//自由列表 	
					}
					$ostr = mb_substr($key, 0,5,'utf-8');
					if($ostr == 'style'){
						$jane_init['style'][$key] = saddslashes($_POST[$key]);//样式	
					}
					if($ostr == 'foucs'){
						$jane_init['foucs'][$key] = saddslashes($_POST[$key]);//样式	
					}
				}

				$jane_init['comment_face'] = addslashes($_POST['comment_face']);
				$jane_init['comment_info'] = addslashes($_POST['comment_info']);

				//转为json 存入文件
				$str = json_encode($jane_init);
				global $CACHE;
				if(!is_file('../content/templates/jane/jane.init.css')){
					@touch('../content/templates/jane/jane.init.css');
				}
				if(!is_writable('../content/templates/jane/jane.init.css')){
						emMsg('提示：./content/templates/jane/不可写。如果您使用的是Linux主机，请修改 jane模板 下所有文件的权限为777。如果您使用的是Windows主机，请联系管理员，将该目录下所有文件设为everyone可写');
				}
				$is = $CACHE->cacheWrite($str, 'jane_init');
				include '../content/plugins/jane/jane_style_setting.php';
				$isd = @file_put_contents('../content/templates/jane/jane.init.css', $css);
				
				if ($is=='') {
					return true;
				}else{
					return false;
				}
		}
		//设置总页
		private function view_init(){	
			$jane_init = $this->get_jane_init();
			if($jane_init){
				$this->foucs_id = $jane_init['foucs']['foucs_id'];
				$this->foucs_num = $jane_init['foucs']['foucs_num'];
			}
			echo "<form role='form' class='form-inline' action='{$this->post_path}' method='post'>";
			$this->foucs($jane_init);
			$this->freedom_list($jane_init);
			$this->other($jane_init);
			$this->style($jane_init);
			echo "<input class='form-control'type='submit' value='保存' /></form>";

		}
		//其它功能
		private function other($jane_init){
			$lckd = !empty($jane_init['style']['style_f_link']) ? 'checked' : '';  
			$i_lckd = !empty($jane_init['style']['style_is_f_link']) ? 'checked' : '';  
			$t_lckd = !empty($jane_init['style']['style_total_link']) ? 'checked' : '';  
			$related_power = !empty($jane_init['style']['style_related_power']) ? 'checked' : '';  

			echo "<table class='table table-bordered'>
							<tr class='success'><th colspan='3'><h4>其他功能</h4></th></tr>
							<tr><td>
							阅读文章页显示相关日志<input class='form-control' type='checkbox' value='true' name='style_related_power' {$related_power}/>
							</td><td colspan='2'>
							相关日志显示的数量(默认4)：<input class='form-control' type='text' value='{$jane_init['style']['style_related_num']}' name='style_related_num' />（4*4排列样式，建议数量为4的n次方）
							</td></tr>
							<tr><td>
							在底部显示友链：<input class='form-control' type='checkbox' value='true' name='style_f_link' {$lckd}/>
							</td><td>
							仅在首页显示友链：<input class='form-control' type='checkbox' value='true' name='style_is_f_link' {$i_lckd}/>
							</td><td>
							友链脚显示统计信息：<input class='form-control' type='checkbox' value='true' name='style_total_link' {$t_lckd}/>
							</td></tr>
							<tr><td colspan='3'>
							导航CSS3加载效果：<select class='form-control' id='nav_scroll' name='style_nav_scroll'>
													<option value='none'>关闭</option>
													<option value='pc_mobile'>pc/mobile显示</option>
													<option value='pc'>只在pc显示</option>
													<option value='mobile'>只在移动端显示</option>
											</select>
							</td></tr>
						</table>";
				$is_pc = !empty($jane_init['style']['style_nav_scroll']) ? $jane_init['style']['style_nav_scroll'] : 'none';
			echo "<script type='text/javascript'>
							$(function(){

									$('#nav_scroll option').attr('selected',function(){
										var v = $(this).attr('value');
										if('".$is_pc."'==v){
											return true;
										}
									});	
							});
					</script>
			";
		}
		//风格设置
		private function style($jane_init){
			$bckd = !empty($jane_init['style']['style_bg']) ? 'checked' : '';  
			$on = !empty($jane_init['style']['style_power']) ? 'checked' : '';  
			$html =<<<CSS
			<div class='jane'>
					<table class="table table-bordered">
						<tr class='success'><th colspan="2">
						<h4>自定义基本样色-----
						总开关：<input class='form-control'type="checkbox" id='on' value="true" name="style_power" {$on}/>（如果要显示默认样式，关闭即可）
						</h4>
							</th>
						</tr>
						<tbody id='dis'>
						<tr><td colspan="2">
						请填写样色的属性，如红色风格的导航，则输入red或#0ff或rgba(255,0,0,1)
						</td></tr>
						<tr><td>
						导航风格样色：<input class='form-control'type="text" value="{$jane_init['style']['style_nav_border']}" name="style_nav_border"/></td><td>导航字体样色：<input class='form-control'type="text" value="{$jane_init['style']['style_nav_a']}" name="style_nav_a"><br />选中导航字体样色：<input class='form-control'type="text" value="{$jane_init['style']['style_current_a']}" name="style_current_a">
						</td></tr>
						<tr><td>
						非连接字体样色：<input class='form-control'type="text" value="{$jane_init['style']['style_a_body']}" name="style_a_body"/></td><td>链接字体样色：<input class='form-control'type="text" value="{$jane_init['style']['style_a_color']}" name="style_a_color">
						</td></tr>
						<tr><td>
						面板风格：<input class='form-control'type="text" value="{$jane_init['style']['style_panel_h']}" name="style_panel_h"/></td><td>面板标题样色：<input class='form-control'type="text" value="{$jane_init['style']['style_panel_t']}" name="style_panel_t">
						</td></tr>
						<tr><td colspan="2">
						全局透明度：<input class='form-control'type="number" value="{$jane_init['style']['style_opacity']}" name="style_opacity"/>(0-99.9),0|100完全透明，99.9不透明,建议90之内，如果太低将无法看见页面
						</td></tr>
						<tr><td colspan="2">
						网站背景>>> 背景图片（可外链,外链优先,请保证外链的可用性）：<input class='form-control'typr="text" name="style_img_url" value="{$jane_init['style']['style_img_url']}"><br/>是否开启开启图片模糊<input class='form-control'type="checkbox" value="true" name="style_bg" {$bckd}/><br/>(图片位置:模板目录/images/bg.png[可自行替换]，大图片建议使用外链，减少服务压力)。<br><br>使用纯色:<input class='form-control'type="text" value="{$jane_init['style']['style_bc']}" name="style_bc" />(与前者冲突，纯色优先)
						</td></tr>
						<tr><td>
						浏览量为：<input class='form-control'type="num" value="{$jane_init['style']['style_view_hot']}" name="style_view_hot" />显示热门文章标志（默认300）</td><td>新文章发表<input class='form-control'type="num" value="{$jane_init['style']['style_view_new']}" name="style_view_new" />天内显示新文章标志（默认3,如果同时是热门文章择只显示热门文章标志）
						</td></tr>
						</tbody>
						</table>
			</div>
CSS;
		echo $html;
		echo "<script type='text/javascript'>
						$(function(){
								$('#on').click(function(){
										if($(this).attr('checked')!=undefined){

											$('.jane #dis input').attr('disabled',false);
										}else{
											$('.jane #dis input').attr('disabled',true);
										}
								});
						});
					</script>";
		}
		//轮播设置
		private function foucs($jane_init){
			global $CACHE;
			$sort_cache = $CACHE->readCache('sort'); 
		
			foreach ($sort_cache as $value) {
			
				$option_one .= "<option value='{$value['sid']}'>{$value['sortname']}</option>";
			} 
				
				$option_one .= "<option value='hot' >热门文章</option><option value='rand'>随机文章</option>";
				$foucs_power = !empty($jane_init['foucs']['foucs_power']) ? ' checked' : '';
			$html = "<table class='table table-bordered'>
						<tr class='success'><th class='success'><h4>轮播 设置<h4></th><th class='success'>开关<input type='checkbox' name='foucs_power' value='true' {$foucs_power}/></th></tr><tr><td colspan='2'><span>播放的条数:<input class='form-control'type='text' value='{$this->foucs_num}' name='foucs_num' /></span></td></tr>
						<tr><td colspan='2'>轮播 播放的栏目：
						<select class='form-control' id='foucs' name='foucs_id'>
							<option value=''>请选择（默认）</option>
							{$option_one}
						</select>（默认随机显示）
						</td></tr>
					"; 
			$html .="</table>";
			echo $html;
			$foucs_id = $jane_init['foucs']['foucs_id'];
			echo "<script type='text/javascript'>
					$(function(){
						$('#foucs option').attr('selected',function(){
									var v = $(this).attr('value')
									if('".$foucs_id."'==v){
											return true;
										}
								});

					});
				</script>";
		}
		//自由列表设置
		private function freedom_list($jane_init){
			global $CACHE;
			$sort_cache = $CACHE->readCache('sort'); 
			foreach ($sort_cache as $value) {
				$option_one .= "<option value='{$value['sid']}' {$selected_one}>{$value['sortname']}</option>";
				$option_two .= "<option value='{$value['sid']}' {$selected_two}>{$value['sortname']}</option>";
			} 
				$option_two .= "<option value='hot'>热门文章</option>
									<option value='rand'>随机文章</option>";
				$option_one .= "<option value='hot'>热门文章</option>
									<option value='rand'>随机文章</option>";
			if(isset($jane_init['free']['free_l_power']) && !empty($jane['free']['free_l_power'])){
				$free_l_power = ' checked';
			}
			$tab_power = !empty($jane_init['free']['free_tab_power']) ? ' checked' : '';
			$l_power = !empty($jane_init['free']['free_l_power']) ? ' checked' : '';
			$html = "<table class='table table-bordered'>
						<tr class='success'><th colspan='2'><h4>自由列表设置</h4></th>
						<th>总开关<input type='checkbox' id='free_l_power' name='free_l_power' value='true' {$l_power}></th>	
						</tr>
						<tbody id='l_dis'>
							<tr><td colspan='3'><span>是否选项卡模式:<input class='form-control' type='checkbox' id='tab' value='true' name='free_tab_power' {$tab_power}/></span></td></tr>
						<tr><td colspan='3'><span>每列显示条数(默认5):<input class='form-control'type='text' value='{$jane_init['free']['free_list_num']}' name='free_list_num' /></span></td></tr>
						<tr><td rowspan='2'>第1个列 显示：</td></tr><tr><td>
						<select class='form-control' id='one' name='free_freedom_list_one'>
							<option value='hot'>请选择（默认）</option>
							{$option_one}
						</select>（默认热门）
						类文章 
							<div class='tab_one' style='display:none'>
									<select class='form-control' name='free_tab_one'>
									<option value='rand'>请选择（默认）</option>
									{$option_one}
									</select>（默认随机）
								类文章 
							</div>
						</td><td>显示的标题:<input class='form-control'type='text' value='{$jane_init['free']['free_title_one'][0]}' name='free_title_one[]' />
								<div class='tab_one' style='display:none'>
									 显示的标题:<input class='form-control'type='text' value='{$jane_init['free']['free_title_one'][1]}' name='free_title_one[]' />
								</div>
						</td></tr><tr><td rowspan='2'>第2个列 显示：</td></tr><tr><td>
						<select class='form-control' id='two' name='free_freedom_list_two'>
							<option value='rand'>请选择（默认）</option>
							{$option_two}
						</select>（默认随机）
						类文章
							<div class='tab_two' style='display:none'>
									<select class='form-control' name='free_tab_two'>
									<option value='hot'>请选择（默认）</option>
									{$option_one}
									</select>（默认热门）
								类文章 
							</div>
						</td><td>显示的标题:<input class='form-control'type='text' value='{$jane_init['free']['free_title_two'][0]}' name='free_title_two[]' />
								<div class='tab_one col-offset-5' style='display:none'>
									 显示的标题:<input class='form-control'type='text' value='{$jane_init['free']['free_title_two'][1]}' name='free_title_two[]' />
								</div>
					
						</td></tr></tbody>
					"; 
			$html .=" </table>";
			echo $html;
				$one = $jane_init['free']['free_freedom_list_one'];
				$two = $jane_init['free']['free_freedom_list_two'];
				$tab_one = $jane_init['free']['free_tab_one'];
				$tab_two = $jane_init['free']['free_tab_two'];

			  echo "<script type='text/javascript'>
						$(function(){
								$('#free_l_power').click(function(){
										if($(this).attr('checked')!=undefined){
											$('#l_dis input,#l_dis select').attr('disabled',false);
										}else{
											$('#l_dis input,#l_dis select').attr('disabled',true);
										}
								});
							if($('#tab').attr('checked')!=undefined){
									$('.tab_one,.tab_two').show();
							}
							$('#tab').click(function(){
								if($(this).attr('checked')!=undefined){
									$('.tab_one,.tab_two').fadeIn();
								}else{
									$('.tab_one,.tab_two').fadeOut();
								}
							});
							
								$('#one option').attr('selected',function(){
									var v = $(this).attr('value')
									if('".$one."'==v){
											return true;
										}
								});
								
								$('#two option').attr('selected',function(){
									var v = $(this).attr('value')
									if('".$two."'==v){
											return true;
										}
								});
								$('.tab_one option').attr('selected',function(){
									var v = $(this).attr('value')
									if('".$tab_one."'==v){
											return true;
										}
								});
								$('.tab_two option').attr('selected',function(){
									var v = $(this).attr('value')
									if('".$tab_two."'==v){
											return true;
										}
								});
						});
					</script>";
		}
		//获取jane_init.php
		private function get_jane_init(){
			if(file_exists($this->init_path)){
				if(!is_writable($this->init_path)){
					emMsg('提示：jane.init文件不可写。如果您使用的是Linux主机，请修改 jane模板 下所有文件的权限为777。如果您使用的是Windows主机，请联系管理员，将该目录下所有文件设为everyone可写');
	 			}
				$data = str_replace("<?php exit;//", '', file_get_contents($this->init_path));
				$jane_init = json_decode($data, true);
				return $jane_init;
			}else{
				@touch($this->init_path);
				return false;
			}
		}
	}
?>

