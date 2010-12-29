<div class="inside">
	<input type="hidden" name="topic_noncename" id="topic_noncename" value="<?php echo wp_create_nonce('topic_editor_post')?>" />
    <p>
        <label for="company_domain" class="selectit"><b><?php _e('Company Domain') ?></b></label>
    </p>
    <p>
        <small>
		A company's domain is the first element of the URL path in the main Get Satisfaction API. For example Twitter's address on Get satisfaction is http://getsatisfaction.com/twitter so its domain is 'twitter' 
		</small>
    </p>
    <p>
        <input type='text' size="30" name="company_domain" id="company_domain" value="world_wide_web "></input>
    </p>

    <p>
        <label for="topic_style" class="selectit"><b><?php _e('Topic Style') ?></b></label>
    </p>    
    <p>
        <small>
		Specifies the topic's style — either question, idea, problem, or answer.
		</small>
    </p>    
    <p>
        <select name="topic_style">
            <option value="update">Update</option>
            <option value="question">Question</option>
            <option value="idea">Idea</option>
            <option value="problem">Problem</option>
            <option value="praise">Praise</option>
        </select>
    </p>
</div>
