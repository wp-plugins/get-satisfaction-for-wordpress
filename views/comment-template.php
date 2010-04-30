<?php 
$GLOBALS['comment'] = $comment; 
$post_id = $comment->comment_post_ID;
$topic = get_gs_topic_metadata($post_id);
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
  		<div class="comment-author vcard">
            <?php echo get_avatar($comment,$size='48',get_gs_user_photo_url($comment->comment_ID)); ?>

            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
		</div>
        <?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.') ?></em>
			<br />
        <?php endif; ?>

		<div class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a>
			<?php edit_comment_link(__('(Edit)'),'  ','') ?>
		</div>

        <?php comment_text() ?>

		<div class="reply">
			<a  class="comment-reply-link" rel="nofollow"  href="<?php echo $topic['reply_url'].'#topic_reply_box';?>"><img src="<?php echo get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/images/postreply.jpg';?>" alt="Reply"></a>
		</div>
	</div>