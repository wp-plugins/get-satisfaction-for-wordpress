<?php
if (!class_exists('GetSatisfactionServiceApi')) {
    class GetSatisfactionServiceApi {
        static function create_topic($topic) {    
            try {
                $api_key = get_option('gs.api_key');
                $api_secret = get_option('gs_api_secret');
                
                $consumer_data = array(
                	'key' => $api_key,
                	'secret' => $api_secret
                );
                
                $creds = array(
                	'token' => $_COOKIE['oauth_access_token_key'],
                	'token_secret' => $_COOKIE['oauth_access_token_secret']
                );
                
                $url = 'http://api.getsatisfaction.com/topics';
                
                $method = HTTP_REQUEST_METHOD_POST;
                
                $req_params = array();
                $query_params = array (
                	'topic[company_domain]' => $topic['company_domain'],
                	'topic[style]' => $topic['style'],
                    'topic[subject]' => $topic['subject'],
                    'topic[additional_detail]' => $topic['additional_detail']
                );
                
                $response = oauthed_request($consumer_data, $method, $url, $creds, $req_params, $query_params);  
                $result = array();
                $result['body'] = $response->_response->_body;
                $result['response_code'] = $response->_response->_code;
        
                return $result;
            } catch (Exception $error) {
                echo 'Execption creating topic : '.$error->getMessage();
            }
        }
        
        /**
         * Update Topic
         * @param unknown_type $post_id
         * @param unknown_type $company_id
         * @param unknown_type $topic_id
         */
        static function update_topic($post_id, $company_id, $topic_id) {    
            try {
                $post = get_post($id);
                $topic_response = topic($company_id, $topic_id);   
                $topic = $topic_response['replies'][0];
                $publish_date = date('Y-m-d H:i:s', $topic['published']);
                
                // Add Topic Metadata
                GetSatisfactionServiceApi::add_topic_metadata($post_id, $topic);        
                
                // Create Replies        
                GetSatisfactionServiceApi::create_replies($post_id, $topic_response);
            
                // Add Company Information
                $company = company_hcard($company_id);
                GetSatisfactionServiceApi::add_topic_company_metadata($post_id, $company);    
            } catch (Exception $error) {
                echo 'Execption updating topic : '.$error->getMessage();
            }
         }
        
        /**
         * Add Topic metadata
         * @param unknown_type $post_id
         * @param unknown_type $topic
         */
        static function add_topic_metadata($post_id, $topic) {
            try {
                $author = $topic['author'];
                $user = get_person($author['url']);
            
                // Topic Information
                add_post_meta($post_id, 'gs.topic.id', $topic['id']);
                add_post_meta($post_id, 'gs.topic.published', $topic['published']);
                
                // Author
                add_post_meta($post_id, 'gs.topic.user.name', $author['name']);
                add_post_meta($post_id, 'gs.topic.user.url', $author['url']);
                add_post_meta($post_id, 'gs.topic.user.photo', $author['photo']);
                add_post_meta($post_id, 'gs.topic.user.canonical_name', $author['canonical_name']);    
            
                // Topic Data
                add_post_meta($post_id, 'gs.topic.sfn_id', $topic['sfn_id']);
                add_post_meta($post_id, 'gs.topic.style', $topic['topic_style']);
                add_post_meta($post_id, 'gs.topic.company_url', $topic['company_url']);
                
                $company_id = GetSatisfactionServiceApi::get_company_id($topic['company_url']);
                add_post_meta($post_id, 'gs.topic.company_id', $company_id);
                
                add_post_meta($post_id, 'gs.topic.replies_url', $topic['replies_url']);    
                add_post_meta($post_id, 'gs.topic.reply_url', $topic['at_sfn']);                    
            } catch (Exception $error) {
                echo 'Execption add topic metadata : '.$error->getMessage();
            }
        }
        
        /**
         * Add Company metadata for a Topic
         * @param $post_id
         * @param $company
         */
        static function add_topic_company_metadata($post_id, $company) {    
            // Company
            try {
                add_post_meta($post_id, 'gs.topic.company.name', trim($company['fn']));
                add_post_meta($post_id, 'gs.topic.company.logo_url', trim($company['logo']));
                add_post_meta($post_id, 'gs.topic.company.note', trim($company['note']));
                add_post_meta($post_id, 'gs.topic.company.organization', trim($company['org']));
                add_post_meta($post_id, 'gs.topic.company.url', trim($company['url'][1]));
            } catch (Exception $error) {
                echo 'Execption add topic company metadata : '.$error->getMessage();
            }
        }
        
        /**
         * Add the replies as comments to a post
         * 
         * @param unknown_type $post_id
         * @param unknown_type $topic
         */
        static function create_replies($post_id, $topic) {
            global $gs_url;
            
            try {
                $replies = $topic['replies'];
                $i = 0;
                foreach ($replies as $reply) {
                    if ($i = 0) { continue; }
                    $duplicate = GetSatisfactionDatabase::is_reply_duplicate($reply['id']);
                    
                    if ($duplicate) {
                        continue;
                    }
                    
                    $comment_date = date('Y-m-d H:i:s', $reply['published']);
                    
                    $data = array(
                        'comment_post_ID' => $post_id,
                        'comment_author' => $reply['author']['name'],
                        'comment_author_email' => 'admin@admin.com',
                        'comment_author_url' => $gs_url.'/people/'.$reply['author']['canonical_name'],
                        'comment_content' => trim($reply['content']),
                        'comment_parent' => 0,
                        'user_id' => $current_user->ID,
                        'comment_date' => $comment_date,
                        'comment_approved' => 1,
                    );
                    $comment_id = wp_insert_comment($data);    
                    GetSatisfactionServiceApi::add_comment_metadata($comment_id, $reply);        
                    
                    $i++;
                }
            } catch (Exception $error) {
                echo 'Execption create replies : '.$error->getMessage();
            }
          }
        
        static function update_replies($post_id) {
            try {
                $company_id = get_post_meta($post_id, 'gs.topic.company_id', true);        
                $topic_id = get_post_meta($post_id, 'gs.topic.id', true);
                $topic_response = topic($company_id, $topic_id);   
                    
                // Update Replies        
                GetSatisfactionServiceApi::create_replies($post_id, $topic_response);
            } catch (Exception $error) {
                echo 'Execption updating replies : '.$error->getMessage();
            }
        }
        /**
         * Add Get Satisfaction specific metadata to comment such as
         * title of the reply and photo_url of the author
         * @param $comment_id
         * @param $reply
         */
        static function add_comment_metadata($comment_id, $reply) {
            global $gs_url;
            try {
                add_comment_meta($comment_id, 'gs.reply.id', $reply['id'], true);
                add_comment_meta($comment_id, 'gs.reply.title', $reply['title'], true);
                add_comment_meta($comment_id, 'gs.reply.photo_url', $reply['author']['photo'], true);                
            } catch (Exception $error) {
                echo 'Execption add comment metadata : '.$error->getMessage();
            }
        }
        
        /**
         * Parse url to get company id
         * @param unknown_type $company_url
         */
        static function get_company_id($company_url) {
            try {
                $url = parse_url($company_url);
                $path = $url[path];
                $path_array = explode  ("/"  ,  $path);
                $company_id = end($path_array);
            
                return $company_id;                
            } catch (Exception $error) {
                echo 'Execption get company id : '.$error->getMessage();
            }
        }    
    }
}
?>