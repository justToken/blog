<?php 
/**
 * 微语部分
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>

<div  class="panel col-md-8" id="w">
    <div class="panel panel-warning" id="tw">
        <div class="panel-heading">
            <h4 class="panel-title">微语</h4>
        </div>
        <div class="panel-body">
        <ul class="list-group">
            <?php 
            foreach($tws as $val):
            $author = $user_cache[$val['author']]['name'];
            $avatar = empty($user_cache[$val['author']]['avatar']) ? BLOG_URL . 'admin/views/images/avatar.jpg' : BLOG_URL . $user_cache[$val['author']]['avatar'];
            $tid = (int)$val['id'];
            $img = empty($val['img']) ? "" : '<a data-toggle="tooltip" data-placement="top"  title="查看图片" href="'.BLOG_URL.str_replace('thum-', '', $val['img']).'" target="_blank"><img  class="img-thumbnail" src="'.BLOG_URL.$val['img'].'" alt="face" /></a>';
            ?> 
            <li class="list-group-item">
                    <div class="post1">
                        <div class="col-sm-2 col-xs-3">
                             <div class="tw-author"><?php // echo $author; ?></div>
                             <div class="main_img"><img class="img-circle img-thumbnail" src="<?php echo $avatar; ?>"  /></div>
                         </div>
                        <div class="t-content col-sm-10 col-xs-9">
                            <?php echo $val['t'].'<p></p>'.$img;?>
                           <p></p>

                            <span class="tw-time"><span class="glyphicon glyphicon-time"></span><?php echo $val['date'];?> </span>
                            <span class="post"><a href="javascript:loadr('<?php echo DYNAMIC_BLOGURL; ?>?action=getr&tid=<?php echo $tid;?>','<?php echo $tid;?>');">
                                <span class="glyphicon glyphicon-comment"></span>回复</a></span>(<?php echo $val['replynum'];?>)<span style="line-height:2em" id="r_<?php echo $tid;?>"></span><br />
                            <?php if ($istreply == 'y'):?>
                            <div class="huifu" id="rp_<?php echo $tid;?>">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h4 class="panel-title">回复</h4>
                                </div>
                                <div class="panel-body">
                                  <form role="form" class="bs-example bs-example-form">
                                        <div id="rp_<?php echo $tid;?>">
                                         <div class="form-group">
                                             <textarea class="form-control" placeholder="请输入回复内容" id="rtext_<?php echo $tid; ?>"></textarea>
                                            <div style="display:<?php if(ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER){echo 'none';}?>">
                                                 <div class="input-group" style="display:<?php if($reply_code == 'n'){echo 'none';}?>">
                                                     <span class="input-group-addon" ><span class="glyphicon glyphicon-code"></span> code</span>
                                                     <input type="text" class="form-control"  id="rcode_<?php echo $tid; ?>" value="" placeholder="请输入验证码" >
                                                 </div>
                                                 <div class="input-group">

                                                     <span class="input-group-addon" ><span class="glyphicon glyphicon-user"></span> name</span>
                                                    <input type="text" class="form-control"  id="rname_<?php echo $tid; ?>" value="" placeholder="请输入你的昵称" >
      
                                                 </div>
                                             
                                             </div>
                                                 <div class="input-group">
                                                    <button class="form-control btn btn-warning" type="button" onclick="reply('<?php echo DYNAMIC_BLOGURL; ?>index.php?action=reply',<?php echo $tid;?>);">回复</button> 
                                                    <div class="msg"><span id="rmsg_<?php echo $tid; ?>" style="color:#FF0000"></span></div>
                                                </div>
                                             </div> 
                                          </div>                  
                                    </form>
                                  </div>
                                </div>
                            </div>
                                    <?php endif;?> 
                        </div> 
            <div class="clearfix"></div>

            </div>

            </li>
            <div class="clearfix"></div>
        <?php endforeach;?>
      </ul>  
     </div>

         

    </div>

     <?php echo $pageurl;?>
    </div>
           

<?php
 include View::getView('side');
 include View::getView('footer');
?>