<?php GetSatisfactionServiceApi::update_replies($post->ID)?>
<?php $company = get_company($post->ID);?>
<div class='clearfix' id='company_header'>
    <a href="<?php echo $company['url']?>" class="company_logo"><img width="48px" alt="<?php echo $company['name'];?>" src="<?php echo $company['logo_url'];?>" /></a>
    <a href="<?php echo $company['url']?>" class="company_name valign"><?php echo $company['name'];?></a>
</div>
<div class='clearfix' id='container' style="margin: 0px 0px 0px 0px">
    <?php echo $post->post_content;?>
</div>
