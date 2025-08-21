<?php 
if( !defined('ABSPATH') ){ exit();}
add_action( 'add_meta_boxes', 'xyz_fbap_add_custom_box' );
$GLOBALS['edit_flag']=0;
function xyz_fbap_add_custom_box()
{
	$posttype="";
	if(isset($_GET['post_type']))
		$posttype=$_GET['post_type'];
	
	if($posttype=="")
		$posttype="post";
	
if(isset($_GET['action']) && $_GET['action']=="edit" && !empty($_GET['post']))   /// empty check added for fixing client scenario
	{
		$postid=intval($_GET['post']);
		$get_post_meta=get_post_meta($postid,"xyz_fbap",true);
		if($get_post_meta==1){
			$GLOBALS['edit_flag']=1;
		}
		global $wpdb;
		$table='posts';
		$accountCount = $wpdb->query($wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.$table.' WHERE id=%d and post_status NOT IN (%s, %s) LIMIT %d,%d',array($postid,'draft','future',0,1) )) ;
		if($accountCount>0){
			$GLOBALS['edit_flag']=1;
			}
		$posttype=get_post_type($postid);
	}
	if ($posttype=="page")
	{

		$xyz_fbap_include_pages=get_option('xyz_fbap_include_pages');
		if($xyz_fbap_include_pages==0)
			return;
	}
	else if($posttype=="post")
	{
		$xyz_fbap_include_posts=get_option('xyz_fbap_include_posts');
		if($xyz_fbap_include_posts==0)
			return;
	}
	else if($posttype!="post")
	{
		$xyz_fbap_include_customposttypes=get_option('xyz_fbap_include_customposttypes');
     	$carr=explode(',', $xyz_fbap_include_customposttypes);
		if(!in_array($posttype,$carr))
			return;

	}
	
	
	
	if((get_option('xyz_fbap_af')==0 && get_option('xyz_fbap_post_permission')==1 && ((get_option('xyz_fbap_fb_token')!=""&& (get_option('xyz_fbap_app_sel_mode')==0))|| (get_option('xyz_fbap_app_sel_mode')==1 && get_option('xyz_fbap_page_names')!=""))))
		add_meta_box( 'xyz_fbap', '<strong>WP2Social Auto Publish </strong>', 'xyz_fbap_addpostmetatags',
				null, 'normal', 'high',
				array(
						'__block_editor_compatible_meta_box' => true,
				)
				);
	//add_meta_box( "xyz_fbap", '<strong>WP2Social Auto Publish </strong>', 'xyz_fbap_addpostmetatags') ;
}
function xyz_fbap_addpostmetatags()
{
	$imgpath= plugins_url()."/facebook-auto-publish/images/";
	$heimg=$imgpath."support.png";
	$xyz_fbap_catlist=get_option('xyz_fbap_include_categories');
// 	if (is_array($xyz_fbap_catlist))
// 	$xyz_fbap_catlist=implode(',', $xyz_fbap_catlist);
	?>
<script>

/****************** Code to reload metabox in Gutenberg editor ******************/
jQuery(document).ready(function($) {
	// Check if the document body is available and if we're on a block editor page
const isBlockEditorPage = document.body && document.body.classList.contains('block-editor-page');
// Ensure wp and necessary methods are available
if (isBlockEditorPage && 
	typeof wp !== 'undefined' && 
	wp.blocks && 
	wp.data && 
	typeof wp.data.select('core/notices') === 'object' && 
	typeof wp.data.select('core/editor') === 'object' && 
	typeof wp.data.select('core/editor').getCurrentPost === 'function' &&
	typeof wp.data.select('core/notices').getNotices === 'function') {
		wp.data.subscribe(() => { 
		const notices = wp.data.select( 'core/notices' ).getNotices(); 
		const publishSuccessNotice = notices.find( notice => notice.content === 'Post published.' || notice.content === 'Post updated.');
		const editorSelect = wp.data.select('core/editor');
            if (editorSelect && typeof editorSelect.getCurrentPost === 'function') {
		const currentPostStatus = wp.data.select('core/editor').getCurrentPost().status;
		if (publishSuccessNotice && currentPostStatus === 'publish') 
			{

                    var xyz_fbap_default_selection_edit="<?php echo esc_html(get_option('xyz_fbap_default_selection_edit'));?>";
				if(xyz_fbap_default_selection_edit==0 && jQuery("input[name='xyz_fbap_post_permission']:checked").val()==1) 
				{
                        document.getElementById("fpabpmd").style.display='none';	
                    	document.getElementById("fpabpmf").style.display='none';	
                    	document.getElementById("fpabpmftarea").style.display='none';
                    	jQuery('#xyz_fbap_post_permission_0').prop('checked',true);
                    	jQuery('#xyz_fbap_post_permission_yes').removeClass('xyz_fbap_toggle_on');
                    	jQuery('#xyz_fbap_post_permission_yes').addClass('xyz_fbap_toggle_off');
                    	jQuery('#xyz_fbap_post_permission_no').removeClass('xyz_fbap_toggle_off');
                    	jQuery('#xyz_fbap_post_permission_no').addClass('xyz_fbap_toggle_on');                    	                    
                    }
				else if(xyz_fbap_default_selection_edit==1 && jQuery("input[name='xyz_fbap_post_permission']:checked").val()==0) 
				{
                    	document.getElementById("fpabpmd").style.display='';	
                    	document.getElementById("fpabpmf").style.display='';
                    	document.getElementById("fpabpmftarea").style.display='';
                    	jQuery('#xyz_fbap_post_permission_1').prop('checked',true);
                    	jQuery('#xyz_fbap_post_permission_no').removeClass('xyz_fbap_toggle_on');
                    	jQuery('#xyz_fbap_post_permission_no').addClass('xyz_fbap_toggle_off');
                    	jQuery('#xyz_fbap_post_permission_yes').removeClass('xyz_fbap_toggle_off');
                    	jQuery('#xyz_fbap_post_permission_yes').addClass('xyz_fbap_toggle_on');
                    }	
                }

        }
		});
	}
});
/*************************************************************************************/
function displaycheck_fbap()
{
	var fcheckid=jQuery("input[name='xyz_fbap_post_permission']:checked").val();
	
// var fcheckid=document.getElementsByName("xyz_fbap_post_permission")[1].value;alert(fcheckid);
if(fcheckid==1)
{
	document.getElementById("fpabpmd").style.display='';	
	document.getElementById("fpabpmf").style.display='';
	document.getElementById("fpabpmftarea").style.display='';

}
else
{
	document.getElementById("fpabpmd").style.display='none';	
	document.getElementById("fpabpmf").style.display='none';	
	document.getElementById("fpabpmftarea").style.display='none';	
}


}


</script>
<script type="text/javascript">
function detdisplay_fbap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_fbap(id)
{
	document.getElementById(id).style.display='none';
}


jQuery(document).ready(function() {
	displaycheck_fbap();
   var xyz_fbap_post_permission=jQuery("input[name='xyz_fbap_post_permission']:checked").val();
   XyzFbapToggleRadio(xyz_fbap_post_permission,'xyz_fbap_post_permission'); 
	var wp_version='<?php echo XYZ_WP_FBAP_WP_VERSION; ?>';
	 if (wp_version <= '5.3') {
	jQuery('#category-all').bind("DOMSubtreeModified",function(){
		fbap_get_categorylist(1);
		});
	
	fbap_get_categorylist(1);fbap_get_categorylist(2);
	jQuery('#category-all').on("click",'input[name="post_category[]"]',function() {
		fbap_get_categorylist(1);
				});

	jQuery('#category-pop').on("click",'input[type="checkbox"]',function() {
		fbap_get_categorylist(2);
				});
	/////////gutenberg category selection
	jQuery(document).on('change', 'input[type="checkbox"]', function() {
		fbap_get_categorylist(2);
				});
	 }
});

function fbap_get_categorylist(val)
{
	var flag=true;
	var cat_list="";var chkdArray=new Array();var cat_list_array=new Array();
	var posttype="<?php echo get_post_type() ;?>";
	if(val==1){
	 jQuery('input[name="post_category[]"]:checked').each(function() {
		 cat_list+=this.value+",";flag=false;
		});
	}else if(val==2)
	{
		jQuery('#category-pop input[type="checkbox"]').each(function() {
			 if(jQuery(this).is(":checked"))
				cat_list+=this.value+",";
				flag=false;
		});
		jQuery('.editor-post-taxonomies__hierarchical-terms-choice input[type="checkbox"]').each(function() { //gutenberg category checkbox
			 if(jQuery(this).is(":checked"))
				cat_list+=this.value+",";
				flag=false;
		});
		if(flag){
		<?php
		if (isset($_GET['post']))
			$postid=intval($_GET['post']);
		if (isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid)){
			$defaults = array('fields' => 'ids');
			$categ_arr=wp_get_post_categories( $postid, $defaults );
			$categ_str=implode(',', $categ_arr);
			?>
			cat_list+='<?php echo $categ_str; ?>';
		<?php }?>
				flag=false;
			
		}
	}
	 if (cat_list.charAt(cat_list.length - 1) == ',') {
		 cat_list = cat_list.substr(0, cat_list.length - 1);
		}
		jQuery('#cat_list').val(cat_list);
		var xyz_fbap_catlist="<?php echo $xyz_fbap_catlist;?>";
		if(xyz_fbap_catlist!="All")
		{
			cat_list_array=xyz_fbap_catlist.split(',');
			var show_flag=1;
			var chkdcatvals=jQuery('#cat_list').val();
			chkdArray=chkdcatvals.split(',');
			for(var x=0;x<chkdArray.length;x++) { 
				
				if(inArray(chkdArray[x], cat_list_array))
				{
					show_flag=1;
					break;
				}
				else
				{
					show_flag=0;
					continue;
				}
				
			}
			if(show_flag==0 && posttype=="post")
				jQuery('#xyz_fbMetabox').hide();
			else
				jQuery('#xyz_fbMetabox').show();
		}
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


</script>
<table class="xyz_fbap_metalist_table">
<input type="hidden" name="cat_list" id="cat_list" value="">
<input type="hidden" name="xyz_fbap_post" id="xyz_fbap_post" value="0" >
<tr id="xyz_fbMetabox"><td colspan="2" >
<?php  if(get_option('xyz_fbap_post_permission')==1) {
	$postid=0;
	if (isset($_GET['post']))
		$postid=intval($_GET['post']);
	$post_permission=1;
	$get_post_meta_future_data='';
	 $get_post_meta=get_post_meta($postid,"xyz_fbap",true);
	if (((get_option('xyz_fbap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1) || ((get_option('xyz_fbap_default_selection_create')==2) && $get_post_meta!=1 && $GLOBALS['edit_flag']!=1))  && !empty($postid))
		$get_post_meta_future_data=get_post_meta($postid,"xyz_fbap_future_to_publish",true);
		if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_fb_permission']))
		{
			$post_permission=$get_post_meta_future_data['post_fb_permission'];
			$xyz_fbap_po_method=$get_post_meta_future_data['xyz_fbap_po_method'];
			$xyz_fbap_message=$get_post_meta_future_data['xyz_fbap_message'];
		}
		else {
			$xyz_fbap_po_method=get_option('xyz_fbap_po_method');
			$xyz_fbap_message=get_option('xyz_fbap_message');
		}
	?>
<table class="xyz_fbap_meta_acclist_table"><!-- FB META -->


<tr>
		<td colspan="2" class="xyz_fbap_pleft15 xyz_fbap_meta_acclist_table_td"><strong> <?php _e('Facebook','facebook-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	
	<tr valign="top">
		<td class="xyz_fbap_pleft15" width="60%"> <?php _e('Enable auto publish post to my facebook account','facebook-auto-publish'); ?>
		</td>
		<td  class="xyz_fbap_switch_field">
		<label id="xyz_fbap_post_permission_yes"><input type="radio" name="xyz_fbap_post_permission" id="xyz_fbap_post_permission_1" value="1" <?php if($post_permission==1) echo 'checked';?>/> <?php _e('Yes','facebook-auto-publish'); ?> </label>
		<label id="xyz_fbap_post_permission_no"><input type="radio" name="xyz_fbap_post_permission" id="xyz_fbap_post_permission_0" value="0" <?php if($post_permission==0) echo 'checked';?> /> <?php _e('No','facebook-auto-publish'); ?> </label>

		</td>
	</tr>
	<tr valign="top" id="fpabpmd">
		<td class="xyz_fbap_pleft15"> <?php _e('Posting method','facebook-auto-publish'); ?>
		</td>
		<td><select id="xyz_fbap_po_method" name="xyz_fbap_po_method">
				<option value="3"
				<?php  if($xyz_fbap_po_method==3) echo 'selected';?>> <?php _e('Simple text message','facebook-auto-publish'); ?> </option>
				
				<optgroup label="Text message with image">
					<option value="4"
					<?php  if($xyz_fbap_po_method==4) echo 'selected';?>> <?php _e('Upload image to app album','facebook-auto-publish'); ?> </option>
					<option value="5"
					<?php  if($xyz_fbap_po_method==5) echo 'selected';?>> <?php _e('Upload image to timeline album','facebook-auto-publish'); ?> </option>
				</optgroup>
				
				<optgroup label="Text message with attached link">
					<option value="1"
					<?php  if($xyz_fbap_po_method==1) echo 'selected';?>> <?php _e('Attach your blog post','facebook-auto-publish'); ?> </option>
					<option value="2"
					<?php  if($xyz_fbap_po_method==2) echo 'selected';?>>
						  <?php _e('Share a link to your blog post','facebook-auto-publish'); ?> </option>
					</optgroup>
		</select>
		</td>
	</tr>
	<tr valign="top" id="fpabpmf">
		<td class="xyz_fbap_pleft15"> <?php _e('Message format for posting','facebook-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_fbap('xyz_xyz_fbap_informationdiv')" onmouseout="dethide_fbap('xyz_xyz_fbap_informationdiv')" style="width:13px;height:auto;">
						<div id="xyz_xyz_fbap_informationdiv" class="xyz_fbap_informationdiv" style="display: none;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','facebook-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','facebook-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','facebook-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','facebook-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','facebook-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','facebook-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','facebook-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','facebook-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','facebook-auto-publish'); ?>
						</div>
		</td>
	<td>
	<select name="xyz_fbap_info" id="xyz_fbap_info" onchange="xyz_fbap_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','facebook-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value= "9">{USER_DISPLAY_NAME}</option>
		</select> </td></tr>
		
		<tr id="fpabpmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_fbap_message"  name="xyz_fbap_message"><?php echo esc_textarea($xyz_fbap_message);?></textarea>
	</td></tr>
	
	</table>
	<?php }?>
	</td></tr>
	
		
</table>
<script type="text/javascript">
	

	var edit_flag="<?php echo $GLOBALS['edit_flag'];?>";
	if(edit_flag==1)
		load_edit_action();
	if(edit_flag!=1)
		load_create_action();
	function load_edit_action()
	{
		document.getElementById("xyz_fbap_post").value=1;
		var xyz_fbap_default_selection_edit="<?php echo esc_html(get_option('xyz_fbap_default_selection_edit'));?>";
		if(xyz_fbap_default_selection_edit=="")
			xyz_fbap_default_selection_edit=0;
		if(xyz_fbap_default_selection_edit==1 || xyz_fbap_default_selection_edit==2)
			return;
		jQuery('#xyz_fbap_post_permission_0').attr('checked',true);
		displaycheck_fbap();
	}
	function load_create_action()
	{
		document.getElementById("xyz_fbap_post").value=1;
		var xyz_fbap_default_selection_create="<?php echo esc_html(get_option('xyz_fbap_default_selection_create'));?>";
		if(xyz_fbap_default_selection_create=="")
			xyz_fbap_default_selection_create=0;
		if(xyz_fbap_default_selection_create==1 || xyz_fbap_default_selection_create==2)
			return;
		jQuery('#xyz_fbap_post_permission_0').attr('checked',true);
		displaycheck_fbap();
		
	}
	function xyz_fbap_info_insert(inf){
		
	    var e = document.getElementById("xyz_fbap_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_fbap_message").val()+ins_opt;
	    jQuery("textarea#xyz_fbap_message").val(str);
	    jQuery('#xyz_fbap_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_fbap_message").focus();

	}
	jQuery("#xyz_fbap_post_permission_no").click(function(){
		displaycheck_fbap();
		XyzFbapToggleRadio(0,'xyz_fbap_post_permission');
	});
	jQuery("#xyz_fbap_post_permission_yes").click(function(){
		displaycheck_fbap();
		XyzFbapToggleRadio(1,'xyz_fbap_post_permission');
	});
	</script>
<?php 
}
?>